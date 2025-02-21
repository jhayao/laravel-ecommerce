@extends('layouts/layoutMaster')

@section('title', 'eCommerce Product Add - Apps')

@section('vendor-style')
  @vite([
    'resources/assets/vendor/libs/quill/typography.scss',
    'resources/assets/vendor/libs/quill/katex.scss',
    'resources/assets/vendor/libs/quill/editor.scss',
    'resources/assets/vendor/libs/select2/select2.scss',
    'resources/assets/vendor/libs/dropzone/dropzone.scss',
    'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
    'resources/assets/vendor/libs/tagify/tagify.scss',
    'resources/assets/vendor/libs/@form-validation/form-validation.scss',
  ])
@endsection

@section('vendor-script')
  @vite([
      'resources/assets/vendor/libs/@form-validation/popular.js',
    'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
    'resources/assets/vendor/libs/@form-validation/auto-focus.js',
    'resources/assets/vendor/libs/quill/katex.js',
    'resources/assets/vendor/libs/quill/quill.js',
    'resources/assets/vendor/libs/select2/select2.js',
    'resources/assets/vendor/libs/dropzone/dropzone.js',
    'resources/assets/vendor/libs/jquery-repeater/jquery-repeater.js',
    'resources/assets/vendor/libs/flatpickr/flatpickr.js',
    'resources/assets/vendor/libs/tagify/tagify.js',

  ])
@endsection

@section('page-script')
  @vite([
    'resources/assets/js/app-ecommerce-product-add.js'
  ])
@endsection

@section('content')
  <div class="app-ecommerce">
    <form action="{{ route('products.add') }}" method="POST" id="eCommerceProductAddForm">
    @csrf
    <!-- Add Product -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
      <div class="d-flex flex-column justify-content-center">
        <h4 class="mb-1">Add a new Product</h4>
        <p class="mb-0">Orders placed across your store</p>
      </div>
      <div class="d-flex align-content-center flex-wrap gap-4">
        <button class="btn btn-outline-secondary" id="discardForm">Discard</button>
        <button type="submit" id="submitForm" class="btn btn-primary">Publish product</button>
      </div>
    </div>

    <div class="row">
      <!-- First column-->
      <div class="col-12 col-lg-8">
        <!-- Product Information -->
        <div class="card mb-6">
          <div class="card-header">
            <h5 class="card-tile mb-0">Product information</h5>
          </div>
          <div class="card-body">
            <div class="form-floating form-floating-outline mb-5">
              <input type="text" class="form-control" id="productTitle" placeholder="Product title" name="productTitle" aria-label="Product title">
              <label for="productTitle">Name</label>
            </div>
            <div class="row mb-5 gx-5">
              <div class="col-md-6">
                <div class="form-floating form-floating-outline">
                  <input type="number" class="form-control" id="productSku" placeholder="00000" name="productSku" aria-label="Product SKU">
                  <label for="productSku">SKU</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating form-floating-outline">
                  <input type="text" class="form-control" id="productBarcode" placeholder="0123-4567" name="productBarcode" aria-label="Product barcode">
                  <label for="productBarcode">Barcode</label>
                </div>
              </div>
            </div>
            <!-- Comment -->
            <div>
              <p class="mb-1">Description (Optional)</p>
              <div class="form-control p-0 pt-1">
                <div class="comment-toolbar border-0 border-bottom">
                  <div class="d-flex justify-content-start">
                    <span class="ql-formats me-0">
                      <button class="ql-bold"></button>
                      <button class="ql-italic"></button>
                      <button class="ql-underline"></button>
                      <button class="ql-list" value="ordered"></button>
                      <button class="ql-list" value="bullet"></button>
                      <button class="ql-link"></button>
                      <button class="ql-image"></button>
                    </span>
                  </div>
                </div>
                <div class="comment-editor border-0 pb-1" id="ecommerce-category-description">
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /Product Information -->
        <!-- Media -->
        <div class="card mb-6">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 card-title">Product Image</h5>
          </div>
          <div class="card-body">
            <div class="dropzone needsclick" id="dropzone-basic">
              <div class="dz-message needsclick">
                <div class="d-flex justify-content-center">
                  <div class="avatar">
                    <span class="avatar-initial rounded-3 bg-label-secondary">
                      <i class="ri-upload-2-line ri-24px"></i>
                    </span>
                  </div>
                </div>
                <p class="h4 needsclick my-2">Drag and drop your image here</p>
                <small class="note text-muted d-block fs-6 my-2">or</small>
                <span class="needsclick btn btn-sm btn-outline-primary" id="btnBrowse">Browse image</span>
              </div>
              <div class="fallback">
                <input name="file" type="file" id="dropzoneFile" />
              </div>
            </div>
          </div>
        </div>
        <!-- /Media -->
      </div>
      <!-- /First column -->

      <!-- Second column -->
      <div class="col-12 col-lg-4">
        <!-- Pricing Card -->
        <div class="card mb-6">
          <div class="card-header">
            <h5 class="card-title mb-0">Pricing</h5>
          </div>
          <div class="card-body">
            <!-- Base Price -->
            <div class="form-floating form-floating-outline mb-5">
              <input type="number" class="form-control" id="productPrice" placeholder="Price" name="productPrice" aria-label="Product price">
              <label for="productPrice">Best Price</label>
            </div>
            <!-- Discounted Price -->
            <div class="form-floating form-floating-outline mb-5">
              <input type="number" class="form-control" id="productDiscountedPrice" placeholder="Discounted Price" name="productDiscountedPrice" aria-label="Product discounted price">
              <label for="productDiscountedPrice">Discounted Price</label>
            </div>

            <div class="form-floating form-floating-outline mb-5">
              <input type="number" class="form-control" id="productStocks" placeholder="Stock" name="productStocks" aria-label="Product Stock">
              <label for="productStocks">Stocks</label>
            </div>
          </div>
        </div>
        <!-- /Pricing Card -->
        <!-- Organize Card -->
        <div class="card mb-6">
          <div class="card-header">
            <h5 class="card-title mb-0">Organize</h5>
          </div>
          <div class="card-body">
            <!-- Category -->
            <div class="mb-5 col ecommerce-select2-dropdown">
              <div class="form-floating form-floating-outline w-100 me-4">
                <select id="productCategory" name="productCategory" class="select2 form-select" data-placeholder="Select Category">
                  @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                  @endforeach
                </select>
                <label for="productCategory">Category</label>
              </div>
            </div>
            <!-- Status -->
            <div class="mb-5 col ecommerce-select2-dropdown">
              <div class="form-floating form-floating-outline">
                <select class="select2 form-select" id="productStatus" name="productStatus" data-placeholder="Select Status">
                  <option value="">Select Status</option>
                  <option value="publish">Published</option>
                  <option value="scheduled">Scheduled</option>
                  <option value="inactive">Inactive</option>
                </select>
                <label for="status-org">Status</label>
              </div>
            </div>
          </div>
        </div>
        <!-- /Organize Card -->
      </div>
      <!-- /Second column -->
    </div>
  </form>
</div>
@endsection
