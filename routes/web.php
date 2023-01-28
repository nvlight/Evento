<?php

use App\Models\Evento;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
//Route::get('/chich', function (){
//
//    $user_id = 1;
//    $items = Evento::
//        join('tag_values', 'tag_values.evento_id', '=', 'eventos.id')
//            ->join('tags as tags1', 'tags1.id', '=', 'tag_values.tag_id_first')
//            ->leftJoin('tags as tags2', 'tags2.id', 'tag_values.tag_id_second')
//            ->select('eventos.id', 'eventos.date', 'tag_values.value', 'tag_values.description',
//                'tag_values.tag_id_first', 'tag_values.tag_id_second',
//                'tags1.name as tag_id_first_name', 'tags2.name as tag_id_second_name',
//                'tags2.text_color as tag2_text_color', 'tags2.bg_color as tag2_bg_color',
//                'tags1.text_color as tag1_text_color', 'tags1.bg_color as tag1_bg_color'
//            )
//            ->where('eventos.user_id', $user_id)
//            ->where('tags1.user_id', $user_id)
//            ->orderBy('eventos.date', 'ASC')
//            ->paginate(10);
//    dump($items->items());
//    //dump($items->data());
//});
