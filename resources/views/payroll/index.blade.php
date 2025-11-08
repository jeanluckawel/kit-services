@php use Illuminate\Support\Carbon; @endphp
@extends('layouts.app')

@section('title', 'Kit Service | Payroll List')

@section('content')
    <div class="max-w-7xl mx-auto mt-10 bg-white p-6 rounded-lg shadow-sm h-[calc(100vh-100px)] flex flex-col">

        <!-- HEADER -->
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-xl font-semibold text-gray-800">Liste des employés - Paie</h1>

            <a href="{{ route('employees.create') }}"
               class="orange-btn flex items-center gap-2 text-sm">
                ➕ Nouveau Employé
            </a>
        </div>

        <!-- TABLE PAYROLL -->
        <div class="overflow-y-auto flex-1 mt-4 border border-gray-200 rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 sticky top-0">
                <tr class="text-gray-700 uppercase text-xs">
                    <th class="px-4 py-3 text-left">Photo</th>
                    <th class="px-4 py-3 text-left">Matricule</th>
                    <th class="px-4 py-3 text-left">Nom complet</th>
                    <th class="px-4 py-3 text-left">Département</th>
                    <th class="px-4 py-3 text-left">Fonction</th>
                    <th class="px-4 py-3 text-left">Echelon</th>
                    <th class="px-4 py-3 text-left">Niveau</th>
                    <th class="px-4 py-3 text-center">Action</th>
                </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                @foreach($employees as $employee)
                    @php
                        $gender = strtolower($employee->gender);
                        $photoPath = $gender === 'female'
                            ? 'assets/profil/female.png'
                            : ($gender === 'male' ? 'assets/profil/male.png' : 'assets/profil/other.png');
                    @endphp

                    <tr class="hover:bg-gray-100 transition">
                        <td class="px-4 py-3">
                            <img src="{{ asset($photoPath) }}" alt="photo" class="w-10 h-10 rounded-full object-cover mx-auto">
                        </td>
                        <td class="px-4 py-3 font-medium">{{ $employee->employee_id }}</td>
                        <td class="px-4 py-3">{{ $employee->first_name }} {{ $employee->last_name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $employee->department ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $employee->function ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $employee->echelon ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $employee->niveau ?? '—' }}</td>

                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('payroll.oneEmployee', $employee->employee_id) }}">
                                <button class="px-4 py-2 bg-orange-500 text-white text-xs font-semibold rounded hover:bg-orange-600 transition">
                                    💰 Payer
                                </button>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

<style>
    .orange-btn {
        background-color: #f97316;
        color: white;
        font-weight: bold;
        padding: 0.5rem 1rem;
        border-radius: 0.25rem;
        transition: background-color 0.3s;
    }

    .orange-btn:hover {
        background-color: #ea580c;
    }
</style>
