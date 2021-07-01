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
    public function validates_fields_on_store()
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

        $product = Product::first();
        $this->assertCount(1, $product->history);
    }

    /**
     * @test
     */
    public function it_stores_multiples_products()
    {
        $this->withoutExceptionHandling();
        $products = Product::factory()->count(10)->make();
        $postData = array('products' => $products->toArray());

        $response = $this->post(route('products.store.multiple'), $postData);
        $response->assertStatus(301)
                ->assertRedirect(route('products.index'));

        $this->assertDatabaseCount('products', $products->count())
            ->assertDatabaseCount('product_histories', $products->count());
    }

    /**
     * @test
     */
    public function it_updates_product()
    {
        $product = Product::factory()->create();
        $newName = 'Named edited';
        $product->name = $newName;

        dump(route('products.update.multiple,', $product->uuid));
        $response = $this->put(route('products.update', $product->uuid), $product->toArray());
        $response->assertStatus(301)
                ->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('products', [
            'name' => $newName
        ]);
    }

    public function it_updates_multiple_products()
    {
        $products = Product::factory()->count(10)->create();
        $postData = array('products' => $products->toArray());

        $response = $this->post(route('products.update.multiple'), $postData);
        $response->assertStatus(301)
                ->assertRedirect(route('products.index'));

        $this->assertDatabaseCount('products', $products->count());
            // ->assertDatabaseHas('products', $productos);
    }

    /**
     * @test
     */
    public function it_deletes_multiple_products()
    {
        $products = Product::factory()->count(10)->create();
        $products_uuids = $products->pluck('uuid')->toArray();
        $deleteData = [
            'products_uuids' => $products_uuids
        ];

        $response = $this->delete(route('products.destroy.multiple'), $deleteData);
        $response->assertStatus(301)
                ->assertRedirect(route('products.index'));

        $this->assertDatabaseMissing('products', array_map(fn($uuid) => [$uuid => 'uuid'], $products_uuids));
    }
}
