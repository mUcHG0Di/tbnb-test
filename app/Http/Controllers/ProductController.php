<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\BulkStoreUpdateRequest;
use App\Http\Requests\Product\DestroyRequest;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProductController extends Controller
{
    /**
     * Redirects after request
     *
     * @var Illuminate\Http\Response
     */
    protected $indexRedirect;

    /**
     * InertiaTable columns
     *
     * @var array
     */
    protected $columns;

    public function __construct()
    {
        $this->tableName = (new Product)->getTable();
        $this->indexRedirect = redirect()->route('products.index', [], 301);
        $this->columns = [
            'uuid' => 'UUID',
            'name' => 'Name',
            'description' => 'Description',
            'price' => 'Price',
            'quantity' => 'Quantity',
        ];
    }

    /**
     * Get products paginated to show
     * in the InertiaTable
     *
     * @return LengthAwarePaginator
     */
    private function getProducts(): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('name', 'LIKE', "%{$value}%")
                    ->orWhere('description', 'LIKE', "%{$value}%")
                    ->orWhere('price', 'LIKE', "%{$value}%")
                    ->orWhere('quantity', 'LIKE', "%{$value}%");
            });
        });

        $products = QueryBuilder::for(Product::class)
                                ->defaultSort('name')
                                ->allowedSorts(array_keys($this->columns))
                                ->allowedFilters([
                                    ...array_keys($this->columns),
                                    $globalSearch
                                ])
                                ->paginate()
                                ->withQueryString();

        return $products;
    }

    /**
     * Show a list of resources
     *
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        $products = $this->getProducts();

        return Inertia::render('Product/Index', compact('products'))
                        ->table(function (InertiaTable $table) {
                            $table->addSearchRows($this->columns)
                                    ->addColumns(array_filter($this->columns, fn($value) => ($value != 'UUID')));
                        });
    }

    /**
     * Show modal to create resource
     *
     * @return Illuminate\Http\Response
     */
    public function create()
    {
        $products = $this->getProducts();
        $formOpened = true;

        return Inertia::render('Product/Index', compact('products', 'formOpened'))
                        ->table(function (InertiaTable $table) {
                            $table->addSearchRows($this->columns)
                                    ->addColumns(array_filter($this->columns, fn($value) => ($value != 'UUID')));
                        });
    }

    /**
     * Store the resource in storage
     *
     * @param StoreRequest $request
     * @return void
     */
    public function store(StoreRequest $request)
    {
        try {
            Product::create($request->all());
            return $this->indexRedirect->with('success', 'Product created successfully!');
        } catch (\Exception $e) {
            return $this->indexRedirect->with('error', 'Product could not be created. ' . $this->getError($e));
        }
    }

    public function bulkStore(BulkStoreUpdateRequest $request)
    {
        try {
            // Add UUID to each product
            $products = array_map(fn($product) => $product += ['uuid' => Str::uuid()], $request->products);
            Product::insert($products)
                    ->each(fn($product) => $product->history()->create(['date' => now()]));
            return $this->indexRedirect->with('success', 'Products saved successfully.');
        } catch (\Exception $e) {
            return $this->indexRedirect->with('error', 'Products could not be saved. ' . $this->getError($e));
        }
    }

    /**
     * Show the resource data
     *
     * @param Product $product
     * @return Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        // TODO: Show the product
    }

    /**
     * View for editing the resource
     *
     * @param Product $product
     * @return Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $products = $this->getProducts();
        $formOpened = true;

        return Inertia::render('Product/Index', compact('products', 'formOpened', 'product'))
                        ->table(function (InertiaTable $table) {
                            $table->addSearchRows($this->columns)
                                    ->addColumns(array_filter($this->columns, fn($value) => ($value != 'UUID')));
                        });
    }

    /**
     * Update the resource
     *
     * @param UpdateRequest $request
     * @param Product $product
     * @return Iluminate\Http\Response
     */
    public function update(UpdateRequest $request, Product $product)
    {
        // TODO: update the resource
    }

    public function bulkUpdate(BulkStoreUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            DB::table($this->tableName)->update($request->products);
            DB::commit();

            return $this->indexRedirect->with('success', 'Products saved successfully.');
        } catch (\Exception $e) {
            DB::rollback();

            return $this->indexRedirect->with('error', 'Products could not be saved. ' . $this->getError($e));
        }
    }

    /**
     * Remove the resource from storage
     *
     * @param DestroyRequest $request
     * @return Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return $this->indexRedirect->with('success', 'Product removed successfully.');
        } catch(\Exception $e) {
            return $this->indexRedirect->with('error', 'The product could not be removed. ' . $this->getError($e));
        }
    }

    /**
     * Destroy multiple resources
     *
     * @param DestroyRequest $request
     * @return Illuminate\Http\Response
     */
    public function bulkDestroy(DestroyRequest $request)
    {
        try {
            DB::table($this->tableName)->whereIn('uuid', $request->products_uuids)->delete();
            return $this->indexRedirect->with('success', 'Products removed successfully.');
        } catch (\Exception $e) {
            return $this->indexRedirect->with('error', 'The products could not be removed. ' . $this->getError($e));
        }
    }
}
