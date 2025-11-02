<?php

namespace App\Http\Controllers;


use App\Exports\EmployeeExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Employee;


class ExportController extends Controller
{
    public function formExport(){
        return view('employees.export');
    }

    public function export(Request $request)
    {

        return Excel::download(new EmployeeExport(
            $request->contract_type,
            $request->status,
            $request->start_date,
            $request->end_date
        ), 'employees_export.xlsx');
    }
}
