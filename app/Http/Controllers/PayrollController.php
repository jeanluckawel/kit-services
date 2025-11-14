<?php

namespace App\Http\Controllers;

use App\Mail\PayrollPdfMail;
use App\Models\Employee;
use App\Models\Payroll;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

/**
 *
 */
class PayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        $employees = Employee::all();
        return view('payroll.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function oneEmployee($employeeId)
    {
        $employees = Employee::where('employee_id', $employeeId)->firstOrFail();
        $childCount = Employee::withCount('child')->get();

        return view('payroll.oneEmployee', compact('employees','childCount'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $employee_id)
    {
        $employee = Employee::where('employee_id', $employee_id)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'exchange_rate' => 'required|numeric|min:0',
            'worked_days' => 'required|integer|min:0|max:31',
            'sick_days' => 'nullable|integer|min:0|max:31',
            'overtime_hours_30' => 'nullable|numeric|min:0',
            'overtime_hours_60' => 'nullable|numeric|min:0',
            'overtime_hours_100' => 'nullable|numeric|min:0',
            'baremic_salary' => 'required|numeric|min:0',
            'sick_leave' => 'nullable|numeric|min:0',
            'accommodation_allowance' => 'nullable|numeric|min:0',
            'overtime_30' => 'nullable|numeric|min:0',
            'overtime_60' => 'nullable|numeric|min:0',
            'overtime_100' => 'nullable|numeric|min:0',
            'total_earnings' => 'required|numeric|min:0',
            'inss_5' => 'nullable|numeric|min:0',
            'ipr_tax_base' => 'nullable|numeric|min:0',
            'annual_ipr_tax_base' => 'nullable|numeric|min:0',
            'tranche_2' => 'nullable|numeric|min:0',
            'tranche_3' => 'nullable|numeric|min:0',
            'tranche_more_3' => 'nullable|numeric|min:0',
            'monthly_ipr' => 'nullable|numeric|min:0',
            'total_taxes_cdf' => 'nullable|numeric|min:0',
            'net' => 'required|numeric|min:0',
            'net_usd' => 'required|numeric|min:0',
            'period' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Payroll::updateOrCreate(
            ['employee_id' => $employee->employee_id, 'period' => $request->period],
            [
                'exchange_rate' => $request->exchange_rate,
                'worked_days' => $request->worked_days,
                'sick_days' => $request->sick_days ?? 0,
                'overtime_hours_30' => $request->overtime_hours_30 ?? 0,
                'overtime_hours_60' => $request->overtime_hours_60 ?? 0,
                'overtime_hours_100' => $request->overtime_hours_100 ?? 0,
                'baremic_salary' => $request->baremic_salary,
                'sick_leave' => $request->sick_leave ?? 0,
                'accommodation_allowance' => $request->accommodation_allowance ?? 0,
                'overtime_30' => $request->overtime_30 ?? 0,
                'overtime_60' => $request->overtime_60 ?? 0,
                'overtime_100' => $request->overtime_100 ?? 0,
                'total_earnings' => $request->total_earnings,
                'inss_5' => $request->inss_5 ?? 0,
                'ipr_tax_base' => $request->ipr_tax_base ?? 0,
                'annual_ipr_tax_base' => $request->annual_ipr_tax_base ?? 0,
                'tranche_2' => $request->tranche_2 ?? 0,
                'tranche_3' => $request->tranche_3 ?? 0,
                'tranche_more_3' => $request->tranche_more_3 ?? 0,
                'monthly_ipr' => $request->monthly_ipr ?? 0,
                'total_taxes_cdf' => $request->total_taxes_cdf ?? 0,
                'net' => $request->net,
                'net_usd' => $request->net_usd,
                'basic_usd_salary' => $employee->basic_usd_salary ?? 0,
            ]
        );

        return redirect()->route('payroll.show', $employee->employee_id)
            ->with('success', 'Paie enregistrée !');
    }

    /**
     * Display payroll form and preview.
     */
    public function show($employee_id)
    {
        $employee = Employee::where('employee_id', $employee_id)->firstOrFail();
        $payroll = Payroll::where('employee_id', $employee_id)
            ->orderByDesc('created_at')
            ->first();

        return view('payroll.show', compact('employee', 'payroll'));
    }




    public function sendPdf(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        if (!$request->hasFile('pdf')) {
            return response()->json(['message' => 'Aucun fichier PDF reçu.'], 400);
        }

        $pdf = $request->file('pdf');
        $pdfContent = file_get_contents($pdf->getRealPath());

        Mail::to($employee->email)->send(new PayrollPdfMail($employee, $pdfContent));

        return response()->json(['message' => 'Bulletin de paie envoyé avec succès.']);
    }






    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payroll $payroll)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payroll $payroll)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payroll $payroll)
    {
        //
    }
}
