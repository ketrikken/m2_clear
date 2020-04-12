<?php
namespace Vkr\Kalman\Block\Adminhtml\Attributes\Edit\Form\Button;

use Magento\Framework\Exception\NoSuchEntityException;

class Generic
{
    protected $context;
    protected $bannerRepository;

    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Vkr\Kalman\Api\AttributeRepositoryInterface $bannerRepository
    ) {
        $this->context = $context;
        $this->bannerRepository = $bannerRepository;
    }

    /**
     * Return banner Kalman ID
     *
     * @return int|null
     */
    public function getAttributeId()
    {
        try {
            return $this->bannerRepository->get(
                $this->context->getRequest()->getParam('attribute_id')
            )->getId();
        } catch (NoSuchEntityException $e) {
        }
        return null;
    }

    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
