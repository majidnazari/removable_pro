<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersonDetailRequest;
use App\Http\Requests\UpdatePersonDetailRequest;
use App\Models\PersonDetail;

class PersonDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePersonDetailRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PersonDetail $personDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PersonDetail $personDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePersonDetailRequest $request, PersonDetail $personDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PersonDetail $personDetail)
    {
        //
    }
}
