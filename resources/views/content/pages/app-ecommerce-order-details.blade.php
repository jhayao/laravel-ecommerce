@extends('layouts/layoutMaster')

@section('title', 'eCommerce Order Details - Apps')

@section('vendor-style')
  @vite([
    'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.scss',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
    'resources/assets/vendor/libs/@form-validation/form-validation.scss',
    'resources/assets/vendor/libs/tagify/tagify.scss',
    'resources/assets/vendor/libs/select2/select2.scss'
  ])
@endsection

@section('vendor-script')
  @vite([
    'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
    'resources/assets/vendor/libs/cleavejs/cleave.js',
    'resources/assets/vendor/libs/cleavejs/cleave-phone.js',
    'resources/assets/vendor/libs/@form-validation/popular.js',
    'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
    'resources/assets/vendor/libs/@form-validation/auto-focus.js',
    'resources/assets/vendor/libs/tagify/tagify.js',
    'resources/assets/vendor/libs/select2/select2.js'
  ])
@endsection

@section('page-script')
  @vite([
    'resources/assets/js/app-ecommerce-order-details.js',
    'resources/assets/js/modal-add-new-address.js',
    'resources/assets/js/modal-edit-user.js'
  ])
@endsection


@section('content')
  <div
    class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 gap-6">

    <div class="d-flex flex-column justify-content-center">
      <div class="d-flex align-items-center mb-1">
        <h5 class="mb-0">Order #{{ $order->order_number }}</h5>
        @php

        @endphp
        @if (strtolower($order->status) === 'pending')
          <span class="badge bg-label-warning me-2 ms-2 rounded-pill">{{ $order->status }}</span>
        @elseif (strtolower($order->status) === 'completed')
          <span class="badge bg-label-success me-2 ms-2 rounded-pill">{{ $order->status }}</span>
        @elseif (strtolower($order->status) === 'cancelled')
          <span class="badge bg-label-danger me-2 ms-2 rounded-pill">{{ $order->status }}</span>
        @endif
{{--        <span class="badge bg-label-info rounded-pill">{{ $order->payment->status }}</span>--}}
      </div>
      <p class="mb-0">{{ $order->created_at->format('M d, Y, h:i A (T)') }}</p>
    </div>
    <div class="d-flex align-content-center flex-wrap gap-2">
      <button class="btn btn-outline-danger delete-order">Delete Order</button>
      {{-- data-bs-toggle="modal" data-bs-target="#updateOrderStatus" --}}
      <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#updateOrderStatus2">Update Status</button>
    </div>
  </div>

  <!-- Order Details Table -->

  <div class="row">
    <div class="col-12 col-lg-8">
      <div class="card mb-6">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="card-title m-0">Order details</h5>
{{--          <h6 class="m-0"><a href=" javascript:void(0)">Edit</a></h6>--}}
        </div>
        <div class="card-datatable table-responsive pb-5">
          <table class="datatables-order-details table">
            <thead>
            <tr>
              <th></th>
              <th></th>
              <th class="w-50">products</th>
              <th>price</th>
              <th>qty</th>
              <th>total</th>
            </tr>
            </thead>
          </table>
          <div class="d-flex justify-content-end align-items-center m-4 p-1 mb-0 pb-0">
            <div class="order-calculations">
              <div class="d-flex justify-content-start gap-4">
                <span class="w-px-100 text-heading">Subtotal:</span>
                <h6 class="mb-0"></h6>
              </div>
              <div class="d-flex justify-content-start gap-4">
                <span class="w-px-100 text-heading">Discount:</span>
                <h6 class="mb-0">$00.00</h6>
              </div>
              <div class="d-flex justify-content-start gap-4">
                <span class="w-px-100 text-heading">Tax:</span>
                <h6 class="mb-0">$100.00</h6>
              </div>
              <div class="d-flex justify-content-start gap-4">
                <h6 class="w-px-100 mb-1">Total:</h6>
                <h6 class="mb-0"></h6>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card mb-6">
        <div class="card-header">
          <h5 class="card-title m-0">Shipping activity</h5>
        </div>
        <div class="card-body mt-3">
          <ul class="timeline pb-0 mb-0">
            <li class="timeline-item timeline-item-transparent border-primary">
              <span class="timeline-point timeline-point-primary"></span>
              <div class="timeline-event">
                <div class="timeline-header mb-2">
                  <h6 class="mb-0">Order was placed (Order ID: #32543)</h6>
                  <small class="text-muted">Tuesday 11:29 AM</small>
                </div>
                <p class="mt-1 mb-2">Your order has been placed successfully</p>
              </div>
            </li>
            <li class="timeline-item timeline-item-transparent border-primary">
              <span class="timeline-point timeline-point-primary"></span>
              <div class="timeline-event">
                <div class="timeline-header mb-2">
                  <h6 class="mb-0">Pick-up</h6>
                  <small class="text-muted">Wednesday 11:29 AM</small>
                </div>
                <p class="mt-1 mb-2">Pick-up scheduled with courier</p>
              </div>
            </li>
            <li class="timeline-item timeline-item-transparent border-primary">
              <span class="timeline-point timeline-point-primary"></span>
              <div class="timeline-event">
                <div class="timeline-header mb-2">
                  <h6 class="mb-0">Dispatched</h6>
                  <small class="text-muted">Thursday 11:29 AM</small>
                </div>
                <p class="mt-1 mb-2">Item has been picked up by courier</p>
              </div>
            </li>
            <li class="timeline-item timeline-item-transparent border-primary">
              <span class="timeline-point timeline-point-primary"></span>
              <div class="timeline-event">
                <div class="timeline-header mb-2">
                  <h6 class="mb-0">Package arrived</h6>
                  <small class="text-muted">Saturday 15:20 AM</small>
                </div>
                <p class="mt-1 mb-2">Package arrived at an Amazon facility, NY</p>
              </div>
            </li>
            <li class="timeline-item timeline-item-transparent border-left-dashed">
              <span class="timeline-point timeline-point-primary"></span>
              <div class="timeline-event">
                <div class="timeline-header mb-2">
                  <h6 class="mb-0">Dispatched for delivery</h6>
                  <small class="text-muted">Today 14:12 PM</small>
                </div>
                <p class="mt-1 mb-2">Package has left an Amazon facility, NY</p>
              </div>
            </li>
            <li class="timeline-item timeline-item-transparent border-transparent pb-0">
              <span class="timeline-point timeline-point-primary"></span>
              <div class="timeline-event pb-0">
                <div class="timeline-header mb-2">
                  <h6 class="mb-0">Delivery</h6>
                </div>
                <p class="mt-1 mb-2">Package will be delivered by tomorrow</p>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="col-12 col-lg-4">
      <div class="card mb-6">
        <div class="card-body">
          <h5 class="card-title mb-6">Customer details</h5>
          <div class="d-flex justify-content-start align-items-center mb-6">
            <div class="avatar me-3">
              <img src="{{ $order->customer->profile_picture ?? asset('assets/img/avatars/1.png')}}" alt="Avatar" class="rounded-circle">
            </div>
            <div class="d-flex flex-column">
              <a href="{{url('app/user/view/account')}}">
                <h6 class="mb-0">{{ $order->customer->full_name }}</h6>
              </a>
              <span>Customer ID: #{{ $order->customer->id }}</span></div>
          </div>
          <div class="d-flex justify-content-start align-items-center mb-6">
            <span
              class="avatar rounded-circle bg-label-success me-3 d-flex align-items-center justify-content-center"><i
                class='ri-shopping-cart-line ri-24px'></i></span>
            <h6 class="text-nowrap mb-0">12 Orders</h6>
          </div>
          <div class="d-flex justify-content-between">
            <h6 class="mb-1">Contact info</h6>
           {{-- <h6 class="mb-1"><a href=" javascript:;" data-bs-toggle="modal" data-bs-target="#updateOrderStatus2">Edit</a></h6> --}}
          </div>
          <p class="mb-1">Email: {{ $order->customer->email }}</p>
          <p class="mb-0">Mobile: {{ $order->customer->phone_number }}</p>
        </div>
      </div>

      <div class="card mb-6">

        <div class="card-header d-flex justify-content-between">
          <h5 class="card-title mb-1">Shipping address</h5>
{{--          <h6 class="m-0"><a href=" javascript:void(0)" data-bs-toggle="modal" data-bs-target="#addNewAddress">Edit</a>--}}
          </h6>
        </div>
        <div class="card-body">
          <p class="mb-0"> {!! $order->customer->address()->first()->full_address !!}  </p>
        </div>

      </div>

    </div>
  </div>

  <!-- Modals -->
  {{-- @include('_partials/_modals/modal-edit-user')
  @include('_partials/_modals/modal-add-new-address') --}}
  @include('_partials._modals.modal-update-order-status', ['order' => $order])

@endsection

<script>
  var orderNumber = "{{ $order->order_number }}";
</script>
