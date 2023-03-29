<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePromptRequest;
use App\Models\Prompt;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PromptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prompts = Prompt::all();

        return Inertia::render('Prompts/Index', ['prompts' => $prompts]);
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
    public function store(StorePromptRequest $request)
    {
        $values = $request->validated();

        $prompt = Prompt::create([
            'body' => $values['body']
        ]);

        return redirect(route('prompts.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $prompt = Prompt::find($id);

        return Inertia::render('Prompts/Show', ['prompt' => $prompt]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prompt $prompt)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prompt $prompt)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prompt $prompt)
    {
        //
    }
}
