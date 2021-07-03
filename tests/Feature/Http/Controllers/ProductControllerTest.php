<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
            'description' => $this->faker->realText(), // Text longer that 128 chars
            'price' => 'some price',
            'quantity' => 'some quantity',
            'image' => null,
        ];
        $response = $this->from(route('products.create'))
                        ->post(route('products.store'), $product);

        $response->assertStatus(302)
                ->assertRedirect(route('products.create'))
                ->assertSessionHasErrors([
                    'name', 'description', 'price', 'quantity', 'image'
                ]);
    }

    /**
     * @test
     */
    public function it_stores_a_single_product()
    {
        Storage::fake();
        $product = Product::factory()->make();
        $product->image = UploadedFile::fake()->image('product.jpg');
        $response = $this->from(route('products.create'))
                        ->post(route('products.store'), $product->toArray());

        $response->assertRedirect(route('products.index'))
                ->assertSessionHasNoErrors();

        Storage::assertExists('images/products/product.jpg');

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
                ['description' => $this->faker->realText()]
            ]
        ];
        $response = $this->from(route('products.create'))
                        ->post(route('products.store.multiple'), $products);

        $response->assertStatus(302)
                ->assertRedirect(route('products.create'))
                ->assertSessionHasErrors([
                    'products.0.name', 'products.0.description', 'products.0.price', 'products.0.quantity'
                ]);
    }

    /**
     * @test
     */
    public function it_stores_multiples_products()
    {
        $products = Product::factory()->count(10)->make();
        $postData = array('products' => $products->toArray());

        $response = $this->from(route('products.create'))
                        ->post(route('products.store.multiple'), $postData);

        $response->assertRedirect(route('products.index'))
                ->assertSessionHasNoErrors();

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

        $response = $this->from(route('products.edit', $product))
                        ->put(route('products.update', $product), $product->toArray());

        $response->assertRedirect(route('products.index'))
                ->assertSessionHasNoErrors();

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
        $products->each(fn($product) => $product->setHidden(['image_url'])->name .= ' edited'); // Edit every model
        $postData = array('products' => $products->toArray());

        $response = $this->patch(route('products.update.multiple'), $postData);

        $response->assertRedirect(route('products.index'))
                ->assertSessionHasNoErrors();

        $this->assertDatabaseCount('products', 10);
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
        $response->assertRedirect(route('products.index'))
                ->assertSessionHasNoErrors();

        $this->assertDeleted('products', $product->setHidden(['image_url'])->toArray());
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
        $response->assertRedirect(route('products.index'))
                ->assertSessionHasNoErrors();

        // map uuids to check if missing in database
        $this->assertDatabaseMissing('products', array_map(fn($uuid) => [$uuid => 'uuid'], $products_uuids));
    }
}
