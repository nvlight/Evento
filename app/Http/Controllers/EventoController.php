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
use Illuminate\Support\Facades\Session;

class EventoController extends Controller
{
    protected int $perPage = 10;

    protected function getEventoSqlPart(){
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
            ->orderBy('eventos.date', 'DESC')
            ->orderBy('eventos.id', 'DESC');
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

    public function index(Request $request)
    {
        try{
            $items = $this->getEventoSqlPart()
                ->paginate($this->perPage)
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
            'current_page' => $request->input('page'),
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

        if ($request->input('tag_id_second', 0) ) {
            $this->validate($request, [
                'tag_id_second' => 'exists:tags,id',
            ]);
        }

        $currentPage = $request->integer('current_page', 1);

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

        $eventosByPage = $this->getEventoSqlPart()
            ->paginate(
                $perPage = $this->perPage,
                $columns = ['*'],
                $pageName = 'page',
                $page = $currentPage
            );
        $eventosCollect = collect($eventosByPage->items());

        $filtered = $eventosCollect->filter(function ($value, $key) use ($dataRs) {
            return $value->id === $dataRs->id;
        });

        return response()->json([
            'success' => 1,
            'data'  => $dataRs,
            //'storedId' => $dataRs->id,
            //'items' => $eventosByPage->items(),
            //'currentPage' => $currentPage,
            //'filtered' => $filtered,
            'filteredCount' => count($filtered),
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

        if ($request->input('tag_id_second', 0) ) {
            $this->validate($request, [
                'tag_id_second' => 'exists:tags,id',
            ]);
        }

        try{
            $tagValue = $evento->tagValue;
            $tagValue->tag_id_first  = $request->input('tag_id_first');
            $tagValue->tag_id_second = $request->input('tag_id_second');
            $tagValue->value         = $request->input('value');
            $tagValue->description   = $request->input('description');
            $tagValue->save();

            // todo: add transaction!
            $evento->date = $request->date('date') ?? date('Y');
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

    /** delete last id fragment from Url */
    private function deleteLastInUrl(string $url):string
    {
        $exp = explode('/', $url);
        if (count($exp)){
            unset($exp[count($exp)-1]);
        }

        return implode('/', $exp);
    }

    /**  */
    protected function getLastItemAfterEventoDestroyed()
    {
        $eventos = $this->getEventoSqlPart()
            ->paginate($this->perPage);

        $items = $eventos->items();
        $lastItem = $items[count($items) - 1];
        $lastPage = $eventos->lastPage();
        $newUrl = $this->deleteLastInUrl($eventos->path());

        return [
            'last_page' => $lastPage,
            'path'      => $newUrl,
            'last_item' => $lastItem,
            'paginate' => $eventos,
            'isNeedAddLastItem' => ($eventos->total() >= $this->perPage),
        ];
    }

    /**  */
    protected function getLastItemAfterFilteredEventoDestroyed($request)
    {
        $date_start  = $request->input('date_start', date('Y'));
        $date_end    = $request->input('date_end', date('Y'));
        $sum_start   = $request->integer('sum_start', 0);
        $sum_end     = $request->integer('sum_end', 1000000);
        $tags        = $request->input('tag_arr', []);
        $zeroFill    = $request->boolean('zeroFill');
        $pickAllTags = $request->boolean('pickAllTags');

        /** @var Evento $sql */
        $rs = $this->filterSql();

        $rs = $rs->whereBetween('eventos.date',  [$date_start, $date_end]);

        //
        if (!$pickAllTags){
            $rs = $rs
                ->where(function ($query) use($tags){
                    $query->whereIn('tags1.id', $tags)
                        ->orWhereIn('tags2.id', $tags);
                });
        }

        //
        if (!$zeroFill){
            $rs = $rs->whereBetween('tag_values.value', [$sum_start, $sum_end]);
        }

        $filteredEventos = $rs->orderByDesc('date')
            ->orderByDesc('eventos.id')
            ->paginate($this->perPage);

        $items = $filteredEventos->items();
        $lastItem = $items[count($items) - 1];
        $last_page = $filteredEventos->lastPage();

        $newUrl = $this->deleteLastInUrl($filteredEventos->path());

        return [
            'last_page' => $last_page,
            'path'      => $newUrl,
            'last_item' => $lastItem,
            'paginate' => $filteredEventos,
            'isNeedAddLastItem' => ($filteredEventos->total() >= $this->perPage),
        ];
    }

    /**  */
    public function destroy(Request $request, Evento $evento)
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

        if (!$request->filter_active) {
            $lastItem = $this->getLastItemAfterEventoDestroyed();
        }else{
            $lastItem = $this->getLastItemAfterFilteredEventoDestroyed($request);
        }
        extract($lastItem);

        return response()->json([
            'success' => 1,
            'last_page' => $last_page,
            'path' => $path,
            'last_item' => $last_item,
            'paginate' => $paginate,
            'isNeedAddLastItem' => $isNeedAddLastItem,
        ]);
    }

    /**  */
    public function filter(Request $request)
    {
        // todo: validate

        $date_start  = $request->input('date_start', date('Y'));
        $date_end    = $request->input('date_end', date('Y'));
        $sum_start   = $request->integer('sum_start', 0);
        $sum_end     = $request->integer('sum_end', 1000000);
        $tags        = $request->input('tag_arr', []);
        $zeroFill    = $request->boolean('zeroFill');
        $pickAllTags = $request->boolean('pickAllTags');

        /** @var Evento $sql */
        $rs = $this->filterSql();

        $rs = $rs->whereBetween('eventos.date',  [$date_start, $date_end]);

        //
        if (!$pickAllTags){
            $rs = $rs
                ->where(function ($query) use($tags){
                    $query->whereIn('tags1.id', $tags)
                        ->orWhereIn('tags2.id', $tags);
                });
        }

        //
        if (!$zeroFill){
            $rs = $rs->whereBetween('tag_values.value', [$sum_start, $sum_end]);
        }

        $rs = $rs->orderByDesc('date')
                 ->orderByDesc('eventos.id')
                 ->paginate($this->perPage);

        return response([
            'data' => $rs,
            'success' => true,
        ]);
    }

    /**  */
    protected function getValuesByMonths($eventos)
    {
        //    "diagram": [
        //    {
        //        "name": "расход",
        //        "id": 123,
        //        "sum": "513",
        //        "month": "December",
        //        "year": 2022
        //    },
        //    {
        //        "name": "расход",
        //        "id": 123,
        //        "sum": "1800",
        //        "month": "December",
        //        "year": 2022
        //    },

        $res = [];
        foreach($eventos as $evento){
            if (!isset($res[$evento->month][$evento->id])){
                $res[$evento->month][$evento->id] = $evento;
                $res[$evento->month][$evento->id]->sum = intval($res[$evento->month][$evento->id]->sum);
            }else{
                $res[$evento->month][$evento->id]->sum += intval($evento->sum);
            }
        }

        return $res;
    }

    /**  */
    protected function getSortedByMonthValues($eventos)
    {
        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December',];

        $res = [];

        foreach($months as $month){
            foreach($eventos as $ev_month => $evento){
                if ($month === $ev_month){
                    $res[$month] = $evento;
                }
            }
        }

        return $res;
    }

    /**  */
    public function getDiagramYears()
    {
        //    select distinct year(eventos.date) as year
        //    from eventos
        //    group by eventos.date
        try{
            $yearsRs = Evento
                ::select( DB::raw('distinct year(eventos.date) as year'))
                ->from('eventos')
                ->groupBy('eventos.date')
                ->orderBy('year', 'asc')
                ->get()
            ;
        }catch (QueryException $e){
            $this->saveToLog(__METHOD__, $e);

            return response()->json([
                'success' => 0,
                'error' => 'some error!'
            ]);
        }

        return response([
            'action' => 'diagramYears',
            'success' => true,
            'years' => $yearsRs,
        ]);
    }

    /**
      *
      *  Diagram

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
    public function diagram(Request $request)
    {
        $year = $request->date('year', 'Y', 'Europe/Moscow') ?? date('Y');

//        return response([
//            'action' => 'diagram',
//            'success' => true,
//            '$year' => $year,
//        ]);

        try{
            $diagramRs = Evento
                ::select('tg1.name', 'tg2.name', 'tg2.id',
                    DB::raw('sum(tag_values.value) sum'),
                    DB::raw('monthname(eventos.date) as month'),
                    DB::raw('year(eventos.date) as year'),
                )
                ->from('eventos')
                ->join('tag_values', 'eventos.id', '=', 'tag_values.evento_id')
                ->join('tags as tg1','tag_values.tag_id_first','=','tg1.id')
                ->leftJoin('tags as tg2', 'tag_values.tag_id_second', '=', 'tg2.id')
                ->where('eventos.user_id', '=', Auth::user()->id)
                ->groupBy('month', 'tg1.name', 'tg2.name', 'tg2.id', 'year')
                ->having('sum', '>', 0)
                ->having('year', '=', $year)
                ->orderBy('month')
                ->orderBy('tg2.id', 'desc')
                //->toSql()
                ->get()
            ;
            $valuesByMonths = $this->getValuesByMonths($diagramRs);
            $sortedByMonthValues = $this->getSortedByMonthValues($valuesByMonths);
        }catch (QueryException $e){
            $this->saveToLog(__METHOD__, $e);

            return response()->json([
                'success' => 0,
                'error' => 'some error!'
            ]);
        }

        return response([
            'action' => 'diagram',
            'success' => true,
            'diagram' => $sortedByMonthValues,
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
