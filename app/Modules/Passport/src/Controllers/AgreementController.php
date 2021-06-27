<?php


namespace App\Modules\Passport\src\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\Passport\src\Models\Agreements;
use App\Modules\Passport\src\Models\Comment;
use App\Modules\Passport\src\Models\Company;
use App\Modules\Passport\src\Models\Eps;
use App\Modules\Passport\src\Models\Image;
use App\Modules\Passport\src\Request\StoreAgreementRequest;
use App\Modules\Passport\src\Request\StoreCompanyRequest;
use App\Modules\Passport\src\Request\StoreImageRequest;
use App\Modules\Passport\src\Resources\AgreementResource;
use App\Modules\Passport\src\Resources\CompanyResource;
use App\Modules\Passport\src\Resources\EpsResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AgreementController extends Controller
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
    public function index(Request $request)
    {
        return $this->success_response(
            AgreementResource::collection(
                $this->setQuery( Agreements::query(), 'id' )
                    ->with('images', 'comments', 'company')
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
                    ->paginate($this->per_page)
            )
        );
    }

    /**
     * @param StoreAgreementRequest $request
     * @return JsonResponse
     */
    public function store(StoreAgreementRequest $request)
    {
        $agreement = new Agreements();
        $agreement->fill( $request->validated() );
        $agreement->save();
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_CREATED
        );
    }

    /**
     * @param StoreAgreementRequest $request
     * @param Agreements $agreement
     * @return JsonResponse
     */
    public function update(StoreAgreementRequest $request, Agreements $agreement)
    {
        $agreement->fill( $request->validated() );
        $agreement->save();
        return $this->success_message(__('validation.handler.success'));
    }

    /**
     * @param Agreements $agreement
     * @return JsonResponse
     */
    public function destroy(Agreements $agreement)
    {
        $agreement->delete();
        return $this->success_message(
            __('validation.handler.deleted'),
            Response::HTTP_OK,
            Response::HTTP_NO_CONTENT
        );
    }

    /**
     * @param StoreImageRequest $request
     * @param Agreements $agreement
     */
    public function image(StoreImageRequest $request, Agreements $agreement)
    {
        $ext = $request->file('image')->getClientOriginalExtension();
        $guidText = random_img_name();

        $request->file('image')->storePubliclyAs(
            'passport-services',
            "IMG-$guidText.$ext",
            [
                'disk' => 'public'
            ]
        );

        $agreement->images()->create([
            'name'  => "IMG-$guidText.$ext"
        ]);
        return $this->success_message(__('validation.handler.success'));
    }

    /**
     * @param Image $image
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroyImage(Image $image)
    {
        if ( Storage::disk('public')->exists("passport-services/{$image->name}") ) {
            Storage::disk('public')->delete("passport-services/{$image->name}");
        }
        $image->delete();
        return $this->success_message(
            __('validation.handler.deleted'),
            Response::HTTP_OK,
            Response::HTTP_NO_CONTENT
        );
    }

    /**
     * @param Comment $comment
     * @return JsonResponse
     */
    public function destroyComment(Comment $comment)
    {
        $comment->delete();
        return $this->success_message(
            __('validation.handler.deleted'),
            Response::HTTP_OK,
            Response::HTTP_NO_CONTENT
        );
    }
}
