<?php

namespace Vkr\Kalman\Controller\Adminhtml\Attributes;

class Edit extends \Vkr\Kalman\Controller\Adminhtml\Attributes
{
    /**
     * Edit banner
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {

        $banner = $this->initAttribute();

        $id = $banner->getAttributeId();
        $title = $id ? __('Edit Attributes') : __('New Attributes');
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb($title, $title);
        $resultPage->getConfig()->getTitle()->prepend(__('Kalmans'));
        $resultPage->getConfig()->getTitle()->prepend($id ? $banner->getName() : __('New Attributes'));
        return $resultPage;
    }
}
