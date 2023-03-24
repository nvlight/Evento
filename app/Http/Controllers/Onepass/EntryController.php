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

        return response()->json([
            'success' => 1,
            'storedId' => $item->id,
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
