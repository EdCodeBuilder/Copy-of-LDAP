<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Imtigger\LaravelJobStatus\JobStatus;

class ExportController extends Controller
{
    public function index($file)
    {
        if (is_null($file)) {
            return $this->error_response(
                __('validation.handler.resource_not_found'),
                Response::HTTP_NOT_FOUND
            );
        }
        $exist = Storage::disk('local')->exists("exports/{$file}");
        if (!$exist) {
            return $this->error_response(
                __('validation.handler.resource_not_found'),
                Response::HTTP_NOT_FOUND
            );
        }
        $export = base64_encode( file_get_contents( storage_path("app/exports/{$file}") ) );
        $response = [
            'name' => $file,
            'file'  => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,$export",
        ];
        Storage::disk('local')->delete("exports/{$file}");
        JobStatus::query()->where('key', $file)->where('user_id', auth('api')->user()->id)->delete();
        return $this->success_message($response);
    }
}
