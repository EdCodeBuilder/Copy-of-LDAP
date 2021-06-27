<?php

namespace App\Http\Controllers\GlobalData;


use App\Http\Controllers\Controller;
use App\Http\Resources\GlobalData\ContributionResource;
use App\Models\Security\Afp;
use App\Models\Security\Arl;
use App\Models\Security\Ccf;
use App\Models\Security\Eps;
use App\Models\Security\Parafiscal;
use Illuminate\Http\JsonResponse;

class ContributionController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return JsonResponse
     */
    public function arl()
    {
        return $this->success_response(
            ContributionResource::collection( $this->setQuery(Arl::active(), 'id')->get() )
        );
    }

    /**
     * @return JsonResponse
     */
    public function eps()
    {
        return $this->success_response(
            ContributionResource::collection( $this->setQuery(Eps::active(), 'id')->get() )
        );
    }

    /**
     * @return JsonResponse
     */
    public function afp()
    {
        return $this->success_response(
            ContributionResource::collection( $this->setQuery(Afp::active(), 'id')->get() )
        );
    }

    /**
     * @return JsonResponse
     */
    public function ccf()
    {
        return $this->success_response(
            ContributionResource::collection( $this->setQuery(Ccf::active(), 'id')->get() )
        );
    }

    /**
     * @return JsonResponse
     */
    public function parafiscal()
    {
        return $this->success_response(
            ContributionResource::collection( $this->setQuery(Parafiscal::active(), 'id')->get() )
        );
    }
}
