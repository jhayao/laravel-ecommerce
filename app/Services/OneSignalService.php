<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class OneSignalService
{
    protected $appId;
    protected $restApiKey;
    protected $apiUrl;
    protected $client;

    public function __construct()
    {
        $this->appId = config('services.onesignal.app_id');
        $this->restApiKey = config('services.onesignal.rest_api_key');
        $this->apiUrl = config('services.onesignal.api_url');
        $this->client = new Client();
    }/**
         * Send a push notification to all users
         *
         * @param string $title
         * @param string $message
         * @param string|null $url
         * @param array $data Additional data to send with notification
         * @param array $options Additional OneSignal options
         * @return array
         */
    public function sendToAll(string $title, string $message, ?string $url = null, array $data = [], array $options = []): array
    {
        $payload = array_merge([
            'app_id' => $this->appId,
            'included_segments' => ['All'],
            'headings' => ['en' => $title],
            'contents' => ['en' => $message],
        ], $options);

        if (!empty($url)) {
            $payload['url'] = $url;
        }

        if (!empty($data)) {
            $payload['data'] = $data;
        }

        return $this->sendNotification($payload);
    }    /**
         * Send a push notification to specific user segments
         *
         * @param string $title
         * @param string $message
         * @param array $segments
         * @param string|null $url
         * @param array $data
         * @param array $options
         * @return array
         */
    public function sendToSegments(string $title, string $message, array $segments, ?string $url = null, array $data = [], array $options = []): array
    {
        $payload = array_merge([
            'app_id' => $this->appId,
            'included_segments' => $segments,
            'headings' => ['en' => $title],
            'contents' => ['en' => $message],
        ], $options);

        if (!empty($url)) {
            $payload['url'] = $url;
        }

        if (!empty($data)) {
            $payload['data'] = $data;
        }

        return $this->sendNotification($payload);
    }    /**
         * Send a push notification to specific player IDs (device tokens)
         *
         * @param string $title
         * @param string $message
         * @param array $playerIds
         * @param string|null $url
         * @param array $data
         * @param array $options
         * @return array
         */
    public function sendToPlayerIds(string $title, string $message, array $playerIds, ?string $url = null, array $data = [], array $options = []): array
    {
        $payload = array_merge([
            'app_id' => $this->appId,
            'include_player_ids' => $playerIds,
            'headings' => ['en' => $title],
            'contents' => ['en' => $message],
        ], $options);

        if (!empty($url)) {
            $payload['url'] = $url;
        }

        if (!empty($data)) {
            $payload['data'] = $data;
        }

        return $this->sendNotification($payload);
    }    /**
         * Send a push notification to external user IDs
         *
         * @param string $title
         * @param string $message
         * @param array $externalUserIds
         * @param string|null $url
         * @param array $data
         * @param array $options
         * @return array
         */
    public function sendToExternalUserIds(string $title, string $message, array $externalUserIds, ?string $url = null, array $data = [], array $options = []): array
    {
        $payload = array_merge([
            'app_id' => $this->appId,
            'include_external_user_ids' => $externalUserIds,
            'headings' => ['en' => $title],
            'contents' => ['en' => $message],
        ], $options);

        if (!empty($url)) {
            $payload['url'] = $url;
        }

        if (!empty($data)) {
            $payload['data'] = $data;
        }

        return $this->sendNotification($payload);
    }    /**
     * Send notification with custom payload
     *
     * @param array $payload
     * @return array
     */
    public function sendNotification(array $payload): array
    {
        try {
            $response = $this->client->request('POST', 'https://api.onesignal.com/notifications?c=push', [
                'body' => json_encode($payload),
                'headers' => [
                    'Authorization' => 'Key ' . $this->restApiKey,
                    'accept' => 'application/json',
                    'content-type' => 'application/json',
                ],
            ]);

            $statusCode = $response->getStatusCode();
            $responseData = json_decode($response->getBody()->getContents(), true);

            if ($statusCode >= 200 && $statusCode < 300) {
                Log::info('OneSignal notification sent successfully', [
                    'response' => $responseData,
                    'payload' => $payload
                ]);

                return [
                    'success' => true,
                    'data' => $responseData,
                    'message' => 'Notification sent successfully'
                ];
            } else {
                Log::error('OneSignal notification failed', [
                    'response' => $responseData,
                    'status' => $statusCode,
                    'payload' => $payload
                ]);

                return [
                    'success' => false,
                    'error' => $responseData,
                    'message' => 'Failed to send notification'
                ];
            }
        } catch (GuzzleException $e) {
            Log::error('OneSignal service GuzzleException', [
                'error' => $e->getMessage(),
                'payload' => $payload
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Exception occurred while sending notification'
            ];
        } catch (\Exception $e) {
            Log::error('OneSignal service exception', [
                'error' => $e->getMessage(),
                'payload' => $payload
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Exception occurred while sending notification'
            ];
        }
    }    /**
     * Get notification details by ID
     *
     * @param string $notificationId
     * @return array
     */
    public function getNotification(string $notificationId): array
    {
        try {
            $response = $this->client->request('GET', $this->apiUrl . "/notifications/{$notificationId}?app_id={$this->appId}", [
                'headers' => [
                    'Authorization' => 'Basic ' . $this->restApiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ]);

            $statusCode = $response->getStatusCode();
            $responseData = json_decode($response->getBody()->getContents(), true);

            if ($statusCode >= 200 && $statusCode < 300) {
                return [
                    'success' => true,
                    'data' => $responseData
                ];
            } else {
                return [
                    'success' => false,
                    'error' => $responseData
                ];
            }
        } catch (GuzzleException $e) {
            Log::error('OneSignal getNotification GuzzleException', [
                'error' => $e->getMessage(),
                'notification_id' => $notificationId
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        } catch (\Exception $e) {
            Log::error('OneSignal getNotification exception', [
                'error' => $e->getMessage(),
                'notification_id' => $notificationId
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }    /**
     * Get app details
     *
     * @return array
     */
    public function getApp(): array
    {
        try {
            $response = $this->client->request('GET', $this->apiUrl . "/apps/{$this->appId}", [
                'headers' => [
                    'Authorization' => 'Basic ' . $this->restApiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ]);

            $statusCode = $response->getStatusCode();
            $responseData = json_decode($response->getBody()->getContents(), true);

            if ($statusCode >= 200 && $statusCode < 300) {
                return [
                    'success' => true,
                    'data' => $responseData
                ];
            } else {
                return [
                    'success' => false,
                    'error' => $responseData
                ];
            }
        } catch (GuzzleException $e) {
            Log::error('OneSignal getApp GuzzleException', [
                'error' => $e->getMessage(),
                'app_id' => $this->appId
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        } catch (\Exception $e) {
            Log::error('OneSignal getApp exception', [
                'error' => $e->getMessage(),
                'app_id' => $this->appId
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }    /**
     * Send a push notification to aliases with external IDs
     *
     * @param string $title
     * @param string $message
     * @param array $externalIds Array of external user IDs
     * @param string|null $url
     * @param array $data
     * @param array $options
     * @return array
     */
    public function sendToAliasExternalIds(string $title, string $message, array $externalIds, ?string $url = null, array $data = [], array $options = []): array
    {
        $payload = array_merge([
            'app_id' => $this->appId,
            'include_aliases' => [
                'external_id' => $externalIds
            ],
            'target_channel' => 'push',
            'headings' => ['en' => $title],
            'contents' => ['en' => $message],
        ], $options);

        if (!empty($url)) {
            $payload['url'] = $url;
        }

        if (!empty($data)) {
            $payload['data'] = $data;
        }

        return $this->sendNotification($payload);
    }
}
