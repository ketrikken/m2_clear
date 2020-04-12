<?php

namespace Vkr\Kalman\Block\Kalman;

use Magento\Catalog\Model\Product;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\LayoutFactory;
use Magento\Widget\Block\BlockInterface;
use Magento\Framework\Url\EncoderInterface;

class Pidr extends \Magento\Framework\View\Element\Template implements BlockInterface
{
    protected $_template = "Vkr_Kalman::kalman/pidr.phtml";

    private $customerAttributeRepository;
    private $customerSession;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Vkr\Kalman\Model\CustomerAttributeRepository $customerAttributeRepository,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->customerSession = $customerSession;
        $this->customerAttributeRepository = $customerAttributeRepository;
    }

    public function isEnabled()
    {
        $customerId = $this->customerSession->getCustomer()->getId();
        if (!$customerId) {
            return false;
        }
        return $this->customerAttributeRepository->needUpdate($customerId);

    }

    public function getCurrentState()
    {

    }

    public function getPrevState()
    {

    }

    public function getMatrixU()
    {

    }

    public function getMatrixSize()
    {
        return 2;
    }

}
