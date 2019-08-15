<?php

namespace Signifly\ParcelShop;

use Zend\Soap\Client;
use Illuminate\Support\Collection;
use Signifly\ParcelShop\Resources\ParcelShop;
use Signifly\ParcelShop\Contracts\ParcelShop as Contract;

class GLSParcelShop implements Contract
{
    /**
     * The Soap client instance.
     *
     * @var \Zend\Soap\Client
     */
    protected $client;

    /**
     * Create a new GLSParcelShop instance.
     *
     * @param \Zend\Soap\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Retrieve all parcel shops for a specific country.
     *
     * @param  string $countryCode
     * @return \Illuminate\Support\Collection
     */
    public function all(string $countryCode): Collection
    {
        $response = $this->client->GetAllParcelShops([
            'countryIso3166A2' => $countryCode,
        ]);

        return $this->transformCollection(
            data_get($response, $this->getAllDataKey())
        );
    }

    /**
     * Find a given parcel shop by its number.
     *
     * @param  string|int $shopNumber
     * @return ParcelShop
     */
    public function find($shopNumber): ParcelShop
    {
        $response = $this->client->GetOneParcelShop([
            'ParcelShopNumber' => $shopNumber,
        ]);

        return new ParcelShop((array) $response->GetOneParcelShopResult);
    }

    /**
     * Retrieve parcel shops near an address.
     *
     * @param  string      $streetName
     * @param  string      $zipCode
     * @param  string      $countryCode
     * @param  int $amount
     * @return \Illuminate\Support\Collection
     */
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

        return $this->transformCollection(
            data_get($response, $this->getNearestDataKey())
        );
    }

    /**
     * Retrieve parcel shops within a given zip code and country code.
     *
     * @param  string $zipCode
     * @param  string $countryCode
     * @return \Illuminate\Support\Collection
     */
    public function within(string $zipCode, string $countryCode): Collection
    {
        $response = $this->client->GetParcelShopsInZipcode([
            'zipcode' => $zipCode,
            'countryIso3166A2' => $countryCode,
        ]);

        return $this->transformCollection(
            data_get($response, $this->getWithinDataKey())
        );
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

    protected function transformCollection(array $items): Collection
    {
        return collect($items)
            ->map(function ($item) {
                return new ParcelShop((array) $data);
            });
    }
}
