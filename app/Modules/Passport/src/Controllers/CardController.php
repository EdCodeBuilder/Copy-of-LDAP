<?php


namespace App\Modules\Passport\src\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\Passport\src\Models\Card;
use App\Modules\Passport\src\Models\Dashboard;
use App\Modules\Passport\src\Request\StoreCardRequest;
use App\Modules\Passport\src\Resources\CardResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class CardController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Dashboard $dashboard)
    {
        return $this->success_response(
            CardResource::collection($dashboard->cards)
        );
    }

    public function store(StoreCardRequest $request, Dashboard $dashboard)
    {
        $dashboard->cards()->create(
            $this->setForm($request)
        );
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_CREATED
        );
    }

    public function update(StoreCardRequest $request, $dashboard, Card $card)
    {
        $form = $this->setForm($request, $card->src);
        $card->fill( $form );
        $card->save();
        return $this->success_message(
            __('validation.handler.updated'),
            200,
            200,
            $form
        );
    }

    public function destroy($dashboard, Card $card)
    {
        $card->delete();
        return $this->success_message(
            __('validation.handler.deleted'),
            Response::HTTP_OK,
            Response::HTTP_NO_CONTENT
        );
    }

    public function setForm(StoreCardRequest $request, $oldSrc = null)
    {
        $form = [
            'title'         =>  $request->get('title'),
            'description'   =>  $request->get('description'),
            'btn_text'      =>  $request->get('btn_text'),
            'flex'           =>  (int) $request->get('flex'),
            'lottie'        =>  $request->get('lottie'),
            'src'           =>  null,
            'to'            =>  $request->get('to'),
            'href'          =>  $request->get('href')
        ];

        if ( $request->has('to') && !is_null( $request->get('to') ) ) {
            Arr::set($form, 'href', null);
        }
        if ( $request->has('href') && !is_null( $request->get('href') ) ) {
            Arr::set($form, 'to', null);
        }
        if ($request->hasFile('src')) {
            $name = $this->moveImage($request);
            Arr::set($form, 'src', $name);
            Arr::set($form, 'lottie', null);
        }
        if ($request->has('lottie')) {
           if ($oldSrc) {
               if (Storage::disk('public')->exists("passport-services/$oldSrc") ) {
                   Storage::disk('public')->delete("passport-services/$oldSrc");
               }
           }
            Arr::set($form, 'src', null);
        }
        return $form;
    }

    public function moveImage(Request $request)
    {
        $ext = $request->file('src')->getClientOriginalExtension();
        $guidText = random_img_name();
        $request->file('src')->storePubliclyAs(
            'passport-services',
            "CARD-$guidText.$ext",
            [
                'disk' => 'public'
            ]
        );
        return "CARD-$guidText.$ext";
    }
}
