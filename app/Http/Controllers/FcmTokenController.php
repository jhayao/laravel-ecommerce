<?php

namespace App\Http\Controllers;

use App\Models\FcmToken;
use App\Http\Requests\SaveFcmTokenRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Exception;

class FcmTokenController extends Controller
{
    /**
     * Save or update FCM token for a device.
     */
    public function saveFCM(SaveFcmTokenRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validated();

            // Check if token already exists
            $existingToken = FcmToken::where('token', $validatedData['token'])->first();

            if ($existingToken) {
                // Update existing token
                $existingToken->update([
                    'user_id' => $validatedData['userId'] ?? $existingToken->user_id,
                    'platform' => $validatedData['platform'],
                    'timestamp' => $validatedData['timestamp'],
                    'app_version' => $validatedData['appVersion'] ?? $existingToken->app_version,
                    'package_name' => $validatedData['packageName'] ?? $existingToken->package_name,
                    'is_active' => true
                ]);

                $fcmToken = $existingToken;
                $message = 'FCM token updated successfully';
            } else {
                // Create new token
                $fcmToken = FcmToken::create([
                    'token' => $validatedData['token'],
                    'user_id' => $validatedData['userId'],
                    'platform' => $validatedData['platform'],
                    'timestamp' => $validatedData['timestamp'],
                    'app_version' => $validatedData['appVersion'],
                    'package_name' => $validatedData['packageName'],
                    'is_active' => true
                ]);

                $message = 'FCM token saved successfully';
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'id' => $fcmToken->id,
                    'token' => $fcmToken->token,
                    'user_id' => $fcmToken->user_id,
                    'platform' => $fcmToken->platform,
                    'is_active' => $fcmToken->is_active
                ]
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to save FCM token',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get FCM tokens for a specific user.
     */
    public function getUserTokens($userId): JsonResponse
    {
        try {
            $tokens = FcmToken::where('user_id', $userId)
                ->active()
                ->get(['id', 'token', 'platform', 'app_version', 'created_at']);

            return response()->json([
                'success' => true,
                'data' => $tokens
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve user tokens',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Deactivate a specific FCM token.
     */
    public function deactivateToken($tokenId): JsonResponse
    {
        try {
            $token = FcmToken::findOrFail($tokenId);
            $token->update(['is_active' => false]);

            return response()->json([
                'success' => true,
                'message' => 'FCM token deactivated successfully'
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to deactivate FCM token',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
