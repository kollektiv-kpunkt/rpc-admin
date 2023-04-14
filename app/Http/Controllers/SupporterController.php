<?php

namespace App\Http\Controllers;

use App\Models\Supporter;
use Illuminate\Http\Request;
use App\Http\Requests\Supporter\ApiStoreRequest;

class SupporterController extends Controller
{
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
     * @param  \App\Models\Supporter  $supporter
     * @return \Illuminate\Http\Response
     */
    public function show(Supporter $supporter)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Supporter  $supporter
     * @return \Illuminate\Http\Response
     */
    public function edit(Supporter $supporter)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supporter  $supporter
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supporter $supporter)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Supporter  $supporter
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supporter $supporter)
    {
        //
    }

    /**
     * Return JSON of all supporters belonging to a site
     */
    public function ApiGet($site)
    {

        $site = \App\Models\Site::findInAny($site);
        if (!$site) {
            return response()->json([
                "code" => 404,
                "status" => "error",
                "message" => "Site not found"
            ],
            404,
            [
                'Content-Type' => 'application/json',
                'Charset' => 'utf-8'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
        $supporters = Supporter::where('site_id', $site->id)->get();
        return response()->json([
            "code" => 200,
            "status" => "ok",
            "data" => $supporters
        ],
        200,
        [
            'Content-Type' => 'application/json',
            'Charset' => 'utf-8'
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Create a new supporter for a site
     */
    public function ApiPost($site, ApiStoreRequest $request)
    {
        $site = \App\Models\Site::findInAny($site);
        if (!$site) {
            return response()->json([
                "code" => 404,
                "status" => "error",
                "message" => "Site not found"
            ],
            404,
            [
                'Content-Type' => 'application/json',
                'Charset' => 'utf-8'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
        $supporter = new Supporter();
        $supporter->fill($request->validated());
        $supporter->site_id = $site->id;
        $supporter->save();
        return response()->json([
            "code" => 200,
            "status" => "ok",
            "data" => $supporter
        ], 200, [
            'Content-Type' => 'application/json',
            'Charset' => 'utf-8'
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
