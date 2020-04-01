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
            $id = $this->getRequest()->getParam('id');
            $bannerData = $formData;

            if (empty($bannerData['id'])) {
                $bannerData['id'] = null;
            }
            $banner = $this->initKalman();

            if (!$banner->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This banner no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            $bannerData = $this->processImages($banner, $bannerData);
            $banner->addData($this->filterKalmanPostData($banner, $bannerData));

            try {
                $this->bannerRepository->save($banner);
                $this->messageManager->addSuccessMessage(__('You saved the banner.'));
                $this->dataPersistor->clear('banner');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $banner->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the banner.'));
            }

            $this->dataPersistor->set('banner', $formData);
            return $resultRedirect->setPath('*/*/edit', [
                    'id' => $this->getRequest()->getParam('id')
                ]
            );
        }
        return $resultRedirect->setPath('*/*/');
    }

    private function processImages(\Vkr\Kalman\Model\Kalman $banner, $data)
    {
        foreach ($banner->getImageAttributes() as $attribute) {
            if (empty($data[$attribute])) {
                unset($data[$attribute]);
                $data[$attribute]['delete'] = true;
            }
        }
        return $data;
    }

    protected function filterKalmanPostData(\Vkr\Kalman\Model\Kalman $banner, array $rawData)
    {
        $data = $rawData;
        foreach ($banner->getImageAttributes() as $attribute) {
            if (isset($data[$attribute]) && is_array($data[$attribute])) {
                if (!empty($data[$attribute]['delete'])) {
                    $data[$attribute] = null;
                } else {
                    if (isset($data[$attribute][0]['name']) && isset($data[$attribute][0]['tmp_name'])) {
                        $data[$attribute] = $data[$attribute][0]['name'];
                    } else {
                        unset($data[$attribute]);
                    }
                }
            }
        }
        return $data;
    }
}
