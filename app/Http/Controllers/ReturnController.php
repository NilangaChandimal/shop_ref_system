<?php

namespace App\Http\Controllers;

use App\Models\ProductReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    public function index()
    {
        $returns = ProductReturn::latest()->paginate(100);

        $totalReturnValue = ProductReturn::sum(DB::raw('returned_quantity * price_per_unit'));

        return view('returns.index', compact('returns', 'totalReturnValue'));
    }
}
