<?php
namespace Vkr\Kalman\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Vkr\Kalman\Api\Data\AttributeInterface;
use Vkr\Kalman\Model\Kalman as KalmanModel;
use Vkr\Kalman\Helper\Kalman as KalmanHelper;
use Psr\Log\LoggerInterface;

class CustomerAttribute extends AbstractDb
{

    private $logger;

    public function __construct(
        Context $context,
        LoggerInterface $logger,
        $connectionName = null
    ) {
        parent::__construct(
            $context,
            $connectionName
        );
        $this->logger = $logger;
    }

    protected function _construct()
    {
        $this->_init('kalman_customer_attributes', 'id');
    }

}
