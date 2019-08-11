<?php

namespace Signifly\ParcelShop\Contracts;

use Illuminate\Support\Collection;
use Signifly\ParcelShop\Resources\ParcelShop;

interface ParcelShop
{
    public function all(string $countryCode): Collection;

    public function find($shopNumber): ParcelShop;

    public function nearest(
        string $streetName,
        string $zipCode,
        string $countryCode,
        int $amount = 5
    ): Collection;

    public function within(string $zipCode, string $countryCode): Collection;
}
