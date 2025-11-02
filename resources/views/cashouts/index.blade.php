@extends('layouts.app')

@section('title', 'Kit Service | Liste des sorties')

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

@section('content')
    <div class="max-w-6xl mx-auto mt-10 bg-white p-6 rounded-lg shadow-sm h-[calc(100vh-100px)] flex flex-col">

        <!-- HEADER FIXE -->
        <div class="sticky top-0 bg-white z-20 pb-4 pt-2 border-b">

            <!-- Cartes résumé -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-4">
                <!-- Caisse Initiale -->
                <div class="flex items-center p-3 bg-white rounded-lg shadow">
                    <div class="p-3 mr-3 text-orange-500 bg-orange-100 rounded-full">
                        <i class='bx bx-wallet text-xl'></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Caisse Initiale</p>
                        <p class="text-base font-bold text-gray-700">$ {{ number_format($initialCash,2) }}</p>
                    </div>
                </div>

                <!-- Dépenses -->
                <div class="flex items-center p-3 bg-white rounded-lg shadow">
                    <div class="p-3 mr-3 text-red-500 bg-red-100 rounded-full">
                        <i class='bx bx-down-arrow-circle text-xl'></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Dépenses</p>
                        <p class="text-base font-bold text-gray-700">$ {{ number_format($expenses,2) }}</p>
                    </div>
                </div>

                <!-- Restant -->
                <div class="flex items-center p-3 bg-white rounded-lg shadow">
                    <div class="p-3 mr-3 text-green-500 bg-green-100 rounded-full">
                        <i class='bx bx-up-arrow-circle text-xl'></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Restant</p>
                        <p class="text-base font-bold text-gray-700">$ {{ number_format($remaining,2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Navigation + Recherche + Bouton -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-2 mb-4">
                <nav class="flex items-center space-x-2 text-sm sm:text-base text-gray-500">
                    <a href="{{ route('dashboard') }}" class="hover:text-orange-600 transition">Dashboard</a>
                    <span class="text-gray-300">/</span>
                    <span class="text-orange-600 font-semibold">Sorties</span>
                </nav>

                <input type="text" placeholder="Rechercher un nom ou téléphone"
                       class="w-52 sm:w-64 p-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400"
                       x-model="searchQuery" @input="filterCashouts()">

                <a href="{{ route('cashouts.create') }}"
                   class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg shadow">
                    + Nouvelle sortie
                </a>
            </div>
        </div>

        <!-- LISTE DES SORTIES SCROLLABLE -->
        <div class="overflow-y-auto flex-1 pr-2 mt-4" x-data="{ searchQuery: '' }">

            <table class="w-full border-collapse">
                <!-- HEADER FIXE -->
                <thead class="bg-orange-100 sticky top-0 z-10">
                <tr>
                    <th class="border px-4 py-2 text-left text-orange-600">#</th>
                    <th class="border px-4 py-2 text-left text-orange-600">Nom</th>
                    <th class="border px-4 py-2 text-left text-orange-600">Téléphone</th>
                    <th class="border px-4 py-2 text-left text-orange-600">Catégorie</th>
                    <th class="border px-4 py-2 text-left text-orange-600">Montant</th>
                    <th class="border px-4 py-2 text-left text-orange-600">Date</th>
                    <th class="border px-4 py-2 text-left text-orange-600">Action</th>
                </tr>
                </thead>
                <tbody>
                @php $counter = 1; @endphp
                @forelse($cashOuts as $cashout)
                    <tr class="hover:bg-orange-50"
                        x-show="searchQuery === '' || '{{ strtolower($cashout->name.' '.$cashout->phone.' '.$cashout->category) }}'.includes(searchQuery.toLowerCase())">
                        <td class="border px-4 py-2">{{ $counter++ }}</td>
                        <td class="border px-4 py-2">{{ $cashout->name }}</td>
                        <td class="border px-4 py-2">{{ $cashout->phone }}</td>
                        <td class="border px-4 py-2">{{ $cashout->category }}</td>
                        <td class="border px-4 py-2 text-red-600 font-semibold">$ {{ number_format($cashout->amount,2) }}</td>
                        <td class="border px-4 py-2">{{ $cashout->created_at->format('d/m/Y H:i') }}</td>
                        <td class="border px-4 py-2">
                            <a href="{{ route('cashouts.show', $cashout->id) }}"
                               class="px-2 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600 transition">
                                Voir facture
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-gray-500">Aucune sortie enregistrée</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

        </div>

    </div>

    <script>
        function filterCashouts() {
            const query = document.querySelector('input[x-model="searchQuery"]').value.toLowerCase();
            document.querySelectorAll('[x-show]').forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(query) ? '' : 'none';
            });
        }
    </script>
@endsection
