<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class TagController extends Controller
{
    public function index()
    {
        try{
            $tags = Tag::
                  where('user_id', Auth::user()->id)
                ->orderBy('id', 'DESC')
                ->get();
        }catch (\Exception $e){
            $this->saveToLog($e);
            return response()->json([
                'success' => 0,
                'error' => 'some error!',
            ]);
        }

        return json_encode([
            'data' => $tags,
            'success' => 1,
        ]);
    }

    public function store(Request $request)
    {
        $attributes = $this->validate($request, [
            'name' => 'required|string|max:111',
            'img' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'text_color' => 'nullable|string|size:7',
            'bg_color' => 'nullable|string|size:7',
        ]);

        $attributes += ['user_id' => Auth::user()->id ];

        if($request->file('img')) {
            $file_name = time().'_'.$request->img->getClientOriginalName();
            $file_path = $request->file('img')->storeAs('uploads', $file_name, 'public');

            //$attributes += ['img' => $file_path ];
            $attributes['img'] = $file_path;
        }

        try{
            $item = Tag::create($attributes);
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
            'img'     => $item->img,
            'savedId' => $item->id,
        ]);
    }

    public function update(Request $request, Tag $tag)
    {
        $attributes = $this->validate($request, [
            'name' => 'required|string|max:111',
            //'img' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'text_color' => 'nullable|string|size:7',
            'bg_color' => 'nullable|string|size:7',
        ]);

        foreach($attributes as $key => $value){
            $tag->$key = $value;
        }

        if($request->file('img')) {

            // delete if exists old file
            $img = Storage::disk('public')->exists($tag->img);
            if ($img){
                Storage::disk('public')->delete($tag->img);
            }

            // create new file
            $file_name = time().'_'.$request->img->getClientOriginalName();
            $file_path = $request->file('img')->storeAs('uploads', $file_name, 'public');
            $tag->img = $file_path;
        }

        try{
            $tag->save();
        }catch (\Exception $e){
            $this->saveToLog(__METHOD__, $e);
            return response()->json([
                'success' => 0,
                'error' => 'some error!',
                'e' => $e,
            ]);
        }

        return response()->json([
            'success'   => 1,
            'img'       => $tag->img,
            'updatedId' => $tag->id,
        ]);
    }

    public function destroy(Tag $tag)
    {
        try{
            if ( $tag->img ){
                $img = Storage::disk('public')->exists($tag->img);
                if ($img){
                    Storage::disk('public')->delete($tag->img);
                }
            }
            $tag->delete();
//            $img = [];
//            $img[] = Storage::disk('public')->exists($tag->img);
//
//            $img[] = Storage::disk('public')->allFiles();
//
//            $prefix = '/storage/';
//
//            $p = public_path($prefix . $tag->img);
//            $i = intval(is_file($p));
//            $ch = File::exists($p);
//            $img[] = implode(' | ', [$i, $ch, $p] );
//
//            $p = public_path($prefix . $tag->img);
//            $i = intval(is_file($p));
//            $ch = file_exists($p);
//            $img[] = implode(' | ', [$i, $ch, $p] );
//            $img[] = 'visibility: ' . Storage::disk('public')->getVisibility($tag->img);
//
//            return response()->json([
//                'rs' => $img,
//                'success' => 0,
//                'error' => 'some error!'
//            ]);
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
