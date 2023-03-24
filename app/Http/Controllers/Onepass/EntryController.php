<?php

namespace App\Http\Controllers\Onepass;

use App\Http\Controllers\Controller;
use App\Http\Requests\Onepass\EntryStoreRequest;
use Illuminate\Http\Request;
use App\Models\Onepass\Entry;
use Illuminate\Database\QueryException;

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
            'image'      => $item->image,
            'image_full' => $item->image ? Storage::disk('public')->url($item->image) : '',
            'savedId' => $item->id,
        ]);
    }
}
