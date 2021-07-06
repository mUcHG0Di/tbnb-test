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
     * Model table name
     *
     * @var string
     */
    protected $tableName;

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
            'name' => __('table.name'),
            'description' => __('table.description'),
            'price' => __('table.price'),
            'quantity' => __('table.quantity'),
            'owner.name' => __('table.owner'),
        ];
    }

    /**
     * Get products paginated for
     * the InertiaTable
     *
     * @param bool $withHistory In case we need to get the history relationship
     * @return LengthAwarePaginator
     */
    private function getProducts(array $withRelations = null): LengthAwarePaginator
    {


        $products = QueryBuilder::for(Product::class)
                                ->defaultSort('name')
                                ->allowedSorts(array_keys($this->columns))
                                ->allowedFilters($this->getAllowedFilters());

        // Load the relationships from $withRelations parameter into the query
        if (!is_null($withRelations) && is_array($withRelations)) {
            $products = $products->with(...$withRelations);
        }

        return $products->paginate()->withQueryString();
    }

    /**
     * Allowed filters for the query builder
     *
     * @return array
     */
    private function getAllowedFilters()
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where('name', 'LIKE', "%{$value}%")
                ->orWhere('description', 'ILIKE', "%{$value}%")
                ->orWhere('price', 'ILIKE', "%{$value}%")
                ->orWhere('quantity', 'ILIKE', "%{$value}%");
        });

        return [
            ...array_keys(Arr::except($this->columns, ['uuid', 'price', 'quantity'])),
            AllowedFilter::callback('uuid', fn($query, $value) => $query->where('uuid', 'LIKE', "%{$value}%")),
            AllowedFilter::callback('price', fn($query, $value) => $query->where('price', 'LIKE', "%{$value}%")),
            AllowedFilter::callback('quantity', fn($query, $value) => $query->where('uuid', 'LIKE', "%{$value}%")),
            $globalSearch
        ];
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

            return redirect()->route('products.index')->with('success', __('crud.store.success'));
        } catch (\Exception $e) {
            return redirect()->route('products.index', [], 302)->with('error', __('crud.store.error') . $this->getError($e));
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
            return redirect()->route('products.index')->with('success', __('crud.bulkstore.success'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('products.index', [], 302)->with('error', __('crud.bulkstore.error') . $this->getError($e));
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
        $products = $this->getProducts(['owner']);
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
        $withRelations = ['history'];
        $products = $this->getProducts($withRelations);
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
            $productData = $request->all();
            if ($request->file('image')) {
                $filename = $request->file('image')->getClientOriginalName();
                $path = $request->file('image')->storeAs('images/products', $filename);
                $productData['image'] = $path;
            }

            $product->update($productData);
            return redirect()->route('products.index')->with('success', __('crud.update.success'));
        } catch (\Exception $e) {
            return redirect()->route('products.index', [], 302)->with('error', __('crud.update.error') . $this->getError($e));
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
            return redirect()->route('products.index')->with('success', __('crud.bulkupdate.success'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('products.index', [], 302)->with('error', __('crud.bulkupdate.error') . $this->getError($e));
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
            return redirect()->route('products.index')->with('success', __('crud.destroy.success'));
        } catch(\Exception $e) {
            return redirect()->route('products.index', [], 302)->with('error', __('crud.destroy.error') . $this->getError($e));
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

            return redirect()->route('products.index')->with('success', __('crud.bulkdestroy.success'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('products.index', [], 302)->with('error', __('crud.bulkdestroy.error') . $this->getError($e));
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
