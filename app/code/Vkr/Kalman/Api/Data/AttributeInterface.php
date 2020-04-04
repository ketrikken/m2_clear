<?php

namespace Vkr\Kalman\Api\Data;

interface AttributeInterface
{
    const
        ID   = 'attribute_id',
        NAME = 'name'
    ;

    public function getName(): string;
    public function setName($name);

    public function getAttributeId();
    public function setAttributeId($id);

}
