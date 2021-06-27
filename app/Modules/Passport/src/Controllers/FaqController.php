<?php


namespace App\Modules\Passport\src\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\Passport\src\Models\Faq;
use App\Modules\Passport\src\Resources\FaqResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FaqController extends Controller
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
    public function index()
    {
        return $this->success_message(
            FaqResource::collection( Faq::all() )
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $form = new Faq();
        $form->header = $request->get('header');
        $form->answer  = json_encode($request->get('answer'));
        $form->url   = $request->get('link');
        $form->to   = $request->get('to');
        $form->video   = $request->get('video');
        $form->save();
        return $this->success_message(__('validation.handler.success'), Response::HTTP_CREATED);
    }

    public function update(Request $request, Faq $faq)
    {
        $faq->header = $request->get('header');
        $faq->answer  = json_encode($request->get('answer'));
        $faq->url   = $request->get('link');
        $faq->to   = $request->get('to');
        $faq->video   = $request->get('video');
        $faq->save();
        return $this->success_message(__('validation.handler.updated'));
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return $this->success_message(__('validation.handler.deleted'), Response::HTTP_OK, Response::HTTP_NO_CONTENT);
    }
}
