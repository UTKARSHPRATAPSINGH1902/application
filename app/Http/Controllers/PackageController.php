<?php 
namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Checklist;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;

class PackageController extends Controller
{
    public function index()
    {
        $checklists = Checklist::all();
        return view('packages.index', compact('checklists'));
    }

    public function list(Request $request)
    {
        $data = Package::with('checklists')->latest()->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('storage', function ($row) {
                return $row->storage_size . ' ' . $row->storage_unit;
            })
            ->addColumn('checklists', function ($row) {
                return $row->checklists->pluck('title')->implode(', ');
            })
            ->addColumn('actions', function ($row) {
                return '<button class="btn btn-sm btn-primary editBtn" data-id="' . $row->id . '"><i class="fa fa-edit"></i></button>
                        <button class="btn btn-sm btn-danger deleteBtn" data-id="' . $row->id . '"><i class="fa fa-trash"></i></button>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'monthly_price' => 'required|numeric',
            'annual_price' => 'required|numeric',
            'max_employees' => 'required|integer',
            'storage_size' => 'required|numeric',
            'storage_unit' => 'required|string',
            'description' => 'nullable|string',
            'checklists' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $package = Package::create($request->only([
            'name', 'monthly_price', 'annual_price',
            'max_employees', 'storage_size', 'storage_unit', 'description'
        ]));

        $package->checklists()->sync($request->checklists ?? []);

        return response()->json(['status' => 'Success', 'message' => 'Package created successfully']);
    }

    public function edit($id)
    {
        $package = Package::with('checklists')->findOrFail($id);
        return response()->json([
            'data' => $package,
            'checklists' => $package->checklists->pluck('id')
        ]);
    }

    public function update(Request $request, $id)
    {
        $package = Package::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'monthly_price' => 'required|numeric',
            'annual_price' => 'required|numeric',
            'max_employees' => 'required|integer',
            'storage_size' => 'required|numeric',
            'storage_unit' => 'required|string',
            'description' => 'nullable|string',
            'checklists' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $package->update($request->only([
            'name', 'monthly_price', 'annual_price',
            'max_employees', 'storage_size', 'storage_unit', 'description'
        ]));

        $package->checklists()->sync($request->checklists ?? []);

        return response()->json(['status' => 'Updated', 'message' => 'Package updated successfully']);
    }

    public function destroy($id)
    {
        $package = Package::findOrFail($id);
        $package->checklists()->detach();
        $package->delete();

        return response()->json(['status' => 'Deleted', 'message' => 'Package deleted successfully']);
    }
}
