<?php

namespace App\Livewire;

use App\Models\Employee;
use Livewire\Component;

class EmployeeManager extends Component
{
    // Form fields
    public $employeeId = null;
    public $name = '';
    public $email = '';
    public $pps_number = '';
    public $department = '';
    public $job_title = '';
    public $hourly_rate = 0.00;
    public $salary = 0.00;
    public $active = true;
    public $tax_credit = 3750.00;
    public $cutoff_point = 42000.00;

    // Search & pagination
    public $search = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'pps_number' => 'required|string|max:15',
        'department' => 'required|string|max:255',
        'job_title' => 'required|string|max:255',
        'hourly_rate' => 'required|numeric|min:0',
        'salary' => 'required|numeric|min:0',
        'active' => 'boolean',
        'tax_credit' => 'required|numeric|min:0',
        'cutoff_point' => 'required|numeric|min:0',
    ];

    public function mount()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->employeeId = null;
        $this->name = '';
        $this->email = '';
        $this->pps_number = '';
        $this->department = '';
        $this->job_title = '';
        $this->hourly_rate = 0.00;
        $this->salary = 0.00;
        $this->active = true;
        $this->tax_credit = 3750.00;
        $this->cutoff_point = 42000.00;
        $this->resetValidation();
    }

    public function loadEmployee($id)
    {
        $employee = Employee::find($id);
        if ($employee) {
            $this->employeeId = $employee->id;
            $this->name = $employee->name;
            $this->email = $employee->email;
            $this->pps_number = $employee->pps_number;
            $this->department = $employee->department;
            $this->job_title = $employee->job_title;
            $this->hourly_rate = floatval($employee->hourly_rate);
            $this->salary = floatval($employee->salary);
            $this->active = (bool) $employee->active;
            $this->tax_credit = floatval($employee->tax_credit);
            $this->cutoff_point = floatval($employee->cutoff_point);
        }
    }

    public function save()
    {
        // Custom dynamic validation rule for unique email/pps_number on update
        $rules = $this->rules;
        $rules['email'] = 'required|email|max:255|unique:employees,email,' . ($this->employeeId ?? 'NULL');
        $rules['pps_number'] = 'required|string|max:15|unique:employees,pps_number,' . ($this->employeeId ?? 'NULL');

        $validatedData = $this->validate($rules);

        Employee::updateOrCreate(
            ['id' => $this->employeeId],
            $validatedData
        );

        $this->resetForm();
        session()->flash('message', $this->employeeId ? 'Employee updated successfully.' : 'Employee created successfully.');
        
        // Dispatch event to refresh list or other components
        $this->dispatch('employee-updated');
    }

    public function delete($id)
    {
        Employee::destroy($id);
        session()->flash('message', 'Employee deleted successfully.');
        $this->dispatch('employee-updated');
        if ($this->employeeId == $id) {
            $this->resetForm();
        }
    }

    public function render()
    {
        $query = Employee::query();

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('pps_number', 'like', '%' . $this->search . '%')
                  ->orWhere('department', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.employee-manager', [
            'employees' => $query->latest()->get()
        ]);
    }
}
