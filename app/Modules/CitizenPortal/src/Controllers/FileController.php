<?php


namespace App\Modules\CitizenPortal\src\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\CitizenPortal\src\Constants\Roles;
use App\Modules\CitizenPortal\src\Jobs\ConfirmStatusFileCitizen;
use App\Modules\CitizenPortal\src\Models\CitizenSchedule;
use App\Modules\CitizenPortal\src\Models\File;
use App\Modules\CitizenPortal\src\Models\Observation;
use App\Modules\CitizenPortal\src\Models\Profile;
use App\Modules\CitizenPortal\src\Models\ProfileView;
use App\Modules\CitizenPortal\src\Models\Status;
use App\Modules\CitizenPortal\src\Notifications\FileStatusNotification;
use App\Modules\CitizenPortal\src\Request\FileStatusRequest;
use App\Modules\CitizenPortal\src\Resources\FileResource;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware(
            Roles::canAny(
                [
                    ['model' => File::class, 'actions' => 'view_or_manage'],
                    ['model' => Profile::class, 'actions' => 'view_or_manage'],
                    ['model' => Profile::class, 'actions' => 'status'],
                    ['model' => File::class, 'actions' => 'status'],
                    ['model' => File::class, 'actions' => 'destroy'],
                    ['model' => CitizenSchedule::class, 'actions' => 'status'],
                    ['model' => CitizenSchedule::class, 'actions' => 'view_or_manage'],
                ],
                true,
                true
            )
        )->only('index', 'show');
        $this->middleware(Roles::actions(File::class, 'status'))
            ->only('update');
        $this->middleware(Roles::actions(File::class, 'destroy'))
            ->only('destroy');
    }

    /**
     * @return JsonResponse
     */
    public function index(Profile $profile)
    {
        return $this->success_response(
            FileResource::collection($profile->files()->latest()->paginate($this->per_page))
        );
    }

    /**
     * @param $profile
     * @param File $file
     * @return JsonResponse
     * @throws FileNotFoundException
     */
    public function show($profile, File $file)
    {
        try {
            if ( Storage::disk('citizen_portal')->exists($file->file) ) {
                $selected = Storage::disk('citizen_portal')->get($file->file);
                $mime = Storage::disk('citizen_portal')->mimeType($file->file);
                return $this->success_message([
                    'name'  => $file->file,
                    'mime'  => $mime,
                    'file'   => 'data:'.$mime.';base64,'.base64_encode($selected)
                ]);
            } elseif (Storage::disk('local')->exists('templates/NOT_FOUND.pdf')) {
                $selected = Storage::disk('local')->get('templates/NOT_FOUND.pdf');
                $mime = Storage::disk('local')->mimeType('templates/NOT_FOUND.pdf');
                return $this->success_message([
                    'name'  => $file->file,
                    'mime'  => $mime,
                    'file'   => 'data:'.$mime.';base64,'.base64_encode($selected)
                ]);
            } else {
                return $this->error_response(
                    __('validation.handler.resource_not_found'),
                    Response::HTTP_NOT_FOUND
                );
            }
        } catch (Exception $exception) {
            if (Storage::disk('local')->exists('templates/NOT_FOUND.pdf')) {
                $selected = Storage::disk('local')->get('templates/NOT_FOUND.pdf');
                $mime = Storage::disk('local')->mimeType('templates/NOT_FOUND.pdf');
                return $this->success_message([
                    'name'  => $file->file,
                    'mime'  => $mime,
                    'file'   => 'data:'.$mime.';base64,'.base64_encode($selected)
                ]);
            } else {
                return $this->error_response(
                    __('validation.handler.resource_not_found'),
                    Response::HTTP_NOT_FOUND
                );
            }
        }
    }

    /**
     * @param FileStatusRequest $request
     * @param ProfileView $profile
     * @param File $file
     * @throws \Throwable
     */
    public function update(FileStatusRequest $request, ProfileView $profile, File $file)
    {
        DB::connection('mysql_citizen_portal')->transaction(function () use ($request, $profile, $file) {
            $file->status_id = $request->get('status_id');
            $file->save();
            $status = Status::find($request->get('status_id'));
            $file_type = isset( $file->file_type->name ) ? (string) $file->file_type->name : null;
            $status_name = isset( $status->name ) ? (string) $status->name : null;

            $observation = $request->has('observation') &&
            ($request->get('observation') != null || $request->get('observation') != '')
                ? toUpper( 'VALIDACIÓN ARCHIVO: '.$file_type.' ESTADO: '.$status_name." OBSERVACIÓN: ".$request->get('observation') )
                : 'VALIDACIÓN ARCHIVO: '.$file_type.' ESTADO: '.$status_name;
            $file->profile->observations()->create([
                'observation'   => $observation,
                'user_ldap_id'       =>  auth('api')->user()->id,
            ]);
            $this->dispatch(new ConfirmStatusFileCitizen(
                $profile,
                $status,
                $file,
                $observation,
                auth('api')->user()->email
            ));
            $profile->user->notify(new FileStatusNotification(
                $profile,
                $status,
                $file,
                $observation,
                auth('api')->user()->full_name
            ));
        });
        return $this->success_message(
            __('validation.handler.success')
        );
    }

    /**
     * @param $profile
     * @param File $file
     * @return JsonResponse
     */
    public function destroy($profile, File $file)
    {
        try {
            if (Storage::disk('citizen_portal')->exists($file->file) ) {
                if (env('APP_ENV') == 'production') {
                    Storage::disk('citizen_portal')->delete($file->file);
                }
            }
            if (env('APP_ENV') == 'production') {
                $file->delete();
            }
            return $this->success_message(
                __('validation.handler.deleted'),
                Response::HTTP_OK,
                Response::HTTP_NO_CONTENT
            );
        } catch (Exception $exception) {
            return $this->error_response(
                __('validation.handler.service_unavailable')
            );
        }
    }
}
