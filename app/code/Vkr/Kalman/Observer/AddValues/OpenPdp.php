<?php

namespace Vkr\Kalman\Observer\AddValues;

use Magento\Checkout\Model\Type\Onepage;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class OpenPdp implements ObserverInterface
{
    const VALUE_OPEN_PDP = 1;

    private $customerAttributeRepository;
    private $attributeCollection;
    private $customerSession;
    private $product;

    public function __construct(
        \Vkr\Kalman\Model\CustomerAttributeRepository $customerAttributeRepository,
        \Vkr\Kalman\Model\ResourceModel\Attribute\CollectionFactory $attributeCollectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Catalog\Model\ProductFactory $productFactory
    ) {
        $this->customerAttributeRepository = $customerAttributeRepository;
        $this->attributeCollection = $attributeCollectionFactory->create();
        $this->customerSession = $customerSession;
        $this->product = $productFactory->create();
    }

    public function execute(Observer $observer)
    {
        $productId = $observer->getEvent()->getProductId();
        $product = $this->product->load($productId);
        $customerId = $this->customerSession->getCustomer()->getId();

        if (!self::VALUE_OPEN_PDP || !$customerId || !$product || !$product->getId()) {
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
            $values[$attributeItem->getProductAttribute()] = self::VALUE_OPEN_PDP;
        }
        $this->customerAttributeRepository->setValues($customerId, $values);
        return $this;
    }
}
