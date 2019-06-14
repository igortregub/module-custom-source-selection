<?php

declare(strict_types=1);

namespace IgorTregub\CustomSourceSelection\Model;

use IgorTregub\CustomSourceSelection\Api\Data\GetStockSourceInterface;
use IgorTregub\CustomSourceSelection\Model\ResourceModel\Source\Collection as SourceCollection;
use IgorTregub\CustomSourceSelection\Model\ResourceModel\Source\CollectionFactory as SourceCollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\InventoryApi\Api\Data\SourceSearchResultsInterface;
use Magento\InventoryApi\Api\Data\SourceSearchResultsInterfaceFactory;

/**
 * Class GetStockSource
 */
class GetStockSource implements GetStockSourceInterface
{
    /**
     * @var SourceCollectionFactory
     */
    private $collectionFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var SourceSearchResultsInterfaceFactory
     */
    private $sourceSearchResultsFactory;

    /**
     * GetStockSource constructor.
     *
     * @param SourceCollectionFactory      $collectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param SourceSearchResultsInterfaceFactory $sourceSearchResultsFactory
     */
    public function __construct(
        SourceCollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        SourceSearchResultsInterfaceFactory $sourceSearchResultsFactory
    ) {
        $this->collectionFactory          = $collectionFactory;
        $this->collectionProcessor        = $collectionProcessor;
        $this->sourceSearchResultsFactory = $sourceSearchResultsFactory;
    }

    /**
     * Find source list by given SearchCriteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SourceSearchResultsInterface
     */
    public function execute(SearchCriteriaInterface $searchCriteria): SourceSearchResultsInterface
    {
        /**
         * @var SourceCollection $collection
         */
        $collection = $this->collectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        /**
         * @var SourceSearchResultsInterface $searchResult
         */
        $searchResult= $this->sourceSearchResultsFactory->create();
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());
        $searchResult->setSearchCriteria($searchCriteria);

        return $searchResult;
    }
}
