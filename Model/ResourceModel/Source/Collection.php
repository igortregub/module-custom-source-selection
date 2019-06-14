<?php

declare(strict_types=1);

namespace IgorTregub\CustomSourceSelection\Model\ResourceModel\Source;

use Magento\Inventory\Model\ResourceModel\Source\Collection as SourceCollection;
use Magento\Inventory\Model\ResourceModel\StockSourceLink;

class Collection extends SourceCollection
{
    public function load($printQuery = false, $logQuery = false)
    {
        $this->join(
            ['source_stock_link' => StockSourceLink::TABLE_NAME_STOCK_SOURCE_LINK],
            'source_stock_link.source_code = main_table.source_code'
        );

        parent::load($printQuery, $logQuery);

        return $this;
    }
}
