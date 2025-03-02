<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storeuser_relationRequest;
use App\Http\Requests\Updateuser_relationRequest;
use App\Models\user_relation;

class UserRelationController extends Controller
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
    public function store(Storeuser_relationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(user_relation $user_relation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(user_relation $user_relation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Updateuser_relationRequest $request, user_relation $user_relation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(user_relation $user_relation)
    {
        //
    }
}
