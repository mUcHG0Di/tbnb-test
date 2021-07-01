<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Return exception error if is for development
     *
     * @param \Exception $e
     * @return string
     */
    protected function getError(\Exception $e): string
    {
        return app()->environment() == 'local' ? $e->getMessage() : '';
    }
}
