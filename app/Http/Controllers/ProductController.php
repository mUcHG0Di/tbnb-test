<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreRequest;
use App\Models\Product;
use Inertia\Inertia;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProductController extends Controller
{
    public function index()
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('name', 'LIKE', "%{$value}%")
                    ->orWhere('description', 'LIKE', "%{$value}%")
                    ->orWhere('price', 'LIKE', "%{$value}%")
                    ->orWhere('quantity', 'LIKE', "%{$value}%");
            });
        });

        $columns = [
            'uuid' => 'UUID',
            'name' => 'Name',
            'description' => 'Description',
            'price' => 'Price',
            'quantity' => 'Quantity',
        ];

        $products = QueryBuilder::for(Product::class)
                                ->defaultSort('name')
                                ->allowedSorts(array_keys($columns))
                                ->allowedFilters([
                                    ...array_keys($columns),
                                    $globalSearch
                                ])
                                ->paginate()
                                ->withQueryString();

        return Inertia::render('Product/Index', compact('products'))
                        ->table(function (InertiaTable $table) use($columns) {
                            $table->addSearchRows($columns)
                                    ->addColumns(array_filter($columns, fn($value) => ($value != 'UUID')));
                        });
    }

    public function store(StoreRequest $request)
    {
        Product::create($request->all());

        return redirect()->route('products.index', [], 301)
                        ->with('success', 'Product created successfully!');
    }
}
