<?php

namespace App\Http\Controllers\GlobalData;

use App\Http\Requests\GlobalData\AnimationsRaquest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AnimationsController extends Controller
{
    public function index(Request $request)
    {
        $page = (int) $request->get('page') ?: 1;
        $files = collect(Storage::disk('public')->allFiles('lottie'))->map(function ($file) {
            return [
                'id'     => Str::substr($file, 7, 100),
                'name'   => Str::substr($file, 7, 100),
                'source' => url()->route('animations.show', [ 'file' => Str::substr($file, 7, 100) ])
            ];
        });

        $slice = $files->slice(($page-1)* $this->per_page, $this->per_page)->toArray();
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            array_values($slice),
            $files->count(),
            $this->per_page,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query()
            ]
        );
        return $this->success_message($paginator);
    }

    public function show($file)
    {
        $file =  $this->file_exists($file)
            ?   file_get_contents( storage_path("app/public/lottie/$file") )
            :  file_get_contents(storage_path("app/public/lottie/404.json"));

        return $this->success_message([
            'lottie'    =>  json_decode($file)
        ]);
    }

    public function store(AnimationsRaquest $request)
    {
        $file = $request->file('file')->getClientOriginalName();
        if ( $this->file_exists( $file ) ) {
            return $this->error_response('Ya existe un archivo con este nombre.');
        }
        $request->file('file')->storePubliclyAs('lottie', $file, [ 'disk' => 'public' ]);
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_CREATED
        );
    }

    public function file_exists($file)
    {
        return Storage::disk('public')->exists("lottie/$file");
    }
}
