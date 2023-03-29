<?php

namespace App\Http\Controllers;

use App\Models\Prompt;
use Illuminate\Http\Request;

class PromptApiController extends Controller
{
    /**
     * Retrun search results
     */
    public function search(Request $request)
    {
        $search = $request->get('search');

        $prompts = Prompt::where('body', 'like', '%' . $search . '%')->get();

        return response()->json($prompts);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Prompt $prompt)
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
