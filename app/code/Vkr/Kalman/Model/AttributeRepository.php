<?php
namespace Vkr\Kalman\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Vkr\Kalman\Api\AttributeRepositoryInterface;
use Vkr\Kalman\Api\Data\AttributeInterface;
use Vkr\Kalman\Model\ResourceModel\Attribute\CollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Vkr\Kalman\Api\Data\AttributeInterfaceFactory;
use Vkr\Kalman\Model\ResourceModel\Attribute as ResourceModel;

class AttributeRepository implements AttributeRepositoryInterface
{
    private $bannerFactory;
    private $resourceModel;
    protected $searchCriteriaBuilder;
    protected $collectionFactory;
    protected $collectionProcessor;
    protected $searchResultsFactory;
    protected $dataObjectFactory;
    protected $dataObjectHelper;

    public function __construct(
        \Vkr\Kalman\Model\AttributeFactory $bannerFactory,
        ResourceModel $resourceModel,
        CollectionProcessorInterface $collectionProcessor,
        SearchResultsInterfaceFactory $searchResultsFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        AttributeInterfaceFactory $dataObjectFactory,
        DataObjectHelper $dataObjectHelper,
        CollectionFactory $collectionFactory
    ) {
        $this->bannerFactory = $bannerFactory;
        $this->resourceModel = $resourceModel;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->collectionFactory = $collectionFactory;
        $this->dataObjectFactory = $dataObjectFactory;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    public function get($id): AttributeInterface
    {
        $banner = $this->bannerFactory->create();
        $this->resourceModel->load($banner, $id);

        if (!$banner->getId()) {
            throw new NoSuchEntityException(__('Kalman not found'));
        }
        return $banner;
    }

    public function save(AttributeInterface $banner)
    {
        $this->resourceModel->save($banner);
    }

    public function delete(AttributeInterface $banner)
    {
        try {
            $this->resourceModel->delete($banner);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
    }

    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null): SearchResultsInterface
    {
        if (!$searchCriteria) {
            $searchCriteria = $this->searchCriteriaBuilder->create();
        }

        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $dataObjects = [];
        foreach ($collection as $model) {
            $dataObjects[] = $this->toDataObject($model);
        }

        /* @var $searchResults \Magento\Framework\Api\SearchResultsInterface */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults
            ->setSearchCriteria($searchCriteria)
            ->setItems($dataObjects)
            ->setTotalCount($collection->getSize());

        return $searchResults;
    }

    protected function toDataObject(\Vkr\Kalman\Model\Kalman $model)
    {
        $dataObject = $this->dataObjectFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $dataObject,
            $model->getData(),
            AttributeInterface::class
        );

        return $dataObject;
    }
}
