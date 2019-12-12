<?php

namespace App\Http\Resources\Users;

use App\Services\Money;

class CartVariationResource extends VariationResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'product' => new ProductIndexResource($this->product),
            'quantity' => $this->pivot->quantity,
            'total' => $this->getTotal()->formatted(),
        ]);
    }

    /**
     * Returns total amount for variations.
     *
     * @return Money $total
     */
    protected function getTotal()
    {
        return new Money($this->pivot->quantity * $this->price->amount());
    }
}
