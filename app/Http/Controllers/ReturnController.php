<?php

namespace App\Http\Controllers;

use App\Models\ProductReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    public function index()
    {
        // Fetch all return details, ordered by most recent
        $returns = ProductReturn::latest()->paginate(10);

        $totalReturnValue = ProductReturn::sum(DB::raw('returned_quantity * price_per_unit'));

        // Return the view with data
        return view('returns.index', compact('returns', 'totalReturnValue'));
    }
}
