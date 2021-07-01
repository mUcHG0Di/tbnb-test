<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, UsesUuid;

    /**
     * Mass assign attributes
     *
     * @var string[]
     */
    protected $fillable = [
        'name', 'description', 'price', 'quantity'
    ];
}
