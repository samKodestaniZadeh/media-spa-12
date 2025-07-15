<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class OrderableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Orderable  $orderable
     * @return \Illuminate\Http\Response
     */
    public function show(Orderable $orderable)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Orderable  $orderable
     * @return \Illuminate\Http\Response
     */
    public function edit(Orderable $orderable)
    {
        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Orderable  $orderable
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Orderable $orderable)
    {
        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Orderable  $orderable
     * @return \Illuminate\Http\Response
     */
    public function destroy(Orderable $orderable)
    {
        return abort(404);
    }
}
