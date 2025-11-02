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
                                $photoPath = $gender === 'female' ||  $gender === 'f'  ||  $gender === 'F'   ? 'assets/profil/female.png' : ($gender === 'male' || $gender === 'M'  ||  $gender === 'm'   ?  'assets/profil/male.png' : 'assets/profil/others.jpg');
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
                                    <p><strong>Date Fin:</strong> {{ $employee->end_contract_date ? \Carbon\Carbon::parse($employee->end_contract_date)->format('d-m-Y') : 'N/A' }}</p>
                                </div>
                            </a>

                            <!-- Buttons -->
                            <div class="flex flex-col sm:flex-row justify-center gap-2 mt-2">
                                <!-- End of Service Certificate -->
                                <a href="{{ route('employees.end-service', $employee->employee_id) }}"
                                   title="Certificate of End of Service"
                                   class="flex-1 px-3 py-1 bg-orange-500 text-white text-sm font-semibold rounded hover:bg-orange-600 transition text-center">
                                    End of Service
                                </a>

                                <!-- Fixed-Term Contract End Notice -->
                                <a href="{{ route('employees.end_list_cdd', $employee->employee_id) }}"
                                   title="Notification of End of Fixed-Term Contract"
                                   class="flex-1 px-3 py-1 bg-red-500 text-white text-sm font-semibold rounded hover:bg-red-600 transition text-center">
                                    End Notice
                                </a>

                                <!-- Certificate of Achievement -->
                                <a href="{{ route('employees.end_list_certificat', $employee->employee_id) }}"
                                   title="Certificate of Achievement"
                                   class="flex-1 px-3 py-1 bg-blue-500 text-white text-sm font-semibold rounded hover:bg-blue-600 transition text-center">
                                    Achievement
                                </a>
                            </div>

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
