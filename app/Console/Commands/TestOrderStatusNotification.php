<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestOrderStatusNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:test-notification {order_id} {new_status}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test order status update notification by updating a specific order status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orderId = $this->argument('order_id');
        $newStatus = $this->argument('new_status');
        
        $order = Order::find($orderId);
        
        if (!$order) {
            $this->error("Order with ID {$orderId} not found!");
            return 1;
        }
        
        $oldStatus = $order->status;
        $this->info("Updating order #{$order->order_number} status from '{$oldStatus}' to '{$newStatus}'");
          try {
            $order->status = $newStatus;
            $order->save();
            
            // The OrderObserver will dispatch the notification job to the queue
            
            $this->info("Order status updated successfully!");
            $this->info("Notification job dispatched to queue for customer ID: {$order->customer_id}");
            $this->info("Run 'php artisan queue:work' in a separate terminal window to process the queue");
            
            return 0;
        } catch (\Exception $e) {
            Log::error("Error updating order status: {$e->getMessage()}");
            $this->error("Error updating order status: {$e->getMessage()}");
            return 1;
        }
    }
}
