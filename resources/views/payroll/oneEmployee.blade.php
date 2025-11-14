@extends('layouts.app')
@section('title', 'Kit Service | Payroll')

@section('content')
    <div class="max-w-7xl mx-auto mt-10 flex flex-col md:flex-row gap-6">

        <!-- FORMULAIRE DE PAIE -->
        <div class="md:w-3/5 bg-white p-6 rounded-xl shadow-md flex flex-col">
            <div class="flex justify-between mb-6 border-b pb-4">
                <h1 class="text-2xl font-bold text-orange-600 uppercase">
                    {{ $employees->first_name }} {{ $employees->last_name }}
                </h1>
                <a href="{{ route('employees.index') }}" class="text-gray-500 hover:text-orange-600">Retour</a>
            </div>

            <div class="flex space-x-4 mb-6 border-b border-gray-200">
                <button type="button" class="tab-btn active-tab" data-target="pay-section">Salaire</button>
                <button type="button" class="tab-btn" data-target="overtime-section">Heures sup</button>
                <button type="button" class="tab-btn" data-target="sickdays-section">Congés maladie</button>
            </div>

            <form id="employeeForm" action="{{ route('payroll.store', $employee->employee_id) }}" method="POST" class="flex-1 flex flex-col gap-6">
                @csrf

                <!-- SALAIRE -->
                <div id="pay-section" class="tab-content active flex flex-col gap-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label>Taux de change (USD → CDF) *</label>
                            <input type="number" step="0.01" name="exchange_rate" value="{{ old('exchange_rate', 1) }}" required class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label>Jours travaillés *</label>
                            <select name="worked_days" class="w-full border rounded px-3 py-2">
                                @for ($i=0;$i<=30;$i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>

                <!-- HEURES SUP -->
                <div id="overtime-section" class="tab-content flex flex-col gap-4">
                    <input type="number" min="0" max="30" name="overtime_hours_30" placeholder="Heures sup 30%">
                    <input type="number" min="0" max="60" name="overtime_hours_60" placeholder="Heures sup 60%">
                    <input type="number" min="0" max="100" name="overtime_hours_100" placeholder="Heures sup 100%">
                </div>

                <!-- CONGÉS MALADIE -->
                <div id="sickdays-section" class="tab-content flex flex-col gap-4">
                    <input type="number" min="0" name="sick_days" placeholder="Jours de congé maladie">
                </div>

                <!-- CHAMPS CACHÉS -->
                <input type="hidden" name="baremic_salary" id="inputBaremic">
                <input type="hidden" name="sick_leave" id="inputSickLeave">
                <input type="hidden" name="accommodation_allowance" id="inputHousing">
                <input type="hidden" name="overtime_30" id="inputOvertime30">
                <input type="hidden" name="overtime_60" id="inputOvertime60">
                <input type="hidden" name="overtime_100" id="inputOvertime100">
                <input type="hidden" name="total_earnings" id="inputTotalEarnings">
                <input type="hidden" name="inss_5" id="inputInss5">
                <input type="hidden" name="ipr_tax_base" id="inputIprBase">
                <input type="hidden" name="annual_ipr_tax_base" id="inputAnnualIprBase">
                <input type="hidden" name="tranche_2" id="inputTranche2">
                <input type="hidden" name="tranche_3" id="inputTranche3">
                <input type="hidden" name="tranche_more_3" id="inputTranche3Sup">
                <input type="hidden" name="monthly_ipr" id="inputIprMonthly">
                <input type="hidden" name="total_taxes_cdf" id="inputTotalDeductions">
                <input type="hidden" name="net" id="inputNetCDF">
                <input type="hidden" name="net_usd" id="inputNetUSD">
                <input type="hidden" name="period" value="{{ now()->month }}">

                <div class="flex justify-end">
                    <button type="submit" class="orange-btn">Valider la paie</button>
                </div>
            </form>
        </div>

        <!-- BULLETIN -->
        <div class="md:w-2/5 bg-white p-6 rounded-xl shadow-md">
            <h2 class="font-bold mb-4">Bulletin de paie</h2>
            <p><strong>Nom:</strong> {{ $employee->first_name }} {{ $employee->last_name }}</p>
            <p><strong>Matricule:</strong> {{ $employee->employee_id }}</p>
            <p><strong>Fonction:</strong> {{ $employee->function }}</p>
            <p><strong>Département:</strong> {{ $employee->department }}</p>
            <hr class="my-2">
            <p><strong>Jours travaillés:</strong> <span id="bpWorkedDays">0</span></p>
            <p><strong>Congés maladie:</strong> <span id="bpSickDays">0</span></p>
            <p><strong>Salaire barémic:</strong> <span id="bpBaremic">0</span></p>
            <p><strong>Sick Leave:</strong> <span id="bpSickLeave">0</span></p>
            <p><strong>Logement:</strong> <span id="bpHousing">0</span></p>
            <p><strong>Total Earnings:</strong> <span id="bpTotalEarnings">0</span></p>
            <p><strong>INSS 5%:</strong> <span id="bpInss_5">0</span></p>
            <p><strong>Ipr monthly:</strong> <span id="bpiprmonthly">0</span></p>
            <p><strong>Total déductions:</strong> <span id="bpTotalDeductions">0</span></p>
            <p><strong>Net CDF:</strong> <span id="bpNetCDF">0</span></p>
            <p><strong>Net USD:</strong> <span id="bpNetUSD">0</span></p>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Onglets
            const tabs = document.querySelectorAll('.tab-btn');
            const contents = document.querySelectorAll('.tab-content');
            tabs.forEach(tab => tab.addEventListener('click', () => {
                contents.forEach(c => c.classList.remove('active'));
                tabs.forEach(t => t.classList.remove('active-tab'));
                document.getElementById(tab.dataset.target).classList.add('active');
                tab.classList.add('active-tab');
            }));

            const preview = {
                workedDays: document.getElementsByName('worked_days')[0],
                sickDays: document.getElementsByName('sick_days')[0],
                overtime30: document.getElementsByName('overtime_hours_30')[0],
                overtime60: document.getElementsByName('overtime_hours_60')[0],
                overtime100: document.getElementsByName('overtime_hours_100')[0],
                exchangeRate: document.getElementsByName('exchange_rate')[0]
            };

            const salaryUSD = parseFloat("{{ $employee->salaire_mensuel_brut ?? 0 }}");

            const updatePreview = () => {
                const worked = parseFloat(preview.workedDays.value) || 0;
                const sick = parseFloat(preview.sickDays.value) || 0;
                const rate = parseFloat(preview.exchangeRate.value) || 1;
                const daily = (salaryUSD * rate) / 22;

                const baremic = daily * worked;
                const sickLeave = daily * (2/3) * sick;
                const housing = (baremic + sickLeave) * 0.3;

                const ot30 = parseFloat(preview.overtime30.value) || 0;
                const ot60 = parseFloat(preview.overtime60.value) || 0;
                const ot100 = parseFloat(preview.overtime100.value) || 0;
                const hourly = daily / 9;

                const ot30Amount = ot30 * hourly * 1.3;
                const ot60Amount = ot60 * hourly * 1.6;
                const ot100Amount = ot100 * hourly * 2;

                const totalEarnings = baremic + sickLeave + housing + ot30Amount + ot60Amount + ot100Amount;

                const inss5 = (totalEarnings - housing) * 0.05;
                const iprBase = totalEarnings - housing - inss5;
                const annualIprBase = iprBase * 12;

                const tranche2 = (annualIprBase > 1944001 && annualIprBase <= 21600000) ? ((annualIprBase - 1944001) * 0.15) : 0;
                const tranche3 = (annualIprBase > 21600000 && annualIprBase <= 43200000) ? (2948400 + (annualIprBase - 21600000) * 0.3) : 0;
                const tranche3Sup = (annualIprBase > 43200000) ? (9428400 + (annualIprBase - 43200000) * 0.4) : 0;

                const totalDeductions = (tranche2 + tranche3 + tranche3Sup) / 12;
                const iprMonthly = totalDeductions;

                const netCDF = totalEarnings - totalDeductions;
                const netUSD = netCDF / rate;

                // UI
                document.getElementById('bpWorkedDays').textContent = worked;
                document.getElementById('bpSickDays').textContent = sick;
                document.getElementById('bpBaremic').textContent = baremic.toFixed(2);
                document.getElementById('bpSickLeave').textContent = sickLeave.toFixed(2);
                document.getElementById('bpHousing').textContent = housing.toFixed(2);
                document.getElementById('bpOvertime30').textContent = ot30 + ' h → ' + ot30Amount.toFixed(2);
                document.getElementById('bpOvertime60').textContent = ot60 + ' h → ' + ot60Amount.toFixed(2);
                document.getElementById('bpOvertime100').textContent = ot100 + ' h → ' + ot100Amount.toFixed(2);
                document.getElementById('bpTotalEarnings').textContent = totalEarnings.toFixed(2);
                document.getElementById('bpInss_5').textContent = inss5.toFixed(2);
                document.getElementById('bpiprmonthly').textContent = iprMonthly.toFixed(2);
                document.getElementById('bpTotalDeductions').textContent = totalDeductions.toFixed(2);
                document.getElementById('bpNetCDF').textContent = netCDF.toFixed(2);
                document.getElementById('bpNetUSD').textContent = netUSD.toFixed(2);

                // Champs cachés
                document.getElementById('inputBaremic').value = baremic.toFixed(2);
                document.getElementById('inputSickLeave').value = sickLeave.toFixed(2);
                document.getElementById('inputHousing').value = housing.toFixed(2);
                document.getElementById('inputOvertime30').value = ot30Amount.toFixed(2);
                document.getElementById('inputOvertime60').value = ot60Amount.toFixed(2);
                document.getElementById('inputOvertime100').value = ot100Amount.toFixed(2);
                document.getElementById('inputTotalEarnings').value = totalEarnings.toFixed(2);
                document.getElementById('inputInss5').value = inss5.toFixed(2);
                document.getElementById('inputIprBase').value = iprBase.toFixed(2);
                document.getElementById('inputAnnualIprBase').value = annualIprBase.toFixed(2);
                document.getElementById('inputTranche2').value = tranche2.toFixed(2);
                document.getElementById('inputTranche3').value = tranche3.toFixed(2);
                document.getElementById('inputTranche3Sup').value = tranche3Sup.toFixed(2);
                document.getElementById('inputIprMonthly').value = iprMonthly.toFixed(2);
                document.getElementById('inputTotalDeductions').value = totalDeductions.toFixed(2);
                document.getElementById('inputNetCDF').value = netCDF.toFixed(2);
                document.getElementById('inputNetUSD').value = netUSD.toFixed(2);
            };

            document.querySelectorAll('input, select').forEach(el => el.addEventListener('input', updatePreview));
            updatePreview();
        });
    </script>
@endsection
