<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet;

//class EmployeeExport implements FromCollection
class EmployeeExport implements FromCollection,  WithHeadings, WithStyles

{
    /**
    * @return \Illuminate\Support\Collection
    */


    protected $contract_type;
    protected $status;
    protected $start_date;
    protected $end_date;

    public function __construct($contract_type = null, $status = null, $start_date = null, $end_date = null)
    {
        $this->contract_type = $contract_type;
        $this->status = $status;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function collection()
    {
        //
        $query = Employee::query();

        if ($this->contract_type) {
            $query->where('contract_type', $this->contract_type);
        }

        if ($this->status !== null && $this->status !== '') {
            $query->where('status', $this->status);
        }

        if ($this->start_date) {
            $query->whereDate('created_at', '>=', $this->start_date);
        }

        if ($this->end_date) {
            $query->whereDate('created_at', '<=', $this->end_date);
        }

        return $query->get([
            'employee_id', 'first_name', 'last_name', 'middle_name', 'personal_id', 'birth_date',
            'gender', 'marital_status', 'highest_education_level', 'nationality',
            'age', 'house_phone', 'mobile_phone', 'email', 'address1', 'address2', 'city',
            'status', 'department', 'function', 'contract_type', 'salaire_mensuel_brut',
            'created_at', 'updated_at'
        ]);
    }
    public function headings(): array
    {
        return [
            'Employee ID', 'Prénom', 'Nom', 'Autres Noms', 'ID Personnel', 'Date Naissance',
            'Genre', 'État Civil', 'Niveau Étude', 'Nationalité',
            'Âge', 'Téléphone Maison', 'Téléphone Mobile', 'Email', 'Adresse 1', 'Adresse 2', 'Ville',
            'Statut', 'Département', 'Fonction', 'Type Contrat', 'Salaire Mensuel Brut',
            'Créé le', 'Mis à jour le'
        ];
    }



    public function styles(Worksheet|\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
    {

        $sheet->getStyle('A1:U1')->getFont()->setBold(true);
        $sheet->getStyle('A1:U1')->getAlignment()->setHorizontal('center');

        return [];
    }


}
