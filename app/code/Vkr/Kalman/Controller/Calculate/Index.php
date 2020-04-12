<?php

namespace Vkr\Kalman\Controller\Calculate;

use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\View\Result\PageFactory;
use Magento\Catalog\Controller\Product as ProductAction;

class Index extends \Magento\Framework\App\Action\Action
{

    public function __construct(
        Context $context
    ) {
        parent::__construct($context);
    }

    public function execute()
    {
        // Get initial data from request
        $customerId = (int) $this->getRequest()->getParam('customerId', false);
        if (!$customerId) {
            return;
        }
        $productId = (int) $this->getRequest()->getParam('id');
        $specifyOptions = $this->getRequest()->getParam('options');

    }
}
