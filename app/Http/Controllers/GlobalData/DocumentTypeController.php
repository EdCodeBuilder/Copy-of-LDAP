<?php

namespace App\Http\Controllers\GlobalData;

use App\Http\Resources\GlobalData\DocumentTypeResource;
use App\Models\Security\DocumentType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        $data = DocumentType::query()
            ->when($request->has('document_types'), function ($query) use($request) {
                $types = $request->get('document_types');
                return is_array($types)
                    ? $query->whereIn('Id_TipoDocumento', $types)
                    : $query->where('Id_TipoDocumento', $types);
            })->get();
        return $this->success_response(
            DocumentTypeResource::collection($data)
        );
    }
}
