<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Rules\Uppercase;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function distinct(Request $request)
    {
        $validated = $request->validate([
            //'foo1.*.id' => 'distinct:ignore_case',
            //'foo2.*.id' => 'distinct:strict',
            //'foo3.*.id' => 'distinct',
            //'chich' => 'ends_with:marin,martin,enigma',
            'name' => 'required|string|in:martin,german',
            'file' => 'required|file|mimes:pdf|max:1427',
            'img' => 'required|image',
            'anather' => 'in_array:props.*',
            'props' => 'array',
            'tj' => 'required|json',
        ]);

        $fileKey = 'file';
        $imgName = 'img';

        return response()->json([
            'tj' => json_decode($validated['tj']),
            //'request_all' => $validated,
            //'files_all' =>  json_encode($request->files->all()),
            //'files_count' => $request->files->count(),

            'getClientOriginalName' => $request->file($fileKey)->getClientOriginalName(),
            'extension' => $request->file($fileKey)->extension(),
            'getMimeType' => $request->file($fileKey)->getMimeType(),
            'file_size' => $request->file($fileKey)->getSize(),
            'file_type' => $request->file($fileKey)->getType(),

            'placer' => '',

            'img_getClientOriginalName' => $request->file($imgName)->getClientOriginalName(),
            'img_12' => $request->file($imgName)->extension(),
            'img_getClientMimeType' => $request->file($imgName)->getClientMimeType(),
            'img_size' => $request->file($imgName)->getSize(),
            'img_type' => $request->file($imgName)->getType(),
        ]);
    }

    public function customRuse(Request $request )
    {
        $request->validate([
            'name' => ['required', 'string', new Uppercase()],
        ]);

        return response()->json([
            'success' => true,
        ]);
    }

    public function eloquent_base(Request $request)
    {
        $eventos = Evento::query()->where('id', '>', 533)->getBindings();
        dump($eventos);

        $evento = Evento::query()->where('id', '>', 0)->first();
        //dump($evento);
        //dump($evento->tagValue);

        $id = Evento::query()->where('id', '>', 0)->value('id');
        //dump($id);

        //$eventoTag = Evento::query()->tag;
        //
    }
}
