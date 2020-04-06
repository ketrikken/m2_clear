<?php
namespace Vkr\Kalman\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Vkr\Kalman\Api\Data\AttributeInterface;
use Vkr\Kalman\Model\ResourceModel\Attribute as AttributeResource;

class CustomerAttributeRepository
{
    const CACHE_TAG = 'kalman_attributes';

    private $modelFactory;
    private $resource;

    public function __construct(
        \Vkr\Kalman\Model\CustomerAttributeFactory $modelFactory,
        \Vkr\Kalman\Model\ResourceModel\CustomerAttribute $resource
    ) {
        $this->modelFactory = $modelFactory;
        $this->resource     = $resource;
    }

    public function setValues($customerId, $values)
    {
        $model = $this->modelFactory->create();
        $this->resource->load($model, $customerId, 'customer_id');

        if (!$model->getId()) {
            $model->setCustomerId($customerId);
            $model->setAttributes(serialize($values));
            $model->save();
            return;
        }
        $customerAttributes = unserialize($model->getAttributes());
        foreach ($values as $id => $value) {
            if (!isset($customerAttributes[$id])) {
                $customerAttributes[$id] = $value;
            } else {
                $customerAttributes[$id] += $value;
            }
        }
        $model->setAttributes(serialize($customerAttributes));
        $model->save();
    }


}
