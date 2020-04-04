<?php
namespace Vkr\Kalman\Controller\Adminhtml;

use Magento\Framework\View\Result\Page;

abstract class Attributes extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Vkr_Kalman::kalman';

    protected $resultPageFactory;
    protected $resultForwardFactory;
    protected $coreRegistry;
    protected $bannerFactory;
    protected $bannerRepository;
    protected $dataPersistor;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Vkr\Kalman\Model\AttributeFactory $bannerFactory,
        \Vkr\Kalman\Api\AttributeRepositoryInterface $bannerRepository
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $coreRegistry;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->bannerFactory = $bannerFactory;
        $this->bannerRepository = $bannerRepository;
    }

    protected function initPage(Page $resultPage)
    {
        return $resultPage
            //->setActiveMenu('Vkr_Kalman::kalman')
            ->addBreadcrumb(__('Kalaman'), __('Attributes'));
    }

    /**
     * Init Attributes Model
     *
     * @return \Vkr\Kalman\Model\Attributes
     */
    protected function initAttribute()
    {
        $id = (int) $this->getRequest()->getParam('attribute_id');

        $banner = $this->bannerFactory->create();
        if ($id) {
            $banner = $this->bannerRepository->get($id);
        }
        $this->coreRegistry->register('current_attribute', $banner);
        return $banner;
    }
}
