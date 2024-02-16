<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventoController extends Controller{
    public function index(){
        return view('evento.index');
    }
    public function create(){
        return view('evento.create');
    }
    public function store(Request $request){
        return redirect()->route('evento.index');
    }
    public function show($id){
        return view('evento.show', compact('id'));
    }
    public function edit($id){
        return view('evento.edit', compact('id'));
    }
    public function update(Request $request, $id){
        return redirect()->route('evento.index');
    }
    public function destroy($id){
        return redirect()->route('evento.index');
    }
}
