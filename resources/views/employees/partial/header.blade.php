<div class="sticky top-0 bg-white z-20 pb-4 pt-2">
    <!-- Cartes résumé -->
    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-4">
        <!-- Total Employees -->
        <a href="{{ route('employees.index') }}">
            <div class="flex items-center p-2 sm:p-3 bg-white rounded-lg shadow-xs">
                <div class="p-2 sm:p-3 mr-2 sm:mr-3 text-orange-500 bg-orange-100 rounded-full">
                    <i class='bx bx-group text-lg sm:text-xl'></i>
                </div>
                <div>
                    <p class="mb-1 text-xs sm:text-sm font-medium text-gray-600">Total Employees</p>
                    <p class="text-sm sm:text-base font-semibold text-gray-700">{{ $employeesAllCount ?? 'N/A' }}</p>
                </div>
            </div>
        </a>

        <!-- Employee CDD -->
        <a href="{{ route('employees.end_list') }}">
            <div class="flex items-center p-2 sm:p-3 bg-white rounded-lg shadow-xs">
                <div class="p-2 sm:p-3 mr-2 sm:mr-3 text-blue-500 bg-blue-100 rounded-full">
                    <i class='bx bx-time-five text-lg sm:text-xl'></i>
                </div>
                <div>
                    <p class="mb-1 text-xs sm:text-sm font-medium text-gray-600">Employee CDD</p>
                    <p class="text-sm sm:text-base font-semibold text-gray-700">{{ $count ?? 'N/A' }}</p>
                </div>
            </div>
        </a>

        <!-- Employee CDI -->
        <a href="{{ route('employees.end-list-cdi') }}">
            <div class="flex items-center p-2 sm:p-3 bg-white rounded-lg shadow-xs">
                <div class="p-2 sm:p-3 mr-2 sm:mr-3 text-green-500 bg-green-100 rounded-full">
                    <i class='bx bx-briefcase text-lg sm:text-xl'></i>
                </div>
                <div>
                    <p class="mb-1 text-xs sm:text-sm font-medium text-gray-600">Employee CDI</p>
                    <p class="text-sm sm:text-base font-semibold text-gray-700">{{ $employeeesAllCdi ?? 'N/A' }}</p>
                </div>
            </div>
        </a>

        <!-- Others -->
        <a href="{{ route('employees.all') }}">
            <div class="flex items-center p-2 sm:p-3 bg-white rounded-lg shadow-xs">
                <div class="p-2 sm:p-3 mr-2 sm:mr-3 text-purple-500 bg-purple-100 rounded-full">
                    <i class='bx bx-category text-lg sm:text-xl'></i>
                </div>
                <div>
                    <p class="mb-1 text-xs sm:text-sm font-medium text-gray-600">Others</p>
                    <p class="text-sm sm:text-base font-semibold text-gray-700">{{ $others ?? 'N/A' }}</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Navigation + Recherche -->
    <div class="flex flex-col sm:flex-row justify-between items-center gap-2 mb-4">
        <!-- Navigation -->
        <nav class="flex items-center space-x-2 text-sm sm:text-base text-gray-500">
            <a href="{{ route('dashboard') }}" class="hover:text-orange-600 transition">Dashboard</a>
            <span class="text-gray-300">/</span>
            <span class="text-orange-600 font-semibold">Total Employee</span>
        </nav>

        <!-- Barre de recherche compacte -->
        <div class="w-52 sm:w-64">
            <input type="text" placeholder="Search by name or ID"
                   class="w-full p-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400"
                   x-model="searchQuery" @input="filterEmployees()">
        </div>
    </div>
</div>
