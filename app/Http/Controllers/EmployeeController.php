<?php
namespace App\Http\Controllers;

use App\Models\{Department, Employee};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmployeeController
{
    public function index(Request $request)
    {
        $request->validate(['search' => 'nullable|string|max:255', 'department' => 'nullable|string|max:100',
            'department_id' => 'nullable|integer|exists:departments,id', 'page' => 'integer|min:1',
            'per_page' => 'integer|between:1,100']);
        $query = Employee::with('department');
        if ($request->filled('search')) {
            $pattern = '%'.$request->input('search').'%';
            $query->where(function ($q) use ($pattern) {
                $q->where('first_name', 'like', $pattern)->orWhere('last_name', 'like', $pattern)
                    ->orWhere('email', 'like', $pattern);
            });
        }
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        if ($request->filled('department')) {
            $query->whereHas('department', fn ($q) => $q->where('name', $request->department));
        }
        return $query->orderBy('last_name')->orderBy('id')->paginate($request->integer('per_page', 10))->withQueryString();
    }

    private function fields(Request $request, ?Employee $employee = null): array
    {
        $presence = $request->isMethod('patch') ? 'sometimes' : 'required';
        $data = $request->validate([
            'first_name' => [$presence, 'required', 'string', 'max:100'],
            'last_name' => [$presence, 'required', 'string', 'max:100'],
            'email' => [$presence, 'required', 'email', 'max:255', Rule::unique('employees')->ignore($employee?->id)],
            'position' => [$presence, 'required', 'string', 'max:100'],
            'department_id' => ['sometimes', 'required', 'integer', 'exists:departments,id'],
            'department' => ['sometimes', 'required', 'string', 'max:100', 'exists:departments,name'],
        ]);
        if (!isset($data['department_id']) && isset($data['department'])) {
            $data['department_id'] = Department::where('name', $data['department'])->firstOrFail()->id;
        }
        if (!$request->isMethod('patch') && !isset($data['department_id'])) {
            $request->validate(['department_id' => 'required']);
        }
        unset($data['department']);
        return $data;
    }

    public function store(Request $request)
    {
        $employee = Employee::create($this->fields($request));
        return response()->json(['data' => $employee->load('department')], 201);
    }

    public function show(Employee $employee)
    {
        return ['data' => $employee->load('department')];
    }

    public function update(Request $request, Employee $employee)
    {
        $employee->update($this->fields($request, $employee));
        return $this->show($employee);
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return ['message' => 'Employee deleted.'];
    }
}
