/**
 * App eCommerce Add Product Script
 */
'use strict';
let storedImageIds = []; // Global variable to store uploaded image IDs

//Javascript to handle the e-commerce product add page

(function () {
  // Comment editor

  const commentEditor = document.querySelector('.comment-editor');

  if (commentEditor) {
    new Quill(commentEditor, {
      modules: {
        toolbar: '.comment-toolbar'
      },
      placeholder: 'Product Description',
      theme: 'snow'
    });
  }

  // previewTemplate: Updated Dropzone default previewTemplate

  // ! Don't change it unless you really know what you are doing



  // ? Start your code from here

  // Basic Dropzone



  // Basic Tags

  // const tagifyBasicEl = document.querySelector('#ecommerce-product-tags');
  // const TagifyBasic = new Tagify(tagifyBasicEl);

  // Flatpickr

  // Datepicker
  const date = new Date();

  const productDate = document.querySelector('.product-date');

  if (productDate) {
    productDate.flatpickr({
      monthSelectorType: 'static',
      defaultDate: date
    });
  }
})();

//Jquery to handle the e-commerce product add page

$(function () {
  // Select2
  var select2 = $('.select2');
  if (select2.length) {
    select2.each(function () {
      var $this = $(this);
      select2Focus($this);
      $this.wrap('<div class="position-relative"></div>').select2({
        dropdownParent: $this.parent(),
        placeholder: $this.data('placeholder') // for dynamic placeholder
      });
    });
  }

  var formRepeater = $('.form-repeater');

  // Form Repeater
  // ! Using jQuery each loop to add dynamic id and class for inputs. You may need to improve it based on form fields.
  // -----------------------------------------------------------------------------------------------------------------

  if (formRepeater.length) {
    var row = 2;
    var col = 1;
    formRepeater.on('submit', function (e) {
      e.preventDefault();
    });
    formRepeater.repeater({
      show: function () {
        var fromControl = $(this).find('.form-control, .form-select');
        var formLabel = $(this).find('.form-label');

        fromControl.each(function (i) {
          var id = 'form-repeater-' + row + '-' + col;
          $(fromControl[i]).attr('id', id);
          $(formLabel[i]).attr('for', id);
          col++;
        });

        row++;
        $(this).slideDown();
        $('.select2-container').remove();
        $('.select2.form-select').select2({
          placeholder: 'Placeholder text'
        });
        $('.select2-container').css('width', '100%');
        var $this = $(this);
        select2Focus($this);
        $('.form-repeater:first .form-select').select2({
          dropdownParent: $(this).parent(),
          placeholder: 'Placeholder text'
        });
        $('.position-relative .select2').each(function () {
          $(this).select2({
            dropdownParent: $(this).closest('.position-relative')
          });
        });
      }
    });
  }
});

document.addEventListener("DOMContentLoaded", function () {
  const eCommerceProductAddForm = document.getElementById('eCommerceProductAddForm');

  if (!eCommerceProductAddForm) {
    console.error("Form not found! Ensure it exists before initializing.");
    return;
  }

  // Initialize form validation
  const fv = FormValidation.formValidation(eCommerceProductAddForm, {
    fields: {
      productTitle: { validators: { notEmpty: { message: 'Please enter Product name' } } },
      productSku: { validators: { notEmpty: { message: 'Please enter Product SKU' } } },
      productBarcode: { validators: { notEmpty: { message: 'Please enter Product Barcode' } } },
      productPrice: { validators: { notEmpty: { message: 'Please enter Product Price' } } },
      productCategory: { validators: { notEmpty: { message: 'Please select Product Category' } } },
      productStocks: { validators: { notEmpty: { message: 'Please enter Product Stock' } } },
      productStatus: { validators: { notEmpty: { message: 'Please select Product Status' } } },
    },
    plugins: {
      trigger: new FormValidation.plugins.Trigger(),
      bootstrap5: new FormValidation.plugins.Bootstrap5({
        eleValidClass: 'is-valid',
        rowSelector: function (field, ele) {
          return '.mb-5';
        }
      }),
      submitButton: new FormValidation.plugins.SubmitButton(),
      autoFocus: new FormValidation.plugins.AutoFocus()
    }
  });



  const previewTemplate = `<div class="dz-preview dz-file-preview">
<div class="dz-details">
  <div class="dz-thumbnail">
    <img data-dz-thumbnail>
    <span class="dz-nopreview">No preview</span>
    <div class="dz-success-mark"></div>
    <div class="dz-error-mark"></div>
    <div class="dz-error-message"><span data-dz-errormessage></span></div>
    <div class="progress">
      <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" data-dz-uploadprogress></div>
    </div>
  </div>
  <div class="dz-filename" data-dz-name></div>
  <div class="dz-size" data-dz-size></div>
</div>
</div>`;

  const dropzoneBasic = document.querySelector('#dropzone-basic');
    const myDropzone = new Dropzone(dropzoneBasic, {
      url: '/api/products/product-image-upload',
      previewTemplate: previewTemplate,
      parallelUploads: 1,
      maxFilesize: 10,
      acceptedFiles: '.jpg,.jpeg,.png,.gif',
      addRemoveLinks: true,
      maxFiles: 5,
      init: function () {
        this.on('addedfile', function (file) {
          if (this.files.length > 5) {
            this.removeFile(this.files[0]);
          }
        });

        this.on('success', function (file, response) {
          console.log(response);
          if (response.success) {
            toastr.options = { positionClass: 'toast-top-left' };
            toastr.success(response.message);
            storedImageIds.push(response.id);
          } else {
            toastr.error(response.message);
          }
        });

        this.on('error', function (file, response) {
          toastr.error(response.message);
        });

        this.on('removedfile', function (file) {
          //call the delete image api
          fetch('/api/products/product-image-delete', {
            method: 'POST',
            body: JSON.stringify({ image: eCommerceProductAddForm.querySelector('[name="productImage"]').value }),
            headers: { 'Content-Type': 'application/json' }
          })
            .catch(error => {
              console.error('Error:', error);
              alert('An unexpected error occurred.');
            });
        });
      }
    });



  fv.on('core.form.valid', function () {
    const submitButton = eCommerceProductAddForm.querySelector('[type="submit"]');

    if (!submitButton) {
      console.error("Submit button not found!");
      return;
    }

    submitButton.disabled = true;

    let formData = new FormData(eCommerceProductAddForm);
    storedImageIds.forEach((imageId) => {
      formData.append('productImage[]', imageId);
    })

    fetch(eCommerceProductAddForm.action, {
      method: eCommerceProductAddForm.method,
      body: formData,
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
      .then(response => response.json())
      .then(data => {
        toastr.options = { positionClass: 'toast-top-left' };

        if (data.success) {
          toastr.success(data.message);
          fv.resetForm(true);
          myDropzone.removeAllFiles();
        } else {
          toastr.error(data.message);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('An unexpected error occurred.');
      })
      .finally(() => {
        submitButton.disabled = false;
      });
  });


});

