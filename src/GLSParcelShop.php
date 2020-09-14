<?php

namespace Signifly\ParcelShop;

use Illuminate\Support\Collection;
use Laminas\Soap\Client;
use Signifly\ParcelShop\Contracts\ParcelShop as Contract;
use Signifly\ParcelShop\Resources\ParcelShop;

class GLSParcelShop implements Contract
{
    /**
     * The Soap client instance.
     *
     * @var \Laminas\Soap\Client
     */
    protected $client;

    /**
     * Create a new GLSParcelShop instance.
     *
     * @param \Laminas\Soap\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Instantiate using only an endpoint.
     *
     * @param  string|null $endpoint
     * @return self
     */
    public static function make(?string $endpoint = null): self
    {
        $client = new Client($endpoint ?? 'http://www.gls.dk/webservices_v4/wsShopFinder.asmx?WSDL', [
            'connection_timeout' => 60,
            'keep_alive' => false,
        ]);

        return new self($client);
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

        $items = data_get($response, $this->getWithinDataKey());

        return $this->transformCollection(
            is_array($items) ? $items : [$items]
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
                return new ParcelShop((array) $item);
            });
    }
}
