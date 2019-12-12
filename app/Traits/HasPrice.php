<?php

namespace App\Traits;

use App\Services\Money;

trait HasPrice
{
    public function getPriceAttribute($value)
    {
        return new Money($value * 100);
    }

    public function getFormattedPriceAttribute()
    {
        return $this->price->formatted();
    }
}
