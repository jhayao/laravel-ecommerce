<?php

namespace App\Http\Controllers;

use App\Services\OneSignalService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OneSignalController extends Controller
{
    protected $oneSignalService;

    public function __construct(OneSignalService $oneSignalService)
    {
        $this->oneSignalService = $oneSignalService;
    }

    /**
     * Send notification to all users
     */
    public function sendToAll(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'url' => 'nullable|string|url',
            'data' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
            try {
            $result = $this->oneSignalService->sendToAll(
                $request->input('title'),
                $request->input('message'),
                $request->input('url'),
                $request->input('data') ?? []
            );

            return response()->json([
                'success' => true,
                'message' => 'Notification sent to all users successfully',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send notification to all users: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send notification to specific segments
     */
    public function sendToSegments(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'segments' => 'required|array|min:1',
            'segments.*' => 'string',
            'url' => 'nullable|string|url',
            'data' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }        try {
            $result = $this->oneSignalService->sendToSegments(
                $request->input('title'),
                $request->input('message'),
                $request->input('segments'),
                $request->input('url'),
                $request->input('data') ?? []
            );

            return response()->json([
                'success' => true,
                'message' => 'Notification sent to segments successfully',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send notification to segments: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send notification to specific player IDs
     */
    public function sendToPlayerIds(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'player_ids' => 'required|array|min:1',
            'player_ids.*' => 'string',
            'url' => 'nullable|string|url',
            'data' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }        try {
            $result = $this->oneSignalService->sendToPlayerIds(
                $request->input('title'),
                $request->input('message'),
                $request->input('player_ids'),
                $request->input('url'),
                $request->input('data') ?? []
            );

            return response()->json([
                'success' => true,
                'message' => 'Notification sent to specific devices successfully',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send notification to player IDs: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send notification to external user IDs
     */
    public function sendToExternalUserIds(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'external_user_ids' => 'required|array|min:1',
            'external_user_ids.*' => 'string',
            'url' => 'nullable|string|url',
            'data' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }        try {
            $result = $this->oneSignalService->sendToExternalUserIds(
                $request->input('title'),
                $request->input('message'),
                $request->input('external_user_ids'),
                $request->input('url'),
                $request->input('data') ?? []
            );

            return response()->json([
                'success' => true,
                'message' => 'Notification sent to external users successfully',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send notification to external user IDs: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send notification to aliases with external IDs
     */
    public function sendToAliasExternalIds(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'external_ids' => 'required|array|min:1',
            'external_ids.*' => 'string',
            'url' => 'nullable|string|url',
            'data' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            $result = $this->oneSignalService->sendToAliasExternalIds(
                $request->input('title'),
                $request->input('message'),
                $request->input('external_ids'),
                $request->input('url'),
                $request->input('data') ?? []
            );

            return response()->json([
                'success' => true,
                'message' => 'Notification sent to aliases with external IDs successfully',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send notification to aliases with external IDs: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get notification details by ID
     */
    public function getNotification(Request $request, string $notificationId): JsonResponse
    {
        try {
            $result = $this->oneSignalService->getNotification($notificationId);

            return response()->json([
                'success' => true,
                'message' => 'Notification details retrieved successfully',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get notification details: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to get notification details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get OneSignal app information
     */
    public function getAppInfo(): JsonResponse
    {
        try {
            $result = $this->oneSignalService->getApp();

            return response()->json([
                'success' => true,
                'message' => 'App information retrieved successfully',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get app information: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to get app information',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test endpoint to check if OneSignal service is working
     */
    public function test(): JsonResponse
    {
        try {
            // Test by getting app info
            $appInfo = $this->oneSignalService->getApp();
            
            return response()->json([
                'success' => true,
                'message' => 'OneSignal service is working correctly',
                'app_name' => $appInfo['name'] ?? 'Unknown',
                'app_id' => config('services.onesignal.app_id')
            ]);
        } catch (\Exception $e) {
            Log::error('OneSignal service test failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'OneSignal service test failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
