<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait HasHistory {

    protected static function bootHasHistory()
    {
        static::saved(function ($model) {
            $model->history()->create([
                'date' => now()
            ]);
        });
    }

}
