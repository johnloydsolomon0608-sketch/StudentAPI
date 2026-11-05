<?php
namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentController
{
    public function index()
    {
        return ['data' => Department::withCount('employees')->orderBy('id')->get()];
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:100|unique:departments']);
        return response()->json(['data' => Department::create($data)], 201);
    }

    public function show(Department $department)
    {
        return ['data' => $department->loadCount('employees')];
    }

    public function update(Request $request, Department $department)
    {
        $department->update($request->validate(['name' => ['required', 'string', 'max:100',
            Rule::unique('departments')->ignore($department->id)]]));
        return $this->show($department);
    }

    public function destroy(Department $department)
    {
        try {
            $department->delete();
        } catch (QueryException $exception) {
            if (($exception->errorInfo[1] ?? null) != 1451) {
                throw $exception;
            }
            return response()->json(['message' => 'Move employees to another department first.'], 409);
        }
        return ['message' => 'Department deleted.'];
    }
}
