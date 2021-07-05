<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductHistory extends Model
{
    use HasFactory;

    /**
     * Determine if the model uses timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Mass assign attributes
     *
     * @var string[]
     */
    protected $fillable = ['quantity', 'date', 'user_id'];

    /**
     * Autoload user involved
     *
     * @var string[]
     */
    protected $with = [
        'user'
    ];

    /**
     * Belongs to product relation
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * User who create/update the product quantity
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
