<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * @test
     */
    public function validates_fields_on_save()
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
    public function it_stores_a_single_product()
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
    public function validates_fields_on_bulk_save()
    {
        $products = [
            'products' => [
                []
            ]
        ];
        $response = $this->post(route('products.store.multiple'), $products);

        $response->assertStatus(302)
                ->assertSessionHasErrors([
                    'products.0.name', 'products.0.description', 'products.0.price', 'products.0.quantity'
                ]);
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
    public function it_updates_a_single_product()
    {
        $product = Product::factory()->create();
        $newName = 'Named edited';
        $product->name = $newName;

        $response = $this->put(route('products.update', $product), $product->toArray());
        $response->assertStatus(301)
                ->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('products', [
            'name' => $newName
        ]);
    }

    /**
     * @test
     */
    public function it_updates_multiple_products()
    {
        $products = Product::factory()->count(10)->create();
        // $products->each(fn($product) => $product->name .= ' edited'); // Edit every model
        $products->each(function($product) {
            $product->name .= ' edited';
            $product->setHidden(['uuid']);
        });
        $postData = array('products' => $products->toArray());

        $response = $this->patch(route('products.update.multiple'), $postData);
        $response->assertStatus(301)
                ->assertRedirect(route('products.index'));

        // $this->assertDatabaseCount('products', 10);
        $products->each(function($product) {
            $product = $product->refresh();
            $this->assertDatabaseHas('products', $product->toArray());
        });
    }

    /**
     * @test
     */
    public function it_deletes_a_single_product()
    {
        $product = Product::factory()->create();

        $response = $this->delete(route('products.destroy', $product));
        $response->assertStatus(301)
                ->assertRedirect(route('products.index'));

        $this->assertDeleted('products', $product->toArray());
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
