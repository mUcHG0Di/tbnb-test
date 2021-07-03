<?php

namespace App\Models;

use App\Models\Concerns\HasHistory;
use App\Models\Concerns\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
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
        'uuid', 'name', 'description', 'price', 'quantity', 'image'
    ];

    /**
     * Fields that are not mass assignable
     *
     * @var string[]
     */
    protected $guarded = [
        'image_url'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'image_url'
    ];

    public function getImageUrlAttribute()
    {
        return Storage::url($this->getAttribute('image'));
    }

    /**
     * History relationship
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function history()
    {
        return $this->hasMany(ProductHistory::class)
                    ->orderBy('date', 'DESC');
    }
}
