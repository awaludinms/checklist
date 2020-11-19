<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Checklist;

class ChecklistController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    //

    public function store(Request $request)
    {
        $input = $request->all();

        
    }
}
