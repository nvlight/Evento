<?php

namespace App\Http\Controllers\Onepass;

use App\Http\Controllers\Controller;
use App\Http\Requests\Onepass\EntryStoreRequest;
use App\Http\Requests\Onepass\EntryUpdateRequest;
use Illuminate\Http\Request;
use App\Models\Onepass\Entry;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class EntryController extends Controller
{
    public function index()
    {
        try{
            $items = Entry::query()
                ->leftJoin('onepass_categories', 'onepass_categories.id', '=', 'onepass_entries.category_id')
                ->where('onepass_entries.user_id', Auth::user()->id)
                ->select('onepass_entries.*', 'onepass_categories.name as category_name')
                ->orderBy('onepass_entries.id', 'DESC')
                ->get();


        }catch (\Exception $e){
            $this->saveToLog('index', $e);
            return response()->json([
                'success' => 0,
                'error' => 'some error!',
            ]);
        }

        return response()->json([
            'data' => $items,
            'success' => 1,
        ]);
    }

    public function show(Entry $entry)
    {
        $entry->category_name = $entry->category->name;
        return response()->json([
            'data' => $entry,
            'success' => 1,
        ]);
    }

    public function store(EntryStoreRequest $request)
    {
        $attributes = $request->validated();

        $attributes += ['user_id' => Auth::user()->id ];

        try{
            $item = Entry::create($attributes);
        }catch (QueryException $e){
            $this->saveToLog(__METHOD__, $e);
            return response()->json([
                'success' => 0,
                'error' => 'some error!',
                'e' => $e,
            ]);
        }

        $item->category_name = $item->category->name;
        return response()->json([
            'success' => 1,
            'storedId' => $item->id,
            'item' => $item,
        ]);
    }

    public function update(EntryUpdateRequest $request, Entry $entry)
    {
        $attributes = $request->validated();

        $attributes += ['user_id' => Auth::user()->id ];

        try{
            // это опять не работает почему-то!
            // $entry->save($attributes);

            // обходной вариант
            foreach($attributes as $key => $value){
                $entry->$key = $value;
            }
            $entry->save($attributes);

        }catch (QueryException $e){
            $this->saveToLog(__METHOD__, $e);
            return response()->json([
                'success' => 0,
                'error' => 'some error!',
                'e' => $e,
            ]);
        }

        $entry->category_name = $entry->category->name;
        return response()->json([
            'success' => 1,
            'updatedId' => $entry->id,
            'item' => $entry,
            'attributes' => $attributes,
        ]);
    }

    public function destroy(Entry $entry)
    {
        try{
            $entry->delete();
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

    public function copy(Entry $entry)
    {
        // todo: do transaction
        $newEntry = $entry->replicate()->fill([
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        $newEntry->save();

        $newEntry->category_name = $newEntry->category->name;
        return response()->json([
            'success' => 1,
            'copiedId' => $newEntry->id,
            'item' => $newEntry,
        ]);
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

    public function filter(Request $request)
    {
        $queryPart = Entry::query()
            ->leftJoin('onepass_categories', 'onepass_categories.id', '=', 'onepass_entries.category_id')
            ->where('onepass_entries.user_id', Auth::user()->id);

        // list of keys email=&login=&phone=&name=&note
        $needKeys = ['url', 'email', 'login', 'phone', 'name', 'note'];

        foreach($needKeys as $key) {
            if ($request->has($key) && !empty($request->input($key))) {

                $queryPart = $queryPart->where('onepass_entries.' . $key, 'LIKE',
                    implode(['%', $request->input($key), '%']));
            }
        }

        $queryPart = $queryPart
            ->select('onepass_entries.*', 'onepass_categories.name as category_name')
            ->orderBy('onepass_entries.id', 'DESC');

        $sql = $queryPart->toSql();
        $params = $queryPart->getBindings();

        $rs = $queryPart->get();

        return response([
            'data' => $rs,
            'success' => true,
            'sql' => $sql,
            'params' => $params,
        ]);
    }
}
