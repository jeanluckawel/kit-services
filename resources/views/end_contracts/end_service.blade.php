@extends('layouts.app')

@section('title', 'Certificat de fin de service')

@section('content')

    <div class="max-w-4xl mx-auto p-4">

        <!-- Contenu à exporter -->
        <div id="end-service" class="bg-white dark:bg-gray-800 rounded-xl shadow-xl text-[13px] leading-relaxed p-6"
             style="width: 21cm; padding: 2cm; box-sizing: border-box; font-size: 13px;">

            <!-- En-tête -->
            <div class="flex items-center justify-between mb-6 border-b border-gray-300 pb-2">
                <div class="text-left">
                    <h1 class="text-xl font-bold text-orange-600">KIT SERVICE Sarl</h1>
                    <p class="text-xs text-gray-600 dark:text-gray-300">
                        1627 B Avenue Kamina, Q/ Mutoshi, Kolwezi, LUALABA, RDC <br>
                        Téléphone : 00243 977 333 977 <br>
                        Email : kitservice17@gmail.com <br>
                        Site web : <a href="https://www.kitservice.net" class="text-blue-600 hover:underline">www.kitservice.net</a> <br>
                        ID. Nat : 05-H5300-N87645R <br>
                        RCCM : CD/LSH/RCCM/20-8-00584
                    </p>
                </div>

                <div class="text-right">
                    <img src="{{ asset('logo/logo.png') }}" alt="Logo Kit Service" class="h-20 inline-block">
                </div>
            </div>

            <!-- Corps du certificat -->
            <div class="text-gray-800 dark:text-gray-100">

                <h2 class="text-center font-bold my-8 underline text-[15px]">
                    CERTIFICAT DE FIN DE SERVICE
                </h2>

                @php
                    use Carbon\Carbon;

                    $start = Carbon::parse($employee->created_at);
                    $end = Carbon::parse($employee->end_contract_date);

                    // Calcul de la durée réelle et arrondie
                    $diffYears = floor($start->diffInYears($end));
                    $diffMonths = floor($start->diffInMonths($end));
                    $diffDays = floor($start->diffInDays($end));

                    // Détermination de la durée textuelle
                    if ($diffYears >= 1) {
                        $duration = "environ $diffYears an" . ($diffYears > 1 ? "s" : "");
                    } elseif ($diffMonths >= 1) {
                        $duration = "environ $diffMonths mois";
                    } else {
                        $duration = "environ $diffDays jour" . ($diffDays > 1 ? "s" : "");
                    }
                @endphp

                <p class="mb-4 text-justify">
                    L’employeur, <strong>KIT SERVICE Sarl</strong>, atteste par le présent certificat que
                    <strong>{{ strtoupper($employee->first_name) }} {{ strtoupper($employee->last_name) }} {{ strtoupper($employee->middle_name) }}</strong>,
                    immatriculé{{ $employee->sex === 'F' ? '(e)' : '' }} à la Caisse Nationale de Sécurité Sociale (CNSS) sous le numéro en cours,
                    a presté, en son sein, des services en qualité de
                    <strong>{{ $employee->function ?? 'Administrator & HR' }}</strong>
                    pendant une durée d’<strong>{{ $duration }}</strong>,
                    soit du <strong>{{ $start->translatedFormat('d F Y') }}</strong>
                    au <strong>{{ $end->translatedFormat('d F Y') }}</strong>.
                </p>

                <p class="mb-4 text-justify">
                    En foi de quoi, le présent certificat lui est délivré pour valoir ce que de droit.
                </p>

                <p class="mb-12 text-right mt-12">
                    Ainsi fait à Kolwezi, le
                    <strong>{{ Carbon::now()->translatedFormat('d F Y') }}</strong>.
                </p>
            </div>

            <!-- Signature -->
            <div class="flex justify-end mb-16">
                <div class="text-center">
                    <p class="font-semibold">Madame KUZO Nelly</p>
                    <p class="text-sm">MANAGER Général</p>
                    <img src="{{ asset('logo/nelly.png') }}" alt="Signature Nelly" class="h-[200px] mx-auto -mt-13">
                </div>
            </div>
        </div>

        <!-- Boutons -->
        <div class="mt-6 flex space-x-3">
            <a href="{{ url()->previous() }}"
               class="bg-red-600 text-white px-4 py-1.5 rounded hover:bg-red-700 transition text-[12px]">
                Retour
            </a>


            <button onclick="downloadPDF()"
                    class="bg-black text-white px-4 py-1.5 rounded hover:bg-orange-700 transition text-[12px]">
                Télécharger PDF
            </button>
        </div>
    </div>

    <!-- Script export PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        function downloadPDF() {
            const element = document.getElementById('end-service');
            const opt = {
                margin: 0.5,
                filename: 'certificat_fin_service_{{ $employee->first_name ?? "employee" }}.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, scrollY: 0 },
                jsPDF: { unit: 'cm', format: 'a4', orientation: 'portrait' },
                pagebreak: { mode: ['css', 'legacy'] }
            };
            html2pdf().set(opt).from(element).save();
        }
    </script>

@endsection
