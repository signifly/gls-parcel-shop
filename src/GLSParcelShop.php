<?php

namespace Signifly\ParcelShop;

use Zend\Soap\Client;
use Illuminate\Support\Collection;
use Signifly\ParcelShop\Resources\ParcelShop;
use Signifly\ParcelShop\Contracts\ParcelShop as Contract;

class GLSParcelShop implements Contract
{
    /** @var \Zend\Soap\Client */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function all(string $countryCode): Collection
    {
        $response = $this->client->GetAllParcelShops([
            'countryIso3166A2' => $countryCode,
        ]);

        return collect(data_get($response, $this->getAllDataKey()))
            ->map(function ($data) {
                return new ParcelShop((array) $data);
            });
    }

    public function find($shopNumber): ParcelShop
    {
        $response = $this->client->GetOneParcelShop([
            'ParcelShopNumber' => $shopNumber,
        ]);

        return new ParcelShop((array) $response->GetOneParcelShopResult);
    }

    public function nearest(
        string $streetName,
        string $zipCode,
        string $countryCode,
        int $amount = 5
    ): Collection {
        $response = $this->client->SearchNearestParcelShops([
            'street' => $streetName,
            'zipcode' => $zipCode,
            'countryIso3166A2' => $countryCode,
            'Amount' => $amount,
        ]);

        return collect(data_get($response, $this->getNearestDataKey()))
            ->map(function ($data) {
                return new ParcelShop((array) $data);
            });
    }

    public function within(string $zipCode, string $countryCode): Collection
    {
        $response = $this->client->GetParcelShopsInZipcode([
            'zipcode' => $zipCode,
            'countryIso3166A2' => $countryCode,
        ]);

        return collect(data_get($response, $this->getWithinDataKey()))
            ->map(function ($data) {
                return new ParcelShop((array) $data);
            });
    }

    protected function getAllDataKey(): string
    {
        return 'GetAllParcelShopsResult.PakkeshopData';
    }

    protected function getNearestDataKey(): string
    {
        return 'SearchNearestParcelShopsResult.parcelshops.PakkeshopData';
    }

    protected function getWithinDataKey(): string
    {
        return 'GetParcelShopsInZipcodeResult.PakkeshopData';
    }
}
