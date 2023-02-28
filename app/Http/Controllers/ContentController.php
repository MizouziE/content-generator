<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContentController extends Controller
{

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $content = Content::find($id);

        return Inertia::render('Content/Show', ['content' => $content]);
    }
}
