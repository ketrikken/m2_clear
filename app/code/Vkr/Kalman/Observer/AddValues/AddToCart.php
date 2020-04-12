<?php

namespace Vkr\Kalman\Observer\AddValues;

use Magento\Checkout\Model\Type\Onepage;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class AddToCart implements ObserverInterface
{
    private $customerAttributeRepository;
    private $attributeCollection;
    private $configProcessor;

    public function __construct(
        \Vkr\Kalman\Model\CustomerAttributeRepository $customerAttributeRepository,
        \Vkr\Kalman\Model\ResourceModel\Attribute\CollectionFactory $attributeCollectionFactory,
        \Vkr\Kalman\Model\Config\ConfigProcessor $configProcessor
    ) {
        $this->customerAttributeRepository = $customerAttributeRepository;
        $this->attributeCollection = $attributeCollectionFactory->create();
        $this->configProcessor = $configProcessor;
    }

    public function execute(Observer $observer)
    {
        if (!$this->configProcessor->isEnabledTracking('add_to_cart')) {
            return $this;
        }
        $valueToAdd = $this->configProcessor->getValueAddToCart();
        $product = $observer->getEvent()->getProduct();
        $quote = $observer->getEvent()->getQuoteItem()->getQuote();
        $customerId = $quote->getCustomer()->getId();

        if (!$valueToAdd || !$customerId || !$product) {
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
