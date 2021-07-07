<?php

/**
 * Spatie QueryBuilder ExcelController
 *
 * Example query:
 * ?page=1&columns[0]=uuid&columns[1]=name&columns[2]=description&columns[3]=price&columns[4]=quantity&columns[5]=owner.name&model=App\Models\Product
 * // Controller ignores the "page" param, so don't worry about it
 *
 * Quick helper for protonemedia inertia js tables:
 * For this controller to work with protonemedia/inertiajs-tables-laravel-query-builder,
 * you need to extend the InteractsWithQueryBuilder.vue
 * file into your project just to edit the method getColumnsForQuery, so you can
 * create a button in your view to append the query into the URL, like:
 *
 * import InteractsWithQueryBuilder from '@/InteractsWithQueryBuilder';
 * ...
 *  methods: {
 *       exportToExcel: function() {
 *               const query = this.queryBuilderString; // The query
 *               const url = route('excel.export'); // Your export route
 *               const model = 'App\Models\Product'; // The model to export
 *               window.location.href = `${url}?${query}&model=${model}`
 *           },
 *       },
 *   },
 * ...
 *
 * Your extension should look like this:
 *
 * // file resources/js/InteractsWithQueryBuilder.vue
 * <script>
 * import filter from "lodash-es/filter";
 * import map from "lodash-es/map";
 * import { InteractsWithQueryBuilder } from '@protonemedia/inertiajs-tables-laravel-query-builder';
 *
 * export default {
 *     mixins: [ InteractsWithQueryBuilder ],
 *     methods: {
 *         getColumnsForQuery(columns) {
 *             let enabledColumns = filter(columns, (column) => {
 *                 return column.enabled;
 *             });
 *
 *             return map(enabledColumns, (column) => {
 *                 return column.key;
 *             });
 *         },
 *     },
 * }
 * </script>
 *
 *
 * @depends Rap2hpoutre\FastExcel\FastExcel (package: rap2hpoutre/fast-excel)
 * @link https://github.com/rap2hpoutre/fast-excel
 * @depends Spatie\QueryBuilder\QueryBuilder (package: spatie/laravel-query-builder)
 * @link https://github.com/spatie/laravel-query-builder
 *
 * @author Marcos Godoy <marcosanibalgg@gmail.com>
 * @link https://gist.github.com/mUcHG0Di
 * @license MIT
 * @copyright 2021 Marcos Godoy
 */

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\QueryBuilder;
use Rap2hpoutre\FastExcel\FastExcel;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

class ExcelController extends Controller
{
    /**
     * The model to export
     * ie: App\Models\Product
     *
     * @var string
     */
    protected $model;

    /**
     * Columns to export from the query
     *
     * @var string[]
     */
    protected $columns;

    /**
     * Relations to export from the query.
     * Must be with dot notation, and relations must be created
     * in your models
     * ie: products.owner.name
     *
     * @var string[]
     */
    protected $relations;

    /**
     * Columns that are not VARCHAR,
     * to avoid postgresql lower(type) errors in filters :,)
     *
     * @var string[]
     */
    protected $notStringColumns = [
        // ie:
        'uuid', 'price', 'quantity', 'date'
    ];

    public function __invoke()
    {
        // ini_set("memory_limit", "REPLACEME"); // Uncomment if needed
        // ini_set("max_execution_time", "REPLACEME"); // Uncomment if needed

        // Handle the url params
        $this->prepareFieldsForQuery();

        $list = $this->getQuery();

        // Export all rows
        $filename = Str::snake(Str::plural(Str::afterLast($this->model, "\\"))) . "_" . date('YmdHis');
        return (new FastExcel($list))->download("$filename.xlsx", fn($model) => $this->formatExcel($model));
    }

    /**
     * Prepare arrays of fields, relations and additionals
     *
     * @return void
     */
    private function prepareFieldsForQuery()
    {
        $this->model = request()->input('model');
        $this->columns = request()->input('columns');
        $this->relations = $this->extractRelations($this->columns);
    }

