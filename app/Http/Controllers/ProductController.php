<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\BulkStoreUpdateRequest;
use App\Http\Requests\Product\DestroyRequest;
use App\Http\Requests\Product\StoreUpdateRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use WF\Batch\Batch;

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
        $this->columns = [
            'uuid' => 'UUID',
            'name' => 'Name',
            'description' => 'Description',
            'price' => 'Price',
            'quantity' => 'Quantity',
        ];
    }

    /**
     * Get products paginated for
     * the InertiaTable
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
    public function store(StoreUpdateRequest $request)
    {
        try {
            Product::create($request->all());
            return redirect()->route('products.index')->with('success', 'Product created successfully!');
        } catch (\Exception $e) {
            return redirect()->route('products.index', [], 302)->with('error', 'Product could not be created. ' . $this->getError($e));
        }
    }

    /**
     * Bulk store resources
     *
     * @param BulkStoreUpdateRequest $request
     * @return Illuminate\Http\Response
     */
    public function bulkStore(BulkStoreUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            // Add UUID to each product
            $products = array_map(fn($product) =>
                        Product::make($product)->setAttribute('uuid', Str::uuid()),
                    $request->products);

            // Batch insert of products with event triggering
            Product::newBatch($products)->save()->now();

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Products saved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            dd("Error bulk");
            return redirect()->route('products.index', [], 302)->with('error', 'Products could not be saved. ' . $this->getError($e));
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
        $products = $this->getProducts();
        $showDialogOpened = true;

        return Inertia::render('Product/Index', compact('products', 'product', 'showDialogOpened'))
                        ->table(function (InertiaTable $table) {
                            $table->addSearchRows($this->columns)
                                    ->addColumns(array_filter($this->columns, fn($value) => ($value != 'UUID')));
                        });
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
     * @param StoreUpdateRequest $request
     * @param Product $product
     * @return Iluminate\Http\Response
     */
    public function update(StoreUpdateRequest $request, Product $product)
    {
        try {
            $product->update($request->all());
            return redirect()->route('products.index')->with('success', 'Product updated successfully!');
        } catch (\Exception $e) {
            return redirect()->route('products.index', [], 302)->with('error', 'Product could not be created. ' . $this->getError($e));
        }
    }

    /**
     * Bulk update resources
     *
     * @param BulkStoreUpdateRequest $request
     * @return Illuminate\Http\Response
     */
    public function bulkUpdate(BulkStoreUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            // For the bulk update to work with update event creategin the history
            // if quantity value was updated, we need the real models (The batch update execute
            // one query for each row)
            $products = Product::whereIn('uuid', array_map(fn($product) => $product['uuid'], $request->products))->get();
            $products->each(function($product) use($request) {
                foreach ($request->products as $productData) {
                    $product->fill($productData);
                }
            });

            // Batch update of products with event triggering
            Product::newBatch($products)->save()->now();

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Products saved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('products.index', [], 302)->with('error', 'Products could not be saved. ' . $this->getError($e));
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
            return redirect()->route('products.index')->with('success', 'Product removed successfully.');
        } catch(\Exception $e) {
            return redirect()->route('products.index', [], 302)->with('error', 'The product could not be removed. ' . $this->getError($e));
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
            DB::table($this->tableName)
                ->whereIn('uuid', $request->products_uuids)
                ->delete();

            return redirect()->route('products.index')->with('success', 'Products removed successfully.');
        } catch (\Exception $e) {
            return redirect()->route('products.index', [], 302)->with('error', 'The products could not be removed. ' . $this->getError($e));
        }
    }
}
