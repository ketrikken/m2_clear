<?php

namespace Vkr\Kalman\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

use Magento\Framework\Api\SearchResultsInterface;
use Vkr\Kalman\Api\Data\AttributeInterface;

/**
 * @api
 */
interface AttributeRepositoryInterface
{
    /**
     * @param int|null $id
     * @return AttributeInterface
     */
    public function get($id): AttributeInterface;

    /**
     * @param AttributeInterface $banner
     * @return void
     */
    public function save(AttributeInterface $banner);

    /**
     * @param AttributeInterface $banner
     * @return void
     */
    public function delete(AttributeInterface $banner);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria = null);
}