    /**
     * Build the query
     *
     * @return Collection
     */
    private function getQuery(): Collection
    {
        $list = QueryBuilder::for($this->model)
                    ->allowedSorts($this->getAllowedSorts())
                    ->allowedFilters($this->getAllowedFilters());

        // Load relations
        if (count($this->relations) > 0) {
            $list = $list->with(...array_map(fn($relation) => Str::beforeLast($relation, '.'), $this->relations));
        }

        return $list->get();
    }

    /**
     * Return values that has a relation.field format
     *
     * @var     array   $array
     * @return  array   $results
     */
    private function extractRelations(&$array): array
    {
        if (!$array || !is_array($array)) {
            return [];
        }

        $relations = array();
        foreach ($array as $column) {
            if (Str::contains($column, ['.'])) {
                $relations[] = $column;
                if (($key = array_search($column, $array)) !== false) {
                    unset($array[$key]);
                }
            }
        }

        return $relations;
    }

    /**
     * Get allowed sorts based on columns and relations
     *
     * @param   array   $columns
     * @param   array   $relations
     * @return  array   $allowedSorts
     */
    private function getAllowedSorts(): array
    {
        $allowedSorts = array();

        foreach ($this->columns as $column) {
            array_push($allowedSorts, $column);
        }

        // You need to create the sorts files in
        // app/Sorts/{ModelToExport}/ directory, with "Sort" as class suffix
        // ie: app/Sorts/Product/OwnerSort
        foreach ($this->relations as $relation) {
            $relationModel = ucfirst(Str::camel(Str::beforeLast($relation, '.')));
            $sortClass = "App\\Sorts\\" . Str::afterLast($this->model, "\\") . "\\" . $relationModel . "Sort";

            // Sort class must be created (in app/Sorts/{$model}/)
            $sortInstance = new $sortClass();
            $allowedSort = AllowedSort::custom($relation, $sortInstance);
            array_push($allowedSorts, $allowedSort);
        }

        return $allowedSorts;
    }

    /**
     * Allowed filters for the query builder
     *
     * @return array
     */
    private function getAllowedFilters()
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $this->applyFilterToColumns($query, $this->columns, $value);
            $this->applyFilterToRelations($query, $this->relations, $value);
        });

        $notStringCallbacks = array_map(fn($col) => AllowedFilter::callback($col, fn($query, $value) => $query->where($col, 'LIKE', "%{$value}%")), $this->notStringColumns);

        return [
            ...array_keys(Arr::except($this->columns, $this->notStringColumns)),
            ...$notStringCallbacks,
            $globalSearch
        ];
    }

    /**
     * Return formatted columns to export
     *
     * @param   Model   $model
     * @return  array   $columns
     */
    private function formatExcel($model) {
        $cols = array();
        foreach (request()->columns as $column) {
            // For relation columns. ie: relation.anotherRelation.column
            $columnName = Str::replace('.', ' ', $column);

            $columnName = Str::title($columnName);
            $cols[$columnName] = data_get($model, $column);
        }

        return $cols;
    }

    /**
     * Apply filter to columns in query
     *
     * @param   QueryBuilder    $query
     * @param   array           $columns
     * @param   string          $value
     * @return  void
     */
    private function applyFilterToColumns(&$query, $columns, $value): void
    {
        $query->where(function($query) use($columns, $value) {
            foreach($columns as $column) {
                $query->orWhere($column, 'ILIKE', "%{$value}%");
            }
        });
    }

    /**
     * Apply filter to relations in query
     *
     * @param   QueryBuilder    $query
     * @param   array           $relations
     * @param   string          $value
     * @return  void
     */
    private function applyFilterToRelations(&$query, $relations, $value): void
    {
        foreach ($relations as $relation) {
            // Separate the relation path and field name
            // ie: {products.user}.{name}
            $relPath = Str::beforeLast($relation, '.');
            $field = Str::afterLast($relation, '.');

            $query->orWhereHas($relPath, fn($query) => $query->where($field, 'ILIKE', "%{$value}%"));
        }
    }
}
