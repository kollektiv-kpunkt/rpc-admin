<?php

namespace App\Http\Controllers;

use App\Models\Supporter;
use App\Models\Site;
use Illuminate\Http\Request;
use App\Http\Requests\Supporter\ApiStoreRequest;

class SupporterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $site = Site::findInAnyOrFail($request->route()->parameter("site"));
        $supporters = Supporter::where("site_id", $site->id)->orderBy("created_at", "desc")->get();
        return view("sites.supporters.index",[
            "site" => $site,
            "supporters" => $supporters
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Site $site)
    {
        $customFields = $site->supporterCustomFields();
        return view("sites.supporters.create", [
            "site" => $site,
            "customFields" => $customFields
        ]);
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
            "uuid" => "required|string",
            "name" => "required|string",
            "email" => "required|email",
            "data" => "required|array",
            "site_id" => "required|integer"
        ]);
        if (!auth()->user()->hasAccessToSite($validated["site_id"]) && !auth()->user()->hasRole("admin")) {
            abort(403);
        }
        try {
            $supporter = Supporter::create($validated);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                abort(409, "Supporter already exists");
            }
        }
        return redirect()->route("supporters.index", $validated["site_id"]);
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
    public function edit(Site $site, Supporter $supporter)
    {
        $customFields = $site->supporterCustomFields();
        return view("sites.supporters.edit", [
            "site" => $site,
            "supporter" => $supporter,
            "customFields" => $customFields
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supporter  $supporter
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Site $site, Supporter $supporter)
    {
        $validated = $request->validate([
            "uuid" => "required|string",
            "name" => "required|string",
            "email" => "required|email",
            "data" => "required|array"
        ]);
        $supporter->update($validated);
        $supporter->save();
        return redirect()->route("supporters.index", ["site"=>$site]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Supporter  $supporter
     * @return \Illuminate\Http\Response
     */
    public function destroy(Site $site, Supporter $supporter)
    {
        $supporter->delete();
        return redirect()->route('supporters.index', $supporter->site_id);
    }

    /**
     * Return JSON of all supporters belonging to a site
     */
    public function ApiGet($site)
    {

        $site = \App\Models\Site::findInAnyOrFail($site);
        $supportersQuery = Supporter::where('site_id', $site->id)->where("status", "active")->whereJsonContains('data', ['public' => "1"]);
        if (request()->has("sort")) {
            $sort = request()->input("sort");
            $order = request()->input("order") ?? "asc";
            $supportersQuery->orderBy($sort, $order);
        }
        $supporters = $supportersQuery->get();
        return response()->json([
            "code" => 200,
            "status" => "ok",
            "data" => [
                "count" => $supporters->count(),
                "supporters" => $supporters
            ]
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
        $data = $request->validated();
        $site = \App\Models\Site::findInAnyOrFail($site);
        if (Supporter::where("uuid", $data["uuid"])->where("site_id", $site->id)->first()) {
            $supporter = Supporter::where("uuid", $data["uuid"])->first();
        } else if (Supporter::where("email", $data["email"])->where("site_id", $site->id)->first()) {
            $supporter = Supporter::where("email", $data["email"])->first();
        } else {
            $supporter = new Supporter();
        }
        $supporter->update($request->validated());
        $supporter->site_id = $site->id;
        return response()->json([
            "code" => 200,
            "status" => "ok",
            "data" => $supporter
        ], 200, [
            'Content-Type' => 'application/json',
            'Charset' => 'utf-8'
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Activate a supporter
     */
    public function activate(Site $site, Supporter $supporter)
    {
        $supporter->status = "active";
        $supporter->save();
        return redirect()->route('supporters.index', $supporter->site_id);
    }

    /**
     * Deactivate a supporter
     */
    public function deactivate(Site $site, Supporter $supporter)
    {
        $supporter->status = "inactive";
        $supporter->save();
        return redirect()->route('supporters.index', $supporter->site_id);
    }

    /**
     * Export Supporters as CSV
     */
    public function export(Site $site)
    {
        $supporters = Supporter::where('site_id', $site->id)->get();
        if (!$supporters->count()) {
            return redirect()->route('supporters.index', $site->id);
        }
        $filename = "supporters-" . $site->name . "-" . date("Y-m-d") . ".csv";
        $fields = [
            "id",
            "uuid",
            "name",
            "email",
            "created_at",
            "updated_at",
            "status"
        ];
        $fields = array_merge($fields, $site->supporterCustomFields());
        $handle = fopen($filename, 'w+');
        fputcsv($handle, $fields);

        foreach($supporters as $supporter) {
            $row = [];
            foreach ($fields as $field) {
                if (isset($supporter->$field)) {
                    $row[] = $supporter->$field;
                } else {
                    $row[] = $supporter->data[$field] ?? "";
                }
            }
            fputcsv($handle, $row);
        }

        fclose($handle);

        $headers = array(
            'Content-Type' => 'text/csv',
        );

        return response()->download($filename, $filename, $headers)->deleteFileAfterSend(true);
    }
}
