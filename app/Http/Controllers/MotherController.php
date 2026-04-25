<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Mother;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MotherController extends Controller
{
    /**
     * Store a newly created mother record in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|unique:mothers,whatsapp_number',
            'country_id' => 'nullable|exists:countries,id',
            'region_id' => 'required|exists:regions,id',
            'district_id' => 'required|exists:districts,id',
            'status' => 'required|string|in:pregnant,new_parent,trying',
            'edd_date' => 'nullable|required_if:status,pregnant|date',
            'baby_age' => 'nullable|required_if:status,new_parent|integer|min:0|max:24',
            'trying_duration' => 'nullable|required_if:status,trying|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $defaultCountryId = Country::where('iso2', 'TZ')->value('id');
            $mother = Mother::create([
                'full_name' => $request->full_name,
                'whatsapp_number' => $request->whatsapp_number,
                'country_id' => $request->country_id ?: $defaultCountryId,
                'region_id' => $request->region_id,
                'district_id' => $request->district_id,
                'status' => $request->status,
                'edd_date' => $request->edd_date,
                'baby_age' => $request->baby_age,
                'trying_duration' => $request->trying_duration,
                'current_step' => '3',
                'metadata' => [
                    'user_agent' => $request->userAgent(),
                    'ip_address' => $request->ip(),
                    'locale' => $request->input('locale', 'sw')
                ]
            ]);

            // Store info in session for Thank You page
            $locale = $request->input('locale', 'sw');
            session(['mother_name' => $mother->full_name]);
            session(['mk_number' => $mother->mk_number]);
            session(['locale' => $locale]);

            return redirect()->route('join.thanks', [
                'name' => $mother->full_name,
                'lang' => $locale,
            ]);
        } catch (\Exception $e) {
            Log::error('Mother intake save failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', $request->input('locale') === 'en' ? 'Sorry, there was an error saving your details. Please try again.' : 'Samahani, kulikuwa na tatizo wakati wa kuhifadhi taarifa zako. Tafadhali jaribu tena.')
                ->withInput();
        }
    }

    /**
     * Display the thank you page.
     */
    public function thanks()
    {
        return view('landing.thanks');
    }
}
