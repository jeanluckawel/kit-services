@php use Illuminate\Support\Carbon; @endphp
@extends('layouts.app')

@section('title', 'Kit Service | Employee List (CDD & CDI)')

@section('content')
    <div class="max-w-7xl mx-auto mt-10 bg-white p-6 rounded-lg shadow-sm h-[calc(100vh-100px)] flex flex-col">

        <!-- HEADER -->
        @include('employees.partial.header')

        <!-- TABLE EMPLOYÉS -->
        <div class="overflow-y-auto flex-1 mt-4 border border-gray-200 rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 sticky top-0">
                <tr class="text-gray-700 uppercase text-xs">
                    <th class="px-4 py-3 text-left">Photo</th>
                    <th class="px-4 py-3 text-left">Matricule</th>
                    <th class="px-4 py-3 text-left">Nom complet</th>
                    <th class="px-4 py-3 text-left">Département</th>
                    <th class="px-4 py-3 text-left">Fonction</th>
                    <th class="px-4 py-3 text-left">Contrat</th>
                    <th class="px-4 py-3 text-left">Date de fin</th>
                    <th class="px-4 py-3 text-left">Jours restants</th>
                    <th class="px-4 py-3 text-center">Action</th>
                </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                @foreach($employees as $employee)
                    @php
                        $daysLeft = null;
                        $rowClass = '';

                        if ($employee->contract_type === 'CDD' && $employee->end_contract_date) {
                            $endDate = Carbon::parse($employee->end_contract_date);
                            $now = Carbon::now();
                            $daysLeft = intval($now->diffInDays($endDate, false));
                            if ($daysLeft <= 20 && $daysLeft >= 0) $rowClass = 'bg-red-50';
                        }

                        $gender = strtolower($employee->gender);
                        $photoPath = $gender === 'female'
                            ? 'assets/profil/female.png'
                            : ($gender === 'male' ? 'assets/profil/male.png' : 'assets/profil/other.png');
                    @endphp

                    <tr class="{{ $rowClass }} hover:bg-gray-100 transition">
                        <td class="px-4 py-3">
                            <img src="{{ asset($photoPath) }}" alt="photo" class="w-10 h-10 rounded-full object-cover mx-auto">
                        </td>
                        <td class="px-4 py-3 font-medium">{{ $employee->employee_id }}</td>
                        <td class="px-4 py-3">{{ $employee->first_name }} {{ $employee->last_name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $employee->department }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $employee->function }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                {{ $employee->contract_type === 'CDD' ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ $employee->contract_type }}
                            </span>
                        </td>
                        <td class="px-4 py-3">{{ $employee->end_contract_date ? Carbon::parse($employee->end_contract_date)->format('d/m/Y') : '—' }}</td>
                        <td class="px-4 py-3">
                            @if($daysLeft !== null && $daysLeft <= 20 && $daysLeft >= 0)
                                <span class="text-red-600 font-semibold">{{ $daysLeft }} jours</span>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-center">
                            {{-- CDD Review --}}
                            @if($employee->contract_type === 'CDD' && $daysLeft !== null && $daysLeft <= 20 && $daysLeft >= 0)
                                <button onclick="openReviewModal('{{ $employee->employee_id }}', '{{ $employee->end_contract_date }}')"
                                        class="px-3 py-1 bg-orange-500 text-white rounded hover:bg-orange-700 text-xs font-semibold">
                                    Review
                                </button>
                            @endif

                            {{-- CDI Termination Letter --}}
                            @if($employee->contract_type === 'CDI' && $employee->end_contract_date && $employee->end_contract_reason)
                                <button onclick="openTerminationModal({{ $employee->id }})"
                                        class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-xs font-semibold">
                                    Termination Letter
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL TERMINATION LETTER -->
    <div id="terminationModal" class="fixed inset-0 z-50 bg-black bg-opacity-40 hidden flex items-center justify-center overflow-y-auto p-4">
        <div class="bg-white rounded-lg shadow-xl w-[21cm] max-w-full max-h-[90vh] overflow-y-auto p-6 relative">
            <!-- Bouton fermer -->
            <button onclick="closeTerminationModal()"
                    class="absolute top-3 right-3 text-gray-500 hover:text-red-600 text-lg font-bold">
                ✖
            </button>

            <!-- Contenu de la lettre -->
            <div id="terminationLetterContent">
{{--                {!! view('letters.termination', ['employee' => $employee])->render() !!}--}}
            </div>

            <!-- Boutons -->
            <div class="mt-6 flex justify-end space-x-3">
                <button onclick="closeTerminationModal()"
                        class="bg-gray-500 text-white px-4 py-1.5 rounded hover:bg-gray-700 transition text-[12px]">
                    Cancel
                </button>
                <button id="downloadPDFBtn"
                        class="bg-orange-500 text-white px-4 py-1.5 rounded hover:bg-orange-700 transition text-[12px]">
                    Download
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>



    {{-- Modal Review CDD --}}
    <div id="reviewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg w-96 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Renew Contract</h2>
            <form id="reviewForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="text-sm text-gray-600 block">Current End Date</label>
                    <input id="oldEndDate" type="text" class="w-full border border-gray-300 rounded px-3 py-2 text-gray-700" readonly>
                </div>
                <div class="mb-4">
                    <label class="text-sm text-gray-600 block mb-1">New End Date</label>
                    <input id="newEndDate" name="end_contract_date" type="date" class="w-full border border-gray-300 rounded px-3 py-2 text-gray-700" required>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeReviewModal()" class="px-3 py-2 bg-gray-300 rounded hover:bg-gray-400 text-sm">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded hover:bg-orange-700 text-sm">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>


        function downloadPDF() {
            const element = document.getElementById("terminationLetterContent");

            // Ajouter un fond blanc pour le PDF
            element.style.background = "#ffffff";

            const opt = {
                margin:       [10, 10, 10, 10],
                filename:     "{{ strtoupper(($employee->first_name ?? '') . '_' . ($employee->last_name ?? '')) }}_lettre_licenciement.pdf",
                image:        { type: 'jpeg', quality: 1 },
                html2canvas:  { scale: 2, useCORS: true, logging: true },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };

            html2pdf().set(opt).from(element).save();
        }

        // Fonction pour fermer le modal
        function closeTerminationModal() {
            document.getElementById('terminationModal').classList.add('hidden');
        }

        function openTerminationModal(employeeId) {
            const modal = document.getElementById('terminationModal');
            const content = document.getElementById('terminationLetterContent');
            modal.classList.remove('hidden');
            content.innerHTML = '<p class="text-center text-gray-400 italic">Chargement...</p>';

            fetch(`/employees/${employeeId}/termination-letter`)
                .then(res => res.text())
                .then(html => content.innerHTML = html)
                .catch(() => content.innerHTML = '<p class="text-red-600 text-center">Erreur de chargement.</p>');
        }

        function closeTerminationModal() { document.getElementById('terminationModal').classList.add('hidden'); }

        function downloadPDF() {
            const element = document.getElementById('terminationLetterContainer');
            html2pdf().set({
                margin: 0.5,
                filename: 'termination_letter.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, scrollY: 0 },
                jsPDF: { unit: 'cm', format: 'a4', orientation: 'portrait' },
            }).from(element).save();
        }

        function openReviewModal(employeeId, oldEndDate) {
            const modal = document.getElementById('reviewModal');
            const form = document.getElementById('reviewForm');
            const oldDateInput = document.getElementById('oldEndDate');
            const newDateInput = document.getElementById('newEndDate');
            modal.classList.remove('hidden');
            oldDateInput.value = oldEndDate ? new Date(oldEndDate).toLocaleDateString() : '—';
            newDateInput.min = oldEndDate;
            form.action = `/employees/${employeeId}/renew`;
        }

        function closeReviewModal() { document.getElementById('reviewModal').classList.add('hidden'); }
    </script>
@endsection
