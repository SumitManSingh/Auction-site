<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;
    protected $primaryKey = 'category_id';
    protected $table = 'categories';

    protected $fillable = [
        'category_name',
        'description',
    ];

     /**
     * A category can contain many items.
     */
    public function items()
    {
        return $this->hasMany(Item::class, 'category_id');
    }
}
