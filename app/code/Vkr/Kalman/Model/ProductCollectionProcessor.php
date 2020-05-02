<?php
namespace Vkr\Kalman\Model;

/**
 * Generates Product Frontend Action Section in Customer Data
 */
class ProductCollectionProcessor
{
    protected $productCollectionFactory;

    public function __construct(
        \Vkr\Kalman\Model\CustomerAttributeFactory $modelFactory,
        \Vkr\Kalman\Model\ResourceModel\CustomerAttribute $resource,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
    ) {
        $this->modelFactory = $modelFactory;
        $this->resource = $resource;
        $this->productCollectionFactory = $productCollectionFactory;
    }

    public function getProducts($customerId)
    {
        $model = $this->modelFactory->create();
        $this->resource->load($model, $customerId, 'customer_id');

        if (!$model || !$model->getId()) {
            return [];
        }
        $customerAttributes = unserialize($model->getAttributes());

        $n = 5;
        $result = [];
        $sum = $max = 0;
        foreach ($customerAttributes as $key => $attribute) {
            $max = max($max, $attribute);
            $sum += $attribute;
        }
        foreach ($customerAttributes as $key => $attribute) {
            $result[$key] = $attribute / $sum * $n;
        }

        $resultIds = [];

        foreach ($result as $key => $item) {
            $collection = $this->productCollectionFactory->create();
            $collection->addAttributeToSelect($key)->setCurPage(1)->setPageSize(ceil($item));
            $resultIds = array_merge($resultIds, $collection->getItems());
        }

        return $resultIds;
    }
}
