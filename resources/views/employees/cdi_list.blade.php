@extends('layouts.app')

@section('title', 'Kit Service | Employee List Cdd')

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

@section('content')
    <div class="max-w-6xl mx-auto mt-10 bg-white p-6 rounded-lg shadow-sm h-[calc(100vh-100px)] flex flex-col">

        <!-- HEADER FIXE -->
        <!-- HEADER FIXE -->
        @include('employees.partial.header')
        <!-- LISTE DES EMPLOYÉS SCROLLABLE -->
        <div class="overflow-y-auto flex-1 pr-2">
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" x-data="{ searchQuery: '' }">
                @foreach($employees as $employee)
                    <div class="w-full bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300"
                         x-show="searchQuery === '' || '{{ strtolower($employee->first_name.' '.$employee->last_name.' '.$employee->employee_id) }}'.includes(searchQuery.toLowerCase())">

                        <!-- Photo de l'employé -->
                        <div class="w-full h-40 bg-gray-100 flex items-center justify-center overflow-hidden">
                            @php
                                $gender = strtolower($employee->gender);
                                $photoPath = $gender === 'female' ? 'assets/profil/female.png' : ($gender === 'male' ? 'assets/profil/male.png' : 'assets/profil/other.png');
                            @endphp
                            <img src="{{ asset($photoPath) }}" alt="{{ $employee->first_name }}" class="max-h-full max-w-full object-contain"/>
                        </div>

                        <!-- Infos -->
                        <div class="p-4 text-center">
                            <a href="{{ route('employees.show', [$employee->employee_id]) }}">
                                <h3 class="text-sm font-bold text-gray-800 truncate">{{ $employee->first_name }} {{ $employee->last_name }}</h3>
                                <p class="text-[11px] text-gray-500 mb-2">ID: {{ $employee->employee_id }}</p>
                                <div class="text-left text-gray-600 text-[11px] space-y-1 mb-3">
                                    <p><strong>Department:</strong> {{ $employee->department }}</p>
                                    <p><strong>Date debut:</strong> {{ $employee->created_at->format('d-m-Y') }}</p>
                                </div>
                            </a>

                            <!-- Buttons -->
                            <!-- Buttons -->
                            <div class="flex flex-col sm:flex-row justify-center gap-2 mt-2">
                                <button onclick="openEndContractModal('{{ $employee->employee_id }}')"
                                        class="flex-1 px-3 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600 transition text-center">
                                    End contract
                                </button>
                            </div>

                            <!-- Modal -->
                            <div id="endContractModal"
                                 class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
                                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-96 p-6">
                                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 text-center">
                                        End Contract
                                    </h2>
                                    <form id="endContractForm" method="POST">
                                        @csrf
                                        @method('PATCH') <!-- ou PUT selon ta route -->

                                        <div class="mb-4">
                                            <label for="end_contract_date" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">End Date</label>
                                            <input type="date" name="end_contract_date" id="end_contract_date" required
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
                                        </div>

                                        <div class="mb-4">
                                            <label for="departure_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Reason for Departure</label>
                                            <textarea name="departure_reason" id="departure_reason" rows="3" required
                                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500"></textarea>
                                        </div>

                                        <div class="flex justify-center gap-3">
                                            <button type="button" onclick="closeEndContractModal()"
                                                    class="px-4 py-2 bg-gray-300 text-gray-800 text-sm rounded hover:bg-gray-400 transition">
                                                Cancel
                                            </button>
                                            <button type="submit"
                                                    class="px-4 py-2 bg-red-500 text-white text-sm rounded hover:bg-red-600 transition">
                                                Confirm
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- JS -->
                            <script>
                                function openEndContractModal(employeeId) {
                                    const modal = document.getElementById('endContractModal');
                                    const form = document.getElementById('endContractForm');
                                    form.action = `/employees/${employeeId}/end-contract`; // route à créer
                                    modal.classList.remove('hidden');
                                }

                                function closeEndContractModal() {
                                    const modal = document.getElementById('endContractModal');
                                    modal.classList.add('hidden');
                                }
                            </script>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        function filterEmployees() {
            const query = document.querySelector('input[x-model="searchQuery"]').value.toLowerCase();
            document.querySelectorAll('[x-show]').forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(query) ? '' : 'none';
            });
        }
    </script>
@endsection
