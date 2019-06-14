<?php

declare(strict_types=1);

namespace IgorTregub\CustomSourceSelection\Api\Data;

/**
 * Interface AddressInterface
 *
 * @package Borgir\CustomSourceSelection\Api\Data
 */
interface SourceWeightAttributeInterface
{
    /**
     * source weight attribute code
     */
    public const SOURCE_WEIGHT_ATTRIBUTE = 'source_weight';

    /**
     * @return int
     */
    public function getSourceWeight(): int;
}
