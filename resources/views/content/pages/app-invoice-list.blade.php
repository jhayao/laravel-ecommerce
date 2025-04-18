@extends('layouts/layoutMaster')

@section('title', 'Invoice List - Pages')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.scss',
  'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss'
])
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/moment/moment.js',
  'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js'
])
@endsection

@section('page-script')
@vite('resources/assets/js/app-invoice-list.js')
@endsection

@section('content')
<!-- Invoice List Widget -->


<!-- Invoice List Table -->
<div class="card">
  <div class="card-datatable table-responsive">
    <table class="invoice-list-table table">
      <thead>
        <tr>
          <th></th>
          <th></th>
          <th>#ID</th>
{{--          <th>#</th>--}}
          <th>Client</th>
          <th>Total</th>
          <th class="text-truncate">Issued Date</th>
          <th>Payment Method</th>
          <th>Invoice Status</th>
          <th class="cell-fit">Actions</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
@endsection
