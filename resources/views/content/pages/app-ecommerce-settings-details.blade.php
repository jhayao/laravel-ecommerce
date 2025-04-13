@extends('layouts/layoutMaster')

@section('title', 'eCommerce Settings Store Details - Apps')

@section('vendor-style')
  @vite('resources/assets/vendor/libs/select2/select2.scss')
@endsection

@section('vendor-script')
  @vite([
    'resources/assets/vendor/libs/select2/select2.js',
    'resources/assets/vendor/libs/cleavejs/cleave.js',
    'resources/assets/vendor/libs/cleavejs/cleave-phone.js'
  ])
@endsection

@section('page-script')
  @vite('resources/assets/js/app-ecommerce-settings.js')
@endsection

@section('content')
  <div class="row gx-6">
    <!-- Navigation -->
    <div class="col-12 col-lg-4">
      <div class="d-flex justify-content-between flex-column mb-4 mb-md-0">
        <h5 class="mb-4">Getting Started</h5>
        <ul class="nav nav-align-left nav-pills flex-column">
          <li class="nav-item mb-1">
            <a class="nav-link active" href="javascript:void(0);">
              <i class="ri-store-2-line me-2"></i>
              <span class="align-middle">Store details</span>
            </a>
          </li>
          <li class="nav-item mb-1">
            <a class="nav-link" href="{{url('/settings/carousel')}}">
              <i class="ri-carousel-view me-2"></i>
              <span class="align-middle">Carousel</span>
            </a>
          </li>
          <li class="nav-item mb-1">
            <a class="nav-link" href="{{url('/settings/checkout')}}">
              <i class="ri-shopping-cart-line me-2"></i>
              <span class="align-middle">Checkout</span>
            </a>
          </li>
          <li class="nav-item mb-1">
            <a class="nav-link" href="{{url('/settings/shipping')}}">
              <i class="ri-car-line me-2"></i>
              <span class="align-middle">Shipping & delivery</span>
            </a>
          </li>
          <li class="nav-item mb-1">
            <a class="nav-link" href="{{url('/settings/locations')}}">
              <i class="ri-map-pin-2-line me-2"></i>
              <span class="align-middle">Locations</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{url('/settings/notifications')}}">
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

      <form method="POST" action="{{ route('pages-settings-save-store-details') }}">
        @csrf
        <div class="card mb-6">
          <div class="card-header">
            <h5 class="card-title m-0">Profile</h5>
          </div>
          <div class="card-body">
            <div class="row mb-5 g-5">
              <div class="col-12 col-md-6">
                <div class="form-floating form-floating-outline">
                  <input type="text" class="form-control @error('store_name') is-invalid @enderror"
                          value="{{ old('store_name', $shop_settings['store_name'] ?? '') }}" required
                         id="ecommerce-settings-details-name"  placeholder="John Doe" name="store_name"
                         aria-label="settings Details">
                  <label for="ecommerce-settings-details-name">Store Name</label>
                  @error('store_name')
                  <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                    <div data-field="formValidationName" data-validator="notEmpty">{{ $message }}</div>
                  </div>
                  @enderror
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="form-floating form-floating-outline">
                  <input type="tel" class="form-control phone-mask @error('phone') is-invalid @enderror" id="ecommerce-settings-details-phone"
                         placeholder="+(123) 456-7890" name="phone" aria-label="phone" value="{{ old('phone', $shop_settings['phone'] ?? '') }}" required>
                  <label for="ecommerce-settings-details-phone">Phone Number</label>
                  @error('phone')
                  <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                    <div data-field="formValidationPhone" data-validator="notEmpty">{{ $message }}</div>
                  </div>
                  @enderror
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="form-floating form-floating-outline">
                  <input type="email" class="form-control @error('email') is-invalid @enderror" id="ecommerce-settings-details-email"
                         placeholder="johndoe@gmail.com" name="email" aria-label="email" value="{{ old('email', $shop_settings['email'] ?? '') }}" required>
                  <label for="ecommerce-settings-details-email">Store contact email</label>
                  @error('email')
                  <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                    <div data-field="formValidationEmail" data-validator="notEmpty">{{ $message }}</div>
                  </div>
                  @enderror
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="form-floating form-floating-outline">
                  <input type="text" id="business-name" class="form-control @error('business_name') is-invalid @enderror" placeholder="Business name" name="business_name" value="{{ old('business_name', $shop_settings['business_name'] ?? '') }}" required />
                  <label for="business-name">Legal business name</label>
                  @error('business_name')
                  <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                    <div data-field="formValidationBusinessName" data-validator="notEmpty">{{ $message }}</div>
                  </div>
                  @enderror
                </div>
              </div>
            </div>
{{--            <div class="alert d-flex align-items-center alert-warning mb-0 h6" role="alert">--}}
{{--              <span class="alert-icon me-4 rounded-3"><i class="ri-notification-3-line ri-22px"></i></span>--}}
{{--              Confirm that you have access to johndoe@gmail.com in sender email settings.--}}
{{--            </div>--}}
          </div>
        </div>

        <div class="card mb-6">
          <div class="card-header">
            <h5 class="card-title m-0">Billing information</h5>
          </div>
          <div class="card-body">
            <div class="row g-5">

              <div class="col-12 col-md-6">
                <div class="form-floating form-floating-outline">
                  <select id="country_region" class="select2 form-select" data-placeholder="United States">
                    <option value="Philippines" selected>Philippines</option>
                  </select>
                  <label for="country_region">Country/region</label>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="form-floating form-floating-outline">
                  <input type="text" id="bill_address" class="form-control @error('street') is-invalid @enderror" placeholder="Street" name="street" value="{{ old('street', $shop_settings['street'] ?? '') }}" required />
                  <label for="bill_address">Street Address</label>
                  @error('street')
                  <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                    <div data-field="formValidationAddress" data-validator="notEmpty">{{ $message }}</div>
                  </div>
                  @enderror
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="form-floating form-floating-outline">
                  <input type="text" id="apa_suite" class="form-control @error('building') is-invalid @enderror" placeholder="Apartment, suite, etc." name="building" value="{{ old('building', $shop_settings['building'] ?? '') }}">
                  <label for="apa_suite">Apartment, suite, etc.</label>
                  @error('building')
                  <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                    <div data-field="formValidationApartment" data-validator="notEmpty">{{ $message }}</div>
                  </div>
                  @enderror
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="form-floating form-floating-outline">
                  <input type="text" id="bill_city" class="form-control @error('city') is-invalid @enderror" placeholder="City" name="city" value="{{ old('city', $shop_settings['city'] ?? '') }}" required />
                  <label for="bill_city">City</label>
                  @error('city')
                  <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                    <div data-field="formValidationCity" data-validator="notEmpty">{{ $message }}</div>
                  </div>
                  @enderror
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="form-floating form-floating-outline">
                  <input type="text" id="bill_state" class="form-control @error('province') is-invalid @enderror" placeholder="Province" name="province" value="{{ old('province', $shop_settings['province'] ?? '') }}" required />
                  <label for="bill_state">Province</label>
                  @error('province')
                  <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                    <div data-field="formValidationState" data-validator="notEmpty">{{ $message }}</div>
                  </div>
                  @enderror
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="form-floating form-floating-outline">
                  <input type="number" id="bill_pincode" class="form-control @error('zip') is-invalid @enderror" placeholder="Zip Code" name="zip" min="0" max="999999" value="{{ old('zip', $shop_settings['zip'] ?? '') }}" required />
                  <label for="bill_pincode">PIN Code</label>
                  @error('zip')
                  <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                    <div data-field="formValidationPincode" data-validator="notEmpty">{{ $message }}</div>
                  </div>
                  @enderror
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="d-flex justify-content-end gap-4">
          <button type="reset" class="btn btn-outline-secondary">Discard</button>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
    <!-- /Options-->
  </div>
@endsection
