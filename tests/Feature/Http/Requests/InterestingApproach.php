<?php

namespace Tests\Feature\Http\Requests;

use App\Http\Requests\Product\StoreRequest;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class InterestingApproach extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * @var \App\Http\Requests\Products\StoreRequest
     */
    private $rules;

    /**
     * @var \Illuminate\Validation\Validator
     */
    private $validator;

    public function setUp(): void
    {
        parent::setUp();

        $this->validator = app()->get('validator');
        $this->rules = (new StoreRequest())->rules();
    }

    /**
     * @testTesting
     * @dataProvider validationProvider
     * @param bool $shouldPass
     * @param array $mockedRequestData
     */
    public function validation_results_as_expected($shouldPass, $mockedRequestData)
    {
        $this->assertEquals(
            $shouldPass,
            $this->validate($mockedRequestData)
        );
    }

    protected function validate($mockedRequestData)
    {
        $this->app->resolving(CreateRedirectRequest::class, function ($resolved) use ($mockedRequestData){
            $resolved->merge($mockedRequestData);
        });

        try {
            app(CreateRedirectRequest::class);

            return true;
        } catch (ValidationException $e) {
            return false;
        }
    }

    public function validationProvider()
    {
        /* WithFaker trait doesn't work in the dataProvider */
        $faker = Factory::create(Factory::DEFAULT_LOCALE);

        return [
            'request_should_fail_when_no_name_is_provided' => [
                'passed' => false,
                'data' => [
                    'name' => null
                ]
            ],
            'request_should_fail_when_name_has_more_than_64_characters' => [
                'passed' => false,
                'data' => [
                    'name' => $faker->paragraph()
                ]
            ],
            'request_should_fail_when_description_has_more_than_128_characters' => [
                'passed' => false,
                'data' => [
                    'description' => $faker->realText()
                ]
            ],
        ];
    }
}
