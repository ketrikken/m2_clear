<?php

namespace Vkr\Kalman\Controller\Adminhtml\Attributes;

class NewAction extends \Vkr\Kalman\Controller\Adminhtml\Attributes
{
    /**
     * Create new banner action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultForward = $this->resultForwardFactory->create();
        return $resultForward->forward('edit');
    }
}
