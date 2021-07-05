<?php

namespace Tests\Feature\Http\Requests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class UpdateRequestTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->product->save();
    }

    /** @test */
    public function request_should_fail_when_no_name_is_provided()
    {
        $this->product->name = null;
        $response = $this->from(route('products.create'))
                        ->put(route('products.update', $this->product), $this->product->toArray());

        $this->checkSessionError($response, 'name');
    }

    /** @test */
    public function request_should_fail_when_name_has_more_than_64_characters()
    {
        $this->product->name = $this->faker->realText(); // Text longer than 64 chars
        $response = $this->from(route('products.create'))
                        ->put(route('products.update', $this->product), $this->product->toArray());

        $this->checkSessionError($response, 'name');
    }

    /** @test */
    public function request_should_fail_when_description_has_more_than_128_characters()
    {
        $this->product->description = $this->faker->realText(); // Text longer that 128 chars
        $response = $this->from(route('products.create'))
                        ->put(route('products.update', $this->product), $this->product->toArray());

        $this->checkSessionError($response, 'description');
    }

    /** @test */
    public function request_should_fail_when_no_price_is_provided()
    {
        $this->product->price = null;
        $response = $this->from(route('products.create'))
                        ->put(route('products.update', $this->product), $this->product->toArray());

        $this->checkSessionError($response, 'price');
    }

    /** @test */
    public function request_should_fail_when_price_is_not_numeric()
    {
        $this->product->price = 'twenty five';
        $response = $this->from(route('products.create'))
                        ->put(route('products.update', $this->product), $this->product->toArray());

        $this->checkSessionError($response, 'price');
    }

    /** @test */
    public function request_should_fail_when_price_value_is_lower_than_1()
    {
        $this->product->price = 0;
        $response = $this->from(route('products.create'))
                        ->put(route('products.update', $this->product), $this->product->toArray());

        $this->checkSessionError($response, 'price');
    }

    /** @test */
    public function request_should_fail_when_no_quantity_is_provided()
    {
        $this->product->quantity = null;
        $response = $this->from(route('products.create'))
                        ->put(route('products.update', $this->product), $this->product->toArray());

        $this->checkSessionError($response, 'quantity');
    }

    /** @test */
    public function request_should_fail_when_quantity_is_not_numeric()
    {

        $this->product->quantity = 'two';
        $response = $this->from(route('products.create'))
                        ->put(route('products.update', $this->product), $this->product->toArray());

        $this->checkSessionError($response, 'quantity');
    }

    /** @test */
    public function request_should_fail_when_quantity_value_is_lower_than_1()
    {

        $this->product->quantity = 0;
        $response = $this->from(route('products.create'))
                        ->put(route('products.update', $this->product), $this->product->toArray());

        $this->checkSessionError($response, 'quantity');
    }

    /** @test */
    public function request_should_fail_when_file_is_not_an_image()
    {
        $this->product->image = UploadedFile::fake()->create('product.pdf');
        $response = $this->from(route('products.create'))
                        ->put(route('products.update', $this->product), $this->product->toArray());

        $this->checkSessionError($response, 'image');
    }

    /** @test */
    public function request_should_fail_when_image_file_size_is_bigger_than_2_mb()
    {
        $this->product->image = UploadedFile::fake()->create('product.png', 5024);
        $response = $this->from(route('products.create'))
                        ->put(route('products.update', $this->product), $this->product->toArray());

        $this->checkSessionError($response, 'image');
    }

    /** @test */
    public function request_should_pass_when_data_is_provided()
    {
        $response = $this->from(route('products.create'))
                        ->put(route('products.update', $this->product), $this->product->toArray());

        $response->assertSessionHasNoErrors();
    }
}
