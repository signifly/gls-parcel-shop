<?php

namespace Signifly\ParcelShop\Resources;

abstract class Resource
{
    /** @var array */
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getData(string $key)
    {
        return data_get($this->data, $key);
    }
}
