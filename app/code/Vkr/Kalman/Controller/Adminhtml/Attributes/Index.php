<?php
namespace Vkr\Kalman\Controller\Adminhtml\Attributes;

use Vkr\Kalman\Controller\Adminhtml\Attributes;

class Index extends Attributes
{
    public function execute()
    {

        $page = $this->resultPageFactory->create();
        $this
            ->initPage($page)
            ->getConfig()
            ->getTitle()
            ->prepend(__('Kalman'))
        ;

        return $page;
    }
}
