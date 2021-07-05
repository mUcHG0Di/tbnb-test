<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Notifications\ProductQuantityUpdated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function it_stores_a_single_product()
    {
        $this->product->image = UploadedFile::fake()->image('product.jpg');
        $response = $this->from(route('products.create'))
                        ->post(route('products.store'), $this->product->toArray());

        $response->assertRedirect(route('products.index'))
                ->assertSessionHasNoErrors();

        Storage::assertExists('images/products/product.jpg');

        $this->assertDatabaseHas('products', [
            'name' => $this->product->name,
            'description' => $this->product->description,
            'price' => $this->product->price,
            'quantity' => $this->product->quantity,
        ]);

        $product = Product::first();
        $this->assertCount(1, $product->history);
    }

    /** @test */
    public function it_stores_multiple_products()
    {
        $this->products->each(fn($product) => $product->setHidden(['image_url', 'image']));
        $postData = array('products' => $this->products->toArray());

        $response = $this->from(route('products.create'))
                        ->post(route('products.store.multiple'), $postData);

        $response->assertRedirect(route('products.index'))
                ->assertSessionHasNoErrors();

        $this->assertDatabaseCount('products', $this->products->count())
            ->assertDatabaseCount('product_histories', $this->products->count());
    }

    /** @test */
    public function it_updates_a_single_product()
    {
        $this->product->save();
        $newName = 'Named edited';
        $this->product->name = $newName;

        $response = $this->from(route('products.edit', $this->product))
                        ->put(route('products.update', $this->product), $this->product->toArray());

        $response->assertRedirect(route('products.index'))
                ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('products', [
            'name' => $newName
        ]);
    }

    /** @test */
    public function it_notifies_the_owner_if_the_quantity_was_updated_by_someone_else()
    {
        Notification::fake();

        // Assert that no notifications were sent...
        Notification::assertNothingSent();

        $this->product = Product::factory()->create();
        $this->product->quantity = 14;

        $this->from(route('products.edit', $this->product))
                ->put(route('products.update', $this->product), $this->product->toArray());

        $product = $this->product->refresh();

        // Assert a specific type of notification was sent meeting the given truth test...
        Notification::assertSentTo(
            $product->owner,
            function (ProductQuantityUpdated $notification, $channels) use ($product) {
                return $notification->product->uuid === $product->id && $notification->product->quantity !== $product->quantity;
            }
        );
    }

    /** @test */
    public function it_updates_multiple_products()
    {
        $this->products->each(function($product) {
            // Save and edit every model
            $product->save();
            $product->setHidden(['image_url', 'image']);
            $product->name .= ' edited';
        });
        $postData = array('products' => $this->products->toArray());

        $response = $this->patch(route('products.update.multiple'), $postData);
        $response->assertRedirect(route('products.index'))
                ->assertSessionHasNoErrors();

        $this->assertDatabaseCount('products', 10);
        $this->products->each(function($product) {
            $product = $product->refresh();
            $this->assertDatabaseHas('products', $product->toArray());
        });
    }

    /** @test */
    public function it_deletes_a_single_product()
    {
        $product = Product::factory()->create();

        $response = $this->delete(route('products.destroy', $product));
        $response->assertRedirect(route('products.index'))
                ->assertSessionHasNoErrors();

        $this->assertDeleted('products', $this->product->setHidden(['image_url'])->toArray());
    }

    /** @test */
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
