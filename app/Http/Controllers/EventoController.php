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

    private function filterSql(){
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
            //->orderBy('eventos.date', 'DESC')
        ;
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
            //$items->withPath('eventos' . $_SERVER['QUERY_STRING']);
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

            // todo: add transaction!
            $evento->date = $request->date;
            $evento->save();

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

    /**  */
    public function filter(Request $request)
    {
        // validate:
//        return response([
//            'response_data' => $request->all(),
//            'success' => false,
//        ]);

        /** @var Evento $sql */
        $sql = $this->filterSql();

        $sqlDump = $sql
            ->  whereIn('tags1.id', $request->get('tag_arr'))
            ->orWhereIn('tags2.id', $request->get('tag_arr'))
            ->toSql()
        ;

        $rs = $sql
            ->  whereIn('tags1.id', $request->get('tag_arr'))
            ->orWhereIn('tags2.id', $request->get('tag_arr'))
            ->orderByDesc('date')
            //->get()
            ->paginate(10)
        ;
        //$rs->withPath('eventos' . $_SERVER['QUERY_STRING']);

        return response([
            //'$sqlDump' => $sqlDump,
            'QUERY_STRING' => $_SERVER['QUERY_STRING'],
            'response_data' => $request->all(),
            'data'  => $rs,
            'success' => true,
        ]);
    }

    /**  */
    public function diagram(Request $request)
    {
        /**
         Получает группировкой сумму по месяцам, тегам и нужному году
         потом остается лишь сложить руками сумму по тегам, чтобы вышло так

         January доход 150000
         January расход  37000
         ...


         select
            tg1.name,
            tg2.name,
            tg2.id,
            sum(tag_values.value) sum,
            monthname(eventos.date) month,
            year(eventos.date) year
        from eventos

        join tag_values on eventos.id = tag_values.evento_id
        join tags tg1 on tag_values.tag_id_first = tg1.id
        left join tags tg2 on tag_values.tag_id_second = tg2.id

        where eventos.user_id = 1
        group by month, tg1.name, tg2.name, tg2.id, year
        having sum > 0 and year = 2022
        order by month, tg2.id desc;
        */

        $year = $request->date ?? date('Y');

        $q_get_years_with_months = DB::table('eventos')
            ->select( DB::raw('year(eventos.date) as dtr'),
                DB::raw('month(eventos.date) as mnth'),
                DB::raw('monthname(eventos.date) as mnthnm'),
                'events.amount as sm',
                'events.type_id as tp',
                'types.name as nm',
                'types.color as cl')
            ->from('eventos')
            ->join('tags','types.id','=','events.type_id')
            ->where('events.user_id', '=', auth()->id() )
            ->whereIn('events.type_id',$type_ids)
            ->where(DB::raw('year(events.date)'), '=', $year)
            //->groupBy('sm','tp','mnthnm','mnth','dtr')
            ->orderBy('mnth')->orderBy('tp')
            //->toSql()
            ->get()
        ;

        // validate:
        return response([
            'action' => 'diagram',
            'success' => true,
        ]);

        /** @var Evento $sql */
        $sql = $this->filterSql();

        $sqlDump = $sql
            ->  whereIn('tags1.id', $request->get('tag_arr'))
            ->orWhereIn('tags2.id', $request->get('tag_arr'))
            ->toSql()
        ;

        $rs = $sql
            ->  whereIn('tags1.id', $request->get('tag_arr'))
            ->orWhereIn('tags2.id', $request->get('tag_arr'))
            ->orderByDesc('date')
            //->get()
            ->paginate(10)
        ;
        //$rs->withPath('eventos' . $_SERVER['QUERY_STRING']);

        return response([
            //'$sqlDump' => $sqlDump,
            'QUERY_STRING' => $_SERVER['QUERY_STRING'],
            'response_data' => $request->all(),
            'data'  => $rs,
            'success' => true,
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
