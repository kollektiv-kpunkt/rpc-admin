<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\User;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('sites.index', [
            'sites' => Site::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sites.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'url' => 'required|url',
            "status" => ""
        ]);
        $site = new Site();
        $site->fill($validated);
        $site->key = bin2hex(random_bytes(32));
        $site->save();
        return redirect()->route('sites.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function show(Site $site)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function edit(Site $site)
    {
        return view('sites.edit', [
            'site' => $site,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Site $site)
    {
        $validated = $request->validate([
            'name' => 'required',
            'url' => 'required|url',
            "status" => ""
        ]);
        $site->fill($validated);
        if (!isset($validated["status"])) {
            $site->status = "pending";
        }
        $site->save();
        return redirect()->route('sites.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function destroy(Site $site)
    {
        $site->delete();
        return redirect()->route('sites.index');
    }

    public function instantiate(Request $request)
    {
        if (Site::where('url', $request->url)->exists()) {
            return response()->json([
                "error" => "Site already exists",
            ]);
        }
        $site = new Site();
        $site->name = $request->url;
        $site->url = $request->url;
        $site->key = bin2hex(random_bytes(32));
        $site->save();
        $users = User::where('role', 'admin')->get();
        foreach ($users as $user) {
            $user->sites = array_merge($user->sites, [$site->id]);
            $user->save();
        }

        return response()->json([
            "status" => "ok",
            "siteID" => $site->id,
            "siteKey" => $site->key,
        ]);
    }
}
