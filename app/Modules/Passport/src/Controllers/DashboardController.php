<?php


namespace App\Modules\Passport\src\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\Passport\src\Constants\Roles;
use App\Modules\Passport\src\Models\Agreements;
use App\Modules\Passport\src\Models\Company;
use App\Modules\Passport\src\Models\Dashboard;
use App\Modules\Passport\src\Models\Eps;
use App\Modules\Passport\src\Models\Passport;
use App\Modules\Passport\src\Models\PassportConfig;
use App\Modules\Passport\src\Models\PassportOld;
use App\Modules\Passport\src\Request\StoreBackgroundRequest;
use App\Modules\Passport\src\Request\StoreCardImageRequest;
use App\Modules\Passport\src\Request\StoreCardTemplateRequest;
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

    /**
     * @param StoreLandingRequest $request
     * @param Dashboard $dashboard
     * @return JsonResponse
     */
    public function landing(StoreLandingRequest $request, Dashboard $dashboard)
    {
        $dashboard->title = $request->get('title');
        $dashboard->text = json_encode($request->get('text'));
        $dashboard->save();
        return $this->success_message(__('validation.handler.updated'));
    }

    /**
     * @param Request $request
     * @param Dashboard $dashboard
     * @return JsonResponse
     */
    public function banner(Request $request, Dashboard $dashboard)
    {
        $dashboard->banner = $request->get('banner');
        $dashboard->save();
        return $this->success_message(
            __('validation.handler.updated')
        );
    }

    /**
     * @param Dashboard $dashboard
     * @return JsonResponse
     */
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

    /**
     * @param StoreCardImageRequest $request
     */
    public function updateCardImage(StoreCardImageRequest $request)
    {
        $config = PassportConfig::query()->latest()->firstOrFail();

        $ext = $request->file('image')->getClientOriginalExtension();
        $guidText = random_img_name();
        $request->file('image')->storePubliclyAs(
            'passport-template',
            "PP-$guidText.$ext",
            [
                'disk' => 'public'
            ]
        );
        if ( $config->file != 'PP-0000-0000-0000.png' && Storage::disk('public')->exists("passport-template/{$config->file}") ) {
            Storage::disk('public')->delete("passport-template/{$config->file}");
        }
        $config->file = "PP-$guidText.$ext";
        $config->dark = $request->get('dark');
        $config->save();
        return $this->success_message(
            __('validation.handler.updated')
        );
    }

    /**
     * @param StoreCardTemplateRequest $request
     * @return JsonResponse
     */
    public function updateCardTemplate(StoreCardTemplateRequest $request)
    {
        $config = PassportConfig::query()->latest()->firstOrFail();

        $ext = $request->file('file')->getClientOriginalExtension();
        $guidText = random_img_name();
        $request->file('file')->storePubliclyAs(
            'templates',
            "TP-$guidText.$ext",
            [
                'disk' => 'local'
            ]
        );
        if ( $config->template != 'PASAPORTE_VITAL.pdf' && Storage::disk('local')->exists("templates/$config->template") ) {
            Storage::disk('local')->delete("templates/$config->template");
        }
        $config->template = "TP-$guidText.$ext";
        $config->save();
        return $this->success_message(
            __('validation.handler.updated')
        );
    }
}
