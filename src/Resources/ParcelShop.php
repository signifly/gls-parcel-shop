<?php

namespace Signifly\ParcelShop\Resources;

use Illuminate\Support\Collection;

class ParcelShop extends Resource
{
    public function city(): string
    {
        return $this->getData('CityName');
    }

    public function company(): string
    {
        return $this->getData('CompanyName');
    }

    public function countryCode(): string
    {
        return $this->getData('CountryCodeISO3166A2');
    }

    public function distance(): int
    {
        return round($this->getData('DistanceMetersAsTheCrowFlies'), 0);
    }

    public function distanceInKm(): float
    {
        return round($this->distance() / 1000, 3);
    }

    public function latitude(): float
    {
        return $this->getData('Latitude');
    }

    public function longitude(): float
    {
        return $this->getData('Longitude');
    }

    public function number(): string
    {
        return $this->getData('Number');
    }

    public function openingHours(): Collection
    {
        return collect($this->getData('OpeningHours.Weekday'))
            ->map(function ($data) {
                return new OpeningHour((array) $data);
            });
    }

    public function streetName(): string
    {
        return $this->getData('Streetname');
    }

    public function streetName2(): string
    {
        return $this->getData('Streetname2');
    }

    public function zipCode(): string
    {
        return $this->getData('ZipCode');
    }
}
