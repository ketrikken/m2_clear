<?php
namespace Vkr\Kalman\Model\ResourceModel\Attribute;

use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Vkr\Kalman\Model\Attribute;
use Vkr\Kalman\Model\ResourceModel\Attribute as AttributeResource;
use Psr\Log\LoggerInterface;

class Collection extends AbstractCollection
{
    private $resourceHelper;

    public function __construct(
        EntityFactoryInterface $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        AdapterInterface $connection = null,
        AbstractDb $resource = null
    ) {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
    }

    protected function _construct()
    {
        $this->_init(Attribute::class, AttributeResource::class);
    }

    public function filterByIds(array $ids)
    {
        return $this->addFieldToFilter('attribute_id', ['in' => $ids]);
    }

    public function excludeIds(array $ids)
    {
        return $this->addFieldToFilter('attribute_id', ['nin' => $ids]);
    }
}
