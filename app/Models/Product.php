<?php

namespace App\Models;

use App\Models\Concerns\HasHistory;
use App\Models\Concerns\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \WF\Batch\Traits\Batchable;

class Product extends Model
{
    use HasFactory, UsesUuid, HasHistory, Batchable;

    /**
     * Mass assign attributes
     *
     * @var string[]
     */
    protected $fillable = [
        'uuid', 'name', 'description', 'price', 'quantity'
    ];

    /**
     * History relationship
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function history()
    {
        return $this->hasMany(ProductHistory::class);
    }
}
