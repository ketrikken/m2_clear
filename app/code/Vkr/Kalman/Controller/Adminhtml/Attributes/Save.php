<?php
namespace Vkr\Kalman\Controller\Adminhtml\Attributes;

use Magento\Framework\Exception\LocalizedException;

class Save extends \Vkr\Kalman\Controller\Adminhtml\Attributes
{
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $formData = $this->getRequest()->getPostValue();
        if ($formData) {
            $id = $this->getRequest()->getParam('attribute_id');
            $bannerData = $formData;

            if (empty($bannerData['attribute_id'])) {
                $bannerData['attribute_id'] = null;
            }
            $banner = $this->initAttribute();

            if (!$banner->getAttributeId() && $id) {
                $this->messageManager->addErrorMessage(__('This attribute no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
            $banner->addData($bannerData);
            try {
                $this->bannerRepository->save($banner);
                $this->messageManager->addSuccessMessage(__('You saved the attribute.'));

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['attribute_id' => $banner->getAttributeId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the banner.'));
            }

            return $resultRedirect->setPath('*/*/edit', [
                    'attribute_id' => $this->getRequest()->getParam('attribute_id')
                ]
            );
        }
        return $resultRedirect->setPath('*/*/');
    }
}
