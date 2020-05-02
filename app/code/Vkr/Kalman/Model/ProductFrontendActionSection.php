<?php
namespace Vkr\Kalman\Model;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product\ProductFrontendAction\Synchronizer;
use Magento\Catalog\Model\ProductRenderFactory;
use Magento\Catalog\Model\ProductRepository;
use Magento\Catalog\Ui\DataProvider\Product\ProductRenderCollectorComposite;
use Magento\Customer\CustomerData\SectionSourceInterface;
use Magento\Customer\Model\Session;
use Magento\Customer\Model\Visitor;
use Magento\Framework\App\Config;
use Magento\Framework\EntityManager\Hydrator;
use Magento\Framework\Registry;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\Url;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManager;
use Psr\Log\LoggerInterface;

/**
 * Generates Product Frontend Action Section in Customer Data
 */
class ProductFrontendActionSection implements SectionSourceInterface
{
    private $typeId;
    private $synchronizer;
    private $logger;
    private $session;
    private $visitor;
    private $appConfig;
    private $productRepository;
    private $productRenderCollectorComposite;
    private $storeManager;
    private $productRenderFactory;
    private $hydrator;
    private $serialize;
    private $url;
    private $registry;
    protected $productCollectionFactory;

    /**
     * @param Synchronizer $synchronizer
     * @param string $typeId Identification of Type of a Product Frontend Action
     * @param LoggerInterface $logger
     * @param Config $appConfig
     */
    public function __construct(
        Session $session,
        Visitor $visitor,
        Synchronizer $synchronizer,
        $typeId,
        LoggerInterface $logger,
        Config $appConfig,
        ProductRepository $productRepository,
        ProductRenderCollectorComposite $productRenderCollectorComposite,
        StoreManager $storeManager,
        ProductRenderFactory $productRenderFactory,
        Hydrator $hydrator,
        SerializerInterface $serialize,
        Url $url,
        Registry $registry,
        \Vkr\Kalman\Model\ProductCollectionProcessor $productCollectionFactory
    ) {
        $this->typeId = $typeId;
        $this->synchronizer = $synchronizer;
        $this->logger = $logger;
        $this->appConfig = $appConfig;
        $this->session = $session;
        $this->visitor = $visitor;
        $this->productRepository = $productRepository;
        $this->productRenderCollectorComposite = $productRenderCollectorComposite;
        $this->storeManager = $storeManager;
        $this->productRenderFactory = $productRenderFactory;
        $this->hydrator = $hydrator;
        $this->serialize = $serialize;
        $this->url = $url;
        $this->registry = $registry;
        $this->productCollectionFactory = $productCollectionFactory;
    }

    /**
     * Post Process collection data in order to eject all customer sensitive information
     *
     * {@inheritdoc}
     */
    public function getSectionData()
    {

        $items = [];

        $customerId = $this->session->getCustomerId();
        $store = $this->storeManager->getStore();

        if (!$customerId) {
            return [
                'count' => count($items),
                'items' => $items,
            ];
        }

        $productCollection = $this->productCollectionFactory->getProducts($customerId);

        foreach ($productCollection as $item) {

            $product = $this->productRepository->get($item->getSku(), $store);
            $items[$product->getId()] = $this->getCurrentProductData($product, $store);
        }

        return [
            'count' => count($items),
            'items' => $items,
        ];
    }
    public function getCurrentProductData($product, $store)
    {
        if (!$product || !$product->getId()) {
            return ([]);
        }

        $productRender = $this->productRenderFactory->create();

        $productRender->setStoreId($store->getId());
        $productRender->setCurrencyCode($store->getCurrentCurrencyCode());
        $this->productRenderCollectorComposite
            ->collect($product, $productRender);
        $data = $this->hydrator->extract($productRender);



        return ($data);
    }
}
