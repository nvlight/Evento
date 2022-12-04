<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\TagValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventoController extends Controller
{
    public function index()
    {
        try{
            $items = Evento::
                  join('tag_values', 'tag_values.evento_id', '=', 'eventos.id')
                ->join('tags', 'tags.id', '=', 'tag_values.tag_id_first')
                ->leftJoin('tags as tags2', 'tags2.id', 'tag_values.tag_id_second')
                ->select('eventos.id', 'eventos.date', 'tag_values.value', 'tag_values.description',
                        'tag_values.tag_id_first', 'tag_values.tag_id_second',
                        'tags.name as tag_id_first_name', 'tags2.name as tag_id_second_name',
                    )
                ->where('eventos.user_id', Auth::user()->id)
                ->where('tags.user_id', Auth::user()->id)
                ->orderBy('eventos.date', 'DESC')
//                ->toSql()
                ->get()
            ;
        }catch (\Exception $e){
            $this->saveToLog(__METHOD__, $e);
            return response()->json([
                'success' => 0,
                'error' => 'some error!',
            ]);
        }

        return json_encode([
            'data' => $items,
            'success' => 1,
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'date' => 'nullable|date',
            'tag_id_first'  => 'required|integer|exists:tags,id',
            'tag_id_second' => 'integer|min:0',
            'value' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
        ]);

        if ($request->tag_id_second > 0) {
            $this->validate($request, [
                'tag_id_second' => 'exists:tags,id',
            ]);
        }

        try{
            DB::transaction(function () use($request){

                $evento = new Evento();
                $evento->user_id = Auth::user()->id;

                $evento->date = $request->date;
                $evento->tag_value_id = 0;
                $evento->save();

                $tagValue = new TagValue();
                $tagValue->evento_id = $evento->id;
                $tagValue->tag_id_first  = $request->tag_id_first;
                $tagValue->tag_id_second = $request->tag_id_second;
                $tagValue->value         = $request->value;
                $tagValue->description   = $request->description;
                $tagValue->save();

                $evento->tag_value_id = $tagValue->id;
                $evento->save();
            });
        }catch (\Exception $e){
            $this->saveToLog(__METHOD__, $e);
            return response()->json([
                'success' => 0,
                'error' => 'some error!',
            ]);
        }

        return response()->json([
            'success' => 1,
            'savedId' => 0,
        ]);
    }

    public function show(Evento $evento)
    {
        //
    }

    public function edit(Evento $evento)
    {
        //
    }

    public function update(Request $request, Evento $evento)
    {
        //
    }

    public function destroy(Evento $evento)
    {
        //
    }

    protected function saveToLog($method, $e){
        logger('error in method: ' . $method. '! '
            . implode(' | ', [
                'msg: '  . $e->getMessage(),
                'line: ' . $e->getLine(),
                'file: ' . $e->getFile(),
                'code: ' . $e->getCode(),
            ])
        );
    }
}
