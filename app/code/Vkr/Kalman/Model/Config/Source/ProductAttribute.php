<?php

namespace Vkr\Kalman\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class ProductAttribute implements OptionSourceInterface
{
    protected $attributeFactory;

    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $attributeFactory
    ) {
        $this->attributeFactory = $attributeFactory;
    }

    public function toOptionArray()
    {
        $availableOptions = [];
        $attributeInfo = $this->attributeFactory->create()->addVisibleFilter();

        foreach ($attributeInfo as $attributes) {
            $attributeCode    = $attributes->getAttributeCode();
            $attributeLabel = $attributes->getFrontendLabel();
            $availableOptions[] = [
                'value' => $attributeCode,
                'label' => $attributeLabel
            ];
        }
        return $availableOptions;
    }
}
