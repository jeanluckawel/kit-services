@extends('layouts.app')

@section('content')
    <style>
        .orange-btn {
            background-color: #f97316; /* orange-500 */
            color: white;
            font-weight: bold;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 0.25rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .orange-btn:hover {
            background-color: #ea580c; /* orange-600 */
        }

        .select-style, .input-style {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            background-color: #f9fafb;
            transition: border-color 0.3s ease;
        }

        .select-style:focus, .input-style:focus {
            border-color: #f97316;
            outline: none;
            background-color: #fff;
        }
    </style>

    <div class="max-w-xl mx-auto mt-10">
        <h2 class="text-2xl font-semibold mb-6">Exporter les employés</h2>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('employees.export') }}" method="GET"
              class="bg-white p-6 rounded shadow-md border border-gray-200">


            <div class="mb-4">
                <label for="contract_type" class="block mb-2 text-sm font-medium text-gray-700">Type de contrat :</label>
                <select name="contract_type" id="contract_type" class="select-style">
                    <option value="">-- Tous les types --</option>
                    <option value="CDI">CDI</option>
                    <option value="CDD">CDD</option>
                    <option value="Stage">Stage</option>
                    <option value="Autre">Autre</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="status" class="block mb-2 text-sm font-medium text-gray-700">Statut :</label>
                <select name="status" id="status" class="select-style">
                    <option value="">-- Tous --</option>
                    <option value="1">Actif</option>
                    <option value="0">Inactif</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="start_date" class="block mb-2 text-sm font-medium text-gray-700">Date de début :</label>
                <input type="date" name="start_date" id="start_date" class="input-style">
            </div>

            <div class="mb-4">
                <label for="end_date" class="block mb-2 text-sm font-medium text-gray-700">Date de fin :</label>
                <input type="date" name="end_date" id="end_date" class="input-style">
            </div>

            <button type="submit" class="orange-btn w-full">Exporter</button>
        </form>
    </div>
@endsection
