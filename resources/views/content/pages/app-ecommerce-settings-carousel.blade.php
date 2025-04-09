@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'eCommerce Settings Store Payments - Apps')

@section('page-script')
    @vite(['resources/assets/js/app-ecommerce-settings.js', 'resources/assets/vendor/libs/dropzone/dropzone.js' . 'resources/assets/vendor/libs/jquery-repeater/jquery-repeater.js'])
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.form-repeater').repeater({
                initEmpty: false,
                defaultValues: {
                    'text-input': ''
                },
                show: function() {
                    $(this).slideDown();
                },
                hide: function(deleteElement) {
                    if (confirm('Are you sure you want to delete this element?')) {
                        $(this).slideUp(deleteElement);
                    }
                },
                ready: function(setIndexes) {
                    // Custom logic if needed
                }
            });

            // Prevent form submission on Add Item button click
            document.querySelectorAll('[data-repeater-create]').forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                });
            });
        });
    </script>
@endsection

@section('content')
    <div class="row gx-6">
        <!-- Navigation -->
        <div class="col-12 col-lg-4">
            <div class="d-flex justify-content-between flex-column mb-4 mb-md-0">
                <h5 class="mb-4">Getting Started</h5>
                <ul class="nav nav-align-left nav-pills flex-column">
                    <li class="nav-item mb-1">
                        <a class="nav-link" href="{{ url('/settings/details') }}">
                            <i class="ri-store-2-line me-2"></i>
                            <span class="align-middle">Store details</span>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link active" href="javascript:void(0);">
                            <i class="ri-carousel-view me-2"></i>
                            <span class="align-middle">Carousel</span>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link" href="{{ url('/settings/checkout') }}">
                            <i class="ri-shopping-cart-line me-2"></i>
                            <span class="align-middle">Checkout</span>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link" href="{{ url('/settings/shipping') }}">
                            <i class="ri-car-line me-2"></i>
                            <span class="align-middle">Shipping & delivery</span>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link" href="{{ url('/settings/locations') }}">
                            <i class="ri-map-pin-2-line me-2"></i>
                            <span class="align-middle">Locations</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/settings/notifications') }}">
                            <i class="ri-notification-4-line me-2"></i>
                            <span class="align-middle">Notifications</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /Navigation -->
        <!-- Form Repeater -->
        <div class="col-12 col-lg-8 pt-6 pt-lg-0">
            <div class="card">
                <h5 class="card-header">Carousel Settings</h5>
                <div class="card-body">
                    <form class="form-repeater" action="{{ url('/settings/carousel/save') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div data-repeater-list="carousel-items">
                            <div data-repeater-item>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card mb-6">
                                            <h5 class="card-header">Basic</h5>
                                            <div class="card-body">
                                                <form action="/upload" class="dropzone needsclick" id="dropzone-basic">
                                                    <div class="dz-message needsclick">
                                                        Drop files here or click to upload
                                                        <span class="note needsclick">(This is just a demo dropzone.
                                                            Selected files are <span class="fw-medium">not</span> actually
                                                            uploaded.)</span>
                                                    </div>
                                                    <div class="fallback">
                                                        <input name="file" type="file" />
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3 col-lg-6">
                                        <label for="title" class="form-label">Title</label>
                                        <input type="text" class="form-control" id="title" name="title"
                                            placeholder="Enter title" required>
                                    </div>
                                    <div class="mb-3 col-lg-6">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter description"
                                            required></textarea>
                                    </div>
                                    <div class="mb-3 col-lg-6">
                                        <label for="url" class="form-label">URL</label>
                                        <input type="url" class="form-control" id="url" name="url"
                                            placeholder="Enter URL" required>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end">
                                        <button class="btn btn-outline-danger" data-repeater-delete>
                                            <i class="ri-close-line me-1"></i> Delete
                                        </button>
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary" data-repeater-create>
                                <i class="ri-add-line me-1"></i> Add Item
                            </button>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-success">
                                <i class="ri-save-line me-1"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /Form Repeater -->
    </div>
@endsection
