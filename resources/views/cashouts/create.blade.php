@extends('layouts.app')

@section('title', 'Kit Service | Sortie d\'argent')

<link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
<link href='https://cdn.boxicons.com/fonts/brands/boxicons-brands.min.css' rel='stylesheet'>

<style>
    .orange-btn { background-color: #f97316; color: white; font-weight: bold; padding: 0.5rem 1rem; border: none; border-radius: 0.25rem; cursor: pointer; transition: background-color 0.3s ease; }
    .orange-black { background-color: #000000; color: white; font-weight: bold; padding: 0.5rem 1rem; border: none; border-radius: 0.25rem; cursor: pointer; transition: background-color 0.3s ease; }
    .orange-btn:hover { background-color: #ea580c; }
    .red-btn { background-color: #ef4444; color: white; font-weight: bold; padding: 0.25rem 0.75rem; border: none; border-radius: 0.25rem; cursor: pointer; font-size: 0.9rem; }
    .red-btn:hover { background-color: #dc2626; }
</style>

@section('content')
    <div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded-lg shadow-md">

        <h3 class="text-lg font-semibold text-gray-700 mb-4">Nouvelle sortie d'argent</h3>

        <!-- Cartes résumé caisse -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="p-4 bg-orange-100 rounded-lg text-center shadow">
                <p class="text-sm text-gray-600">Caisse Initiale</p>
                @php
                    $totalFact = \App\Models\Invoice::sum('pt_mois');
                    $totalDepense = \App\Models\CashOut::sum('amount');
                    $caisse = $totalFact - $totalDepense
                @endphp
                <h3 id="initial-cash" class="text-lg font-semibold text-gray-800">${{ number_format($remaining ?? 0, 2)  }}</h3>
            </div>
            <div class="p-4 bg-red-100 rounded-lg text-center shadow">
                <p class="text-sm text-gray-600">Dépenses</p>
                <h3 id="total-expense" class="text-lg font-semibold text-gray-800">$0.00</h3>
            </div>
            <div class="p-4 bg-green-100 rounded-lg text-center shadow">
                <p class="text-sm text-gray-600">Total Restant</p>
                <h3 id="remaining-cash" class="text-lg font-semibold text-gray-800">${{ number_format($remaining ?? 0, 2) }}</h3>
            </div>
        </div>

        <form action="{{ route('cashouts.store') }}" method="POST" id="cashout-form">
            @csrf

            <div id="cashout-items">
                <div class="item-row mb-6 border p-4 rounded-lg shadow-sm bg-gray-50 relative">
                    <button type="button" class="remove-btn red-btn absolute top-2 right-2 hidden" onclick="removeRow(this)">➖</button>

                    <div class="flex flex-col md:flex-row gap-4 mb-4">
                        <x-form.input name="name[]" label="Nom" required autocomplete="off"/>
                        <x-form.input name="phone[]" label="Téléphone" autocomplete="off"/>
                    </div>

                    <div class="flex flex-col md:flex-row gap-4 mb-4">
                        <x-form.input name="amount[]" label="Montant" type="number" step="0.01" required autocomplete="off"/>
                        <x-form.select name="category[]" label="Catégorie" required :options="['urgent'=>'urgent','normal'=>'normal']"/>
                    </div>

                    <div class="flex flex-col mb-2">
                        <x-form.input name="description[]" label="Description" required autocomplete="off"/>
                    </div>
                </div>
            </div>

            <button type="button" onclick="addRow()" class="mt-4 orange-btn">➕ Ajouter une sortie</button>

            <div class="overflow-x-auto mt-6">
                <table class="min-w-full table-auto border border-gray-300 text-sm text-gray-800 mt-4">
                    <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 border">#</th>
                        <th class="p-2 border">Nom</th>
                        <th class="p-2 border">Téléphone</th>
                        <th class="p-2 border">Montant</th>
                        <th class="p-2 border">Catégorie</th>
                        <th class="p-2 border">Description</th>
                    </tr>
                    </thead>
                    <tbody id="cashout-summary"></tbody>
                </table>
            </div>

            <div class="mt-6 flex justify-end">
                <a href="{{ route('cashouts.index') }}">
                    <button type="button" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg mr-2">
                        Back
                    </button>
                </a>
                <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-lg shadow">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>

    <script>
        let initialCash = parseFloat('{{ $remaining ?? 0 }}');
        const minCash = 50;

        function attachEvents(row){
            row.querySelectorAll('input, select').forEach(input=>{
                input.addEventListener('input', updateSummary);
            });
        }

        function addRow(){
            const container = document.getElementById('cashout-items');
            const newRow = container.children[0].cloneNode(true);
            newRow.querySelectorAll('input').forEach(i => i.value = '');
            newRow.querySelector('select').selectedIndex = 0;
            newRow.querySelector('.remove-btn').classList.remove('hidden');
            container.appendChild(newRow);
            attachEvents(newRow);
            updateSummary();
        }

        function removeRow(button){
            const container = document.getElementById('cashout-items');
            if(container.children.length > 1){
                button.closest('.item-row').remove();
                updateSummary();
            }
        }

        function updateSummary(){
            const tbody = document.getElementById('cashout-summary');
            tbody.innerHTML = '';
            let totalExpense = 0;

            const rows = document.querySelectorAll('#cashout-items .item-row');
            rows.forEach((row,index)=>{
                const name = row.querySelector('input[name="name[]"]').value;
                const phone = row.querySelector('input[name="phone[]"]').value;
                let amount = parseFloat(row.querySelector('input[name="amount[]"]').value) || 0;

                // Vérification stock minimum
                if(totalExpense + amount > initialCash - minCash){
                    amount = initialCash - minCash - totalExpense;
                    if(amount < 0) amount = 0;
                    row.querySelector('input[name="amount[]"]').value = amount.toFixed(2);
                }

                totalExpense += amount;

                const category = row.querySelector('select[name="category[]"]').value;
                const desc = row.querySelector('input[name="description[]"]').value;

                const tr = document.createElement('tr');
                tr.innerHTML = `
            <td class="border p-2 text-center">${index+1}</td>
            <td class="border p-2">${name}</td>
            <td class="border p-2">${phone}</td>
            <td class="border p-2 text-right">${amount.toFixed(2)}</td>
            <td class="border p-2 text-center">${category}</td>
            <td class="border p-2">${desc}</td>
        `;
                tbody.appendChild(tr);
            });

            document.getElementById('total-expense').textContent = '$' + totalExpense.toFixed(2);
            document.getElementById('remaining-cash').textContent = '$' + (initialCash - totalExpense).toFixed(2);
        }

        document.addEventListener('DOMContentLoaded',()=>{
            attachEvents(document.querySelector('#cashout-items .item-row'));
            updateSummary();
        });
    </script>
@endsection
