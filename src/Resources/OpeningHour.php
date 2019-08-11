<?php

namespace Signifly\ParcelShop\Resources;

class OpeningHour extends Resource
{
    public function day(): string
    {
        return $this->getData('day');
    }

    public function from(): string
    {
        return $this->getData('openAt.From');
    }

    public function to(): string
    {
        return $this->getData('openAt.To');
    }
}
