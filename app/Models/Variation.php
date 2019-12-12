<?php

namespace App\Models;

use App\Services\Money;
use App\Traits\HasPrice;
use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    use HasPrice;

    /**
     * Relationship between stocks and products.
     *
     * @return collection
     */
    public function getPriceAttribute($value)
    {
        if ($value === null) {
            return $this->product->price;
        }

        return new Money($value * 100);
    }

    /**
     * Relationship between stocks and products.
     *
     * @return collection
     */
    public function priceVaries()
    {
        return $this->price->amount() !== $this->product->price->amount();
    }

    public function inStock()
    {
        return $this->stockCount() > 0;
    }

    public function stockCount()
    {
        return $this->stock->sum('pivot.stock');
    }

    /**
     * Relationship between stocks and products.
     *
     * @return collection
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relationship between stocks and products.
     *
     * @return collection
     */
    public function type()
    {
        return $this->belongsTo(VariationType::class, 'variation_type_id');
    }

    /**
     * Relationship between stocks and products.
     *
     * @return collection
     */
    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function stock()
    {
        return $this->belongsToMany(
            Variation::class, 'variation_stock_view'
        )->withPivot([
            'stock',
            'in_stock',
        ]);
    }
}
