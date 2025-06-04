<?php

namespace App\Observers;

use App\Jobs\SendOrderStatusNotification;
use App\Models\Order;
use App\Services\OneSignalService;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    protected $oneSignalService;

    public function __construct(OneSignalService $oneSignalService)
    {
        $this->oneSignalService = $oneSignalService;
    }

    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        // We're focusing on updates, so this is left empty
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Check if the status was changed
        if ($order->isDirty('status')) {
            $oldStatus = $order->getOriginal('status');
            $newStatus = $order->status;
            
            // Only send notification when status changes
            if ($oldStatus !== $newStatus) {
                $this->sendStatusUpdateNotification($order, $oldStatus, $newStatus);
            }
        }
    }    /**
     * Send push notification about order status update
     */
    private function sendStatusUpdateNotification(Order $order, $oldStatus, $newStatus): void
    {
        try {
            $customerId = (string) $order->customer_id; // Convert to string as OneSignal expects string IDs
            $url = config('app.url') . '/orders/' . $order->id;

            // Dispatch the job to the queue instead of sending directly
            \App\Jobs\SendOrderStatusNotification::dispatch(
                $order->id,
                $order->order_number,
                $customerId,
                $oldStatus,
                $newStatus,
                $url
            );

            Log::info('Order status notification job dispatched to queue', [
                'order_id' => $order->id,
                'customer_id' => $customerId
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to dispatch order status notification job', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        // We're focusing on updates, so this is left empty
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        // We're focusing on updates, so this is left empty
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        // We're focusing on updates, so this is left empty
    }
}
