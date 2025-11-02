<?php

namespace App\Http\Controllers;

use App\Exports\EmployeeExport;
use App\Mail\NewEmployeeNotification;
use App\Models\department;
use App\Models\echelon;
use App\Models\Employee;
use App\Models\EndContract;
use App\Models\fonction;
use App\Models\niveau;
use App\Models\salary_grid;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function formExport()
    {
        return view('employees.export');
    }
    public function all_employee()
    {
        $employees = Employee::all();

        $employeesAllCount = Employee::where('status', 1)->count();
        $employeeesAllCdi = Employee::where('contract_type', 'CDI')

            ->count();
        $count = $employees->whereNotNull('end_contract_date')->count();

        return view('employees.all_employee', compact('employees', 'employeesAllCount', 'employeeesAllCdi', 'count'));
    }



    public function index()
    {
        // Employés actifs
        $employees = Employee::where('status', 1)
            ->orderByDesc('created_at')
            ->get();

        $employeesAllCount = Employee::where('status', 1)->count();
        $employeeesAllCdi = Employee::where('contract_type', 'CDI')
        ->where('status', 1)->count();

        // Employés avec date de fin de contrat renseignée
        $count = $employees->whereNotNull('end_contract_date')->count();

        return view('employees.index', compact('employees', 'count', 'employeesAllCount', 'employeeesAllCdi'));
    }

    public function end_list()
    {
        // CDD actifs
        $employees = Employee::where('contract_type', 'CDD')
            ->where('status', 1)
            ->orderByDesc('created_at')
            ->get();

        $employeesAllCount = Employee::where('status', 1)->count();
        $employeeesAllCdi = Employee::where('contract_type', 'CDI')
            ->where('status', 1)->count();

        $count = $employees->whereNotNull('end_contract_date')->count();

        return view('employees.end_list', compact('employees', 'count', 'employeesAllCount', 'employeeesAllCdi'));
    }

    public function archive_list()
    {
        // Tous les employés ayant une date de fin de contrat
        $employees = Employee::whereNotNull('end_contract_date')
            ->orderByDesc('created_at')
            ->get();

        $employeesAllCount = Employee::where('status', 1)->count();
        $employeeesAllCdi = Employee::where('contract_type', 'CDI')
        ->where('status', 1)->count();

        $count = $employees->count();

        return view('employees.archive_list', compact('employees', 'count', 'employeesAllCount', 'employeeesAllCdi'));
    }

    public function end_list_cdi()
    {
        // CDI actifs
        $employees = Employee::where('contract_type', 'CDI')
            ->where('status', 1)
            ->orderByDesc('created_at')
            ->get();

        $employeesAllCount = Employee::where('status', 1)->count();
        $employeeesAllCdi = Employee::where('contract_type', 'CDI')
            ->where('status', 1)->count();

        $count = $employees->whereNotNull('end_contract_date')->count();

        return view('employees.cdi_list', compact('employees', 'count', 'employeesAllCount', 'employeeesAllCdi'));
    }


    public function restartContart()
    {

        $employees = EndContract::where('status', 0)
            ->where('end_contract_date', '>', \Carbon\Carbon::now())
            ->get();

        return view('employees.restartContart', compact('employees'));
    }





    public function end_list_certificat($employee_id)
    {
        $employee = Employee::where('employee_id', $employee_id)->firstOrFail();

        return view('end_contracts.certificat',compact('employee'));

    }
    public function create()
    {
        $departments = Department::pluck('name', 'id');
        $fonctions   = Fonction::pluck('name', 'id');
        $niveaux     = Niveau::pluck('name', 'id');
        $echelons    = Echelon::pluck('name', 'id');

        // Grilles avec relations (pour JS)
        $salaryGrids = salary_grid::with(['department','fonction','niveau','echelon'])->get();

        return view('employees.create', compact(
            'departments',
            'fonctions',
            'niveaux',
            'echelons',
            'salaryGrids'
        ));
    }




    /**
     * Show the form for creating a new resource.
     */


    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $request->validate([
//            'first_name'               => 'required|string|max:255',
//            'last_name'                => 'required|string|max:255',
//            'middle_name'              => 'nullable|string|max:255',
//            'personal_id'              => 'required|string|unique:employees,personal_id',
//            'birth_date'               => 'required|date',
//            'gender'                   => 'required|in:M,F',
//            'marital_status'           => 'required|string|max:255',
//            'highest_education_level'  => 'nullable|string|max:255',
//            'nationality'              => 'nullable|string|max:255',
            'photo'                    => 'nullable|image|max:2048',
//
//            'mobile_phone'             => 'required|string|max:20',
//            'email'                    => 'required|email|max:255',
//            'address1'                 => 'required|string|max:255',
//            'address2'                 => 'nullable|string|max:255',
//            'city'                     => 'required|string|max:100',
//            'house_phone'              => 'nullable|string|max:20',
//
//            'emergency_full_name'      => 'nullable|string|max:255',
//            'emergency_relationship'   => 'nullable|string|max:255',
//            'emergency_mobile_phone'   => 'nullable|string|max:20',
//            'emergency_address'        => 'nullable|string|max:255',
//            'emergency_city'           => 'nullable|string|max:100',
//
//            'father_name'              => 'nullable|string|max:255',
//            'father_name_status'       => 'nullable|string|max:255',
//            'mother_name'              => 'nullable|string|max:255',
//            'mother_name_status'       => 'nullable|string|max:255',
//            'spouse_name'              => 'nullable|string|max:255',
//            'spouse_phone'             => 'nullable|string|max:20',
//            'spouse_birth_date'        => 'nullable|date',
//
//            'department'               => 'required|string|max:255',
//            'function'                 => 'required|string|max:255',
//            'niveau'                   => 'required|string|max:255',
//            'echelon'                  => 'required|string|max:255',
//            'contract_type'            => 'required|string|max:255',
//            'taux_horaire_brut'        => 'nullable|numeric',
//            'situation_avant_embauche' => 'nullable|string|max:255',
//            'salaire_mensuel_brut'     => 'nullable|numeric',
        'end_contract_date' => 'nullable|date',
        ]);

        // Génération automatique de l’employee_id
        $lastEmployee = Employee::orderBy('id', 'desc')->first();
        $lastNumber = $lastEmployee ? intval(substr($lastEmployee->employee_id, -5)) : 0;
        $newEmployeeId = 'KAM_KIT' . str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);

        $data = $request->all();

        // Gestion de la photo (upload direct ou base64 depuis Croppie)
        if ($request->has('photo_cropped') && !empty($request->photo_cropped)) {
            // si Croppie renvoie en base64
            $image = $request->photo_cropped;
            $image = str_replace('data:image/jpeg;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $fileName = uniqid() . '.jpg';
            \Storage::disk('public')->put("photos/$fileName", base64_decode($image));
            $data['photo'] = "photos/$fileName";
        } elseif ($request->hasFile('photo')) {
            // si upload normal
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        // Champs supplémentaires
        $data['employee_id'] = $newEmployeeId;
        $data['status'] = 1;
        $data['age'] = $this->calculateAge($data['birth_date']);

        // Création en base
        $employee = Employee::create($data);

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employé ajouté avec succès.');
    }

    /**
     * Calcule l’âge à partir de la date de naissance
     */
    private function calculateAge($birthDate)
    {
        return \Carbon\Carbon::parse($birthDate)->age;
    }


    /**
     * Display the specified resource.
     */
    public function show($employee_id)
    {
        $employee = Employee::where('employee_id', $employee_id)->firstOrFail();
        $address = $employee->address ?? null;
        $family = $employee->family ?? null;
        $child = $employee->child ?? null;

        return view('employees.show', compact('employee', 'address', 'family','child'));
    }

    public function profile($employee_id)
    {
        $employee = Employee::where('employee_id', $employee_id)->firstOrFail();

        return view('employees.profile', compact('employee'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($employee_id)
    {
        $employee = Employee::where('employee_id', $employee_id)->firstOrFail();

        $departments = department::pluck('name');
        $fonctions   = fonction::pluck('name');
        $niveaux     = niveau::pluck('name');
        $echelons    = echelon::pluck('name');

        return view('employees.edit', compact('employee','departments','fonctions','niveaux','echelons'));
    }

    public function end_list_cdd($employee_id)
    {
        $employee = Employee::where('employee_id',$employee_id)->firstOrFail();

        return view('end_contracts.cdd',compact('employee'));

    }
    public function end_service($employee_id)
    {
        $employee = Employee::where('employee_id',$employee_id)->firstOrFail();

        return view('end_contracts.end_service',compact('employee'));

    }

    public function update(Request $request, $employee_id)
    {
        $employee = Employee::where('employee_id', $employee_id)->firstOrFail();

        $data = $request->validate([
            'first_name'               => 'sometimes|nullable|string|max:255',
            'last_name'                => 'sometimes|nullable|string|max:255',
            'middle_name'              => 'sometimes|nullable|string|max:255',
            'personal_id'              => 'sometimes|nullable|string|unique:employees,personal_id,' . $employee->id,
            'birth_date'               => 'sometimes|nullable|date',
            'gender'                   => 'sometimes|nullable|in:M,F',
            'marital_status'           => 'sometimes|nullable|string|max:255',
            'highest_education_level'  => 'sometimes|nullable|string|max:255',
            'nationality'              => 'sometimes|nullable|string|max:255',
            'photo'                    => 'sometimes|nullable|image|max:2048',

            'mobile_phone'             => 'sometimes|nullable|string|max:20',
            'email'                    => 'sometimes|nullable|email|max:255',
            'address1'                 => 'sometimes|nullable|string|max:255',
            'address2'                 => 'sometimes|nullable|string|max:255',
            'city'                     => 'sometimes|nullable|string|max:100',
            'house_phone'              => 'sometimes|nullable|string|max:20',

            'emergency_full_name'      => 'sometimes|nullable|string|max:255',
            'emergency_relationship'   => 'sometimes|nullable|string|max:255',
            'emergency_mobile_phone'   => 'sometimes|nullable|string|max:20',
            'emergency_address'        => 'sometimes|nullable|string|max:255',
            'emergency_city'           => 'sometimes|nullable|string|max:100',

            'father_name'              => 'sometimes|nullable|string|max:255',
            'father_name_status'       => 'sometimes|nullable|string|max:255',
            'mother_name'              => 'sometimes|nullable|string|max:255',
            'mother_name_status'       => 'sometimes|nullable|string|max:255',
            'spouse_name'              => 'sometimes|nullable|string|max:255',
            'spouse_phone'             => 'sometimes|nullable|string|max:20',
            'spouse_birth_date'        => 'sometimes|nullable|date',

            'department'               => 'sometimes|nullable|string|max:255',
            'function'                 => 'sometimes|nullable|string|max:255',
            'niveau'                   => 'sometimes|nullable|string|max:255',
            'echelon'                  => 'sometimes|nullable|string|max:255',
            'contract_type'            => 'sometimes|nullable|string|max:255',
            'taux_horaire_brut'        => 'sometimes|nullable|numeric',
            'situation_avant_embauche' => 'sometimes|nullable|string|max:255',
            'salaire_mensuel_brut'     => 'sometimes|nullable|numeric',
        ]);

        // gestion photo
        if ($request->photo_cropped) {
            $imageName = 'employee_'.$employee->employee_id.'.jpg';
            $path = public_path('storage/employees/'.$imageName);
            file_put_contents(
                $path,
                base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->photo_cropped))
            );
            $data['photo'] = 'employees/'.$imageName;
        }

        $employee->update($data);

        return redirect()
            ->route('employees.show', $employee->employee_id)
            ->with('success', 'Employee updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($employee_id)
    {
        $employee = Employee::where('employee_id', $employee_id)->first();

        if (!$employee) {
            return redirect()->back()->with('error', 'Employee not found.');
        }


        $employee->status = 0;
        $employee->save();

        return redirect()->route('employees.index')->with('success', 'Employee deactivated successfully.');
    }


    public function downloadTemplate() : BinaryFileResponse
    {
        $file = public_path('templates/add new employee kit service .xlsx');

        return response()->download($file, 'add new employee kit service .xlsx');

    }

    public function file()
    {
        return view('file.file');

    }

    public function search(Request $request)
    {

        $employees = [];

        if ($request->has('search')) {
            $query = $request->input('search');

            $employees = Employee::where('employee_id', 'like', "%$query%")
                ->orWhere('first_name', 'like', "%$query%")
                ->orWhere('last_name', 'like', "%$query%")
                ->orWhere('middle_name', 'like', "%$query%")
                ->orWhere('personal_id', 'like', "%$query%")
                ->orWhere('department', 'like', "%$query%")
                ->get();
        }

        return view('employees.search', compact('employees'));

    }


    public function export()
    {
        return Excel::download(new EmployeeExport, 'employee.xlsx');
    }


    public function updateContractStatusAjax()
    {
        $today = \Carbon\Carbon::today();

        $expiredEmployees = Employee::where('contract_type', 'CDD')
            ->where('status', 1)
            ->whereDate('end_contract_date', '<', $today)
            ->get();

        foreach ($expiredEmployees as $employee) {
            $employee->update(['status' => 0]);
        }

        return response()->json([
            'message' => 'Statuts CDD mis à jour',
            'updated_count' => $expiredEmployees->count()
        ]);



    }



    public function endContract(Request $request, $employeeId)
    {
        $request->validate([
            'end_contract_date' => 'required|date',
            'departure_reason' => 'required|string|max:500',
        ]);

        $employee = Employee::where('employee_id', $employeeId)->firstOrFail();

        $employee->update([
            'end_contract_date' => $request->end_contract_date,
            'end_contract_reason' => $request->departure_reason,
            'status' => 0,
        ]);

        return redirect()->back()->with('success', 'Le contrat a été terminé avec succès.');
    }


    public function badge($employeeId)
    {
        $employee = Employee::where('employee_id', $employeeId)->firstOrFail();
        return view('employees.partials.badge', compact('employee'));
    }

// app/Http/Controllers/EmployeeController.php

    public function renewContract(Request $request, $employeeId)
    {
        $employee = Employee::where('employee_id', $employeeId)->firstOrFail();

        $validated = $request->validate([
            'end_contract_date' => 'required|date|after:' . $employee->end_contract_date,
        ]);

        $employee->update([
            'end_contract_date' => $validated['end_contract_date'],
        ]);

        return redirect()->back()->with('success', 'Contract renewed successfully.');
    }

    public function terminationLetter($id)
    {
        $employee = Employee::findOrFail($id);
        if(!$employee->end_contract_date || !$employee->end_contract_reason) abort(404, 'Termination info not available');
        return view('employees.partials.termination-letter', compact('employee'));
    }

}
