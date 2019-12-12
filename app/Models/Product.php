<?php

namespace App\Models;

use App\Traits\HasPrice;
use App\Traits\CanBeScoped;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use CanBeScoped, HasPrice;

    /**
     * Get the route key.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function inStock()
    {
        return $this->stockCount() > 0;
    }

    public function stockCount()
    {
        return $this->variations->sum(function ($variation) {
            return $variation->stockCount();
        });
    }

    /**
     * Relationship between categories and products.
     *
     * @return collection
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Relationship between variations and products.
     *
     * @return collection
     */
    public function variations()
    {
        return $this->hasMany(Variation::class);
    }
}
