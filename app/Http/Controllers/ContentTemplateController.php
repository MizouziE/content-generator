<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContentTemplateRequest;
use App\Imports\Spreadsheet;
use App\Jobs\ProcessContentTemplate;
use App\Models\ContentTemplate;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class ContentTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templates = ContentTemplate::with('content')->get();

        return Inertia::render('ContentTemplates/Index', ['templates' => $templates]);
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
        
        $columns = array_filter($values['columns']);
        $prompts = array_filter($values['prompts']);

        $contentTemplate = ContentTemplate::create([
            'columns' => json_encode($columns),
            'prompts' => json_encode($prompts),
            'max_tokens' => $values['maxTokens'],
            'user_id' => Auth::user()->id,
            'csv_path' => $csvPath
        ]);

        // Start job
        Excel::import(new Spreadsheet($columns, $prompts, $contentTemplate->id, $values['maxTokens']), $request->file('csv'));

        return redirect(route('contentTemplates'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $template = ContentTemplate::with('content')->find($id);

        return Inertia::render('ContentTemplates/Show', ['template' => $template]);
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
