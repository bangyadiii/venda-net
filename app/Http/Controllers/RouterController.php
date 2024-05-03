<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RouterController extends Controller
{
    public function index()
    {
        return view('content.router.index');
    }

    public function create()
    {
        return view('content.router.create');
    }

    public function store()
    {
        //
    }

    public function edit($id)
    {
        return view('content.router.edit');
    }
}
