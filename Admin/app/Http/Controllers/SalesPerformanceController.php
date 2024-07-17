<?php

 // app/Http/Controllers/SalesPerformanceController.php
namespace App\Http\Controllers;

use App\Models\SalesDetails; 
use Illuminate\Http\Request;
use DB;
use App\Models\SalesListDraft;

class SalesPerformanceController extends Controller
{
    public function index()
    {
        $salesData = DB::table('sales_list_draft')
            ->join('sales_details', 'sales_list_draft.sales_id', '=', 'sales_details.id')
            ->select(
                'sales_details.name as sales_name',
                DB::raw('count(*) as total'),
                DB::raw('sum(case when sales_list_draft.status = "signed" then 1 else 0 end) as signed'),
                DB::raw('sum(case when sales_list_draft.status = "pending" then 1 else 0 end) as pending'),
                DB::raw('sum(case when sales_list_draft.status = "" then 1 else 0 end) as `empty`')
            )
            ->groupBy('sales_details.name')
            ->get();

        return view('Sales-Performence', compact('salesData'));
    }
}
