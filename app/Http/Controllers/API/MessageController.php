<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Discussion;
use App\Models\Message;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;


class MessageController extends Controller
{
    public function storeMessage(Request $request, $id)
    {
        try {
            // Validation simple
            $request->validate([
                'content' => 'required|string|min:1|max:1000',
            ]);

            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthenticated',
                ], 401);
            }

            // Vérifie si la discussion existe
            $discussion = Discussion::find($id);
            if (!$discussion) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Discussion not found',
                ], 404);
            }

            // Vérifie que l'utilisateur appartient à la discussion
            if (!$discussion->users->contains($user->id)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You cannot post in this discussion',
                ], 403);
            }

            // Création du message
            $message = Message::create([
                'discussion_id' => $discussion->id,
                'user_id'       => $user->id,
                'content'       => $request->content,
            ]);

            return response()->json([
                'response_code' => 201,
                'status'        => 'success',
                'message'       => 'Message added successfully',
                'data'          => $message,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Store Message Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Unable to add message',
            ], 500);
        }
    }


    public function getMessage($discussionId)
    {
        try {
            $discussion = Discussion::with('messages.user')->find($discussionId);

            if (!$discussion) {
                return response()->json([
                    'response_code' => 404,
                    'status'        => 'error',
                    'message'       => 'Discussion not found'
                ], 404);
            }

            // Vérifie que l’utilisateur connecté est bien participant
            if (!$discussion->users->contains(auth()->id())) {
                return response()->json([
                    'response_code' => 403,
                    'status'        => 'error',
                    'message'       => 'Vous n’êtes pas autorisé à voir cette discussion'
                ], 403);
            }

            return response()->json([
                'response_code' => 200,
                'status'        => 'success',
                'data'          => $discussion->messages
            ]);
        } catch (\Exception $e) {
            Log::error('Logout Error: ' . $e->getMessage());
            return response()->json([
                'response_code' => 500,
                'status'        => 'error',
                'message'       => 'Erreur lors de la récupération des messages'
            ], 500);
        }
    }
}
