<?php


namespace App\Modules\Contractors\src\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Security\User;
use App\Modules\Contractors\src\Constants\Roles;
use App\Modules\Contractors\src\Jobs\ConfirmArlContractor;
use App\Modules\Contractors\src\Jobs\SendArlContractor;
use App\Modules\Contractors\src\Mail\ContractorLegalMail;
use App\Modules\Contractors\src\Models\Contract;
use App\Modules\Contractors\src\Models\Contractor;
use App\Modules\Contractors\src\Models\ContractType;
use App\Modules\Contractors\src\Models\File;
use App\Modules\Contractors\src\Models\FileType;
use App\Modules\Contractors\src\Notifications\LegalNotification;
use App\Modules\Contractors\src\Request\StoreFileRequest;
use App\Modules\Contractors\src\Resources\ContractorResource;
use App\Modules\Contractors\src\Resources\ContractTypeResource;
use App\Modules\Contractors\src\Resources\FileResource;
use App\Modules\Contractors\src\Resources\FileTypeResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
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
     * @param Contract $contract
     * @return JsonResponse
     */
    public function index(Contract $contract)
    {
        return $this->success_response(
            FileResource::collection($contract->files())
        );
    }

    public function store(StoreFileRequest $request, Contract $contract)
    {
        $ext = $request->file('file')->getClientOriginalExtension();
        $document = isset($contract->contractor->document) ? $contract->contractor->document : '00000';
        $contract_number = isset($contract->contract) ? str_replace('-','_', $contract->contract) : '000_0000';
        $type = FileType::find( $request->get('file_type_id') );
        $type = isset( $type->name ) ? str_replace(' ', '_', $type->name) : 'SYSTEM';
        $now = now()->format('YmdHis');
        $filename = "{$type}_{$document}_{$contract_number}_{$now}.$ext";
        if ($request->file('file')->storeAs('arl', $filename, [ 'disk' => 'local' ])) {
            $contract->files()->create([
                'name'          =>  $filename,
                'file_type_id'  =>  $request->get('file_type_id'),
                'user_id'       =>  auth()->user()->id,
            ]);
            if ($request->get('file_type_id') == 1) {
                $this->dispatch(new ConfirmArlContractor($contract->contractor, $contract));
                $this->dispatch(new SendArlContractor($contract->contractor, $contract));
                Notification::send( User::whereIs(Roles::ROLE_LEGAL)->get(), new LegalNotification($contract->contractor, $contract) );
            }
            return $this->success_message(__('validation.handler.success'));
        }
        return $this->error_response(__('validation.handler.unexpected_failure'));
    }

    public function file(File $file)
    {
        $name = isset($file->name) ? $file->name : null;
        if (Storage::disk('local')->exists("arl/{$name}")) {
            return response()->file(storage_path("app/arl/{$name}"));
        }
        abort(Response::HTTP_NOT_FOUND);
    }

    public function destroy($contract, File $file)
    {
        $name = isset($file->name) ? $file->name : null;
        $file->delete();
        if (Storage::disk('local')->exists("arl/{$name}")) {
            Storage::disk('local')->delete("arl/{$name}");
            return $this->success_message(__('validation.handler.deleted'));
        }
        return $this->error_response(__('validation.handler.unexpected_failure'));
    }
}
