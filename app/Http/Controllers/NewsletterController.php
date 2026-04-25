<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please provide a valid email address.',
            ], 422);
        }

        try {
            $exists = DB::table('newsletters')->where('email', $request->email)->first();

            if ($exists) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'You are already subscribed to our newsletter!',
                ]);
            }

            DB::table('newsletters')->insert([
                'email' => $request->email,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Thank you for subscribing to Malkia Konnect!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred. Please try again later.',
            ], 500);
        }
    }
}
