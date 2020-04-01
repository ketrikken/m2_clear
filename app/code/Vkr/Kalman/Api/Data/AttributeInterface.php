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

}
