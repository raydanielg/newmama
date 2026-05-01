<?php

namespace App\Http\Controllers;

use App\Models\Mother;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MotherOnboardingController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $mother = $user->mother;

        if (!$mother) {
            return redirect()->route('mother.login');
        }

        if ($mother->is_onboarded) {
            return redirect()->route('mother.dashboard');
        }

        return view('mother.onboarding', compact('mother'));
    }

    public function complete(Request $request)
    {
        $user = Auth::user();
        $mother = $user->mother;

        if (!$mother) {
            return response()->json(['error' => 'Mother profile not found'], 404);
        }

        $request->validate([
            'status' => 'required|in:pregnant,new_parent,trying',
            'edd_date' => 'required_if:status,pregnant|nullable|date|after:today',
            'baby_age' => 'required_if:status,new_parent|nullable|integer|min:0',
            'trying_duration' => 'required_if:status,trying|nullable|string',
            'location_pref' => 'nullable|string',
        ]);

        $mother->update([
            'status' => $request->status,
            'edd_date' => $request->edd_date,
            'baby_age' => $request->baby_age,
            'trying_duration' => $request->trying_duration,
            'is_onboarded' => true,
        ]);

        return response()->json([
            'success' => true,
            'redirect' => route('mother.dashboard')
        ]);
    }
}
