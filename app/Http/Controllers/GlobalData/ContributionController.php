<?php

namespace App\Http\Controllers\GlobalData;


use App\Http\Controllers\Controller;
use App\Http\Resources\GlobalData\ContributionResource;
use App\Models\Security\Afp;
use App\Models\Security\Arl;
use App\Models\Security\Ccf;
use App\Models\Security\Eps;
use App\Models\Security\Parafiscal;

class ContributionController extends Controller
{
    public function arl()
    {
        return $this->success_response(
            ContributionResource::collection( Arl::active()->get() )
        );
    }
    public function eps()
    {
        return $this->success_response(
            ContributionResource::collection( Eps::active()->get() )
        );
    }

    public function afp()
    {
        return $this->success_response(
            ContributionResource::collection( Afp::active()->get() )
        );
    }

    public function ccf()
    {
        return $this->success_response(
            ContributionResource::collection( Ccf::active()->get() )
        );
    }

    public function parafiscal()
    {
        return $this->success_response(
            ContributionResource::collection( Parafiscal::active()->get() )
        );
    }
}
