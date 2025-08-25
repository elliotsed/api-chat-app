<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Discussion;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class DiscussionController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'user_ids' => 'required|array|min:1|max:2', // participants
                'user_ids.*' => 'exists:users,id',
            ]);

            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthenticated'
                ], 401);
            }

            // Create discussion
            $discussion = Discussion::create();

            // Add the authenticated user + other participants
            $discussion->users()->attach(array_merge([$request->user()->id], $request->user_ids));

            return response()->json([
                'response_code' => 201,
                'status'        => 'success',
                'message'       => 'Discussion created',
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Create Discussion Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Unable to create discussion',
            ], 500);
        }
    }

    public function index(Request $request)
    {
        try {
            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthenticated'
                ], 401);
            }

            // Fetch discussions with participants
            $discussions = $user->discussions()->with('users')->get();

            return response()->json([
                'response_code' => 200,
                'status'        => 'success',
                'data'          => $discussions,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Fetch Discussions Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Unable to fetch discussions',
            ], 500);
        }
    }
}
