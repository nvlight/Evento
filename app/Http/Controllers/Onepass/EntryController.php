<?php

namespace App\Http\Controllers\Onepass;

use App\Http\Controllers\Controller;
use App\Http\Requests\Onepass\EntryStoreRequest;
use Illuminate\Http\Request;
use App\Models\Onepass\Entry;
use Illuminate\Database\QueryException;
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
