<?php
namespace App\Http\Controllers;
use App\Models\Checklist;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ChecklistController extends Controller
{
    public function index()
    {
        return view('checklist.index');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Checklist::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    return '
                        <button class="btn btn-sm btn-primary editBtn" data-id="'.$row->id.'"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-danger deleteBtn" data-id="'.$row->id.'"><i class="fas fa-trash"></i></button>
                    ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $request->validate(['title' => 'required|string|max:255|unique:checklists,title']);
        Checklist::create(['title' => $request->title]);
        return response()->json(['status' => 'Success', 'message' => 'Checklist created successfully.']);
    }

    public function edit($id)
    {
        $data = Checklist::findOrFail($id);
        return response()->json(['data' => $data]);
    }

    public function update(Request $request, $id)
    {
        $request->validate(['title' => 'required|string|max:255|unique:checklists,title,'.$id]);
        Checklist::findOrFail($id)->update(['title' => $request->title]);
        return response()->json(['status' => 'Success', 'message' => 'Checklist updated successfully.']);
    }

    public function destroy($id)
    {
        Checklist::findOrFail($id)->delete();
        return response()->json(['status' => 'Success', 'message' => 'Checklist deleted successfully.']);
    }
}

