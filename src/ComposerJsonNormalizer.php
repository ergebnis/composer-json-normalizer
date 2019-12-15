<?php

declare(strict_types=1);

/**
 * Copyright (c) 2018 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/composer-json-normalizer
 */

namespace Ergebnis\Composer\Json\Normalizer;

use Ergebnis\Json\Normalizer;
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;

final class ComposerJsonNormalizer implements Normalizer\NormalizerInterface
{
    /**
     * @var Normalizer\NormalizerInterface
     */
    private $normalizer;

    public function __construct(string $schemaUri = 'https://getcomposer.org/schema.json')
    {
        $this->normalizer = new Normalizer\ChainNormalizer(
            new Normalizer\SchemaNormalizer(
                $schemaUri,
                new SchemaStorage(),
                new Normalizer\Validator\SchemaValidator(new Validator())
            ),
            new BinNormalizer(),
            new ConfigHashNormalizer(),
            new PackageHashNormalizer(),
            new VersionConstraintNormalizer()
        );
    }

    public function normalize(Normalizer\Json $json): Normalizer\Json
    {
        if (!\is_object($json->decoded())) {
            return $json;
        }

        return $this->normalizer->normalize($json);
    }
}
