<?php

declare(strict_types=1);

namespace IgorTregub\CustomSourceSelection\Api\Data;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\InventoryApi\Api\Data\SourceSearchResultsInterface;

/**
 * Find Stock Source list by SearchCriteria API
 *
 * @api
 */
interface GetStockSourceInterface
{
    /**
     * Find StockSource list by given SearchCriteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SourceSearchResultsInterface
     */
    public function execute(SearchCriteriaInterface $searchCriteria): SourceSearchResultsInterface;
}
