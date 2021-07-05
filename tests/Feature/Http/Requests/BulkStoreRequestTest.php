<?php

namespace Tests\Feature\Http\Requests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class BulkStoreRequestTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function request_should_fail_if_a_name_is_not_provided()
    {
        $this->products->each(fn($product) => $product->setAttribute('name', null));
        $response = $this->from(route('products.create'))
                        ->post(route('products.store.multiple'), $this->products->toArray());

        $this->checkSessionError($response, 'products');
    }

    /** @test */
    public function request_should_fail_if_a_name_has_more_than_64_characters()
    {
        $this->products->each(fn($product) => $product->setAttribute('name', $this->faker->realText()));
        $response = $this->from(route('products.create'))
                        ->post(route('products.store.multiple'), $this->products->toArray());

        $this->checkSessionError($response, 'products');
    }

    /** @test */
    public function request_should_fail_if_a_description_has_more_than_128_characters()
    {
        $this->products->each(fn($product) => $product->setAttribute('description', $this->faker->realText()));
        $response = $this->from(route('products.create'))
                        ->post(route('products.store.multiple'), $this->products->toArray());

        $this->checkSessionError($response, 'products');
    }

    /** @test */
    public function request_should_fail_if_a_price_is_not_provided()
    {
        $this->products->each(fn($product) => $product->setAttribute('price', null));
        $response = $this->from(route('products.create'))
                        ->post(route('products.store.multiple'), $this->products->toArray());

        $this->checkSessionError($response, 'products');
    }

    /** @test */
    public function request_should_fail_if_a_price_is_not_numeric()
    {
        $this->products->each(fn($product) => $product->setAttribute('price', 'twenty five'));
        $response = $this->from(route('products.create'))
                        ->post(route('products.store.multiple'), $this->products->toArray());

        $this->checkSessionError($response, 'products');
    }

    /** @test */
    public function request_should_fail_if_a_price_value_is_less_than_1()
    {
        $this->products->each(fn($product) => $product->setAttribute('price', 0));
        $response = $this->from(route('products.create'))
                        ->post(route('products.store.multiple'), $this->products->toArray());

        $this->checkSessionError($response, 'products');
    }

    /** @test */
    public function request_should_fail_if_a_quantity_is_not_provided()
    {
        $this->products->each(fn($product) => $product->setAttribute('quantity', null));
        $response = $this->from(route('products.create'))
                        ->post(route('products.store.multiple'), $this->products->toArray());

        $this->checkSessionError($response, 'products');
    }

    /** @test */
    public function request_should_fail_if_a_quantity_is_not_numeric()
    {
        $this->products->each(fn($product) => $product->setAttribute('quantity', 'twenty five'));
        $response = $this->from(route('products.create'))
                        ->post(route('products.store.multiple'), $this->products->toArray());

        $this->checkSessionError($response, 'products');
    }

    /** @test */
    public function request_should_fail_if_a_quantity_value_is_less_than_1()
    {
        $this->products->each(fn($product) => $product->setAttribute('quantity', 0));
        $response = $this->from(route('products.create'))
                        ->post(route('products.store.multiple'), $this->products->toArray());

        $this->checkSessionError($response, 'products');
    }
}
