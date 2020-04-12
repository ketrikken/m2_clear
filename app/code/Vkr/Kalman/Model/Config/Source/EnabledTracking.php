<?php

namespace Vkr\Kalman\Model\Config\Source;

use Magento\Catalog\Model\CategoryRepository;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Option\ArrayInterface;
use Magento\Setup\Exception;
use Magento\Store\Model\StoreManagerInterface;
use Mygento\Goldapple\Model\CategoryManagement;

class EnabledTracking implements ArrayInterface
{

    protected $trackingCollection;

    protected function getTrackingCollection()
    {
        if (!$this->trackingCollection) {

            $this->trackingCollection = [
                'add_to_cart'  => 'Add to cart',
                'open_pdp'     => 'Open PDP',
                'create_order' => 'Create Order',
            ];

        }
        return $this->trackingCollection;
    }


    public function toArray()
    {
        $result = [];

        $collection = $this->getTrackingCollection();
        foreach ($collection as $key => $item) {
            $result [] =  [
                $key => $item
            ];
        }

        return $result;
    }

    public function toOptionArray()
    {
        $result = [];

        $collection = $this->getTrackingCollection();
        foreach ($collection as $key => $item) {
            $result [] =  [
                'label' => $item,
                'value' => $key
            ];
        }
        return $result;
    }
}
