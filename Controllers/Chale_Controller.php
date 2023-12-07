<?php

namespace App\Http\Controllers;

use App\Exports\ChaleErrorExport;
use App\Models\ChaleError;
use App\Models\General;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ChaleController extends Controller
{
    //
    public function index()
    {
        // return $dataTable->render('admin.chale_errors');
        return view('admin.chale_errors');
    }

    public function all_chale_errors(Request $request)
    {
        // return $dataTable->render('admin.chale_errors');
        Log::info($request->start_date);
        $my_category = $request->category;
        $my_start_date = $request->start_date;
        $my_end_date = $request->end_date;

        $my_date = General::addDayToDate($my_end_date, 1);
        // $data = ChaleError::query();
        if ($my_category !== null && $my_start_date == null && $my_end_date == null) {
            $data = ChaleError::where('response', 'LIKE', "%$my_category%")
                ->select('nominee', 'nominee_phone', 'nominator_phone', 'code', 'transaction_id', 'response', 'dob', 'created_at')
                ->get();
        }
        elseif ($my_category !== null && $my_start_date !== null && $my_end_date !== null) {
            $data = ChaleError::where('response', 'LIKE', "%$my_category%")
                ->select('nominee', 'nominee_phone', 'nominator_phone', 'code', 'transaction_id', 'response', 'dob', 'created_at')
                ->whereBetween('created_at', [$my_start_date, $my_date])
                ->get();
        }
        elseif ($my_category !== null && $my_start_date != null && $my_end_date == null) {
            $data = ChaleError::where('response', 'LIKE', "%$my_category%")
                ->select('nominee', 'nominee_phone', 'nominator_phone', 'code', 'transaction_id', 'response', 'dob', 'created_at')
                ->where('created_at', '>=', $my_start_date)
                ->get();
        }
        elseif ($my_category !== null && $my_start_date == null && $my_end_date !== null) {
            $data = ChaleError::where('response', 'LIKE', "%$my_category%")
                ->select('nominee', 'nominee_phone', 'nominator_phone', 'code', 'transaction_id', 'response', 'dob', 'created_at')
                ->where('created_at', '>=', $my_end_date)
                ->get();
        }
        elseif ($my_category == null && $my_start_date !== null && $my_end_date !== null) {
            $data = ChaleError::select('nominee', 'nominee_phone', 'nominator_phone', 'code', 'transaction_id', 'response', 'dob', 'created_at')
                ->whereBetween('created_at', [$my_start_date, $my_date])
                ->get();
        }
        elseif ($my_category == null && $my_start_date !== null && $my_end_date == null) {
            $data = ChaleError::select('nominee', 'nominee_phone', 'nominator_phone', 'code', 'transaction_id', 'response', 'dob', 'created_at')
                ->where('created_at', '<=', $my_start_date)
                ->get();
        }
        else {
            $data = ChaleError::select('nominee', 'nominee_phone', 'nominator_phone', 'code', 'transaction_id', 'response', 'dob', 'created_at')
                ->get();
        }
        // return $dataTable->json();
        return DataTables::of($data)->make(true);

    }

    public function chale_errors_export(Request $request)
    {
        Log::info($request->search_value);
        $category = $request->category;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $value = $request->search_value;
        return Excel::download(new ChaleErrorExport($category, $start_date, $end_date, $value), 'Chale_Errors_Report.xlsx');
    }
}
