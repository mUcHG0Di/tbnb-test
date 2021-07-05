<?php

namespace App\Http\Controllers;

use App\Http\Requests\Products\BulkStoreRequest;
use App\Http\Requests\Products\BulkUpdateRequest;
use App\Http\Requests\Products\DestroyRequest;
use App\Http\Requests\Products\StoreRequest;
use App\Http\Requests\Products\UpdateRequest;
use App\Models\Product;
use Illuminate\Support\Arr;
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
     * @param bool $withHistory In case we need to get the history relationship
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
        $model = Product::class;

        return Inertia::render('Products/Index', compact('products', 'model'))
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
    public function store(StoreRequest $request)
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
     * @param BulkStoreRequest $request
     * @return Illuminate\Http\Response
     */
    public function bulkStore(BulkStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            // Batch insert of products with event triggering
            Product::newBatch($request->products)->save()->now();

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
     * @param UpdateRequest $request
     * @param Product $product
     * @return Iluminate\Http\Response
     */
    public function update(UpdateRequest $request, Product $product)
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
     * @param BulkUpdateRequest $request
     * @return Illuminate\Http\Response
     */
    public function bulkUpdate(BulkUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            // For the bulk update to work with "updated" event creating the history
            // if quantity value was updated, we need them to be model instances
            $products = Product::whereIn('uuid', array_map(fn($product) => $product['uuid'], $request->products))->get();
            $products->each(function($product) use($request) {
                $matchingProduct = Arr::first($request->products, fn($reqProduct) => $reqProduct['uuid'] == $product->uuid);
                if ($matchingProduct) $product->fill($matchingProduct);
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
        DB::beginTransaction();
        try {
            DB::table($this->tableName)
                ->whereIn('uuid', $request->products_uuids)
                ->delete();
            DB::commit();

            return redirect()->route('products.index')->with('success', 'Products removed.');
        } catch (\Exception $e) {
            DB::rollBack();
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
