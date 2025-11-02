


@php use Illuminate\Support\Carbon; @endphp
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
                    @php
                        $borderClass = '';
                        $daysLeft = null;

                    if ($employee->contract_type === 'CDD' && $employee->end_contract_date) {
                        $endDate = \Carbon\Carbon::parse($employee->end_contract_date);
                        $now = \Carbon\Carbon::now();

                        $daysLeft = $now->diffInDays($endDate, false); // déjà un entier
                        $daysLeft = intval($daysLeft); // s'assure que c'est bien un entier

                            if ($daysLeft <= 30 && $daysLeft >= 0) {

                                $borderClass = 'border-2 border-red-500';
                            }
                        }
                    @endphp

                    <div class="w-full bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 {{ $borderClass }}"
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
                                    <p><strong>Function:</strong> {{ $employee->function}}</p>
                                    <p><strong>Phone:</strong> {{ $employee->mobile_phone ??  'N/A' }}</p>



                                    @if($employee->contract_type === 'CDD')
                                        <p><strong>Start:</strong> {{ \Carbon\Carbon::parse($employee->created_at)->format('d/m/Y') }}</p>
                                        <p><strong>End:</strong> {{ \Carbon\Carbon::parse($employee->end_contract_date)->format('d/m/Y') }}</p>
                                        @if($daysLeft !== null && $daysLeft <= 30 && $daysLeft >= 0)
                                            <p class="text-red-500 flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ $daysLeft }}
                                                @php
                                                    if ($daysLeft === 1) {
                                                        echo 'day left';
                                                    } else {
                                                        echo 'days left';
                                                    }
                                                @endphp
                                            </p>
                                        @endif
                                    @endif
                                </div>
                            </a>

                            <!-- Buttons -->
                            <!-- Buttons -->
                            <!-- Buttons -->
                            <div class="flex flex-col sm:flex-row justify-center gap-2 mt-2">
                                <!-- Add Child -->
                                <a href="{{ route('children.create', $employee->employee_id) }}"
                                   class="flex-1 px-3 py-1 bg-orange-500 text-white text-sm rounded hover:bg-orange-600 transition text-center">
                                    Add child
                                </a>

                                <!-- Badge -->
                                <!-- Bouton Badge -->
                                <button
                                    onclick="openBadgeModal('{{ $employee->employee_id }}')"
                                    class="flex-1 px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600 transition text-center">
                                    Badge
                                </button>

                                <!-- Modal Badge -->
                                <div id="badgeModal"
                                     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
                                    <div class="bg-white rounded-lg shadow-lg w-[95%] max-w-3xl relative overflow-y-auto max-h-[90vh]">
                                        <!-- Bouton fermer -->
                                        <button onclick="closeBadgeModal()" class="absolute top-2 right-2 text-gray-600 hover:text-red-500">
                                            ✕
                                        </button>

                                        <!-- Contenu du badge -->
                                        <div id="badgeModalContent" class="p-4 text-center">
                                            <p class="text-gray-500 text-sm">Chargement...</p>
                                        </div>
                                    </div>
                                </div>

                                <script>
                                    function openBadgeModal(employeeId) {
                                        const modal = document.getElementById('badgeModal');
                                        const content = document.getElementById('badgeModalContent');

                                        modal.classList.remove('hidden');
                                        content.innerHTML = '<p class="text-gray-500 text-sm">Chargement...</p>';

                                        fetch(`/employees/${employeeId}/badge`)
                                            .then(response => response.text())
                                            .then(html => {
                                                content.innerHTML = html;
                                            })
                                            .catch(() => {
                                                content.innerHTML = '<p class="text-red-500 text-sm">Erreur de chargement du badge.</p>';
                                            });
                                    }

                                    function closeBadgeModal() {
                                        document.getElementById('badgeModal').classList.add('hidden');
                                    }
                                </script>




                                <!-- Delete Button triggers modal -->
                                <button onclick="openDeleteModal('{{ $employee->employee_id }}')"
                                        class="flex-1 px-3 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600 transition text-center">
                                    Delete
                                </button>
                            </div>

                            <!-- Modal (hidden by default) -->
                            <div id="deleteModal"
                                 class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
                                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-80 p-6 text-center">
                                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">
                                        Confirm Deletion
                                    </h2>
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-5">
                                        Are you sure you want to delete this employee? This action cannot be undone.
                                    </p>
                                    <div class="flex justify-center gap-3">
                                        <!-- Cancel -->
                                        <button onclick="closeDeleteModal()"
                                                class="px-4 py-2 bg-gray-300 text-gray-800 text-sm rounded hover:bg-gray-400 transition">
                                            Cancel
                                        </button>

                                        <!-- Confirm Delete -->
                                        <form id="deleteForm" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-4 py-2 bg-red-500 text-white text-sm rounded hover:bg-red-600 transition">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- JS Modal Control -->
                            <script>
                                function openDeleteModal(employeeId) {
                                    const modal = document.getElementById('deleteModal');
                                    const form = document.getElementById('deleteForm');
                                    form.action = `/employees/${employeeId}`;
                                    modal.classList.remove('hidden');
                                }

                                function closeDeleteModal() {
                                    const modal = document.getElementById('deleteModal');
                                    modal.classList.add('hidden');
                                }
                            </script>


                        </div>
                    </div>
                @endforeach


            </div>
        </div>
    </div>


@endsection

<script>

    function filterEmployees() {
        const query = document.querySelector('input[x-model="searchQuery"]').value.toLowerCase();
        document.querySelectorAll('[x-show]').forEach(card => {
            const text = card.textContent.toLowerCase();
            card.style.display = text.includes(query) ? '' : 'none';
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Appel initial dès que la page est chargée
        updateCddStatus();

        // Puis toutes les heures (3600000 ms)
        setInterval(updateCddStatus, 3600000);

        function updateCddStatus() {
            fetch('{{ route("employees.updateCddStatusAjax") }}')
                .then(response => response.json())
                .then(data => {
                    console.log('Mise à jour CDD :', data.updated_count, 'employés');
                })
                .catch(err => console.error('Erreur mise à jour CDD:', err));
        }
    });
</script>


