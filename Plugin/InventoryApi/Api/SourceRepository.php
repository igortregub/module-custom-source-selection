<?php

declare(strict_types=1);

namespace IgorTregub\CustomSourceSelection\Plugin\InventoryApi\Api;

use IgorTregub\CustomSourceSelection\Api\Data\SourceWeightAttributeInterface;
use Magento\Framework\Model\AbstractExtensibleModel;
use Magento\InventoryApi\Api\Data\SourceExtension;
use Magento\InventoryApi\Api\Data\SourceExtensionFactory;
use Magento\InventoryApi\Api\Data\SourceExtensionInterface;
use Magento\InventoryApi\Api\Data\SourceInterface;
use Magento\InventoryApi\Api\Data\SourceSearchResultsInterface;
use Magento\InventoryApi\Api\SourceRepositoryInterface;

class SourceRepository
{
    /**
     * @var SourceExtensionFactory
     */
    private $extensionFactory;

    /**
     * SourceRepository constructor.
     *
     * @param SourceExtensionFactory $extensionFactory
     */
    public function __construct(SourceExtensionFactory $extensionFactory)
    {
        $this->extensionFactory = $extensionFactory;
    }

    /**
     * @param SourceRepositoryInterface               $subject
     * @param SourceInterface|AbstractExtensibleModel $source
     */
    // @codingStandardsIgnoreLine
    public function beforeSave(SourceRepositoryInterface $subject, SourceInterface $source): void
    {
        $extensionAttributes = $source->getExtensionAttributes();

        if ($extensionAttributes) {
            $source->setData(
                SourceWeightAttributeInterface::SOURCE_WEIGHT_ATTRIBUTE,
                $this->getWeightAttributeValue($extensionAttributes)
            );
        }
    }

    /**
     * @param SourceRepositoryInterface $subject
     * @param SourceInterface           $source
     * @return SourceInterface
     */
    // @codingStandardsIgnoreLine
    public function afterGet(SourceRepositoryInterface $subject, SourceInterface $source): SourceInterface
    {
        $this->addSourceWeight($source);

        return $source;
    }

    /**
     * @param SourceRepositoryInterface    $subject
     * @param SourceSearchResultsInterface $results
     *
     * @return SourceSearchResultsInterface
     */
    // @codingStandardsIgnoreLine
    public function afterGetList(
        SourceRepositoryInterface $subject,
        SourceSearchResultsInterface $results
    ): SourceSearchResultsInterface {
        foreach ($results->getItems() as $item) {
            $this->addSourceWeight($item);
        }

        return $results;
    }

    /**
     * @param SourceInterface|AbstractExtensibleModel $item
     */
    private function addSourceWeight(SourceInterface $item): void
    {
        /**
         * @var SourceExtensionInterface|SourceExtension $extensionAttributes
         */
        $extensionAttributes = $item->getExtensionAttributes() ?? $this->extensionFactory->create();
        $extensionAttributes->setData(
            SourceWeightAttributeInterface::SOURCE_WEIGHT_ATTRIBUTE,
            $item->getData(SourceWeightAttributeInterface::SOURCE_WEIGHT_ATTRIBUTE)
        );
        $item->setExtensionAttributes($extensionAttributes);
    }

    /**
     * @param SourceExtensionInterface $extensionAttributes
     * @return int
     */
    private function getWeightAttributeValue(SourceExtensionInterface $extensionAttributes): int
    {
        /**
         * @var SourceWeightAttributeInterface $sourceWeight
         */
        $sourceWeight = $extensionAttributes->getSourceWeight();

        if ($sourceWeight instanceof SourceWeightAttributeInterface) {
            return $sourceWeight->getSourceWeight();
        }

        return 0;
    }
}
