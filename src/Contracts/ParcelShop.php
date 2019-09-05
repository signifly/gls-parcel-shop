<?php

namespace Signifly\ParcelShop\Contracts;

use Illuminate\Support\Collection;
use Signifly\ParcelShop\Resources\ParcelShop as ParcelShopResource;

interface ParcelShop
{
    public function all(string $countryCode): Collection;

    public function find($shopNumber): ParcelShopResource;

    public function nearest(
        string $streetName,
        string $zipCode,
        string $countryCode,
        int $amount = 5
    ): Collection;

    public function within(string $zipCode, string $countryCode): Collection;
}
