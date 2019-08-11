<?php

namespace Signifly\ParcelShop\Resources;

/**
 * @internal
 */
abstract class Resource
{
    /**
     * The data for the resource.
     *
     * @var array
     */
    protected $data;

    /**
     * Create a new Resource instance.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get data for the resource by the provided key.
     *
     * @param  string $key
     * @return mixed
     */
    public function getData(string $key)
    {
        return data_get($this->data, $key);
    }
}
