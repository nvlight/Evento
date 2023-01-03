<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\TagValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Database\QueryException;

class EventoController extends Controller
{
    private function getEventoSqlPart(){
        return Evento::
              join('tag_values', 'tag_values.evento_id', '=', 'eventos.id')
            ->join('tags as tags1', 'tags1.id', '=', 'tag_values.tag_id_first')
            ->leftJoin('tags as tags2', 'tags2.id', 'tag_values.tag_id_second')
            ->select('eventos.id', 'eventos.date', 'tag_values.value', 'tag_values.description',
                'tag_values.tag_id_first', 'tag_values.tag_id_second',
                'tags1.name as tag_id_first_name', 'tags2.name as tag_id_second_name',
                'tags2.text_color as tag2_text_color', 'tags2.bg_color as tag2_bg_color',
                'tags1.text_color as tag1_text_color', 'tags1.bg_color as tag1_bg_color'
            )
            ->where('eventos.user_id', Auth::user()->id)
            ->where('tags1.user_id', Auth::user()->id)
            ->orderBy('eventos.date', 'DESC');
    }

    protected function getOneEvento($id)
    {
        try{
            $items = $this->getEventoSqlPart()
                ->where('eventos.id', $id)
                ->get()
            ;
        }catch (\Exception $e){
            $this->saveToLog(__METHOD__, $e);
            return [
                'success' => 0,
            ];
        }

        return [
            'success' => 1,
            'data' => $items[0],
        ];
    }

    public function index()
    {
        try{
            $items = $this->getEventoSqlPart()
                ->orderBy('eventos.date', 'ASC')
                ->paginate(10)
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

    public function store(Request $request)
    {
        $this->validate($request, [
            'date' => 'required|date',
            'tag_id_first'  => 'required|integer|exists:tags,id',
            'tag_id_second' => 'nullable',
            'value' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $request->tag_id_second = intval($request->tag_id_second);

        if ($request->tag_id_second) {
            $this->validate($request, [
                'tag_id_second' => 'exists:tags,id',
            ]);
        }

        $eventoId = 0;
        try{
            DB::transaction(function () use($request, &$eventoId){

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

                $eventoId = $evento->id;
            });

            $dataRowWithNeedSelected = $this->getOneEvento($eventoId);
            $dataRs = $dataRowWithNeedSelected['success'] ? $dataRowWithNeedSelected['data'] : null;
        }catch (QueryException $e){
            $this->saveToLog(__METHOD__, $e);
            return response()->json([
                'success' => 0,
                'error' => 'some error!',
            ]);
        }

        return response()->json([
            'success' => 1,
            'savedId' => 0,
            'data' => $dataRs,
        ]);
    }

    public function update(Request $request, Evento $evento)
    {
        $this->validate($request, [
            'date' => 'nullable|date',
            'tag_id_first'  => 'required|integer|exists:tags,id',
            'tag_id_second' => 'nullable',
            'value' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $request->tag_id_second = intval($request->tag_id_second);
        if ($request->tag_id_second) {
            $this->validate($request, [
                'tag_id_second' => 'exists:tags,id',
            ]);
        }

        try{
            $tagValue = $evento->tagValue;
            $tagValue->tag_id_first  = $request->tag_id_first;
            $tagValue->tag_id_second = $request->tag_id_second;
            $tagValue->value         = $request->value;
            $tagValue->description   = $request->description;
            $tagValue->save();

            $dataRowWithNeedSelected = $this->getOneEvento($evento->id);
            $dataRs = $dataRowWithNeedSelected['success'] ? $dataRowWithNeedSelected['data'] : null;
        }catch (QueryException $e){
            $this->saveToLog(__METHOD__, $e);
            return response()->json([
                'success' => 0,
                'error' => 'some error!',
            ]);
        }

        return response()->json([
            'success' => 1,
            'data' => $dataRs,
        ]);
    }

    public function destroy(Evento $evento)
    {
        try{
            $evento->delete();
        }catch (\Exception $e){
            $this->saveToLog(__METHOD__, $e);
            return response()->json([
                'success' => 0,
                'error' => 'some error!'
            ]);
        }

        return response()->json([
            'success' => 1,
        ]);
    }

    protected function saveToLog($method, $e)
    {
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
