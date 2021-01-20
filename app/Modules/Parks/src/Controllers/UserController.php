<?php

namespace App\Modules\Parks\src\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a menu of the menu for current user.
     *
     * @return JsonResponse
     */
    public function menu()
    {
        $menu = collect([
            [
                'icon'  =>  'mdi-account-multiple-plus',
                'title' =>  __('parks.menu.users'),
                'to'    =>  [ 'name' => 'parks-users' ],
                'exact' =>  true,
                'can'   =>  true,
            ],
            [
                'icon'  =>  'mdi-view-dashboard',
                'title' =>  __('parks.menu.dashboard'),
                'to'    =>  [ 'name' => 'parks' ],
                'exact' =>  true,
                'can'   =>  true,
            ],
            [
                'icon'  =>  'mdi-magnify-scan',
                'title' =>  __('parks.menu.finder'),
                'to'    =>  [ 'name' => 'parks-finder' ],
                'exact' =>  true,
                'can'   =>  true,
            ],
            [
                'icon'  =>  'mdi-clipboard-list-outline',
                'title' =>  __('parks.menu.parks'),
                'to'    =>  [ 'name' => 'parks-create' ],
                'exact' =>  true,
                'can'   =>  true,
            ],
            [
                'icon'  =>  'mdi-map',
                'title' =>  __('parks.menu.map'),
                'to'    =>  [ 'name' => 'parks-map' ],
                'exact' =>  true,
                'can'   =>  true,
            ],
        ]);

        return $this->success_message( array_values( $menu->where('can', true)->toArray() ) );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
