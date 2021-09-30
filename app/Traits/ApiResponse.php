<?php


namespace App\Traits;

use Illuminate\Database\Query\Builder as Query;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;

trait ApiResponse
{

    /**
     * The model of the table to filter.
     *
     * @var string
     */
    protected $model;

    /**
     * The transformer of the model.
     *
     * @var string
     */
    protected $transformer;


    /**
     * The column of the table to filter.
     *
     * @var string
     */
    protected $column;

    /**
     * The order of the data must have returned.
     *
     * @var string
     */
    protected $order;

    /**
     * The quantity of resources shown per query.
     *
     * @var string
     */
    protected $per_page;

    /**
     * The value to filter in the table.
     *
     * @var string
     */
    protected $query;

    /**
     * The value to filter in the table.
     *
     * @var string
     */
    protected $where;

    /**
     * The value to filter in the table.
     *
     * @var string
     */
    protected $where_not;


    /**
     * The value to filter in the table.
     * E.g. where_in=1,2,3 | where_in[]=1&where_in[]=2&where_in[]=3
     *
     * @var string|array
     */
    protected $where_in;

    /**
     * The value to filter in the table.
     * E.g. where_not_in=1,2,3 | where_not_in[]=1&where_not_in[]=2&where_not_in[]=3
     *
     * @var string|array
     */
    protected $where_not_in;

    /**
     * The value to filter in the table.
     * E.g.
     * where_between=1,6 |
     * where_between=2021-01-01,2021-12-31 |
     * where_between[]=2021-01-01&where_between[]=2021-12-31
     *
     * @var array
     */
    protected $where_between;

    /**
     * The value to filter in the table.
     * E.g.
     * where_columns_between=column_1,column_2|value_1,value_2
     *
     * @var array
     */
    protected $where_columns_between;

    /**
     * The value to filter in the table.
     * E.g.
     * where_columns_not_between=column_1:value_1,value_2|column_2:value_1,value_2
     *
     * @var array
     */
    protected $where_columns_not_between;

    /**
     * The value to filter in the table.
     * E.g.
     * where_not_between=1,6 |
     * where_not_between=2021-01-01,2021-12-31 |
     * where_not_between[]=2021-01-01&where_not_between[]=2021-12-31
     *
     * @var array
     */
    protected $where_not_between;

    /**
     * The value to filter in the table.
     *
     * @var string
     */
    protected $or_where;


    /**
     * The value to filter in the table.
     * E.g. or_where_in=1,2,3 | or_where_in[]=1&or_where_in[]=2&or_where_in[]=3
     *
     * @var string|array
     */
    protected $or_where_in;

    /**
     * The value to filter in the table.
     * E.g. or_where_not_in=1,2,3 | or_where_not_in[]=1&or_where_not_in[]=2&or_where_not_in[]=3
     *
     * @var string|array
     */
    protected $or_where_not_in;

    /**
     * The value to filter in the table.
     * E.g.
     * or_where_between=1,6 |
     * or_where_between=2021-01-01,2021-12-31 |
     * or_where_between[]=2021-01-01&or_where_between[]=2021-12-31
     *
     * @var array
     */
    protected $or_where_between;

    /**
     * The value to filter in the table.
     * E.g.
     * or_where_not_between=1,6 |
     * or_where_not_between=2021-01-01,2021-12-31 |
     * or_where_not_between[]=2021-01-01&or_where_not_between[]=2021-12-31
     *
     * @var array
     */
    protected $or_where_not_between;


    public function __construct()
    {
        $column = request()->has( 'column' ) ? request()->get('column') : 'id';
        $order = request()->has( 'order' ) ? request()->get('order') : 'false';
        $order = is_array($order) ? Arr::first($order) : $order;
        $this->column   =  is_array($column) ? Arr::first($column) : $column;
        $this->order    = $order == 'true' ? 'asc' : 'desc';
        $this->per_page =  request()->has( 'per_page' ) ? request()->get('per_page') : 10;
        $this->query    =  request()->has( 'query' ) ? request()->get('query') : null;
        $this->where    =  request()->has( 'where' ) ? request()->get('where') : null;
        $this->where_not    =  request()->has( 'where_not' ) ? request()->get('where_not') : null;
        $this->where_in    =  request()->has( 'where_in' ) ? request()->get('where_in') : null;
        $this->where_not_in    =  request()->has( 'where_not_in' ) ? request()->get('where_not_in') : null;
        $this->where_between    =  request()->has( 'where_between' ) ? request()->get('where_between') : null;
        $this->where_not_between    =  request()->has( 'where_not_between' ) ? request()->get('where_not_between') : null;
        $this->or_where    =  request()->has( 'or_where' ) ? request()->get('or_where') : null;
        $this->or_where_in    =  request()->has( 'or_where_in' ) ? request()->get('or_where_in') : null;
        $this->or_where_not_in    =  request()->has( 'or_where_not_in' ) ? request()->get('or_where_not_in') : null;
        $this->or_where_between    =  request()->has( 'or_where_between' ) ? request()->get('or_where_between') : null;
        $this->or_where_not_between    =  request()->has( 'or_where_not_between' ) ? request()->get('or_where_not_between') : null;
        // Multiple Columns
        $this->where_columns_between    =  $this->setColumnParams('where_columns_between');
        $this->where_columns_not_between    =  $this->setColumnParams('where_columns_not_between');
    }

