@extends('layouts/layoutMaster')

@section('title', 'Extras - Forms')

<!-- Vendor Styles -->
@section('vendor-style')
    @vite(['resources/assets/vendor/libs/dropzone/dropzone.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
    @vite(['resources/assets/vendor/libs/jquery-repeater/jquery-repeater.js', 'resources/assets/vendor/libs/dropzone/dropzone.js'])
@endsection

<!-- Page Scripts -->
@section('page-script')
    @vite(['resources/assets/js/forms-extras.js', 'resources/assets/js/forms-file-upload.js'])
@endsection

@section('content')
    <div class="row gx-6">
        <!-- Navigation -->
        <div class="col-12 col-lg-4">
            <div class="d-flex justify-content-between flex-column mb-4 mb-md-0">
                <h5 class="mb-4">Getting Started</h5>
                <ul class="nav nav-align-left nav-pills flex-column">
                    <li class="nav-item mb-1">
                        <a class="nav-link " href="javascript:void(0);">
                            <i class="ri-store-2-line me-2"></i>
                            <span class="align-middle">Store details</span>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link active" href="{{ url('/settings/carousel') }}">
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

        <!-- Options -->
        <div class="col-12 col-lg-8 pt-6 pt-lg-0">
            <!-- Form Repeater -->
            <div class="col-12">
                <div class="card">
                    <h5 class="card-header">Form Repeater</h5>
                    <div class="card-body">
                        <form class="form-repeater">
                            <div data-repeater-list="group-a">
                                <div data-repeater-item>
                                    <div class="row">
                                        <div class="mb-3 col-lg-6">
                                            <label for="title" class="form-label">Title</label>
                                            <input type="text" class="form-control border-primary" id="title"
                                                name="title" placeholder="Enter title" required>
                                        </div>
                                        <div class="mb-3 col-lg-6">
                                            <label for="url" class="form-label">URL</label>
                                            <input type="url" class="form-control border-primary" id="url"
                                                name="url" placeholder="Enter URL" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 col-12">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control border-primary" id="description" name="description" rows="3"
                                                placeholder="Enter description" required></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 col-lg-6">
                                            <label for="image" class="form-label">Image</label>
                                            <input type="file" class="form-control border-primary" id="image"
                                                name="image" accept="image/*" required>
                                            <div class="mt-2 text-center">
                                                <img id="image-preview" src="#" alt="Image Preview"
                                                    class="rounded border border-secondary"
                                                    style="max-width: 100px; max-height: 100px; display: none;" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 text-end">
                                            <button type="button" class="btn btn-danger" data-repeater-delete>
                                                <i class="ri-delete-bin-line me-1"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                    <hr class="mt-0">
                                </div>
                            </div>
                            <div class="mb-0">
                                <button class="btn btn-primary" data-repeater-create>
                                    <i class="ri-add-line me-1"></i>
                                    <span class="align-middle">Add</span>
                                </button>
                            </div>
                            <div class="row">
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-save-line me-1"></i> Save
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /Form Repeater -->
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageInputs = document.querySelectorAll('input[type="file"][id="image"]');

            imageInputs.forEach((imageInput) => {
                const imagePreview = imageInput.closest('.mb-3').querySelector('#image-preview');

                imageInput.addEventListener('change', function(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imagePreview.src = e.target.result;
                            imagePreview.style.display = 'block';
                        };
                        reader.readAsDataURL(file);
                    } else {
                        imagePreview.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endsection
