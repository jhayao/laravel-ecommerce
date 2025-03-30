@php
    $order = $order ?? null;
    $order_status = $order->status ?? '';
    $order_id = $order->id;
    $shipment_status = $order->shipment()->latest()->first()->status ?? '';
@endphp
<!-- Update Order Status Modal -->
<div class="modal fade" id="updateOrderStatus2" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-m modal-simple modal-update-order-status">
    <div class="modal-content">
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-body p-0">
        <div class="text-center mb-6">
          <h4 class="status-title mb-2">Update Order and Shipping Status</h4>
          <p class="status-subtitle">Modify the current status of the order and shipment</p>
        </div>
        <form id="updateOrderStatusForm" class="row g-5" onsubmit="return false">
          <div class="col-12">
            <div class="form-floating form-floating-outline">
              <select id="orderStatus" name="orderStatus" class="form-select">
                <option value="pending" @selected(strtolower($order_status) === 'pending')>Pending</option>
                <option value="processing" @selected(strtolower($order_status) === 'processing')>Processing</option>
                <option value="completed" @selected(strtolower($order_status) === 'completed')>Completed</option>
                <option value="declined" @selected(strtolower($order_status) === 'declined')>Declined</option>
              </select>
              <label for="orderStatus">Order Status</label>
            </div>
          </div>
          <div class="col-12">
            <div class="form-floating form-floating-outline">
              <select id="shipmentStatus" name="shipmentStatus" class="form-select">
                <option value="pending" @selected(strtolower($shipment_status) === 'pending')>Pending</option>
                <option value="shipped" @selected(strtolower($shipment_status) === 'shipped')>Shipped</option>
                <option value="delivered" @selected(strtolower($shipment_status) === 'delivered')>Delivered</option>
                <option value="fail" @selected(strtolower($shipment_status) === 'fail')>Failed</option>
              </select>
              <label for="shipmentStatus">Shipping Status</label>
            </div>
          </div>
          <div class="col-12 mt-6 d-flex flex-wrap justify-content-center gap-4 row-gap-4">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--/ Update Order Status Modal -->

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('updateOrderStatusForm');
    const modal = document.getElementById('updateOrderStatus2');
    const orderStatus = document.getElementById('orderStatus');
    const shipmentStatus = document.getElementById('shipmentStatus');

    // Handle form submission via AJAX
    form.addEventListener('submit', function (event) {
      event.preventDefault();

      const data = {
        orderStatus: orderStatus.value,
        shipmentStatus: shipmentStatus.value,
      };

      fetch(`/api/order/update-status/${@json($order_id)}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify(data),
      })
        .then(response => response.json())
        .then(result => {
          if (result.success) {
            alert('Order status updated successfully!');
            // Optionally close the modal
            const bootstrapModal = bootstrap.Modal.getInstance(modal);
            bootstrapModal.hide();
          } else {
            alert('Failed to update order status.');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('An error occurred while updating the order status.');
        });
    });

    // Reset fields on cancel
    modal.addEventListener('hidden.bs.modal', function () {
      form.reset();
    });
  });
</script>
