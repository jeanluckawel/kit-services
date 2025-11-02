@extends('layouts.app')

@section('title', 'Kit Service | Factures de ' . $customer->name)

@section('content')
    <div class="max-w-5xl mx-auto mt-10 bg-white p-6 rounded-lg shadow-sm flex flex-col">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-bold text-orange-600">
                Factures de {{ $customer->name }}
            </h2>
            <a href="{{ route('customers.index') }}" class="text-gray-600 hover:underline text-sm">
                ← Retour aux clients
            </a>
        </div>

        <!-- TABLE FACTURES -->
        <div class="overflow-x-auto">
            @if($invoices->isEmpty())
                <p class="p-4 text-gray-500">Aucune facture trouvée pour ce client.</p>
            @else
                <table class="w-full border border-gray-200 text-sm table-auto">
                    <thead class="bg-gray-50 sticky top-0">
                    <tr class="text-gray-700 uppercase text-xs">
                        <th class="px-3 py-2 text-left">N° Facture</th>
                        <th class="px-3 py-2 text-left">PO</th>
                        <th class="px-3 py-2 text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                    @foreach($invoices as $invoice)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-3 py-2">{{ $invoice->numero_invoice }}</td>
                            <td class="px-3 py-2">{{ $invoice->po }}</td>
                            <td class="px-3 py-2 text-center">
                                <a href="{{ route('invoices.show', $invoice->id) }}"
                                   class="px-2 py-1 bg-orange-500 text-white rounded hover:bg-orange-700 text-xs font-semibold inline-flex items-center gap-1">
                                    <i data-lucide="eye" class="w-4 h-4"></i> Voir
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
