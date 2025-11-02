<div id="terminationLetter" style="font-size:13px; width:100%; padding:2cm; box-sizing:border-box;">
    <div class="flex justify-between border-b border-gray-300 pb-2 mb-4">
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
        <img src="{{ asset('logo/logo.png') }}" alt="Logo" class="h-16">
    </div>

    <h2 class="text-center font-bold underline mb-6">LETTRE DE LICENCIEMENT</h2>

    @php
        $start = \Carbon\Carbon::parse($employee->created_at);
        $end = \Carbon\Carbon::now(); // date du licenciement
        $diffYears = $start->diffInYears($end);
        $diffMonths = $start->diffInMonths($end);
        $diffDays = $start->diffInDays($end);
        $duration = $diffYears>=1 ? "environ $diffYears an".($diffYears>1?"s":"") :
                    ($diffMonths>=1 ? "environ $diffMonths mois" : "environ $diffDays jour".($diffDays>1?"s":""));
    @endphp

    <p class="mb-4 text-justify">
        Madame/Monsieur <strong>{{ strtoupper($employee->first_name) }} {{ strtoupper($employee->last_name) }} {{ strtoupper($employee->middle_name) }}</strong>,
        employé(e) en qualité de <strong>{{ $employee->function ?? 'Administrateur' }}</strong> au sein de KIT SERVICE Sarl depuis le
        <strong>{{ $start->translatedFormat('d F Y') }}</strong>, est informé(e) par la présente de sa décision de licenciement,
        effectif à partir du <strong>{{ $end->translatedFormat('d F Y') }}</strong>.
    </p>

    <p class="mb-4 text-justify">
        Cette décision a été prise pour le motif suivant : <strong>{{ $employee->end_contract_reason ?? 'Motif professionnel' }}</strong>.
        Durant sa période de travail, l’employé(e) a presté pendant <strong>{{ $duration }}</strong>.
    </p>

    <p class="mb-4 text-justify">
        Nous remercions <strong>{{ strtoupper($employee->first_name) }}</strong> pour son engagement et son service au sein de KIT SERVICE Sarl.
        Les droits et indemnités dus conformément à la législation en vigueur seront versés à la date de départ.
    </p>

    <p class=" mb-2 text-right mt-5">
        Fait à Kolwezi, le <strong>{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</strong>.
    </p>

    <div class="flex justify-end text-center">
        <div class="text-center">
            <p class="font-semibold">Madame KUZO Nelly</p>
            <p class="text-sm">MANAGER Général</p>
            <img src="{{ asset('logo/nelly.png') }}" alt="Signature Nelly" class="h-[200px] mx-auto -mt-13">
        </div>
    </div>
</div>

