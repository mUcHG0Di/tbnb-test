<?php

namespace App\Models\Concerns;

use App\Models\User;
use App\Notifications\ProductQuantityUpdated;

trait BelongsToUser {

    protected static function bootBelongsToUser()
    {
        static::creating(function ($model) {
            // Append user ID to the model
            $model->owner_id = auth()->user()->id;
        });

        static::updated(function($model) {
            // Notify owner if is not the one editing and the quantity was updated
            if (auth()->user()->id != $model->owner_id && $model->wasChanged('quantity')) {
                $model->owner->notify(new ProductQuantityUpdated($model));
            }
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

}
