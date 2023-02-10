<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventoFilterRequest;
use App\Http\Requests\EventoStoreRequest;
use App\Models\Evento;
use App\Models\TagValue;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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

    public function store(EventoStoreRequest $request)
    {
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
                $evento->save();

                $tagValue = new TagValue();
                $tagValue->evento_id = $evento->id;
                $tagValue->tag_id_first  = $request->tag_id_first;
                $tagValue->tag_id_second = $request->tag_id_second;
                $tagValue->value         = $request->value;
                $tagValue->description   = $request->description;
                $tagValue->save();

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

        $filtered = $this->isExistsEventoInPaginatePage($currentPage, $dataRs->id);

        return response()->json([
            'success' => 1,
            'data'  => $dataRs,
            //'storedId' => $dataRs->id,
            //'items' => $eventosByPage->items(),
            //'currentPage' => $currentPage,
            //'filtered' => $filtered,
            'filteredCount' => $filtered,
        ]);
    }

    /**
     * Присутствует ли только что добавленный элемент в текущей странице
     *
     * @param int $needPage
     * @param int $eventoId
     * @return bool
     */
    protected function isExistsEventoInPaginatePage(int $needPage, int $eventoId)
    {
        $eventosByPage = $this->getEventoSqlPart()
            ->paginate(
                $perPage = $this->perPage,
                $columns = ['*'],
                $pageName = 'page',
                $page = $needPage
            );
        $eventosCollect = collect($eventosByPage->items());

        $filtered = $eventosCollect->filter(function ($value, $key) use ($eventoId) {
            return $value->id === $eventoId;
        });

        return !!count($filtered);
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
    public function copy(Request $request, Evento $evento)
    {
        // todo: do transaction
        $newEvento = $evento->replicate()->fill([
            'created_at' => Carbon::now(),
            'date' => Carbon::now()->format('Y-m-d'),
        ]);
        $newEvento->save();

        $tagValue = $evento->tagValue->replicate();
        $tagValue->evento_id = $newEvento->id;
        $tagValue->save();
        //

        $dataRowWithNeedSelected = $this->getOneEvento($newEvento->id);
        $dataRs = $dataRowWithNeedSelected['success'] ? $dataRowWithNeedSelected['data'] : null;

        $filtered = $this->isExistsEventoInPaginatePage($request->integer('current_page', 1), $dataRs->id);

        return response()->json([
            'success' => 1,
            'data'  => $dataRs,
            'filteredCount' => $filtered,
        ]);
    }

    /**  */
    public function filter(EventoFilterRequest $request)
    {
        // todo: validate

        //$tags        = $request->input('tag_arr', []);
        $zeroFill    = $request->boolean('zeroFill');
        $pickAllTags = $request->boolean('pickAllTags');

        /** @var Evento $sql */
        $rs = $this->filterSql();

        $rs = $rs->whereBetween('eventos.date', [
            $request->input('date_start'),
            $request->input('date_end')
        ]);

        //
        if (!$pickAllTags){
            $tags = $request->input('tag_arr', []);
            $rs = $rs
                ->where(function ($query) use($tags){
                    $query->whereIn('tags1.id', $tags)
                        ->orWhereIn('tags2.id', $tags);
                });
        }

        //
        if (!$zeroFill){
            $rs = $rs->whereBetween('tag_values.value', [
                $request->integer('sum_start'),
                $request->integer('sum_end')
            ]);
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
    protected function getSortedByMonthValues($eventos, array $allowedTagIds)
    {
        $months = [
            'January' => 'Январь',
            'February' => 'Февраль',
            'March' => 'Март',
            'April' => 'Апрель',
            'May' => 'Май',
            'June' => 'Июнь',
            'July' => 'Июль',
            'August' => 'Август',
            'September' => 'Сентябрь',
            'October' => 'Октябрь',
            'November' => 'Ноябрь',
            'December' => 'Декабрь',
        ];

        $res = [];
        foreach($months as $monthEn => $monthRu){
            foreach($eventos as $evento){
                if ($monthEn === $evento->month){
                    foreach($allowedTagIds as $tagId){
                        if ($tagId === $evento->tg1_id) {
                            if (!isset($res[$monthRu][$evento->tg1_name] )){
                                $res[$monthRu][$evento->tg1_name] = $evento;
                                //$res[$monthRu][$evento->tg1_name]->sum = $res[$monthRu][$evento->tg1_name]->sum;
                            }else{
                                $res[$monthRu][$evento->tg1_name]->sum += $evento->sum;
                            }
                        } elseif ($tagId === $evento->tg2_id){
                            if (!isset($res[$monthRu][$evento->tg2_name] )){
                                $res[$monthRu][$evento->tg2_name] = $evento;
                                //$res[$monthRu][$evento->tg2_name]->sum = $res[$monthRu][$evento->tg2_name]->sum;
                            }else{
                                $res[$monthRu][$evento->tg2_name]->sum += $evento->sum;
                            }
                        }
                    }
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
                ::select('tg1.name as tg1_name', 'tg2.name as tg2_name', 'tg1.id as tg1_id', 'tg2.id as tg2_id',
                    DB::raw('sum(tag_values.value) sum'),
                    DB::raw('monthname(eventos.date) as month'),
                    DB::raw('year(eventos.date) as year'),
                )
                ->from('eventos')
                ->join('tag_values', 'eventos.id', '=', 'tag_values.evento_id')
                ->join('tags as tg1','tag_values.tag_id_first','=','tg1.id')
                ->leftJoin('tags as tg2', 'tag_values.tag_id_second', '=', 'tg2.id')
                ->where('eventos.user_id', '=', Auth::user()->id)
                ->groupBy('month', 'tg1.name', 'tg2.name', 'tg1.id', 'tg2.id', 'year')
                ->having('sum', '>', 0)
                ->having('year', '=', $year)
                ->orderBy('month')
                ->orderBy('tg2.id', 'desc')
                //->toSql()
                ->get()
            ;

//            return response([
//                'action' => 'diagram',
//                'success' => true,
//                'sql' => $diagramRs,
//                '$year' => $year,
//            ]);

            // todo: сделать возможность для задания этих фильтрующих тегов!
            $allowedTagIds = [122, 123];
            $sortedByMonthValues = $this->getSortedByMonthValues($diagramRs, $allowedTagIds);
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
