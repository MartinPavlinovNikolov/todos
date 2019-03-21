<?php

use Marto\Todos\Todos;
use Illuminate\Http\Request;

Route::post('todos', function(Request $request){
    $data = json_encode($request->all());
    $todos = new Todos();
    return $todos->storeData($data);
});

