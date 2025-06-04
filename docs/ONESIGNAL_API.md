# OneSignal Push Notification API

This Laravel application includes OneSignal push notification functionality for sending notifications to users.

## Setup

1. **Environment Configuration**
   The following environment variables are configured in `.env`:
   ```
   ONESIGNAL_APP_ID=d04df66e-dedc-40b9-9bb8-9922109636f4
   ONESIGNAL_REST_API_KEY=3qyz45jxquxzvdy3y6zurqkbt
   ONESIGNAL_REST_API_URL=https://api.onesignal.com
   ```

2. **Service Registration**
   - OneSignalService is registered in `app/Providers/OneSignalServiceProvider.php`
   - Service provider is registered in `bootstrap/providers.php`

## API Endpoints

All OneSignal endpoints are prefixed with `/api/onesignal`

### 1. Test Connection
- **GET** `/api/onesignal/test`
- Tests if the OneSignal service is working correctly
- Returns app information if successful

### 2. Get App Information
- **GET** `/api/onesignal/app-info`
- Retrieves OneSignal app information
- Useful for verifying configuration

### 3. Send Notification to All Users
- **POST** `/api/onesignal/send/all`
- Sends a notification to all users
- **Body Parameters:**
  ```json
  {
    "title": "Notification Title",
    "message": "Notification message content",
    "url": "https://example.com" (optional),
    "data": {} (optional)
  }
  ```

### 4. Send Notification to Segments
- **POST** `/api/onesignal/send/segments`
- Sends notifications to specific user segments
- **Body Parameters:**
  ```json
  {
    "title": "Notification Title",
    "message": "Notification message content",
    "segments": ["Subscribed Users", "Active Users"],
    "url": "https://example.com" (optional),
    "data": {} (optional)
  }
  ```

### 5. Send Notification to Player IDs
- **POST** `/api/onesignal/send/player-ids`
- Sends notifications to specific device tokens
- **Body Parameters:**
  ```json
  {
    "title": "Notification Title",
    "message": "Notification message content",
    "player_ids": ["player-id-1", "player-id-2"],
    "url": "https://example.com" (optional),
    "data": {} (optional)
  }
  ```

### 6. Send Notification to External User IDs
- **POST** `/api/onesignal/send/external-user-ids`
- Sends notifications to external user IDs
- **Body Parameters:**
  ```json
  {
    "title": "Notification Title",
    "message": "Notification message content",
    "external_user_ids": ["user123", "user456"],
    "url": "https://example.com" (optional),
    "data": {} (optional)
  }
  ```

### 7. Send Notification to Aliases with External IDs
- **POST** `/api/onesignal/send/alias-external-ids`
- Sends notifications to aliases with specific external user IDs
- **Body Parameters:**
  ```json
  {
    "title": "Notification Title",
    "message": "Notification message content",
    "external_ids": ["user1", "user2", "user3"],
    "url": "https://example.com" (optional),
    "data": {} (optional)
  }
  ```
- **Example Response:**
  ```json
  {
    "success": true,
    "message": "Notification sent to aliases with external IDs successfully",
    "data": {
      "success": true,
      "data": {
        "id": "notification_id",
        "recipients": 3
      },
      "message": "Notification sent successfully"
    }
  }
  ```

### 8. Get Notification Details
- **GET** `/api/onesignal/notification/{notificationId}`
- Retrieves details about a specific notification
- Replace `{notificationId}` with the actual notification ID

## OneSignal Service Methods

The OneSignalService class provides the following methods with corrected parameter signatures:

### 1. sendToAll(title, message, url, data, options)
- **Parameters:**
  - `string $title` - Notification title
  - `string $message` - Notification message  
  - `string|null $url` - Optional URL to open when notification is clicked
  - `array $data` - Optional custom data to send with notification
  - `array $options` - Optional OneSignal-specific options

### 2. sendToSegments(title, message, segments, url, data, options)
- **Parameters:**
  - `string $title` - Notification title
  - `string $message` - Notification message
  - `array $segments` - Array of segment names to target
  - `string|null $url` - Optional URL to open when notification is clicked
  - `array $data` - Optional custom data to send with notification
  - `array $options` - Optional OneSignal-specific options

### 3. sendToPlayerIds(title, message, playerIds, url, data, options)
- **Parameters:**
  - `string $title` - Notification title
  - `string $message` - Notification message
  - `array $playerIds` - Array of OneSignal player IDs (device tokens)
  - `string|null $url` - Optional URL to open when notification is clicked
  - `array $data` - Optional custom data to send with notification
  - `array $options` - Optional OneSignal-specific options

### 4. sendToExternalUserIds(title, message, externalUserIds, url, data, options)
- **Parameters:**
  - `string $title` - Notification title
  - `string $message` - Notification message
  - `array $externalUserIds` - Array of external user IDs
  - `string|null $url` - Optional URL to open when notification is clicked
  - `array $data` - Optional custom data to send with notification
  - `array $options` - Optional OneSignal-specific options

### 5. sendToAliasExternalIds(title, message, externalIds, url, data, options)
- **Parameters:**
  - `string $title` - Notification title
  - `string $message` - Notification message
  - `array $externalIds` - Array of external user IDs for aliases
  - `string|null $url` - Optional URL to open when notification is clicked
  - `array $data` - Optional custom data to send with notification
  - `array $options` - Optional OneSignal-specific options

### Using the Web Interface
1. Open `public/onesignal-api-test.html` in your browser
2. Use the interactive forms to test different notification types
3. View responses in real-time

### Using cURL Examples

**Test Connection:**
```bash
curl -X GET http://your-domain.com/api/onesignal/test
```

**Send to All Users:**
```bash
curl -X POST http://your-domain.com/api/onesignal/send/all \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Test Notification",
    "message": "This is a test message"
  }'
```

**Send to Segments:**
```bash
curl -X POST http://your-domain.com/api/onesignal/send/segments \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Segment Notification",
    "message": "Message for specific segments",
    "segments": ["Subscribed Users"]
  }'
```

## Response Format

All endpoints return JSON responses in the following format:

**Success Response:**
```json
{
  "success": true,
  "message": "Operation completed successfully",
  "data": {
    // OneSignal API response data
  }
}
```

**Error Response:**
```json
{
  "success": false,
  "message": "Error description",
  "error": "Detailed error message"
}
```

## Files Structure

- `app/Services/OneSignalService.php` - Main service class
- `app/Http/Controllers/OneSignalController.php` - API controller
- `app/Providers/OneSignalServiceProvider.php` - Service provider
- `config/services.php` - OneSignal configuration
- `routes/api.php` - API routes
- `public/onesignal-api-test.html` - Test interface

## Error Handling

The implementation includes comprehensive error handling:
- Validation of request parameters
- HTTP client error handling
- Logging of errors to Laravel logs
- Proper HTTP status codes
- Detailed error messages

## Security Notes

- Keep your OneSignal REST API key secure
- Consider adding authentication middleware to sensitive endpoints
- Validate all input parameters
- Monitor API usage and logs

## Integration with Existing FCM System

This OneSignal implementation works alongside the existing FCM token management system:
- FCM endpoints remain at `/api/fcm/*`
- OneSignal endpoints are at `/api/onesignal/*`
- Both can be used simultaneously for different notification strategies
