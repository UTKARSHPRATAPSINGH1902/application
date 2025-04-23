<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller
{    public function index()
    {
        $subscribers = Subscriber::with('package')->get();
        return view('subscribers.index', compact('subscribers'));
    }
    
    public function destroy($id)
    {
        $subscriber = Subscriber::findOrFail($id);
        $subscriber->delete();
    
        return response()->json(['status' => 'success', 'message' => 'Subscriber deleted successfully.']);
    }
    
    public function edit($id)
{
    $subscriber = Subscriber::findOrFail($id);
    return response()->json($subscriber); // For AJAX edit form
}
public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        'phone' => 'required'
    ]);

    $subscriber = Subscriber::findOrFail($id);
    $subscriber->update($request->only('name', 'email', 'phone'));

    return response()->json(['status' => 'success']);
}
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'package_id' => 'required|exists:packages,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:subscribers,email',
            'phone' => 'required|string|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Save subscriber
        $subscriber = Subscriber::create([
            'package_id' => $request->package_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'You have successfully subscribed!',
            'data' => $subscriber
        ]);
    }
}
