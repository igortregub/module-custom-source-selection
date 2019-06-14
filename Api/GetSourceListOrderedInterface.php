<?php

declare(strict_types=1);

namespace IgorTregub\CustomSourceSelection\Api;

use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\InventoryApi\Api\Data\SourceInterface;

interface GetSourceListOrderedInterface
{
    /**
     * Get Sources assigned to Stock ordered by source weight
     *
     * If Stock with given id doesn't exist then return an empty array
     *
     * @param int $stockId
     * @return SourceInterface[]
     * @throws InputException
     * @throws LocalizedException
     */
    public function execute(int $stockId): array;
}
