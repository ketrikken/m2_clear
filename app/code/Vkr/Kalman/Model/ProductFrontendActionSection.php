<?php
namespace Vkr\Kalman\Model;

use Magento\Catalog\Model\Product\ProductFrontendAction\Synchronizer;
use Magento\Catalog\Model\ProductFrontendAction;
use Magento\Customer\CustomerData\SectionSourceInterface;
use Magento\Framework\App\Config;
use Psr\Log\LoggerInterface;

/**
 * Generates Product Frontend Action Section in Customer Data
 */
class ProductFrontendActionSection implements SectionSourceInterface
{
    /**
     * Identification of Type of a Product Frontend Action
     *
     * @var string
     */
    private $typeId;

    /**
     * @var Synchronizer
     */
    private $synchronizer;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Config
     */
    private $appConfig;

    /**
     * @param Synchronizer $synchronizer
     * @param string $typeId Identification of Type of a Product Frontend Action
     * @param LoggerInterface $logger
     * @param Config $appConfig
     */
    public function __construct(
        Synchronizer $synchronizer,
        $typeId,
        LoggerInterface $logger,
        Config $appConfig
    ) {
        $this->typeId = $typeId;
        $this->synchronizer = $synchronizer;
        $this->logger = $logger;
        $this->appConfig = $appConfig;
    }

