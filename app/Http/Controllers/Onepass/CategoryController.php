<?php

namespace App\Http\Controllers\Onepass;

use App\Http\Controllers\Controller;
use App\Http\Requests\Onepass\CategoryStoreRequest;
use App\Http\Requests\Onepass\CategoryUpdateRequest;
use App\Models\Onepass\Category;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        try{
            $items = Category::
                where('user_id', Auth::user()->id)
                    ->orderBy('id', 'DESC')
                    ->get();

            $itemsWithFullImgPaths = $items;
            foreach($itemsWithFullImgPaths as $item) {
                if ($item->image){
                    $item->image_full = Storage::disk('public')->url($item->image);
                }
            }

        }catch (\Exception $e){
            $this->saveToLog($e);
            return response()->json([
                'success' => 0,
                'error' => 'some error!',
            ]);
        }

        return json_encode([
            'data' => $itemsWithFullImgPaths,
            'success' => 1,
        ]);
    }

    public function store(CategoryStoreRequest $request)
    {
        $attributes = $request->validated();

        $attributes += ['user_id' => Auth::user()->id ];

        if($request->file('image')) {
            $file_name = time().'_'.$request->image->getClientOriginalName();
            $file_path = $request->file('image')->storeAs('uploads', $file_name, 'public');
            $attributes['image'] = $file_path;
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
            'image'      => $item->image,
            'image_full' => $item->image ? Storage::disk('public')->url($item->image) : '',
            'savedId' => $item->id,
        ]);
    }

    public function show(Category $category)
    {
        abort_unless(Auth::user()->id === $category->user_id, 403);

        return $category;
    }

    public function image_validator(Request $request, Category $category)
    {
        $v = Validator::make($request->all(), [
            'name' => 'required|string|max:255|min:2',
            'image' => 'required|image|max:2048',
            //'image2' => 'nullable|string|max:255',
            'note' => 'nullable|string',
        ]);
        if ($v->fails()){
            //return $v->messages();
            return $v->errors();
        }

        return response()->json([
            'request_all' => $request->all(),
            'v - fails' => $v->passes(),
            'v - messages' => $v->messages(),
        ]);
    }

    public function update(Request $request, Category $category)
    {
        //$attributes = $request->validated();

        // нужно 2 валидатора, этот для общих полей
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|min:2',
            'note' => 'nullable|string',
        ]);

        if ($validator->fails()){
            //return $v->messages();
            //return $validator->errors();
            return $validator->validate();
        }
        $attributes = $validator->validated();

        $item = $category;

        // этот для картинки, если картинка есть, нужно удалить старый и сохранить новый и сохранить его в БД.
        $req_image = Validator::make($request->all(), [
            'image' => 'required|image|max:2048',
        ]);
        if( ($req_image->passes()) && $request->file('image')) {
            // delete if exists old file
            if ($item->image){
                $image = Storage::disk('public')->exists($item->image);
                if ($image){
                    Storage::disk('public')->delete($item->image);
                }
            }

            // create new file
            $file_name = time().'_'.$request->image->getClientOriginalName();
            $file_path = $request->file('image')->storeAs('uploads', $file_name, 'public');
            //$item->image = $file_path;
            $attributes['image'] = $file_path;
        }

        try{
            foreach($attributes as $key => $val){
                $item[$key] = $val;
            }

            $item->save();
        }catch (\Exception $e){
            $this->saveToLog(__METHOD__, $e);
            return response()->json([
                'success' => 0,
                'error' => 'some error!',
                'e' => $e,
            ]);
        }

        return response()->json([
            //'item' => $item,
            //'attributes' => $attributes,
            'success'    => 1,
            'image'      => $item->image,
            'image_full' => $item->image ? Storage::disk('public')->url($item->image) : '',
            'updatedId'  => $item->id,
        ]);
    }

    public function destroy(Category $category)
    {
        $item = $category;
        try{
            if ( $item->image ){
                $image = Storage::disk('public')->exists($item->image);
                if ($image){
                    Storage::disk('public')->delete($item->image);
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
