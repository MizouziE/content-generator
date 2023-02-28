<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ContentController extends Controller
{

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $content = Content::with('contentTemplate')->find($id);
        $body = Str::markdown(str_replace("\n","  \n", $content->body));

        return Inertia::render('Content/Show', [
            'content' => $content,
            'body' => $body
        ]);
    }
}
