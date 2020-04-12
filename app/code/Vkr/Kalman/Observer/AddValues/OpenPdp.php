<?php

namespace Vkr\Kalman\Observer\AddValues;

use Magento\Checkout\Model\Type\Onepage;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use MathPHP\LinearAlgebra\Vector;
use MathPHP\LinearAlgebra\MatrixFactory;

class OpenPdp implements ObserverInterface
{
    private $customerAttributeRepository;
    private $attributeCollection;
    private $customerSession;
    private $product;
    private $configProcessor;
    protected $layoutFactory;
    protected $kalmanCalculate;

    public function __construct(
        \Vkr\Kalman\Model\CustomerAttributeRepository $customerAttributeRepository,
        \Vkr\Kalman\Model\ResourceModel\Attribute\CollectionFactory $attributeCollectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Vkr\Kalman\Model\Config\ConfigProcessor $configProcessor,
        \Magento\Framework\View\Result\PageFactory $layoutFactory,
        \Vkr\Kalman\Model\KalmanCalculate $kalmanCalculate
    ) {
        $this->customerAttributeRepository = $customerAttributeRepository;
        $this->attributeCollection = $attributeCollectionFactory->create();
        $this->customerSession = $customerSession;
        $this->product = $productFactory->create();
        $this->configProcessor = $configProcessor;
        $this->layoutFactory = $layoutFactory;
        $this->kalmanCalculate = $kalmanCalculate;
    }

    public function execute(Observer $observer)
    {
        if (!$this->configProcessor->isEnabledTracking('open_pdp')) {
            return $this;
        }
        $valueToAdd = $this->configProcessor->getValueOpenPdp();
        $productId = $observer->getEvent()->getProductId();
        $product = $this->product->load($productId);
        $customerId = $this->customerSession->getCustomer()->getId();

        if (!$valueToAdd || !$customerId || !$product || !$product->getId()) {
            return $this;
        }
        $values = [];
        $attributeItems = $this->attributeCollection->getItems();
        foreach ($attributeItems as $attributeItem) {
            $productValue = $product->getData($attributeItem->getProductAttribute());
            if (!$productValue) {
                continue;
            }
            //id new model
            $values[$attributeItem->getProductAttribute()] = $valueToAdd;
        }
        $this->customerAttributeRepository->setValues($customerId, $values);

        return $this;
    }
}
