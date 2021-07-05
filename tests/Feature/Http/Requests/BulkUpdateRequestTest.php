<?php

namespace Tests\Feature\Http\Requests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BulkUpdateRequestTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function request_should_fail_if_a_name_is_not_provided()
    {
        $this->products->each(function($product) {
            $product->save();
            $product->setHidden(['image_url', 'image'])
                    ->setAttribute('name', null);
        });
        $postData = array('products' => $this->products->toArray());
        $response = $this->from(route('products.create'))
                        ->patch(route('products.update.multiple'), $postData);

        $this->checkSessionError($response, $this->getValidationNames('name'));
    }

    /** @testa */
    public function request_should_fail_if_a_name_has_more_than_64_characters()
    {
        $this->products->each(function($product) {
            $product->save();
            $product->setHidden(['image_url', 'image'])
                    ->setAttribute('name', $this->faker->paragraph());
        });
        $postData = array('products' => $this->products->toArray());
        $response = $this->from(route('products.create'))
                        ->patch(route('products.update.multiple'), $postData);

        $this->checkSessionError($response, $this->getValidationNames('name'));
    }

    /** @test */
    public function request_should_fail_if_a_description_has_more_than_128_characters()
    {
        $this->products->each(function($product) {
            $product->save();
            $product->setHidden(['image_url', 'image'])
                    ->setAttribute('description', $this->faker->realText());
        });
        $postData = array('products' => $this->products->toArray());
        $response = $this->from(route('products.create'))
                        ->patch(route('products.update.multiple'), $postData);

        $this->checkSessionError($response, $this->getValidationNames('description'));
    }

    /** @testa */
    public function request_should_fail_if_a_price_is_not_provided()
    {
        $this->products->each(function($product) {
            $product->save();
            $product->setHidden(['image_url', 'image'])
                    ->setAttribute('price', null);
        });
        $postData = array('products' => $this->products->toArray());
        $response = $this->from(route('products.create'))
                        ->patch(route('products.update.multiple'), $postData);

        $this->checkSessionError($response, $this->getValidationNames('price'));
    }

    /** @testa */
    public function request_should_fail_if_a_price_is_not_numeric()
    {
        $this->products->each(function($product) {
            $product->save();
            $product->setHidden(['image_url', 'image'])
                    ->setAttribute('price', 'twenty five');
        });
        $postData = array('products' => $this->products->toArray());
        $response = $this->from(route('products.create'))
                        ->patch(route('products.update.multiple'), $postData);

        $this->checkSessionError($response, $this->getValidationNames('price'));
    }

    /** @testa */
    public function request_should_fail_if_a_price_value_is_less_than_1()
    {
        $this->products->each(function($product) {
            $product->save();
            $product->setHidden(['image_url', 'image'])
                    ->setAttribute('price', 0);
        });
        $postData = array('products' => $this->products->toArray());
        $response = $this->from(route('products.create'))
                        ->patch(route('products.update.multiple'), $postData);

        $this->checkSessionError($response, $this->getValidationNames('price'));
    }

    /** @testa */
    public function request_should_fail_if_a_quantity_is_not_provided()
    {
        $this->products->each(function($product) {
            $product->save();
            $product->setHidden(['image_url', 'image'])
                    ->setAttribute('quantity', null);
        });
        $postData = array('products' => $this->products->toArray());
        $response = $this->from(route('products.create'))
                        ->patch(route('products.update.multiple'), $postData);

        $this->checkSessionError($response, $this->getValidationNames('quantity'));
    }

    /** @testa */
    public function request_should_fail_if_a_quantity_is_not_numeric()
    {
        $this->products->each(function($product) {
            $product->save();
            $product->setHidden(['image_url', 'image'])
                    ->setAttribute('quantity', 'two');
        });
        $postData = array('products' => $this->products->toArray());
        $response = $this->from(route('products.create'))
                        ->patch(route('products.update.multiple'), $postData);

        $this->checkSessionError($response, $this->getValidationNames('quantity'));
    }

    /** @testa */
    public function request_should_fail_if_a_quantity_value_is_less_than_1()
    {
        $this->products->each(function($product) {
            $product->save();
            $product->setHidden(['image_url', 'image'])
                    ->setAttribute('quantity', 0);
        });
        $postData = array('products' => $this->products->toArray());
        $response = $this->from(route('products.create'))
                        ->patch(route('products.update.multiple'), $postData);

        $this->checkSessionError($response, $this->getValidationNames('quantity'));
    }

    /**
     * Creates an array of strings with the format table.index.field
     * ie: ['products.0.name', 'products.1.name', ...]
     *
     * @param string $field
     * @return string[]
     */
    private function getValidationNames(string $field): array
    {
        return $this->products->map(fn($product, $index) => $product->setAttribute("validation_name", "products.{$index}.{$field}", ))
                                            ->pluck('validation_name')->toArray();
    }
}
