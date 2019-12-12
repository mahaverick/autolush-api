<?php

namespace App\Services;

use App\Models\User;

class Cart
{
    /**
     * User passed to the cart instance.
     *
     * @var User
     */
    protected $user;

    /**
     * Constructor for the cart class.
     *
     * @param User $user [description]
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Adds variations to user cart.
     *
     * @param array $variations [description]
     */
    public function add(array $variations)
    {
        $this->user->cart()->syncWithoutDetaching($this->formatPayload($variations));
    }

    /**
     * Updates specified variation to user cart.
     *
     * @param int $variation_id
     * @param int $quantity
     */
    public function update(int $variation_id, int $quantity)
    {
        $this->user->cart()->updateExistingPivot($variation_id, [
            'quantity' => $quantity,
        ]);
    }

    /**
     * Deletes specified variation from user cart.
     *
     * @param int $variation_id
     */
    public function destroy(int $variation_id)
    {
        $this->user->cart()->detach($variation_id);
    }

    /**
     * Empties all variations from user cart.
     */
    public function empty()
    {
        $this->user->cart()->detach();
    }

    /**
     * This method formats the payload in a proper array structure.
     *
     * @param  array  $variations
     *
     * @return array $variations [formatted variations array]
     */
    protected function formatPayload(array $variations)
    {
        return collect($variations)->keyBy('id')->map(function ($variation) {
            return [
                'quantity' => $variation['quantity'] + $this->currentQuantity($variation['id']),
            ];
        })->toArray();
    }

    /**
     * Returns the current qunatity in the cart pivot.
     *
     * @param  int $variation_id
     *
     * @return int quantity
     */
    protected function currentQuantity(int $variation_id)
    {
        if ($variation = $this->user->cart->where('id', $variation_id)->first()) {
            return $variation->pivot->quantity;
        }

        return 0;
    }
}
