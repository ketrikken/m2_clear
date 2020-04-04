<?php
namespace Vkr\Kalman\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Vkr\Kalman\Api\Data\AttributeInterface;
use Vkr\Kalman\Model\ResourceModel\Attribute as AttributeResource;

class Attribute extends AbstractModel implements AttributeInterface, IdentityInterface
{
    const CACHE_TAG = 'kalman_attributes';

    protected function _construct()
    {
        $this->_init(AttributeResource::class);
    }
    public function getName(): string
    {
        return (string) $this->getData(self::NAME);
    }

    public function setName($value)
    {
        return $this->setData(self::NAME, $value);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG, $this->getEntityCacheTag()];
    }

    public function getAttributeId()
    {
        return (string) $this->getData(self::ID);
    }
    public function setAttributeId($id)
    {
        return $this->setData(self::ID, $id);
    }

}
