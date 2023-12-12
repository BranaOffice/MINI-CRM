<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

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

use App\Models\Company;


class CompanyController extends Controller
{
    public function index()
    {
        return view('manage_companies');
    }


    public function createOrUpdateCompany(Request $request)
    {
        // Validation rules
        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('companies')->ignore($request->input('id')),
            ],
            'website' => 'required|url|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('companies')->ignore($request->input('id')),
            ],
            'companyLogo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:min_width=100,min_height=100',
        ];
    
        $this->validate($request, $rules);
    
        $data = [
            'name' => $request->input('name'),
            'website' => $request->input('website'),
            'email' => $request->input('email'),
        ];
    
        if ($request->hasFile('companyLogo')) {
            $image = $request->file('companyLogo');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
    
            // Store the image in storage/app/public
            $image->storeAs('public', $imageName);
    
            $data['logo'] = $imageName;
        }
    
        $formType = $request->input('formType');
        $id = $request->input('id');
    
        if ($formType == 'edit' && $id > 0) {
            Company::where('id', $id)->update($data);
        } else {
            Company::create($data);
        }
    
        return 'success';
    }
    
    public function companiesPaginate()
    {
        $query = Company::query();
        return DataTables::of($query)->addIndexColumn()->make(true);
    }
    function getCompanyById(Request $request)
    {
        $id = $request->input('id');
        $company = Company::where('id', $id)->first();
        if ($company) {
            return response()->json($company);
        } else {
            return response()->json(['error' => 'Company not found'], 404);
        }
    }
    function deleteCompany(Request $request)
    {
        $id = $request->input('id');
        try {
            $company = Company::find($id);
            if ($company) {
                if ($company->employee()->count() > 0) {
                    return json_encode([
                        'statusCode' => 503,
                        'message' => 'Cannot delete company with related records.!',
                    ]);
                }
                // If no related records, delete the company
                $company->delete();
                return json_encode([
                    'statusCode' => 200,
                    'message' => 'Company deleted successfully.!',
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
