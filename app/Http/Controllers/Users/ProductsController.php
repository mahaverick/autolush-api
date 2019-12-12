<?php

namespace App\Http\Controllers\Users;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Scoping\Scopes\CategoryScope;
use App\Http\Resources\Users\ProductResource;
use App\Http\Resources\Users\ProductIndexResource;

class ProductsController extends Controller
{
    protected function scopes()
    {
        return [
            'category' => new CategoryScope(),
        ];
    }

    /**
     * Shows all products.
     *
     * @return ProductResource collection of Products
     */
    public function index()
    {
        $products = Product::with(['variations.stock'])->withScopes($this->scopes())->paginate(10);

        return ProductIndexResource::collection($products);
    }

    /**
     * Shows selected product.
     *
     * @param Product $product [description]
     *
     * @return ProductResource object of product
     */
    public function show(Product $product)
    {
        $product->load(['variations.type', 'variations.stock', 'variations.product']);

        return new ProductResource($product);
    }
}
