<?php

namespace App\Http\Controllers\GlobalData;

use App\Http\Resources\GlobalData\DocumentTypeResource;
use App\Models\Security\DocumentType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use function Clue\StreamFilter\fun;
use function foo\func;

class DocumentTypeController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $data = $this->setQuery(DocumentType::query(), (new DocumentType())->getKeyName())
                    ->when($request->has('document_types'), function ($query) use($request) {
                        $types = $request->get('document_types');
                        return is_array($types)
                            ? $query->whereIn('Id_TipoDocumento', $types)
                            : $query->where('Id_TipoDocumento', $types);
                    })
                    ->when($request->has('age'), function ($query) use ($request) {
                        $keys = $request->get('age', 18) < 18 ? [2, 3, 6, 12] : [1, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14];
                        return $query->whereKey($keys);
                    })
                    ->get();
        return $this->success_response(
            DocumentTypeResource::collection($data)
        );
    }
}
