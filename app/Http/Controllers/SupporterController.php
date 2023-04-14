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
        $supporter->delete();
        return redirect()->route('sites.supporters', $supporter->site_id);
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
        $supporter = Supporter::where("uuid", $data["uuid"])->first();
        if (!$supporter) {
            $supporter = new Supporter();
        }
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

    /**
     * Activate a supporter
     */
    public function activate(Supporter $supporter)
    {
        $supporter->status = "active";
        $supporter->save();
        return redirect()->route('sites.supporters', $supporter->site_id);
    }

    /**
     * Export Supporters as CSV
     */
    public function export(Site $site)
    {
        $supporters = Supporter::where('site_id', $site->id)->get();
        if (!$supporters->count()) {
            return redirect()->route('sites.supporters', $site->id);
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
        foreach($supporters as $supporter) {
            foreach ($supporter->data as $key => $field) {
                $fields[] = $key;
            }
        }
        $fields = array_unique($fields);
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
