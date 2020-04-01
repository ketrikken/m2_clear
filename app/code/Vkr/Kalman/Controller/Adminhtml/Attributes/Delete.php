<?php

namespace Vkr\Kalman\Controller\Adminhtml\Attributes;

use Magento\Framework\Exception\NoSuchEntityException;

class Delete extends \Vkr\Kalman\Controller\Adminhtml\Attributes
{
    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            try {
                $banner = $this->bannerRepository->get($id);
                $this->bannerRepository->delete($banner);
                $this->messageManager->addSuccessMessage(__('You deleted the banner.'));
                return $resultRedirect->setPath('*/*/');
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('We can\'t find a banner to delete.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a banner to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
