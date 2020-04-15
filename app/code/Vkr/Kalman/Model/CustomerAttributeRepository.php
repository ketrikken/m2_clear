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
    protected $kalmanProcessor;

    public function __construct(
        \Vkr\Kalman\Model\CustomerAttributeFactory $modelFactory,
        \Vkr\Kalman\Model\ResourceModel\CustomerAttribute $resource,
        \Vkr\Kalman\Model\KalmanProcessor $kalmanProcessor
    ) {
        $this->modelFactory = $modelFactory;
        $this->resource = $resource;
        $this->kalmanProcessor = $kalmanProcessor;
    }

    public function setValues($customerId, $values)
    {
        return;
        $model = $this->modelFactory->create();
        $this->resource->load($model, $customerId, 'customer_id');

        if (!$model->getId()) {
            $model->setCustomerId($customerId);
            $model->setAttributes(serialize($values));// чистые данные
            //засетить только новое значение по калману
            $result = $this->kalmanProcessor->getKalmanValue(serialize($values), null, null, null);
            $model->setNewX(serialize($result['x']));
            $model->setNewP(serialize($result['P']));
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
        // расчитать новое значение, и поменять местами старое и новое.
        $model->setAttributes(serialize($customerAttributes));

        $oldValueX = $model->getOldX();
        $newValueX = $model->getNewX();
        $newValueP = $model->getNewP();
        $result = $this->kalmanProcessor->getKalmanValue(serialize($customerAttributes), $oldValueX, $newValueX, $newValueP);

        $model->setOldX($model->getNewX());
        $model->setNewX(serialize($result['x']));
        $model->setNewP(serialize($result['P']));

        $model->save();
    }

}
