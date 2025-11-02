@extends('layouts.app')

@section('title', 'Kit Service | Facture Dépense')

@section('content')

    <div class="flex justify-end mb-4 mr-10">
        <!-- Bouton PDF -->
        <button onclick="downloadPDF()" class="bg-black text-white font-bold py-2 px-4 rounded">
            Télécharger la facture PDF
        </button>

        <!-- Bouton retour -->
        <a href="{{ route('cashouts.index') }}"
           class="bg-gray-200 text-gray-800 font-bold py-2 px-4 rounded ml-2 inline-flex items-center gap-1">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour
        </a>
    </div>

    <!-- Contenu facture -->
    <div id="invoice-content" class="mx-auto shadow-lg" style="width: 21cm; height: 29.7cm; padding: 2cm; background: white; font-size: 12px; overflow: hidden;">

        <!-- Header -->
        <div class="flex justify-between items-start border-b pb-4 mt-5 mb-4">
            <!-- Infos Kit Service -->
            <div class="w-1/3 space-y-1 text-sm">
                <h2 class="text-orange-500 font-bold text-base">KIT SERVICE SARL</h2>
                <p>1627 B Avenue Kamina, Q/ Mutoshi Kolwezi</p>
                <p>LUALABA RDC</p>
                <p>00243 977 333 977</p>
                <p><a href="mailto:kitservice17@gmail.com" class="text-blue-600 underline">kitservice17@gmail.com</a></p>
                <p><a href="http://www.kitservice.net" class="text-blue-600 underline">www.kitservice.net</a></p>
                <p>ID NAT: 05-H5300-N876458R</p>
                <p>RCCM: CD/LSH/RCCM/20-B-00584</p>
            </div>

            <!-- Infos dépense -->
            <div class="w-1/3 space-y-1 text-sm text-left">
                <h2 class="text-gray-700 font-semibold text-base">Bénéficiaire: {{ $cashout->name }}</h2>
                @if($cashout->phone)
                    <p>Téléphone : {{ $cashout->phone }}</p>
                @endif
                <p>Catégorie : {{ ucfirst($cashout->category) }}</p>
            </div>

            <!-- Logo + infos facture -->
            <div class="w-1/3 text-right space-y-1 text-sm">
                <img src="{{ asset('logo/logo (1).png') }}" alt="Kit Service Logo" class="h-20 mx-auto">
                <h3 class="text-base font-bold text-gray-800 mt-2">FACTURE DÉPENSE</h3>
                <p class="text-xs">No. {{ $cashout->id }}</p>
                <p class="text-xs">Date: {{ \Carbon\Carbon::parse($cashout->created_at)->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <!-- Table dépense -->
        <table class="w-full border text-xs mb-7 mt-7">
            <thead class="bg-gray-100 text-left">
            <tr class="border">
                <th class="border px-2 py-1">N°</th>
                <th class="border px-2 py-1">DESCRIPTION</th>
                <th class="border px-2 py-1 text-right">MONTANT</th>
            </tr>
            </thead>
            <tbody>
            <tr class="border">
                <td class="border px-2 py-1">1</td>
                <td class="border px-2 py-1">{{ $cashout->description }}</td>
                <td class="border px-2 py-1 text-right">$ {{ number_format($cashout->amount, 2) }}</td>
            </tr>
            </tbody>
        </table>

        <!-- Totaux -->
        <div class="w-full flex justify-end mt-4">
            <table class="text-sm w-1/2">
                <tr>
                    <td class="text-right pr-4 py-1 border-t font-bold">TOTAL :</td>
                    <td class="text-right font-bold border-t">$ {{ number_format($cashout->amount, 2) }}</td>
                </tr>
            </table>
        </div>

        <!-- Infos bancaires -->
        <div class="mt-48 text-sm">
            <p class="font-semibold underline">Bank details</p>
            <p>Nom de la banque : RAWBANK</p>
            <p>N° compte : 05100 - 05139 - 00703347001-87</p>
            <p>Intitulé du compte : KIT SERVICE SARL</p>
            <p>Swift code : RAWBCDRC</p>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-xs text-gray-600">
            <p>Merci pour votre confiance !</p>
            <p>Pour toute question, contactez-nous :
                <a href="mailto:kitservice17@gmail.com" class="underline text-blue-600">kitservice17@gmail.com</a>
            </p>
        </div>
    </div>

    <!-- Script PDF -->
    <script src="https://raw.githack.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.min.js"></script>
    <script>
        function downloadPDF() {
            const element = document.getElementById('invoice-content');
            const options = {
                filename: 'Depense_KIT_SERVICE_{{ $cashout->id }}.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'cm', format: [21, 29.7], orientation: 'portrait' },
                pagebreak: { mode: ['avoid-all', 'css', 'legacy'] }
            };
            html2pdf().set(options).from(element).save();
        }
    </script>
@endsection