    /**
     * Post Process collection data in order to eject all customer sensitive information
     *
     * {@inheritdoc}
     */
    public function getSectionData()
    {
        $actions = $this->synchronizer->getActionsByType($this->typeId);
        $items = [];


        foreach ($actions as $action) {
            $items[$action->getProductId()] = [
                'added_at' => $action->getAddedAt(),
                'product_id' => $action->getProductId(),
            ];
        }

    /*    $items[1514] = [
            'added_at'   => '1586780554.07',
            'product_id' => '1514'
        ];
        $items[1518] = [
            'added_at'   => '1586780554.07',
            'product_id' => '2252'
        ];
        $items[724] = [
            'added_at'   => '1586803278.318',
            'product_id' => '724'
        ];
    */
        $items[1450] =
                array (
                    'add_to_cart_button' =>
                        array (
                            'post_data' => '{"action":"http:\\/\\/m2.loc\\/checkout\\/cart\\/add\\/uenc\\/%25uenc%25\\/product\\/1450\\/","data":{"product":"1450","uenc":"%uenc%"}}',
                            'url' => 'http://m2.loc/checkout/cart/add/uenc/%25uenc%25/product/1450/',
                            'required_options' => true,
                        ),
                    'add_to_compare_button' =>
                        array (
                            'post_data' => NULL,
                            'url' => '{"action":"http:\\/\\/m2.loc\\/catalog\\/product_compare\\/add\\/","data":{"product":"1450","uenc":"aHR0cDovL20yLmxvYy9sYXlsYS10ZWUuaHRtbA,,"}}',
                            'required_options' => NULL,
                        ),
                    'price_info' =>
                        array (
                            'final_price' => 29,
                            'max_price' => 29,
                            'max_regular_price' => 29,
                            'minimal_regular_price' => 29,
                            'special_price' => NULL,
                            'minimal_price' => 29,
                            'regular_price' => 29,
                            'formatted_prices' =>
                                array (
                                    'final_price' => '<span class="price">$29.00</span>',
                                    'max_price' => '<span class="price">$29.00</span>',
                                    'minimal_price' => '<span class="price">$29.00</span>',
                                    'max_regular_price' => '<span class="price">$29.00</span>',
                                    'minimal_regular_price' => NULL,
                                    'special_price' => NULL,
                                    'regular_price' => '<span class="price">$29.00</span>',
                                ),
                            'extension_attributes' =>
                                array (
                                    'msrp' =>
                                        array (
                                            'msrp_price' => '<span class="price">$0.00</span>',
                                            'is_applicable' => '',
                                            'is_shown_price_on_gesture' => '1',
                                            'msrp_message' => '',
                                            'explanation_message' => '',
        ),
        'tax_adjustments' =>
        array (
          'final_price' => 29,
          'max_price' => 29,
          'max_regular_price' => 29,
          'minimal_regular_price' => 29,
          'special_price' => 29,
          'minimal_price' => 29,
          'regular_price' => 29,
          'formatted_prices' =>
          array (
            'final_price' => '<span class="price">$29.00</span>',
            'max_price' => '<span class="price">$29.00</span>',
            'minimal_price' => '<span class="price">$29.00</span>',
            'max_regular_price' => '<span class="price">$29.00</span>',
            'minimal_regular_price' => NULL,
            'special_price' => '<span class="price">$29.00</span>',
            'regular_price' => '<span class="price">$29.00</span>',
          ),
        ),
        'weee_attributes' =>
        array (
        ),
        'weee_adjustment' => '<span class="price">$29.00</span>',
      ),
    ),
    'images' =>
    array (
      0 =>
      array (
        'url' => 'http://m2.loc/media/catalog/product/cache/ad83761a5a36971adcb35b301dd06b4d/w/s/ws04-green_main_1.jpg',
        'code' => 'recently_viewed_products_grid_content_widget',
        'height' => 300,
        'width' => 240,
        'label' => 'Layla Tee',
        'resized_width' => 240,
        'resized_height' => 300,
      ),
      1 =>
      array (
          'url' => 'http://m2.loc/media/catalog/product/cache/0db21afcf93d651ade2d2c1d566fb8fb/w/s/ws04-green_main_1.jpg',
          'code' => 'recently_viewed_products_list_content_widget',
          'height' => 340,
          'width' => 270,
          'label' => 'Layla Tee',
          'resized_width' => 270,
          'resized_height' => 340,
      ),
      2 =>
      array (
          'url' => 'http://m2.loc/media/catalog/product/cache/0c6ccf8e3139800d61381c74a17d931b/w/s/ws04-green_main_1.jpg',
          'code' => 'recently_viewed_products_images_names_widget',
          'height' => 90,
          'width' => 75,
          'label' => 'Layla Tee',
          'resized_width' => 75,
          'resized_height' => 90,
      ),
      3 =>
      array (
          'url' => 'http://m2.loc/media/catalog/product/cache/ad83761a5a36971adcb35b301dd06b4d/w/s/ws04-green_main_1.jpg',
          'code' => 'recently_compared_products_grid_content_widget',
          'height' => 300,
          'width' => 240,
          'label' => 'Layla Tee',
          'resized_width' => 240,
          'resized_height' => 300,
      ),
      4 =>
      array (
          'url' => 'http://m2.loc/media/catalog/product/cache/0db21afcf93d651ade2d2c1d566fb8fb/w/s/ws04-green_main_1.jpg',
          'code' => 'recently_compared_products_list_content_widget',
          'height' => 340,
          'width' => 270,
          'label' => 'Layla Tee',
          'resized_width' => 270,
          'resized_height' => 340,
      ),
      5 =>
      array (
          'url' => 'http://m2.loc/media/catalog/product/cache/0c6ccf8e3139800d61381c74a17d931b/w/s/ws04-green_main_1.jpg',
          'code' => 'recently_compared_products_images_names_widget',
          'height' => 90,
          'width' => 75,
          'label' => 'Layla Tee',
          'resized_width' => 75,
          'resized_height' => 90,
      ),
    ),
    'url' => 'http://m2.loc/layla-tee.html',
    'id' => 1450,
    'name' => 'Layla Tee',
    'type' => 'configurable',
    'is_salable' => '1',
    'store_id' => 1,
    'currency_code' => 'USD',
    'extension_attributes' =>
    array (
        'review_html' => '        <div class="product-reviews-summary short">
                <div class="rating-summary">
            <span class="label"><span>Rating:</span></span>
            <div class="rating-result" title="60%">
                <span style="width:60%"><span>60%</span></span>
            </div>
        </div>
                <div class="reviews-actions">
            <a class="action view" href="http://m2.loc/layla-tee.html#reviews">2 <span>Reviews</span></a>
        </div>
    </div>
',
        'wishlist_button' =>
            array (
                'post_data' => NULL,
                'url' => '{"action":"http:\\/\\/m2.loc\\/wishlist\\/index\\/add\\/","data":{"product":1450,"uenc":"aHR0cDovL20yLmxvYy9sYXlsYS10ZWUuaHRtbA,,"}}',
                'required_options' => NULL,
            ),
    ),
);



        return [
            'count' => 500,//count($items),
            'items' => $items,
        ];
    }
}
