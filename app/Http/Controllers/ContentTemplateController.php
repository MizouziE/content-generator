<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContentTemplateRequest;
use App\Models\ContentTemplate;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ContentTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('ContentTemplates/Show');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContentTemplateRequest $request)
    {
        $values = $request->validated();

        $csvPath = $request->file('csv')->store();

        $contentTemplate = ContentTemplate::create([
            'columns' => json_encode($values['columns']),
            'prompts' => json_encode($values['prompts']),
            'user_id' => Auth::user()->id,
            'csv_path' => $csvPath
        ]);

        return to_route('contentTemplates');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, string $id)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
