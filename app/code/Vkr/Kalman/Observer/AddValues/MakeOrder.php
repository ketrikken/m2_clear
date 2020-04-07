<?php

namespace Vkr\Kalman\Observer\AddValues;

use Magento\Checkout\Model\Type\Onepage;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class MakeOrder implements ObserverInterface
{

    private $customerAttributeRepository;
    private $attributeCollection;
    private $customerSession;
    private $product;
    private $configProcessor;

    public function __construct(
        \Vkr\Kalman\Model\CustomerAttributeRepository $customerAttributeRepository,
        \Vkr\Kalman\Model\ResourceModel\Attribute\CollectionFactory $attributeCollectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Vkr\Kalman\Model\Config\ConfigProcessor $configProcessor
    ) {
        $this->customerAttributeRepository = $customerAttributeRepository;
        $this->attributeCollection = $attributeCollectionFactory->create();
        $this->customerSession = $customerSession;
        $this->product = $productFactory->create();
        $this->configProcessor = $configProcessor;
    }

    public function execute(Observer $observer)
    {
        if (!$this->configProcessor->isEnabledTracking('create_order')) {
            return $this;
        }
        $valueToAdd = $this->configProcessor->getValueCreateOrder();
        $order = $observer->getEvent()->getOrder();
        $customerId = $this->customerSession->getCustomer()->getId();
        $attributeItems = $this->attributeCollection->getItems();
        $values = [];
        foreach ($order->getItems() as $item) {
            $product = $item->getProduct();

            if (!$valueToAdd || !$customerId || !$product || !$product->getId()) {
                return $this;
            }
            foreach ($attributeItems as $attributeItem) {
                $productValue = $product->getData($attributeItem->getProductAttribute());
                if (!$productValue) {
                    continue;
                }
                //id new model
                if (!isset($values[$attributeItem->getProductAttribute()])) {
                    $values[$attributeItem->getProductAttribute()] = 0;
                }
                $values[$attributeItem->getProductAttribute()] += $valueToAdd;

            }
        }

        $this->customerAttributeRepository->setValues($customerId, $values);
        return $this;
    }
}
