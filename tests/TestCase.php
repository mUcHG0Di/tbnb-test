<?php

namespace Tests;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations;

    /**
     * Single product to test
     *
     * @var Producto
     */
    protected $product;

    /**
     * Collection of products to test
     *
     * @var Collection
     */
    protected $products;

    /**
     * Setup test
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->product = Product::factory()->makeOne();
        $this->products = Product::factory()->count(10)->make();

        $this->actingAs(User::factory()->create());
    }

    /**
     * Check if the given field was rejected
     *
     * @param Illuminate\Testing\TestResponse $response
     * @param string[] $field
     * @return void
     */
    protected function checkSessionError(TestResponse $response, mixed $fields)
    {
        // $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        $response->assertStatus(302)
                ->assertRedirect(route('products.create'))
                ->assertSessionHasErrors(Arr::wrap($fields));
    }
}
