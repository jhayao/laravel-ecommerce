# Order Status Notification System

## Overview

The Order Status Notification System automatically sends push notifications to customers when their order status changes. This system leverages the OneSignal service to deliver real-time updates to users, utilizing Laravel's queue system for improved performance and reliability.

## How It Works

1. When an order's status is updated (e.g., from "pending" to "processing" or "completed")
2. The `OrderObserver` detects this change through Laravel's model observers
3. The observer dispatches a `SendOrderStatusNotification` job to the queue
4. The job processes asynchronously and sends a push notification via OneSignal
5. The notification is delivered to the customer's devices using their customer ID as an external ID alias

## Components

### OrderObserver

Located at `app/Observers/OrderObserver.php`, this observer:
- Monitors changes to the `status` field in the Order model
- When status changes, dispatches a job to the queue to handle notification sending
- Logs the dispatch event for audit purposes

### SendOrderStatusNotification Job

Located at `app/Jobs/SendOrderStatusNotification.php`, this job:
- Handles the asynchronous processing of notifications
- Is dispatched to the queue by the OrderObserver
- Implements retry logic (up to 3 attempts with exponential backoff)
- Logs success or failure of notification delivery

### OneSignalService Integration

The system uses the `sendToAliasExternalIds` method to target specific customers:
- Customer ID is used as the external ID alias
- Notification includes order number, new status, and a link to view the order

## Queue Configuration

For proper functioning of the notification system, ensure your queue is configured correctly:

1. Set your preferred queue driver in your `.env` file:
   ```
   QUEUE_CONNECTION=database  # or redis, sqs, etc.
   ```

2. If using the database driver, make sure you've run the migration to create the jobs table:
   ```bash
   php artisan queue:table
   php artisan migrate
   ```

3. Start the queue worker:
   ```bash
   php artisan queue:work --tries=3
   ```

For production environments, consider using a process monitor like Supervisor to keep your queue workers running. You can also use Laravel Horizon if you're using Redis as your queue driver for better monitoring and management of queues.

### Testing

To test this functionality you can use the built-in command:

```bash
php artisan order:test-notification {order_id} {new_status}
```

Example:
```bash
php artisan order:test-notification 1 completed
```

This will:
1. Update the status of order #1 to "completed"
2. Trigger the OrderObserver
3. Send a notification to the customer associated with that order

## Notification Content

Each notification includes:
- **Title**: "Order Status Update"
- **Message**: "Your order #[order_number] status has been updated to [new_status]"
- **URL**: Link to view the order details
- **Data**: JSON payload with order_id, order_number, old_status, and new_status

## Technical Implementation

The notification system targets devices using OneSignal's "aliases with external IDs" feature, which allows targeting specific users across multiple devices.

```json
{
  "include_aliases": {
    "external_id": ["123"] // Customer ID
  },
  "target_channel": "push"
}
```

This ensures that all devices registered to a specific customer receive the notification.
