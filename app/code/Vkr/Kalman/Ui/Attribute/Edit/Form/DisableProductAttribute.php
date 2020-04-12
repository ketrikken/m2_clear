<?php

namespace Vkr\Kalman\Ui\Attribute\Edit\Form;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Form\Field;

class DisableProductAttribute extends Field
{
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepare()
    {
        parent::prepare();
        $config = $this->getData('config');
        if ($this->getContext()->getRequestParam('attribute_id')) {
            $config['disabled'] = true;
            $this->setData('config', $config);
        }
    }
}
