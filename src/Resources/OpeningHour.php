<?php

namespace Signifly\ParcelShop\Resources;

class OpeningHour extends Resource
{
    /**
     * Get the day.
     *
     * @return string
     */
    public function day(): string
    {
        return $this->getData('day');
    }

    /**
     * Get the from time.
     *
     * @return string
     */
    public function from(): string
    {
        return $this->getData('openAt.From');
    }

    /**
     * Get the to time.
     *
     * @return string
     */
    public function to(): string
    {
        return $this->getData('openAt.To');
    }
}
