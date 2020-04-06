<?php

namespace Vkr\Kalman\Observer\AddValues;

use Magento\Checkout\Model\Type\Onepage;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class AddToCart implements ObserverInterface
{
    const VALUE_ADD_TO_CART = 3;

    private $customerAttributeRepository;
    private $attributeCollection;

    public function __construct(
        \Vkr\Kalman\Model\CustomerAttributeRepository $customerAttributeRepository,
        \Vkr\Kalman\Model\ResourceModel\Attribute\CollectionFactory $attributeCollectionFactory
    ) {
        $this->customerAttributeRepository = $customerAttributeRepository;
        $this->attributeCollection = $attributeCollectionFactory->create();
    }

    public function execute(Observer $observer)
    {
        $product = $observer->getEvent()->getProduct();
        $quote = $observer->getEvent()->getQuote();
        $customerId = $quote->getCustomer()->getId();

        if (!self::VALUE_ADD_TO_CART || !$customerId || !$product) {
            return $this;
        }
        $values = [];
        $attributeItems = $this->attributeCollection->getItems();
        foreach ($attributeItems as $attributeItem) {
            $productValue = $product->getData($attributeItem->getAttrivuteCode());
            if (!$productValue) {
                continue;
            }
            //id new model
            $values[$attributeItem->getAttributeId()] = self::VALUE_ADD_TO_CART;
        }
        $this->customerAttributeRepository->setValues($customerId, $values);
        return $this;
    }
}
