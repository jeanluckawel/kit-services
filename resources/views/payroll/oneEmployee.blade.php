@php use Illuminate\Support\Carbon; @endphp
@extends('layouts.app')

@section('title', 'Kit Service | Payroll')

@section('content')
    <div class="max-w-7xl mx-auto mt-10 flex flex-col md:flex-row gap-6">

        <!-- COLONNE GAUCHE : FORMULAIRE -->
        <div class="md:w-3/5 bg-white p-6 rounded-xl shadow-md flex flex-col">
            <!-- HEADER -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 border-b pb-4 gap-3">
                <div class="flex items-center gap-3">
                    <i class='bx bx-wallet text-3xl text-orange-500'></i>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Fiche de paie de</p>
                        <h1 class="text-2xl md:text-3xl font-bold text-orange-600 uppercase tracking-wide">
                            {{ $employees->first_name }} {{ $employees->last_name }}
                        </h1>
                    </div>
                </div>
                <a href="{{ route('employees.index') }}" class="text-sm text-gray-500 hover:text-orange-600 flex items-center gap-1">
                    <i class='bx bx-arrow-back'></i> Retour
                </a>
            </div>

            <!-- ONGLETS -->
            <div class="flex space-x-4 mb-6 border-b border-gray-200">
                <button type="button" class="tab-btn active-tab" data-target="pay-section">
                    <i class='bx bx-money mr-1'></i> Salaire
                </button>
                <button type="button" class="tab-btn" data-target="overtime-section">
                    <i class='bx bx-time mr-1'></i> Heures sup
                </button>
                <button type="button" class="tab-btn" data-target="sickdays-section">
                    <i class='bx bx-first-aid mr-1'></i> Congés maladie
                </button>
            </div>

            <!-- FORMULAIRE DE PAIE -->
            <form id="employeeForm" action="{{ route('payroll.store', $employees->employee_id) }}" method="POST" class="flex-1 flex flex-col gap-6">
                @csrf

                <!-- SALAIRE -->
                <div id="pay-section" class="tab-content active flex flex-col gap-4">
                    <div class="flex justify-between items-center text-sm text-gray-600 mb-4">
                        <span>Période :</span>
                        <span class="font-semibold" id="start-period"></span> - <span class="font-semibold" id="end-period"></span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 font-medium mb-1">Taux de change (USD → CDF) <span class="text-red-600">*</span></label>
                            <input type="number" name="exchange_rate" step="0.01" value="{{ old('exchange_rate') }}" required
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-1">Jours travaillés <span class="text-red-600">*</span></label>
                            <select name="worked_days" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">
                                @for ($i = 0; $i <= 30; $i++)
                                    <option value="{{ $i }}" {{ old('worked_days') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>

                <!-- HEURES SUP -->
                <div id="overtime-section" class="tab-content flex flex-col gap-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-form.input type="number" min="0" name="overtime_hours_30" max="30" label="Heures sup 30%" :value="old('overtime_hours_30')" />
                        <x-form.input type="number" min="0" name="overtime_hours_60" max="60" label="Heures sup 60%" :value="old('overtime_hours_60')" />
                        <x-form.input type="number" min="0" name="overtime_hours_100" max="100" label="Heures sup 100%" :value="old('overtime_hours_100')" />
                    </div>
                </div>

                <!-- CONGÉS MALADIE -->
                <div id="sickdays-section" class="tab-content flex flex-col gap-4">
                    <x-form.input type="number" min="0" name="sick_days" label="Nombre de jours de congé maladie" :value="old('sick_days')" />
                </div>

                <!-- VALIDATION -->
                <div class="flex justify-end mt-4">
                    <button type="submit" class="orange-btn flex items-center gap-2">
                        <i class='bx bx-check-circle'></i> Valider la paie
                    </button>
                </div>
            </form>
        </div>

        <!-- COLONNE DROITE : BULLETIN -->
        <div class="md:w-2/5 bg-white p-6 rounded-xl shadow-md h-fit sticky top-6 overflow-auto">
            <!-- KIT SERVICE HEADER -->
            <div class="flex justify-between items-start mb-4 border-b pb-3">
                <div class="space-y-1">
                    <h1 class="text-2xl font-bold uppercase text-gray-700">Kit Service SARL</h1>
                    <p class="text-sm text-gray-600">Adresse: Av. du Commerce, Kinshasa, RDC</p>
                    <p class="text-sm text-gray-600">Tel: +243 81 234 5678 | Email: info@kitservices.com</p>
                    <p class="text-sm text-gray-600">Site web: www.kitservices.com</p>
                </div>
                <img src="{{ asset('assets/img/logokitservices.png') }}" alt="Logo" class="logo w-20">
            </div>

            <!-- INFO EMPLOYÉ -->
            <div class="flex justify-between text-sm mb-6">
                <div class="space-y-1">
                    <p><strong>Matricule:</strong> <span id="bpEmployeeId">{{ $employees->employee_id }}</span></p>
                    <p><strong>Nom:</strong> <span id="bpFullName">{{ $employees->first_name }} {{ $employees->last_name }}</span></p>
                    <p><strong>Fonction:</strong> <span id="bpFunction">{{ $employees->function }}</span></p>
                    <p><strong>Département:</strong> <span id="bpDepartment">{{ $employees->department }}</span></p>
                    <p><strong>Salaire de Base:</strong> $<span id="bpSalary">{{ $employees->salaire_mensuel_brut ?? 0 }}</span></p>
                    <p><strong>Date d'embauche:</strong> {{ $employees->created_at?->format('d/m/Y') ?? '' }}</p>
                </div>
            </div>

            <!-- DÉTAILS PAIE 1 -->
            <div class="grid grid-cols-2 gap-6 text-sm border-t border-b py-4 mb-4">
                <div class="space-y-1">
                    <p><strong>Temps (jours):</strong> <span id="bpWorkedDays">0</span></p>
                    <p><strong>Congé Maladie:</strong> <span id="bpSickDays">0</span></p>
                </div>
                <div class="space-y-1">
                    <p><strong>Heures sup 30%:</strong> <span id="bpOvertime30">0 h → 0</span></p>
                    <p><strong>Heures sup 60%:</strong> <span id="bpOvertime60">0 h → 0</span></p>
                    <p><strong>Heures sup 100%:</strong> <span id="bpOvertime100">0 h → 0</span></p>
                </div>
            </div>
            {{-- detail 2 --}}
            <div class="grid grid-cols-2 gap-6 text-sm border-t border-b py-4 mb-4">
                <div class="space-y-1">
                    <p><strong>Salaire barémic:</strong> <span id="bpBaremic">0</span></p>
                    <p><strong>Sick Leave:</strong> <span id="bpSickLeave">0</span></p>
                    <p><strong>Logement:</strong> <span id="bpHousing">0</span></p>
                </div>
                <div class="space-y-1">
                    <p><strong>Total Earnings:</strong> <span id="bpTotalEarnings">0</span></p>
                    <p><strong>INSS 5%:</strong> <span id="bpInss_5">0</span></p>
                    <p><strong>Ipr monthly</strong> <span id="bpiprmonthly">0</span></p>
                </div>
            </div>
            {{-- detail 3 --}}
            <div class="grid grid-cols-2 gap-6 text-sm border-t border-b py-4 mb-4">
                <div class="space-y-1">
                    <p><strong>Inss Tax base:</strong> <span id="bpinsstaxebase">0</span></p>
                    <p><strong>Ipr taxe base :</strong> <span id="bpiprbase">0</span></p>
                    <p><strong>Annual Ipr tax base:</strong> <span id="bpannualiprbase">0</span></p>
                </div>
                <div class="space-y-1">
                    <p><strong>Tranche 2:</strong> <span id="bptranche2">0</span></p>
                    <p><strong>Tranche 3:</strong> <span id="bptranche3">0</span></p>
                    <p><strong>Tranche 3 sup :</strong> <span id="bptranche3sup">0</span></p>
                </div>
            </div>








            <!-- SALAIRE BRUT ET DÉDUCTIONS -->
            <div class="grid grid-cols-2 text-sm font-semibold mb-4">
                <p><strong>Total Brut:</strong> $<span id="bpTotalBrut">0</span></p>
                <p><strong>Total Déductions:</strong> $<span id="bpTotalDeductions">0</span></p>
            </div>

            <!-- NET À PAYER -->
            <div class="grid grid-cols-2 text-sm font-bold text-green-700 mb-6">
                <p><strong>Net à Payer (USD):</strong> $<span id="bpNetUSD">0</span></p>
                <p><strong>Net à Payer (CDF):</strong> <span id="bpNetCDF">0</span></p>
            </div>
        </div>
    </div>

    <style>
        .tab-btn {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            font-weight: 600;
            border-bottom: 2px solid transparent;
            color: #4B5563;
            cursor: pointer;
            display: flex;
            align-items: center;
            transition: all 0.2s ease;
        }
        .tab-btn:hover { color: #f97316; }
        .tab-btn.active-tab { border-bottom-color: #f97316; color: #f97316; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        .orange-btn { background-color: #f97316; color: white; font-weight: bold; padding: 0.6rem 1.2rem; border-radius: 0.5rem; transition: background-color 0.3s; }
        .orange-btn:hover { background-color: #ea580c; }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');
            const showTab = (id) => {
                tabContents.forEach(sec => sec.classList.toggle('active', sec.id === id));
                tabButtons.forEach(btn => btn.classList.toggle('active-tab', btn.dataset.target === id));
            };
            tabButtons.forEach(btn => btn.addEventListener('click', () => showTab(btn.dataset.target)));
            showTab('pay-section');

            // Période
            const today = new Date();
            const start = new Date(today.getFullYear(), today.getMonth(), 16);
            const end = new Date(today.getFullYear(), today.getMonth()+1, 15);
            const format = (d) => `${String(d.getDate()).padStart(2,'0')}/${String(d.getMonth()+1).padStart(2,'0')}/${d.getFullYear()}`;
            document.getElementById('start-period').textContent = format(start);
            document.getElementById('end-period').textContent = format(end);

            // Preview dynamique
            const preview = {
                workedDays: document.getElementsByName('worked_days')[0],
                overtime30: document.getElementsByName('overtime_hours_30')[0],
                overtime60: document.getElementsByName('overtime_hours_60')[0],
                overtime100: document.getElementsByName('overtime_hours_100')[0],
                sickDays: document.getElementsByName('sick_days')[0],
                exchangeRate: document.getElementsByName('exchange_rate')[0]
            };

            const updatePreview = () => {
                const salary = parseFloat("{{ $employees->salaire_mensuel_brut ?? 0 }}");
                const workedDays = parseFloat(preview.workedDays?.value) || 0;
                const sickDays = parseFloat(preview.sickDays?.value) || 0;
                const rate = parseFloat(preview.exchangeRate?.value) || 1;

                // Salaire journalier en CDF
                const dailySalary = (salary * rate) / 22;

                // Salaire barémic
                const baremic = dailySalary * workedDays;

                // Congé maladie : 2/3 du salaire journalier
                const sickLeave = dailySalary * (2/3) * sickDays;

                // Logement 30%
                const housing = (baremic + sickLeave) * 0.3;

                // Heures sup
                const overtime30Hours = parseFloat(preview.overtime30?.value) || 0;
                const overtime60Hours = parseFloat(preview.overtime60?.value) || 0;
                const overtime100Hours = parseFloat(preview.overtime100?.value) || 0;

                const hourlyRate = dailySalary / 9;
                const overtime30Amount = hourlyRate * overtime30Hours * 1.3;
                const overtime60Amount = hourlyRate * overtime60Hours * 1.6;
                const overtime100Amount = hourlyRate * overtime100Hours * 2;

                // Total des gains
                const totalEarnings = baremic + sickLeave + housing + overtime30Amount + overtime60Amount + overtime100Amount;

                // INSS
                const inss_tax_base = totalEarnings - housing; // Base hors logement

                const inss_5 = inss_tax_base * 0.05;

                // IPR tax base
                const ipr_tax_base = inss_tax_base - inss_5;
                const annual_ipr_rate_base = ipr_tax_base * 12;

                // IPR monthly
                // IF(((AK7+AL7+AM7)÷AJ7)<0,3;AK7+AL7+AM7−AO7;(AJ7×0,3)−AO7)÷12
                // AK7 = tranche2
                // AL7 = tranche3
                // AM7 = tranche3sup
                // AJ7 = annual_ipr_rate_base
                // AO7 = totalDeductions


                // const ipr_monthly = ((totalEarnings / workedDays) < 0.3 ? totalEarnings : (dailySalary * 22 * 0.3)) - inss_5 / 12;

                // Tranches IPR
                const tranche2 = (annual_ipr_rate_base > 1944001 && annual_ipr_rate_base <= 21600000)
                    ? ((annual_ipr_rate_base - 1944001) * 0.15) : 0;
                const tranche3 = (annual_ipr_rate_base > 21600000 && annual_ipr_rate_base <= 43200000)
                    ? (2948400 + (annual_ipr_rate_base - 21600000) * 0.3) : 0;

                // IF(AJ7>43200000;9428400+(AJ7−43200000)×0,4;0)
                const tranche3sup = (annual_ipr_rate_base > 43200000)
                    ? (9428400 + (annual_ipr_rate_base - 43200000) * 0.4)
                    : 0;




                // Variables selon ton code
                const totalTranches = tranche2 + tranche3 + tranche3sup;
                const totalDeductions = totalTranches / 12; // AO7 ramené au mois

                let ipr_monthly;

                if ((totalTranches / annual_ipr_rate_base) < 0.3) {
                    // Si le rapport est inférieur à 0.3
                    ipr_monthly = (totalTranches - totalDeductions) / 12;
                } else {
                    // Sinon
                    ipr_monthly = ((annual_ipr_rate_base * 0.3) - totalDeductions) / 12;
                }






                // Total brut / Net
                const totalBrut = totalEarnings;
                const netCDF = totalBrut - totalDeductions;
                const netUSD = netCDF / rate;

                // === 🔥 AFFICHAGE EN TEMPS RÉEL ===
                document.getElementById('bpBaremic').textContent = baremic.toFixed(2);
                document.getElementById('bpSickLeave').textContent = sickLeave.toFixed(2);
                document.getElementById('bpHousing').textContent = housing.toFixed(2);
                document.getElementById('bpOvertime30').textContent = `${overtime30Hours} h → ${overtime30Amount.toFixed(2)}`;
                document.getElementById('bpOvertime60').textContent = `${overtime60Hours} h → ${overtime60Amount.toFixed(2)}`;
                document.getElementById('bpOvertime100').textContent = `${overtime100Hours} h → ${overtime100Amount.toFixed(2)}`;
                document.getElementById('bpTotalEarnings').textContent = totalEarnings.toFixed(2);
                document.getElementById('bpWorkedDays').textContent = workedDays;
                document.getElementById('bpSickDays').textContent = sickDays;

                // --- Ces 6 là sont les tiens :
                document.getElementById('bpinsstaxebase').textContent = inss_tax_base.toFixed(2);
                document.getElementById('bpiprbase').textContent = ipr_tax_base.toFixed(2);
                document.getElementById('bpannualiprbase').textContent = annual_ipr_rate_base.toFixed(2);
                document.getElementById('bptranche2').textContent = tranche2.toFixed(2);
                document.getElementById('bptranche3').textContent = tranche3.toFixed(2);
                document.getElementById('bptranche3sup').textContent = ipr_monthly.toFixed(2);

                document.getElementById('bpInss_5').textContent = inss_5.toFixed(2);
                document.getElementById('bpiprmonthly').textContent = ipr_monthly.toFixed(2);


                // --- Totaux :
                document.getElementById('bpTotalBrut').textContent = totalBrut.toFixed(2);
                document.getElementById('bpTotalDeductions').textContent = totalDeductions.toFixed(2);
                document.getElementById('bpNetUSD').textContent = netUSD.toFixed(2);
                document.getElementById('bpNetCDF').textContent = netCDF.toFixed(2);

                // Debug pour vérifier
                console.log({
                    totalEarnings,
                    inss_tax_base,
                    ipr_tax_base,
                    annual_ipr_rate_base,
                    tranche2,
                    tranche3,
                    tranche3sup,
                    totalDeductions
                });
            };



            Object.values(preview).forEach(input => { if(input) input.addEventListener('input', updatePreview); });
            updatePreview();
        });
    </script>
@endsection
