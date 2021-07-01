<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     */
    public function it_validates_fields_on_store()
    {
        $product = [
            'name' => null,
            'description' => $this->faker->realText(),
            'price' => 'some price',
            'quantity' => 'some quantity',
        ];
        $response = $this->post(route('products.store'), $product);

        $response->assertStatus(302)
                ->assertSessionHasErrors([
                    'name', 'description', 'price', 'quantity'
                ]);
    }

    /**
     * @test
     */
    public function it_stores_a_product()
    {
        $product = Product::factory()->make();
        $response = $this->post(route('products.store'), $product->toArray());

        $response->assertStatus(301)
                ->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('products', [
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'quantity' => $product->quantity,
        ]);
    }
}
