<?php


namespace App\Modules\Passport\src\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\Passport\src\Models\Agreements;
use App\Modules\Passport\src\Models\Company;
use App\Modules\Passport\src\Models\Dashboard;
use App\Modules\Passport\src\Models\Eps;
use App\Modules\Passport\src\Models\Faq;
use App\Modules\Passport\src\Request\StoreCommentRequest;
use App\Modules\Passport\src\Request\StoreRateRequest;
use App\Modules\Passport\src\Resources\AgreementResource;
use App\Modules\Passport\src\Resources\CompanyResource;
use App\Modules\Passport\src\Resources\DashboardResource;
use App\Modules\Passport\src\Resources\EpsResource;
use App\Modules\Passport\src\Resources\FaqResource;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\Psr7\str;

class LandingController extends Controller
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
    public function background()
    {
        $bg = Dashboard::query()->first();
        $bg = isset($bg->background)
            ? (string) $bg->background
            : 'https://images.unsplash.com/photo-1568480289356-5a75d0fd47fc?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80';
        return $this->success_message(
            [
                'background' => $bg
            ]
        );
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
        return $this->success_response( new DashboardResource( Dashboard::query()->first() ) );
    }

    /**
     * Portfolio
     *
     * @return JsonResponse
     */
    public function portfolio(Request $request)
    {
        $agreements = Agreements::with('images', 'comments', 'company')
                            ->withCount('comments')
                            ->addSelect(DB::raw('IFNULL(ROUND((5 * rate_5 + 4 * rate_4 + 3 * rate_3 + 2 * rate_2 + 1 * rate_1) / (rate_5 + rate_4 + rate_3 + rate_2 + rate_1),1), 0) AS rate'))
                            ->addSelect(DB::raw('(rate_5 + rate_4 + rate_3 + rate_2 + rate_1) AS raters'))
                            ->when($request->has('rate'), function ($query) use ($request) {
                                return $query->whereRaw(
                                    DB::raw('IFNULL(ROUND((5 * rate_5 + 4 * rate_4 + 3 * rate_3 + 2 * rate_2 + 1 * rate_1) / (rate_5 + rate_4 + rate_3 + rate_2 + rate_1),1), 0) >= ?'),
                                    [ (int) $request->get('rate') ]
                                )->whereRaw(
                                    DB::raw('IFNULL(ROUND((5 * rate_5 + 4 * rate_4 + 3 * rate_3 + 2 * rate_2 + 1 * rate_1) / (rate_5 + rate_4 + rate_3 + rate_2 + rate_1),1), 0) < ?'),
                                    [ (int) $request->get('rate') + 1 ]
                                );
                            })
                            ->when($request->has('company_id'), function ($query) use ($request) {
                                return $query->where('company_id', $request->get('company_id'));
                            })
                            ->when($request->has('query'), function ($query) use ($request) {
                                return $query
                                        ->where('agreement', 'like', "%{$request->get('query')}%")
                                        ->orWhere('agreement', 'like', "%{$request->get('query')}%");
                            })
                            ->latest()
                            ->paginate($this->per_page);

        return $this->success_response(AgreementResource::collection($agreements));
    }

    /**
     * @param StoreRateRequest $request
     * @param Agreements $agreement
     * @return JsonResponse
     */
    public function rate(StoreRateRequest $request, Agreements $agreement)
    {
        $agreement->increment("rate_{$request->get('rate')}");
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_CREATED,
            Response::HTTP_CREATED,
            [
                'rating'        =>  isset($agreement->rate) ? (float) number_format($agreement->rate, 1) : 0,
            ]
        );
    }

    /**
     * @param StoreCommentRequest $request
     * @param Agreements $agreement
     * @return JsonResponse
     */
    public function comment(StoreCommentRequest $request, Agreements $agreement)
    {
        $comment = $agreement->comments()->create($request->all());
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_CREATED,
            Response::HTTP_CREATED,
            $comment
        );
    }

    /**
     * @return JsonResponse
     */
    public function faq()
    {
        return $this->success_message(
            FaqResource::collection( Faq::all() )
        );
    }
}