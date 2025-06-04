<?php

namespace App\Jobs;

use App\Services\OneSignalService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendOrderStatusNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 30;

    protected $orderId;
    protected $orderNumber;
    protected $customerId;
    protected $oldStatus;
    protected $newStatus;
    protected $url;

    /**
     * Create a new job instance.
     */
    public function __construct(
        int $orderId,
        string $orderNumber, 
        string $customerId, 
        string $oldStatus, 
        string $newStatus,
        ?string $url = null
    ) {
        $this->orderId = $orderId;
        $this->orderNumber = $orderNumber;
        $this->customerId = $customerId;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->url = $url;
    }

    /**
     * Execute the job.
     */
    public function handle(OneSignalService $oneSignalService): void
    {
        try {
            $title = 'Order Status Update';
            $message = "Your order #{$this->orderNumber} status has been updated to {$this->newStatus}";
            
            $data = [
                'order_id' => $this->orderId,
                'order_number' => $this->orderNumber,
                'old_status' => $this->oldStatus,
                'new_status' => $this->newStatus
            ];

            // Send notification using alias with external ID
            $result = $oneSignalService->sendToAliasExternalIds(
                $title,
                $message,
                [$this->customerId], // Array with single customer ID
                $this->url,
                $data
            );

            if (!$result['success']) {
                Log::warning('Failed to send order status update notification', [
                    'order_id' => $this->orderId,
                    'customer_id' => $this->customerId,
                    'error' => $result['error'] ?? 'Unknown error'
                ]);
                
                // If we've maxed out our retries, log a more severe error
                if ($this->attempts() >= $this->tries) {
                    Log::error('Final attempt to send order status notification failed', [
                        'order_id' => $this->orderId,
                        'customer_id' => $this->customerId,
                        'error' => $result['error'] ?? 'Unknown error'
                    ]);
                } else {
                    // Otherwise release the job back to the queue with backoff
                    $this->release(30 * $this->attempts());
                }
            } else {
                Log::info('Order status update notification sent successfully', [
                    'order_id' => $this->orderId,
                    'customer_id' => $this->customerId
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Exception when sending order status notification', [
                'order_id' => $this->orderId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // If this was our last attempt, log it specially
            if ($this->attempts() >= $this->tries) {
                Log::critical('All attempts to send notification failed', [
                    'order_id' => $this->orderId,
                    'attempts' => $this->attempts()
                ]);
            } else {
                // Otherwise release the job back to the queue with backoff
                $this->release(30 * $this->attempts());
            }
        }
    }
}
