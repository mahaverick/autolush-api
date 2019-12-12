<?php

namespace Tests\Feature\Categories;

use Tests\TestCase;
use App\Models\Category;

class CategoryIndexTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_a_collection_of_categories()
    {
        $categories = factory(Category::class, 2)->create();

        $this->json('GET', 'api/v1/users/categories')->assertJsonFragment([
            'slug' => $categories[0]->slug,
            'slug' => $categories[1]->slug,
        ]);
    }

    public function test_get_categories_in_order()
    {
        $categories = factory(Category::class, 2)->create();
        $this->json('GET', 'api/v1/users/categories')->assertSeeInOrder([$categories[0]->slug, $categories[1]->slug]);
    }

    public function test_get_only_parent_categories()
    {
        $category = factory(Category::class)->create();

        $category->children()->save(
            $subcategory = factory(Category::class)->create(),
        );
        $this->json('GET', 'api/v1/users/categories')->assertJsonCount(1, 'data');
    }
}
