<?php


namespace App\Modules\Passport\src\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\Passport\src\Constants\Roles;
use App\Modules\Passport\src\Models\Agreements;
use App\Modules\Passport\src\Models\Company;
use App\Modules\Passport\src\Models\Dashboard;
use App\Modules\Passport\src\Models\Eps;
use App\Modules\Passport\src\Models\Passport;
use App\Modules\Passport\src\Models\PassportOld;
use App\Modules\Passport\src\Request\StoreBackgroundRequest;
use App\Modules\Passport\src\Request\StoreLandingRequest;
use App\Modules\Passport\src\Resources\EpsResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
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
    public function stats()
    {
        return $this->success_message(
            [
                'companies' =>  Company::count(),
                'services'  =>  Agreements::count(),
                'downloads' =>  (int) Passport::query()->sum('downloads') + (int) PassportOld::query()->sum('downloads')
            ]
        );
    }

    /**
     * @param StoreBackgroundRequest $request
     * @param Dashboard $dashboard
     * @return JsonResponse
     */
    public function background(StoreBackgroundRequest $request, Dashboard $dashboard)
    {
        $ext = $request->file('background')->getClientOriginalExtension();
        $guidText = random_img_name();
        $request->file('background')->storePubliclyAs(
            'passport-services',
            "BG-$guidText.$ext",
            [
                'disk' => 'public'
            ]
        );
        if ( Storage::disk('public')->exists("passport-services/{$dashboard->background}") ) {
            Storage::disk('public')->delete("passport-services/{$dashboard->background}");
        }
        $dashboard->background = "BG-$guidText.$ext";
        $dashboard->save();
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_CREATED
        );
    }


    public function landing(StoreLandingRequest $request, Dashboard $dashboard)
    {
        $dashboard->title = $request->get('title');
        $dashboard->text = $request->get('text');
        $dashboard->save();
        return $this->success_message(__('validation.handler.updated'));
    }

    public function banner(Request $request, Dashboard $dashboard)
    {
        $dashboard->banner = $request->get('banner');
        $dashboard->save();
        return $this->success_message(
            __('validation.handler.updated')
        );
    }

    public function destroyBanner(Dashboard $dashboard)
    {
        abort_unless(
            auth('api')->user()->isAn(...Roles::all()),
            Response::HTTP_UNAUTHORIZED,
            __('validation.handler.unauthorized')
        );
        $dashboard->banner = null;
        $dashboard->save();
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_OK,
            Response::HTTP_NO_CONTENT
        );
    }
}
