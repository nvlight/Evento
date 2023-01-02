<?php

namespace App\Http\Controllers;

use App\Models\Tag;
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

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:111',
            'img' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
        ]);

        $item = new Tag();
        $item->user_id = Auth::user()->id;
        $item->name = $request['name'];
        $item->description = $request['description'];

        if($request->file('img')) {
            $file_name = time().'_'.$request->img->getClientOriginalName();
            $file_path = $request->file('img')->storeAs('uploads', $file_name, 'public');
            $item->img = $file_path;
        }

        try{
            $item->save();
        }catch (\Exception $e){
            $this->saveToLog($e);
            return response()->json([
                'success' => 0,
                'error' => 'some error!',
                'e' => $e,
                'img' => $item->img,
            ]);
        }

        return response()->json([
            'success' => 1,
            'img'     => $item->img,
            'savedId' => $item->id,
        ]);
    }

    public function show(Tag $tag)
    {
        //
    }

    public function edit(Tag $tag)
    {
    }

    public function update(Request $request, Tag $tag)
    {
//        return response()->json([
//            'success'   => 0,
//            'request'   => $request->all(),
//            //'request2'   => $request->file('img'),
//            'tag'       => $tag,
//        ]);

        $this->validate($request, [
            'name' => 'required|string|max:111',
//            'img' => 'nullable|string|image|max:2048',
            'description' => 'nullable|string',
        ]);

        $tag->name = $request['name'];
        $tag->description = $request['description'];

        if($request->file('img')) {

            // delete if exists old file
            if ($img = Storage::disk('public')->exists($tag->img)){
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
                'img' => $tag->img,
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
//            return response()->json([
//                'img' => $tag->img,
//                'success' => 0,
//            ]);
            if ( $tag->img ){
                $img = Storage::disk('public')->exists($tag->img);
                Storage::disk('public')->delete($tag->img);

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
