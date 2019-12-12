<?php

namespace App\Models;

use App\Traits\Orderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    use Orderable;

    protected $fillables = [
        'name',
        'slug',
        'order',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function scopeParents(Builder $builder)
    {
        $builder->whereNull('parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
