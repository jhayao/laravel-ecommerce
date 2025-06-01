<?php

namespace Tests\Feature;

use App\Models\FcmToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FcmTokenTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_save_fcm_token()
    {
        $payload = [
            'token' => 'test-fcm-token-123456789',
            'userId' => 'user123',
            'platform' => 'android',
            'timestamp' => now()->toISOString(),
            'appVersion' => '1.0.0',
            'packageName' => 'com.example.app'
        ];

        $response = $this->postJson('/api/fcm/save', $payload);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'FCM token saved successfully'
                ])
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'id',
                        'token',
                        'user_id',
                        'platform',
                        'is_active'
                    ]
                ]);

        $this->assertDatabaseHas('fcm_tokens', [
            'token' => 'test-fcm-token-123456789',
            'user_id' => 'user123',
            'platform' => 'android',
            'is_active' => true
        ]);
    }

    /** @test */
    public function it_can_update_existing_fcm_token()
    {
        // Create initial token
        $token = FcmToken::create([
            'token' => 'existing-token-123',
            'user_id' => 'user456',
            'platform' => 'ios',
            'timestamp' => now(),
            'is_active' => true
        ]);

        $payload = [
            'token' => 'existing-token-123',
            'userId' => 'user789', // Different user
            'platform' => 'android',
            'timestamp' => now()->toISOString(),
            'appVersion' => '2.0.0'
        ];

        $response = $this->postJson('/api/fcm/save', $payload);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'FCM token updated successfully'
                ]);

        $this->assertDatabaseHas('fcm_tokens', [
            'token' => 'existing-token-123',
            'user_id' => 'user789', // Should be updated
            'platform' => 'android',
            'app_version' => '2.0.0'
        ]);
    }

    /** @test */
    public function it_validates_required_token_field()
    {
        $payload = [
            'userId' => 'user123',
            'platform' => 'android'
        ];

        $response = $this->postJson('/api/fcm/save', $payload);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['token']);
    }

    /** @test */
    public function it_validates_platform_values()
    {
        $payload = [
            'token' => 'test-token-123',
            'platform' => 'invalid-platform'
        ];

        $response = $this->postJson('/api/fcm/save', $payload);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['platform']);
    }

    /** @test */
    public function it_can_get_user_tokens()
    {
        $userId = 'user123';
        
        FcmToken::create([
            'token' => 'token1',
            'user_id' => $userId,
            'platform' => 'android',
            'timestamp' => now(),
            'is_active' => true
        ]);

        FcmToken::create([
            'token' => 'token2',
            'user_id' => $userId,
            'platform' => 'ios',
            'timestamp' => now(),
            'is_active' => true
        ]);

        // Inactive token (should not be returned)
        FcmToken::create([
            'token' => 'token3',
            'user_id' => $userId,
            'platform' => 'web',
            'timestamp' => now(),
            'is_active' => false
        ]);

        $response = $this->getJson("/api/fcm/user/{$userId}");

        $response->assertStatus(200)
                ->assertJson(['success' => true])
                ->assertJsonCount(2, 'data'); // Only active tokens
    }

    /** @test */
    public function it_can_deactivate_token()
    {
        $token = FcmToken::create([
            'token' => 'token-to-deactivate',
            'user_id' => 'user123',
            'platform' => 'android',
            'timestamp' => now(),
            'is_active' => true
        ]);

        $response = $this->putJson("/api/fcm/deactivate/{$token->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'FCM token deactivated successfully'
                ]);

        $this->assertDatabaseHas('fcm_tokens', [
            'id' => $token->id,
            'is_active' => false
        ]);
    }
}
