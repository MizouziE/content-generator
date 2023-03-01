<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContentTemplateRequest;
use App\Jobs\ProcessContentTemplate;
use App\Models\ContentTemplate;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ContentTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templates = ContentTemplate::all();

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

        $contentTemplate = ContentTemplate::create([
            'columns' => json_encode(array_filter($values['columns'])),
            'prompts' => json_encode(array_filter($values['prompts'])),
            'max_tokens' => $values['maxTokens'],
            'user_id' => Auth::user()->id,
            'csv_path' => $csvPath
        ]);

        // Start job
        ProcessContentTemplate::dispatch($contentTemplate);

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
