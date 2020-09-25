<?php


namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

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


    public function __construct()
    {
        $this->column   =  \request()->has( 'column' ) ? \request()->get('column')[0] : 'id';
        $this->order    =  \request()->has( 'order' ) ? (boolean) \request()->get('order')[0] : false;
        $this->per_page =  \request()->has( 'per_page' ) ? \request()->get('per_page') : 10;
        $this->query    =  \request()->has( 'query' ) ? \request()->get('query') : null;
        $this->order    =  ( $this->order ) ? 'asc' : 'desc';
    }

    public function validation_errors($errors, $code = Response::HTTP_UNPROCESSABLE_ENTITY)
    {
        return response()->json($errors, $code);
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
            'code'  =>  $code
        ], $code);
    }

    /**
     * @param JsonResource $collection
     * @param int $code
     * @return JsonResponse
     */
    protected function success_response(JsonResource $collection, int $code = Response::HTTP_OK )
    {
        return $collection->response()->setStatusCode( $code );
    }

    protected function success_message($message, $code = Response::HTTP_OK, $overrideCode = null, $details = null)
    {
        return response()->json([
            'data'    =>  $message,
            'details' =>  $details,
            'code'    =>  $overrideCode ? $overrideCode : $code
        ], $code);
    }
}
