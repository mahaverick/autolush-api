<?php

namespace App\Http\Controllers\Users;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\Users\CategoryResource;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Category::with('children')->parents()->ordered()->get();

        return CategoryResource::collection($categories);
    }
}
