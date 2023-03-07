<?php

namespace App\Http\Controllers\Onepass;

use App\Http\Controllers\Controller;
use App\Models\Onepass\Category;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        try{
            $items = Category::
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
            'data' => $items,
            'success' => 1,
        ]);
    }

    public function store(Request $request)
    {
        $attributes = $this->validate($request, [
            'name' => 'required|string|max:255|min:2',
            'img' => 'nullable|image|max:2048',
            'note' => 'nullable|string',
        ]);

        $attributes += ['user_id' => Auth::user()->id ];

        if($request->file('img')) {
            $file_name = time().'_'.$request->img->getClientOriginalName();
            $file_path = $request->file('img')->storeAs('uploads', $file_name, 'public');
            $attributes['img'] = $file_path;
        }

        try{
            $item = Category::create($attributes);
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

    public function show(Category $category)
    {
        abort_unless(Auth::user()->id === $category->user_id, 403);

        return $category;
    }

    public function update(Request $request, Category $category)
    {
        $attributes = $this->validate($request, [
            'name' => 'required|string|max:255|min:2',
            'img' => 'nullable|image|max:2048',
            'note' => 'nullable|string',
        ]);

        //foreach($attributes as $key => $value){
        //    $tag->$key = $value;
        //}

        $item = $category;
        if($request->file('img')) {

            // delete if exists old file
            $img = Storage::disk('public')->exists($item->img);
            if ($img){
                Storage::disk('public')->delete($item->img);
            }

            // create new file
            $file_name = time().'_'.$request->img->getClientOriginalName();
            $file_path = $request->file('img')->storeAs('uploads', $file_name, 'public');
            $item->img = $file_path;
        }

        try{
            $item->save($attributes);
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
            'img'       => $item->img,
            'updatedId' => $item->id,
        ]);
    }

    public function destroy(Category $category)
    {
        $item = $category;
        try{
            if ( $item->img ){
                $img = Storage::disk('public')->exists($item->img);
                if ($img){
                    Storage::disk('public')->delete($item->img);
                }
            }
            $item->delete();

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
