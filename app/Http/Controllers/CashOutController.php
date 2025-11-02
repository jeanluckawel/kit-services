<?php

namespace App\Http\Controllers;

use App\Models\CashOut;
use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Http\Request;

class CashOutController extends Controller
{

    public function index()
    {
        $cashOuts = CashOut::latest()->get();
        $initialCash = Invoice::sum('pt_mois') - CashOut::sum('amount');
        $expenses = $cashOuts->sum('amount');
        $remaining = $initialCash - $expenses;

        return view('cashouts.index', compact('cashOuts', 'initialCash', 'expenses', 'remaining'));
    }

    public function create()
    {
        $cashOuts = CashOut::latest()->get();
        $initialCash = Invoice::sum('pt_mois');

        $expenses = $cashOuts->sum('amount');
        $remaining = $initialCash - $expenses;
        $minCash = 50;

        return view('cashouts.create', compact('initialCash', 'minCash', 'cashOuts','remaining','expenses'));
    }

    public function store(Request $request)
    {
        $minCash = 50;
        $initialCash = Invoice::sum('pt_mois');
        $expenses = CashOut::sum('amount');
        $remaining = $initialCash - $expenses;

        $request->validate([
            'amount' => 'required|array',
            'amount.*' => 'required|numeric|min:0.01',
            'name' => 'required|array',
            'name.*' => 'required|string|max:255',
            'phone' => 'nullable|array',
            'phone.*' => 'nullable|string|max:255',
            'category' => 'required|array',
            'category.*' => 'required|string|in:urgent,normal',
            'description' => 'required|array',
            'description.*' => 'required|string|max:500',
        ]);

        $totalRequested = array_sum($request->amount);

        if ($totalRequested > ($remaining - $minCash)) {
            return redirect()->back()->with('error', "Impossible de retirer plus que le stock minimum de $minCash$");
        }

        foreach ($request->amount as $i => $amount) {
            CashOut::create([
                'amount' => $amount,
                'name' => $request->name[$i],
                'phone' => $request->phone[$i] ?? null,
                'category' => strtolower($request->category[$i]),
                'description' => $request->description[$i],
            ]);
        }

        return redirect()->route('cashouts.index')->with('success', "Sorties d'argent enregistrées avec succès !");
    }
    /**
     * Display the specified resource.
     */
    public function show(CashOut $cashout)
    {
        return view('cashouts.show', compact('cashout'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CashOut $cashOut)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CashOut $cashOut)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CashOut $cashOut)
    {
        //
    }
}