    public function setColumnParams($key)
    {
        $request = request()->has( $key )
            ? explode('|', request()->get( $key ))
            : null;
        $columns = isset($request[0]) ? explode(',', $request[0]) : [];
        $values =  isset($request[1]) ? explode(',', $request[1]) : [];
        $params = [
            'columns' => [
                Arr::first($columns),
                Arr::last($columns)
            ],
            'values' => [
                Arr::first($values),
                Arr::last($values)
            ]
        ];
        return count($columns) == 2 && count($values) == 2 ? $params : [];
    }

    /**
     * Create custom api query based in request
     *
     * @param Model|Builder|Query $query
     * @param $column
     * @param bool $orderBy
     * @param string $orderByColumn
     * @return Model|Builder|Query
     */
    public function setQuery($query, $column, bool $orderBy = false, string $orderByColumn = 'id')
    {
        return $query
                ->when(request()->has('where'), function ($query) use ($column) {
                    return $query->where($column, $this->where);
                })
                ->when(request()->has('where_not'), function ($query) use ($column) {
                    return $query->where($column, '!=', $this->where_not);
                })
                ->when(request()->has('where_in'), function ($query) use ($column) {
                    return is_array($this->where_in)
                        ? $query->whereIn($column, $this->where_in)
                        : $query->whereIn($column, explode(',', $this->where_in));
                })
                ->when(request()->has('where_not_in'), function ($query) use ($column) {
                    return is_array($this->where_not_in)
                        ? $query->whereNotIn($column, $this->where_not_in)
                        : $query->whereNotIn($column, explode(',', $this->where_not_in));
                })
                ->when(request()->has('where_between'), function ($query) use ($column) {
                    return $query->whereBetween(
                        $column,
                        $this->getQueryArrayForWheres($this->where_between)
                    );
                })
                ->when(request()->has('where_not_between'), function ($query) use ($column) {
                    return $query->whereNotBetween(
                        $column,
                        $this->getQueryArrayForWheres($this->where_not_between)
                    );
                })
                ->when(request()->has('or_where'), function ($query) use ($column) {
                    return isset($this->where)
                        ? $query->orWhere($column, $this->or_where)
                        : $query->where($column, $this->or_where);
                })
                ->when(request()->has('or_where_in'), function ($query) use ($column) {
                    if ( is_array( $this->where_in ) ) {
                        return is_array($this->or_where_in)
                            ? $query->orWhereIn($column, $this->or_where_in)
                            : $query->orWhereIn($column, explode(',', $this->or_where_in));
                    } else {
                        return is_array($this->or_where_in)
                            ? $query->whereIn($column, $this->or_where_in)
                            : $query->whereIn($column, explode(',', $this->or_where_in));
                    }
                })
                ->when(request()->has('or_where_not_in'), function ($query) use ($column) {
                    if ( is_array( $this->where_not_in ) ) {
                        return is_array($this->or_where_not_in)
                            ? $query->orWhereNotIn($column, $this->or_where_not_in)
                            : $query->orWhereNotIn($column, explode(',', $this->or_where_not_in));
                    } else {
                        return is_array($this->or_where_not_in)
                            ? $query->whereNotIn($column, $this->or_where_not_in)
                            : $query->whereNotIn($column, explode(',', $this->or_where_not_in));
                    }
                })
                ->when(request()->has('or_where_between'), function ($query) use ($column) {
                    return isset($this->where_between)
                            ?   $query->orWhereBetween(
                                    $column,
                                    $this->getQueryArrayForWheres($this->or_where_between)
                                )
                            :   $query->whereBetween(
                                    $column,
                                    $this->getQueryArrayForWheres($this->or_where_between)
                                );
                })
                ->when(request()->has('or_where_not_between'), function ($query) use ($column) {
                    return isset($this->where_not_between)
                        ?   $query->orWhereNotBetween(
                                $column,
                                $this->getQueryArrayForWheres($this->or_where_not_between)
                            )
                        :   $query->whereNotBetween(
                                $column,
                                $this->getQueryArrayForWheres($this->or_where_not_between)
                            );
                })
                ->when($orderBy && $this->order, function ($query) use ($orderByColumn) {
                   return $query->orderBy($orderByColumn, $this->order);
                });
    }

    public function getQueryArrayForWheres($request)
    {
        $data = is_array($request)
            ? $request
            : explode(',', $request);
        return [
            Arr::first($data),
            Arr::last($data)
        ];
    }

    public function validation_errors($errors, $code = Response::HTTP_UNPROCESSABLE_ENTITY)
    {
        return response()->json([
            'message'   => __('validation.handler.validation_failed'),
            'errors'    => $errors,
        ], $code);
    }

    /**
     * @param $message
     * @param int $code
     * @param null $details
     * @return JsonResponse
     */
    protected function error_response($message, $code = Response::HTTP_UNPROCESSABLE_ENTITY, $details = null )
    {
        return response()->json([
            'message' =>  $message,
            'details' => $details,
            'code'  =>  $code,
            'requested_at'  =>  now()->toIso8601String()
        ], $code);
    }

    /**
     * @param JsonResource $collection
     * @param int $code
     * @param null $additional
     * @return JsonResponse
     */
    protected function success_response(JsonResource $collection, int $code = Response::HTTP_OK, $additional = null )
    {
        return $collection->additional([
            'code' => $code,
            'details'   => $additional,
            'requested_at'  =>  now()->toIso8601String()
        ])->response()->setStatusCode( $code );
    }

    protected function success_message($message, $code = Response::HTTP_OK, $overrideCode = null, $details = null)
    {
        return response()->json([
            'data'          =>  $message,
            'details'       =>  $details,
            'code'          =>  $overrideCode ? $overrideCode : $code,
            'requested_at'  =>  now()->toIso8601String()
        ], $code);
    }
}
