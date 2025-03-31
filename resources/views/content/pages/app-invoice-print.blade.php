@extends('layouts/layoutMaster')

@section('title', 'Invoice (Print version) - Pages')

@section('page-style')
@vite('resources/assets/vendor/scss/pages/app-invoice-print.scss')
@endsection

@section('page-script')
@vite('resources/assets/js/app-invoice-print.js')
@endsection

@section('content')
<div class="invoice-print p-6">
  <div class="d-flex justify-content-between flex-row">
    <div>
      <div class="d-flex svg-illustration align-items-center gap-2 mb-6">
        <span class="app-brand-logo demo">@include('_partials.macros',["width"=>25,"withbg"=>'var(--bs-primary)'])</span>
        <span class="mb-0 app-brand-text demo fw-semibold">{{ config('variables.templateName') }}</span>
      </div>
      <p class="mb-1">Office 149, 450 South Brand Brooklyn</p>
      <p class="mb-1">San Diego County, CA 91905, USA</p>
      <p class="mb-0">+1 (123) 456 7891, +44 (876) 543 2198</p>
    </div>
    <div>
      <h4 class="mb-6">INVOICE # {{ $payment->invoice_id }}</h4>
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

  <hr class="my-6" />

  <div class="d-flex justify-content-between mb-6">
    <div>
      <h6>Invoice To:</h6>
      <p class="mb-1">{{ $payment->order->customer->full_name }}</p>
      {{--            <p class="mb-1">Shelby Company Limited</p>--}}
      <p class="mb-1"> {{ $address }}</p>
      <p class="mb-1">{{ $payment->order->customer->phone_number }}</p>
      <p class="mb-0">{{ $payment->order->customer->email }}</p>
    </div>
{{--    <div>--}}
{{--      <h6>Bill To:</h6>--}}
{{--      <table>--}}
{{--        <tbody>--}}
{{--          <tr>--}}
{{--            <td class="pe-4">Total Due:</td>--}}
{{--            <td>$12,110.55</td>--}}
{{--          </tr>--}}
{{--          <tr>--}}
{{--            <td class="pe-4">Bank name:</td>--}}
{{--            <td>American Bank</td>--}}
{{--          </tr>--}}
{{--          <tr>--}}
{{--            <td class="pe-4">Country:</td>--}}
{{--            <td>United States</td>--}}
{{--          </tr>--}}
{{--          <tr>--}}
{{--            <td class="pe-4">IBAN:</td>--}}
{{--            <td>ETD95476213874685</td>--}}
{{--          </tr>--}}
{{--          <tr>--}}
{{--            <td class="pe-4">SWIFT code:</td>--}}
{{--            <td>BR91905</td>--}}
{{--          </tr>--}}
{{--        </tbody>--}}
{{--      </table>--}}
{{--    </div>--}}
  </div>

  <div class="table-responsive border border-bottom-0 rounded">
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
          <td class="text-nowrap text-heading">{{  \Illuminate\Support\Str::limit($item->product->name, 20, '..')  }}</td>
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
          <td class="px-0 py-6 w-px-100">
            <p class="mb-1">Subtotal:</p>
            <p class="mb-1">Discount:</p>
            <p class="mb-1 border-bottom pb-2">Tax:</p>
            <p class="mb-0 pt-2">Total:</p>
          </td>
          <td class="text-end px-0 py-6 w-px-100">
            <p class="fw-medium mb-1">₱ {{ $total }}.00</p>
            <p class="fw-medium mb-1">₱00.00</p>
            <p class="fw-medium mb-1 border-bottom pb-2">₱00.00</p>
            <p class="fw-medium mb-0 pt-2">₱ {{$total}}.00</p>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <hr class="mt-0 mb-6">
  <div class="row">
    <div class="col-12">
      <span class="fw-medium text-heading">Note:</span>
      <span>It was a pleasure working with you and your team. We hope you will keep us in mind for future
        freelance projects. Thank You!</span>
    </div>
  </div>
</div>
@endsection
