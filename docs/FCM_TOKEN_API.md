# FCM Token API Documentation

## Overview

The FCM (Firebase Cloud Messaging) Token API allows you to manage device tokens for push notifications in the Laravel ecommerce application. This API provides endpoints to save, retrieve, and deactivate FCM tokens.

## Database Schema

The `fcm_tokens` table contains the following fields:

| Field | Type | Description |
|-------|------|-------------|
| `id` | bigint | Primary key |
| `token` | string(512) | FCM token (unique) |
| `user_id` | string | User identifier (nullable) |
| `platform` | string | Device platform (android/ios/web) |
| `timestamp` | timestamp | Token registration timestamp |
| `app_version` | string | Application version (nullable) |
| `package_name` | string | Application package name (nullable) |
| `is_active` | boolean | Token status (default: true) |
| `created_at` | timestamp | Record creation time |
| `updated_at` | timestamp | Record update time |

## API Endpoints

### 1. Save FCM Token

**Endpoint:** `POST /api/fcm/save`

**Description:** Save or update an FCM token for a device.

**Request Body:**
```json
{
  "token": "string (required)",
  "userId": "string (optional)",
  "platform": "android|ios|web (optional, default: android)",
  "timestamp": "ISO datetime (optional, default: current time)",
  "appVersion": "string (optional)",
  "packageName": "string (optional)"
}
```

**Response:**
```json
{
  "success": true,
  "message": "FCM token saved successfully",
  "data": {
    "id": 1,
    "token": "fcm-token-here",
    "user_id": "user123",
    "platform": "android",
    "is_active": true
  }
}
```

**Validation Rules:**
- `token`: Required, string, max 255 characters
- `userId`: Optional, string, max 255 characters
- `platform`: Optional, must be one of: android, ios, web
- `timestamp`: Optional, valid date format
- `appVersion`: Optional, string, max 50 characters
- `packageName`: Optional, string, max 255 characters

### 2. Get User Tokens

**Endpoint:** `GET /api/fcm/user/{userId}`

**Description:** Retrieve all active FCM tokens for a specific user.

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "token": "fcm-token-1",
      "platform": "android",
      "app_version": "1.0.0",
      "created_at": "2025-06-01T12:00:00.000000Z"
    },
    {
      "id": 2,
      "token": "fcm-token-2",
      "platform": "ios",
      "app_version": "1.0.0",
      "created_at": "2025-06-01T12:05:00.000000Z"
    }
  ]
}
```

### 3. Deactivate Token

**Endpoint:** `PUT /api/fcm/deactivate/{tokenId}`

**Description:** Deactivate a specific FCM token.

**Response:**
```json
{
  "success": true,
  "message": "FCM token deactivated successfully"
}
```

## Usage Examples

### JavaScript/Fetch API

```javascript
// Save FCM Token
const saveFcmToken = async (tokenData) => {
  try {
    const response = await fetch('/api/fcm/save', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        token: tokenData.token,
        userId: tokenData.userId,
        platform: tokenData.platform || 'android',
        timestamp: new Date().toISOString(),
        appVersion: tokenData.appVersion,
        packageName: tokenData.packageName
      })
    });
    
    const result = await response.json();
    
    if (result.success) {
      console.log('FCM token saved:', result.data);
    } else {
      console.error('Failed to save FCM token:', result.message);
    }
  } catch (error) {
    console.error('Error saving FCM token:', error);
  }
};

// Get user tokens
const getUserTokens = async (userId) => {
  try {
    const response = await fetch(`/api/fcm/user/${userId}`);
    const result = await response.json();
    
    if (result.success) {
      return result.data;
    }
  } catch (error) {
    console.error('Error fetching user tokens:', error);
  }
};

// Deactivate token
const deactivateToken = async (tokenId) => {
  try {
    const response = await fetch(`/api/fcm/deactivate/${tokenId}`, {
      method: 'PUT',
      headers: {
        'Accept': 'application/json'
      }
    });
    
    const result = await response.json();
    return result.success;
  } catch (error) {
    console.error('Error deactivating token:', error);
    return false;
  }
};
```

### cURL Examples

```bash
# Save FCM Token
curl -X POST http://your-domain.com/api/fcm/save \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "token": "fcm-token-example-123456789",
    "userId": "user123",
    "platform": "android",
    "timestamp": "2025-06-01T12:00:00Z",
    "appVersion": "1.0.0",
    "packageName": "com.example.app"
  }'

# Get user tokens
curl -X GET http://your-domain.com/api/fcm/user/user123 \
  -H "Accept: application/json"

# Deactivate token
curl -X PUT http://your-domain.com/api/fcm/deactivate/1 \
  -H "Accept: application/json"
```

## Error Handling

All endpoints return JSON responses with the following structure for errors:

```json
{
  "success": false,
  "message": "Error description",
  "error": "Detailed error message (in development mode)"
}
```

Common HTTP status codes:
- `200`: Success
- `422`: Validation error
- `404`: Resource not found
- `500`: Internal server error

## Model Relationships

The `FcmToken` model includes the following relationships:

- `customer()`: Belongs to Customer model (based on user_id)

## Scopes

Available query scopes:

- `active()`: Get only active tokens
- `platform($platform)`: Filter by platform

Example usage:
```php
// Get all active Android tokens
$androidTokens = FcmToken::active()->platform('android')->get();

// Get all active tokens for a user
$userTokens = FcmToken::where('user_id', 'user123')->active()->get();
```

## Security Considerations

1. FCM tokens are sensitive data and should be handled securely
2. Consider implementing rate limiting for the save endpoint
3. Implement authentication/authorization as needed for your application
4. Regularly clean up inactive or expired tokens
5. Validate token format if needed (FCM tokens have specific patterns)

## Performance Optimizations

1. Database indexes are created on:
   - `user_id, is_active` (compound index)
   - `platform, is_active` (compound index)
   - `token` (unique index)

2. Use scopes for common queries to maintain consistency
3. Consider implementing token cleanup jobs for inactive tokens

## Integration with Firebase

To integrate with Firebase Cloud Messaging:

1. Use the saved tokens to send push notifications
2. Handle token refresh in your client application
3. Update tokens when they change using the save endpoint
4. Deactivate tokens when users uninstall the app or opt out
