<?php

namespace App\Http\Controllers;

use App\Models\Sheet;

class SheetController extends Controller
{
    public function getSheets()
    {
        $sheets = Sheet::all();
        return view('getSheets', ['sheets' => $sheets]);
    }
}
