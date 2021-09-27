<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\JobStatusRequest;
use App\Http\Resources\JobStatusResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Imtigger\LaravelJobStatus\JobStatus;

class JobStatusController extends Controller
{
    /**
     * @param JobStatusRequest $request
     * @return JsonResponse
     */
    public function index(JobStatusRequest $request)
    {
        return $this->success_response(
            JobStatusResource::collection(
                JobStatus::query()
                    ->where('queue', $request->get('type'))
                    ->where('user_id', auth('api')->user()->id)
                    ->get()
            )
        );
    }

    /**
     * @param Request $request
     * @param $job
     * @return JsonResponse
     */
    public function show(Request $request, $job)
    {
        try {
            return $this->success_response(
                new JobStatusResource(
                    JobStatus::query()
                        ->where('key', $job)
                        ->when($request->has('type'), function ($query) use ($request) {
                            return $query->where('queue', $request->get('type'));
                        })
                        ->where('user_id', auth('api')->user()->id)
                        ->firstOrFail()
                )
            );
        } catch (\Exception $exception) {
            return $this->error_response(
                __('validation.handler.resource_not_found'),
                Response::HTTP_NOT_FOUND,
                $exception->getMessage()
            );
        }
    }
}
