<?php

declare(strict_types=1);

namespace IgorTregub\CustomSourceSelection\Model;

use IgorTregub\CustomSourceSelection\Api\Data\SourceWeightAttributeInterface;

/**
 * Implements SourceWeightAttributeInterface
 */
class SourceWeightAttribute implements SourceWeightAttributeInterface
{
    /**
     * @var int
     */
    private $sourceWeight;

    /**
     * @param int $data
     */
    public function __construct(int $data)
    {
        $this->sourceWeight = $data;
    }

    /**
     * @return int
     */
    public function getSourceWeight(): int
    {
        return $this->sourceWeight;
    }
}
