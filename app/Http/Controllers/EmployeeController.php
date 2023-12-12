<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;


use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;

use App\Models\Employee;
use App\Models\Company;



class EmployeeController extends Controller
{
    public function index()
    {
        $minutes = 5;
        $companies = Cache::remember('company_list', $minutes, function () {
            return Company::select('id', 'name')->get();
        });

        return view('manage_employee', ['companies' => $companies]);
    }


    public function createOrUpdateEmployee(Request $request)
    {
        // Validation rules
        $rules = [
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'dropCompanyId' => 'required',
            'phone' => 'required|regex:/^[0-9+\(\)#\.\s\-\,ext]+$/',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('employees')->ignore($request->input('id')),
            ],
        ];

        $this->validate($request, $rules);

        $data = [
            'first_name' => $request->input('fname'),
            'last_name' => $request->input('lname'),
            'company_id' => $request->input('dropCompanyId'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
        ];

        $formType = $request->input('formType');
        $id = $request->input('id');
        if ($formType == 'edit' && $id > 0) {
            Employee::where('id', $id)->update($data);
        } else {
            Employee::create($data);
        }

        return 'success';
    }

    public function employeesPaginate()
    {
        $query = Employee::query();
        return DataTables::of($query)->addIndexColumn()->make(true);
    }
    function getEmployeeById(Request $request)
    {
        $id = $request->input('id');
        $employee = Employee::where('id', $id)->first();
        if ($employee) {
            return response()->json($employee);
        } else {
            return response()->json(['error' => 'Employee not found'], 404);
        }
    }
    function deleteEmployee(Request $request)
    {
        $id = $request->input('id');
        try {
            $employee = Employee::find($id);
            if ($employee) {
                // If no related records, delete the employee
                $employee->delete();
                return json_encode([
                    'statusCode' => 200,
                    'message' => 'Employee deleted successfully.!',
                ]);
            }
        } catch (\Exception $th) {
            // . $th->getMessage()
            echo json_encode([
                'statusCode' => 503,
                'message' => 'Failed to delete the row!',
            ]);
        }
    }
}
