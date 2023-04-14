<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('users.index', [
            'users' => User::all()
        ]);
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
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    /**
     * Activate a user
     */
    public function activate(User $user)
    {
        $user->admin_activation = true;
        $user->save();
        return redirect()->route('users.index');
    }

    public function WPUser(Request $request)
    {
        $site = \App\Models\Site::findInAny($request->site);
        if (!$site) {
            return response()->json([
                "error" => "Site not found"
            ]);
        }
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->password = bcrypt($request->password);
            $user->sites = array_merge($user->sites, [$site->id]);
            $user->save();
        } else {
            $user = User::create([
                "name" => $request->name,
                "email" => $request->email,
                "role" => "user",
                "password" => bcrypt($request->password),
                "sites" => [$site->id]
            ]);
        }

        return response()->json([
            "id" => $user->id,
            "email" => $user->email,
            "name" => $user->name,
            "role" => $user->role
        ]);
    }
}
