<?php

namespace App\Models\Concerns;

trait HasHistory {

    protected static function bootHasHistory()
    {
        static::saved(function ($model) {
            // If it is created or the quantity was updated,
            // save the histoty
            if ($model->wasRecentlyCreated || $model->wasChanged('quantity')) {
                $model->history()->create([
                    'quantity' => $model->quantity,
                    'date' => now(),
                    'user_id' => auth()->user()->id,
                ]);
            }
        });
    }

}
