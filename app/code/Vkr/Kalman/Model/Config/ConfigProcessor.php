<?php

namespace Vkr\Kalman\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;

class ConfigProcessor
{
    const VALUE_CREATE_ORDER = 5;
    const VALUE_ADD_TO_CART = 3;
    const VALUE_OPEN_PDP = 1;

    private $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function getValueCreateOrder()
    {
        return $this->scopeConfig->getValue('attributes/tracking/add_to_order_value') ?? self::VALUE_CREATE_ORDER;
    }

    public function getValueOpenPdp()
    {
        return $this->scopeConfig->getValue('attributes/tracking/open_pdp_value') ?? self::VALUE_OPEN_PDP;
    }

    public function getValueAddToCart()
    {
        return $this->scopeConfig->getValue('attributes/tracking/add_to_cart_value') ?? self::VALUE_ADD_TO_CART;
    }

    public function isEnabledTracking($code)
    {
        $data = $this->scopeConfig->getValue('attributes/tracking/enabled_tracking');
        if (empty($data)) {
            return false;
        }
        $data = explode(',', $data);
        if (in_array($code, $data)) {
            return true;
        }
        return false;
    }
}
