<?php
namespace Vkr\Kalman\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Vkr\Kalman\Api\Data\AttributeInterface;
use Vkr\Kalman\Model\ResourceModel\CustomerAttribute as AttributeResource;

class CustomerAttribute extends AbstractModel
{
    const CACHE_TAG = 'kalman_attributes';

    protected function _construct()
    {
        $this->_init(AttributeResource::class);
    }


}
