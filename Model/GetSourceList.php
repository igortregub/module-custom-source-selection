<?php

declare(strict_types=1);

namespace IgorTregub\CustomSourceSelection\Model;

use Exception;
use IgorTregub\CustomSourceSelection\Api\Data\GetStockSourceInterface;
use IgorTregub\CustomSourceSelection\Api\Data\SourceWeightAttributeInterface;
use IgorTregub\CustomSourceSelection\Api\GetSourceListOrderedInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\InventoryApi\Api\Data\SourceInterface;
use Magento\InventoryApi\Api\Data\StockSourceLinkInterface;
use Psr\Log\LoggerInterface;

class GetSourceList implements GetSourceListOrderedInterface
{
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var GetStockSourceInterface
     */
    private $getStockSource;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var SortOrderBuilder
     */
    private $sortOrderBuilder;

    /**
     * @param SearchCriteriaBuilder   $searchCriteriaBuilder
     * @param GetStockSourceInterface $getStockSource
     * @param SortOrderBuilder        $sortOrderBuilder
     * @param LoggerInterface         $logger
     */
    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        GetStockSourceInterface $getStockSource,
        SortOrderBuilder $sortOrderBuilder,
        LoggerInterface $logger
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->getStockSource        = $getStockSource;
        $this->sortOrderBuilder      = $sortOrderBuilder;
        $this->logger                = $logger;
    }

    /**
     * Get Sources assigned to Stock ordered by source weight
     *
     * If Stock with given id doesn't exist then return an empty array
     *
     * @param int $stockId
     * @return SourceInterface[]
     * @throws LocalizedException
     */
    public function execute(int $stockId): array
    {
        try {
            return $this->getStockSourceList($stockId);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            throw new LocalizedException(__('Could not load Sources for Stock'), $e);
        }
    }

    /**
     * Get all stock-source list by given stockId
     *
     * @param int $stockId
     * @return array
     */
    private function getStockSourceList(int $stockId): array
    {
        $sortOrder = $this->sortOrderBuilder
            ->setField(SourceWeightAttributeInterface::SOURCE_WEIGHT_ATTRIBUTE)
            ->setDescendingDirection()
            ->create();

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(StockSourceLinkInterface::STOCK_ID, $stockId)
            ->addSortOrder($sortOrder)
            ->create();
        $searchResult   = $this->getStockSource->execute($searchCriteria);

        return $searchResult->getItems();
    }
}
