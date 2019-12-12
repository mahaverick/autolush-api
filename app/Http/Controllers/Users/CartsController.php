<?php

namespace App\Http\Controllers\Users;

use App\Services\Cart;
use App\Models\Variation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Users\CartResource;

class CartsController extends Controller
{
    /**
     * Return cart items.
     *
     * @param Request $request
     *
     * @return [type] [description]
     */
    public function index(Request $request)
    {
        $request->user()->load([
            'cart.product',
            'cart.product.variations.stock',
            'cart.stock',
        ]);

        return new CartResource($request->user());
    }

    /**
     * Stores product variations and quanitities to the cart.
     *
     * @param Request $request
     *
     * @return [type] [description]
     */
    public function store(Request $request, Cart $cart)
    {
        $this->validate($request, [
            'variations' => 'required|array',
            'variations.*.id' => 'required|exists:variations,id',
            'variations.*.quantity' => 'numeric|min:1',
        ]);

        $cart->add($request->variations);
    }

    /**
     * Updates the cart.
     *
     * @param Request   $request
     * @param Variation $variation
     * @param Cart      $cart
     *
     * @return resource
     */
    public function update(Request $request, Variation $variation, Cart $cart)
    {
        $this->validate($request, [
            'quantity' => 'required|numeric|min:1',
        ]);

        $cart->update($variation->id, $request->quantity);
    }

    /**
     * Destroys the item from cart.
     *
     * @param Variation $variation
     * @param Cart $cart
     *
     * @return [type]
     */
    public function destroy(Variation $variation, Cart $cart)
    {
        $cart->destroy($variation->id);
    }
}
