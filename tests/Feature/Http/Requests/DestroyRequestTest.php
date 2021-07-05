<?php

namespace Tests\Feature\Http\Requests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DestroyRequestTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function request_should_fail_when_products_uuids_is_not_provided()
    {
        $deleteData = [];

        $response = $this->from(route('products.create'))
                        ->delete(route('products.destroy.multiple'), $deleteData);

        $this->checkSessionError($response, 'products_uuids');
    }

    /** @test */
    public function request_should_fail_when_products_uuids_is_empty()
    {
        $deleteData = [
            'products_uuids' => []
        ];

        $response = $this->from(route('products.create'))
                        ->delete(route('products.destroy.multiple'), $deleteData);

        $this->checkSessionError($response, 'products_uuids');
    }

    /** @test */
    public function request_should_fail_when_a_products_uuid_is_invalid()
    {
        $deleteData = [
            'products_uuids' => ['kjasdfu']
        ];

        $response = $this->from(route('products.create'))
                        ->delete(route('products.destroy.multiple'), $deleteData);

        $this->checkSessionError($response, 'products_uuids.0');
    }

}
