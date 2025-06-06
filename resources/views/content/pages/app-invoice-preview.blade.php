@extends('layouts/layoutMaster')

@section('title', 'Preview - Invoice')

@section('vendor-style')
@vite('resources/assets/vendor/libs/flatpickr/flatpickr.scss')
@endsection

@section('page-style')
@vite('resources/assets/vendor/scss/pages/app-invoice.scss')
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/moment/moment.js',
  'resources/assets/vendor/libs/flatpickr/flatpickr.js',
  'resources/assets/vendor/libs/cleavejs/cleave.js',
  'resources/assets/vendor/libs/cleavejs/cleave-phone.js'
])
@endsection

@section('page-script')
@vite([
  'resources/assets/js/offcanvas-add-payment.js',
  'resources/assets/js/offcanvas-send-invoice.js'
])
@endsection


@section('content')

<div class="row invoice-preview">
  <!-- Invoice -->
  <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-6">
    <div class="card invoice-preview-card p-sm-12 p-6">
      <div class="card-body invoice-preview-header rounded-4 p-6">
        <div class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column text-heading align-items-xl-center align-items-md-start align-items-sm-center flex-wrap gap-6">
          <div>
            <div class="d-flex svg-illustration align-items-center gap-2 mb-6">
              <span class="app-brand-logo demo">@include('_partials.macros',["width"=>25,"withbg"=>'var(--bs-primary)'])</span>
              <span class="mb-0 app-brand-text fw-semibold demo">{{ $store_name }}</span>
            </div>
            <p class="mb-1">{{ $admin_address }}</p>
            <p class="mb-0">{{ $admin_phone }}</p>
          </div>
          <div>
            <h5 class="mb-6">Invoice # {{ $payment->invoice_id }}</h5>
            <div class="mb-1">
              <span>Date Issues:</span>
              <span>{{ $delivered_at }}</span>
            </div>
            <div>
              <span>Total Amount:</span>
              <span>{{$payment->amount}}</span>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body py-6 px-0">
        <div class="d-flex justify-content-between flex-wrap gap-6">
          <div>
            <h6>Invoice To:</h6>
            <p class="mb-1">{{ $payment->order->customer->full_name }}</p>
{{--            <p class="mb-1">Shelby Company Limited</p>--}}
            <p class="mb-1"> {{ $address }}</p>
            <p class="mb-1">{{ $payment->order->customer->phone_number }}</p>
            <p class="mb-0">{{ $payment->order->customer->email }}</p>
          </div>
{{--          <div>--}}
{{--            <h6>Bill To:</h6>--}}
{{--            <table>--}}
{{--              <tbody>--}}
{{--                <tr>--}}
{{--                  <td class="pe-4">Total Due:</td>--}}
{{--                  <td>$ {{  }}</td>--}}
{{--                </tr>--}}
{{--                <tr>--}}
{{--                  <td class="pe-4">Bank name:</td>--}}
{{--                  <td>American Bank</td>--}}
{{--                </tr>--}}
{{--                <tr>--}}
{{--                  <td class="pe-4">Country:</td>--}}
{{--                  <td>United States</td>--}}
{{--                </tr>--}}
{{--                <tr>--}}
{{--                  <td class="pe-4">IBAN:</td>--}}
{{--                  <td>ETD95476213874685</td>--}}
{{--                </tr>--}}
{{--                <tr>--}}
{{--                  <td class="pe-4">SWIFT code:</td>--}}
{{--                  <td>BR91905</td>--}}
{{--                </tr>--}}
{{--              </tbody>--}}
{{--            </table>--}}
{{--          </div>--}}
        </div>
      </div>
      <div class="table-responsive border rounded-4 border-bottom-0">
        <table class="table m-0">
          <thead>
            <tr>
              <th>Item</th>
              <th>Description</th>
              <th>Cost</th>
              <th>Qty</th>
              <th>Price</th>
            </tr>
          </thead>
          <tbody>
          @php
            $total = 0;
          @endphp
          @foreach($order_items as $item)
            @php
              $total += $item->price * $item->quantity;
            @endphp
            <tr>
              <td class="text-nowrap text-heading">{{ $item->product->name }}</td>
              <td class="text-nowrap">{{ $item->product->description_clean }}</td>
              <td>₱ {{ $item->price }}</td>
              <td>{{ $item->quantity }}</td>
              <td>₱ {{ $item->price * $item->quantity }}</td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
      <div class="table-responsive">
        <table class="table m-0 table-borderless">
          <tbody>
            <tr>
              <td class="align-top px-0 py-6">
                <p class="mb-1">
                  <span class="me-2 fw-medium text-heading">Salesperson:</span>
                  <span>Alfie Solomons</span>
                </p>
                <span>Thanks for your business</span>
              </td>
              <td class="pe-0 py-6 w-px-100">
                <p class="mb-1">Subtotal:</p>
                <p class="mb-1">Discount:</p>
                <p class="mb-1 border-bottom pb-2">Tax:</p>
                <p class="mb-0 pt-2">Total:</p>
              </td>
              <td class="text-end px-0 py-6 w-px-100">
                <p class="fw-medium mb-1">₱ {{ $total }}</p>
                <p class="fw-medium mb-1">₱00.00</p>
                <p class="fw-medium mb-1 border-bottom pb-2">₱00.00</p>
                <p class="fw-medium mb-0 pt-2">₱ {{$total}}</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <hr class="mt-0 mb-6">
      <div class="card-body p-0">
        <div class="row">
          <div class="col-12">
            <span class="fw-medium text-heading">Note:</span>
            <span>It was a pleasure working with you and your team. We hope you will keep us in mind for future freelance projects. Thank You!</span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /Invoice -->

  <!-- Invoice Actions -->
  <div class="col-xl-3 col-md-4 col-12 invoice-actions">
    <div class="card">
      <div class="card-body">
        <button class="btn btn-primary d-grid w-100 mb-4" data-bs-toggle="offcanvas" data-bs-target="#sendInvoiceOffcanvas">
          <span class="d-flex align-items-center justify-content-center text-nowrap"><i class="ri-send-plane-line ri-16px scaleX-n1-rtl me-2"></i>Send Invoice</span>
        </button>
{{--        <button class="btn btn-outline-secondary d-grid w-100 mb-4">--}}
{{--          Download--}}
{{--        </button>--}}
        <div class="d-flex mb-4">
          <a class="btn btn-outline-primary d-grid w-100 " target="_blank" href="{{url('invoice/print/'.$payment->id)}}">
            Print
          </a>
{{--          <a href="{{url('app/invoice/edit')}}" class="btn btn-outline-secondary d-grid w-100">--}}
{{--            Edit--}}
{{--          </a>--}}
        </div>
{{--        <button class="btn btn-success d-grid w-100" data-bs-toggle="offcanvas" data-bs-target="#addPaymentOffcanvas">--}}
{{--          <span class="d-flex align-items-center justify-content-center text-nowrap"><i class="ri-money-dollar-circle-line ri-16px me-2"></i>Add Payment</span>--}}
{{--        </button>--}}
      </div>
    </div>
  </div>
  <!-- /Invoice Actions -->
</div>

<!-- Offcanvas -->
@include('_partials/_offcanvas/offcanvas-send-invoice')
@include('_partials/_offcanvas/offcanvas-add-payment')
<!-- /Offcanvas -->
@endsection
