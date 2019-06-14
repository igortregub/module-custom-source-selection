<?php

declare(strict_types=1);

namespace IgorTregub\CustomSourceSelection\Model\Algorithms;

use IgorTregub\CustomSourceSelection\Api\GetSourceListOrderedInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\InventoryApi\Api\Data\SourceInterface;
use Magento\InventorySourceSelectionApi\Api\Data\InventoryRequestInterface;
use Magento\InventorySourceSelectionApi\Api\Data\SourceSelectionResultInterface;
use Magento\InventorySourceSelectionApi\Model\Algorithms\Result\GetDefaultSortedSourcesResult;
use Magento\InventorySourceSelectionApi\Model\SourceSelectionInterface;

/**
 * Returns sources ordered by source weight
 */
class SourceWeightBased implements SourceSelectionInterface
{
    /**
     * @var GetSourceListOrderedInterface
     */
    private $getSourcesOrdered;

    /**
     * @var GetDefaultSortedSourcesResult
     */
    private $getDefaultSortedSourcesResult;

    /**
     * SourceWeightBased constructor.
     *
     * @param GetSourceListOrderedInterface $getSourcesOrdered
     * @param GetDefaultSortedSourcesResult $getDefaultSortedSourcesResult
     */
    public function __construct(
        GetSourceListOrderedInterface $getSourcesOrdered,
        GetDefaultSortedSourcesResult $getDefaultSortedSourcesResult
    ) {
        $this->getSourcesOrdered             = $getSourcesOrdered;
        $this->getDefaultSortedSourcesResult = $getDefaultSortedSourcesResult;
    }

    /**
     * @param InventoryRequestInterface $inventoryRequest
     * @return SourceSelectionResultInterface
     *
     * @throws InputException
     * @throws LocalizedException
     */
    public function execute(InventoryRequestInterface $inventoryRequest): SourceSelectionResultInterface
    {
        $stockId       = $inventoryRequest->getStockId();
        $sortedSources = $this->getSourcesOrderedBySourceWeight($stockId);

        return $this->getDefaultSortedSourcesResult->execute($inventoryRequest, $sortedSources);
    }

    /**
     * Get enabled sources ordered by source_weight attribute for $stockId
     *
     * @param int $stockId
     * @return array
     *
     * @throws InputException
     * @throws LocalizedException
     */
    private function getSourcesOrderedBySourceWeight(int $stockId): array
    {
        $sources = $this->getSourcesOrdered->execute($stockId);
        $sources = array_filter($sources, static function (SourceInterface $source) {
            return $source->isEnabled();
        });

        return $sources;
    }
}
