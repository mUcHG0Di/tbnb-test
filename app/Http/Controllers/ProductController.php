<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\BulkStoreUpdateRequest;
use App\Http\Requests\Product\DestroyRequest;
use App\Http\Requests\Product\StoreUpdateRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Models\Product;
use Illuminate\Support\Arr;
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
    private function getProducts(bool $withHistory = false): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where('name', 'LIKE', "%{$value}%")
                ->orWhere('description', 'ILIKE', "%{$value}%")
                ->orWhere('price', 'ILIKE', "%{$value}%")
                ->orWhere('quantity', 'ILIKE', "%{$value}%");
        });

        $products = QueryBuilder::for(Product::class)
                                ->defaultSort('name')
                                ->allowedSorts(array_keys($this->columns))
                                ->allowedFilters([
                                    ...array_keys(Arr::except($this->columns, ['uuid', 'price', 'quantity'])),
                                    AllowedFilter::callback('uuid', fn($query, $value) => $query->where('uuid', 'LIKE', "%{$value}%")),
                                    AllowedFilter::callback('price', fn($query, $value) => $query->where('price', 'LIKE', "%{$value}%")),
                                    AllowedFilter::callback('quantity', fn($query, $value) => $query->where('uuid', 'LIKE', "%{$value}%")),
                                    $globalSearch
                                ]);

        if ($withHistory) {
            $products = $products->with('history');
        }

        return $products->paginate()->withQueryString();
    }

    /**
     * Callback to render the inertia table
     *
     * @param InertiaTable $table
     * @return void
     */
    private function renderTable(InertiaTable $table) {
        $table->addSearchRows($this->columns)
                ->addColumns($this->columns);
    }

    /**
     * Show a list of resources
     *
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        $products = $this->getProducts();

        return Inertia::render('Products/Index', compact('products'))
                        ->table(fn(InertiaTable $table) => $this->renderTable($table));
    }

    /**
     * Show modal to create resource
     *
     * @return Illuminate\Http\Response
     */
    public function create()
    {
        $products = $this->getProducts();
        $formDialogOpened = true;

        return Inertia::render('Products/Index', compact('products', 'formDialogOpened'))
                        ->table(fn(InertiaTable $table) => $this->renderTable($table));
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
            $filename = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('images/products', $filename);
            $productData = $request->all();
            $productData['image'] = $path;
            Product::create($productData);

            return redirect()->route('products.index')->with('success', 'Product created!');
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
            return redirect()->route('products.index')->with('success', 'Products created.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('products.index', [], 302)->with('error', 'Products could not be created. ' . $this->getError($e));
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

        return Inertia::render('Products/Index', compact('products', 'product', 'showDialogOpened'))
                        ->table(fn(InertiaTable $table) => $this->renderTable($table));
    }

    /**
     * Show the resource history
     *
     * @param Product $product
     * @return Illuminate\Http\Response
     */
    public function showHistory(Product $product)
    {
        $withHistory = true;
        $products = $this->getProducts($withHistory);
        $historyDialogOpened = true;

        return Inertia::render('Products/Index', compact('products', 'product', 'historyDialogOpened'))
                        ->table(fn(InertiaTable $table) => $this->renderTable($table));
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

        return Inertia::render('Products/Index', compact('products', 'formOpened', 'product'))
                        ->table(fn(InertiaTable $table) => $this->renderTable($table));
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
            return redirect()->route('products.index')->with('success', 'Product updated!');
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
            // For the bulk update to work with "updated" event creating the history
            // if quantity value was updated, we need the real models (The batch update execute
            // one query for each row)
            $products = Product::whereIn('uuid', array_map(fn($product) => $product['uuid'], $request->products))->get();
            $products->each(function($product) use($request) {
                foreach ($request->products as $productData) {
                    if ($productData['uuid'] == $product->uuid) $product->fill($productData);
                }
            });

            // Batch update of products with event triggering
            Product::newBatch($products)->save()->now();

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Products saved.');
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
            return redirect()->route('products.index')->with('success', 'Product removed.');
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

            return redirect()->route('products.index')->with('success', 'Products removed.');
        } catch (\Exception $e) {
            return redirect()->route('products.index', [], 302)->with('error', 'The products could not be removed. ' . $this->getError($e));
        }
    }

    public function getHistory(Product $product)
    {
        try {
            return [
                'status' => 'success',
                'message' => 'History retrieved',
                'data' => $product->history
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'History could not be retrieved. ' . $this->getError($e)
            ];
        }
    }
}
