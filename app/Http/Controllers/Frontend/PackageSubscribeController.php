<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;

class PackageSubscribeController extends Controller
{
    public function showPackages()
    {
        $packages = Package::with('checklists')->get();
        return view('subscription.index', compact('packages'));
    }
}
