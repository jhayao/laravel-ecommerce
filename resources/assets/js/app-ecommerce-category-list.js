/**
 * App eCommerce Category List
 */

'use strict';

// Comment editor

import { $ } from "../vendor/libs/jquery/jquery.js"

const commentEditor = document.querySelector('.comment-editor');

const quill = new Quill(commentEditor, {
  modules: {
    toolbar: '.comment-toolbar'
  },
  placeholder: 'Write a Comment...',
  theme: 'snow'
});

// Get hidden input field
const descriptionInput = document.getElementById('ecommerce-category-description-input');

// Sync Quill content to hidden input whenever user types
quill.on('text-change', function () {
  descriptionInput.value = quill.root.innerHTML.trim();
});

// Datatable (jquery)
var dt_category;

$(function () {
  let borderColor, bodyBg, headingColor;

  if (isDarkStyle) {
    borderColor = config.colors_dark.borderColor;
    bodyBg = config.colors_dark.bodyBg;
    headingColor = config.colors_dark.headingColor;
  } else {
    borderColor = config.colors.borderColor;
    bodyBg = config.colors.bodyBg;
    headingColor = config.colors.headingColor;
  }

  // Variable declaration for category list table


  //select2 for dropdowns in offcanvas

  var select2 = $('.select2');
  if (select2.length) {
    select2.each(function () {
      var $this = $(this);
      select2Focus($this);
      $this.wrap('<div class="position-relative"></div>').select2({
        dropdownParent: $this.parent(),
        placeholder: $this.data('placeholder') //for dynamic placeholder
      });
    });
  }

  // Customers List Datatable


});

var dt_category;

function initializeDataTable() {
  var dt_category_list_table = $('.datatables-category-list');
  if (dt_category_list_table.length) {
    dt_category = dt_category_list_table.DataTable({
      ajax: {
        url: '/api/categories/list', // Your server-side endpoint
        type: 'GET',
        dataSrc: 'data'
      },
      columns: [
        // columns according to JSON
        { data: '' },
        { data: 'id' },
        { data: 'categories' },
        { data: 'total_products' },
        { data: 'total_earnings' },
        { data: 'status'},
        { data: '' }
      ],
      columnDefs: [
        {
          // For Responsive
          className: 'control',
          searchable: false,
          orderable: false,
          targets: 0,
          render: function (data, type, full, meta) {
            return '';
          }
        },
        {
          // For Checkboxes
          targets: 1,
          orderable: false,
          searchable: false,
          responsivePriority: 4,
          // checkboxes: true,
          render: function () {
            return '<input type="checkbox" class="dt-checkboxes form-check-input">';
          },
          checkboxes: {
            selectAllRender: '<input type="checkbox" class="form-check-input">'
          }
        },
        {
          // Categories and Category Detail
          targets: 2,
          responsivePriority: 2,
          render: function (data, type, full, meta) {
            var $name = full['categories'],
              $category_detail = full['category_detail'],
              $image = full['cat_image'],
              $id = full['id'];
            if ($image) {
              // For Product image
              var $output =
                '<img src="' +
                // storagePath +
                $image +
                '" alt="Product-' +
                $id +
                '" class="rounded img-fluid">';
            } else {
              // For Product badge
              var stateNum = Math.floor(Math.random() * 6);
              var states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
              var $state = states[stateNum],
                $name = full['category_detail'],
                $initials = $name.match(/\b\w/g) || [];
              $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
              $output = '<span class="avatar-initial rounded-2 bg-label-' + $state + '">' + $initials + '</span>';
            }
            // Creates full output for Categories and Category Detail
            var $row_output =
              '<div class="d-flex align-items-center">' +
              '<div class="avatar-wrapper me-3 rounded bg-label-secondary user-name">' +
              '<div class="avatar">' +
              $output +
              '</div>' +
              '</div>' +
              '<div class="d-flex flex-column justify-content-center">' +
              '<span class="text-heading fw-medium text-wrap">' +
              $name +
              '</span>' +
              '<small class="text-truncate mb-0 d-none d-sm-block">' +
              $category_detail +
              '</small>' +
              '</div>' +
              '</div>';
            return $row_output;
          }
        },
        {
          // Total products
          targets: 3,
          responsivePriority: 3,
          render: function (data, type, full, meta) {
            var $total_products = full['total_products'];
            return '<div class="text-sm-end">' + $total_products + '</div>';
          }
        },
        {
          // Total Earnings
          targets: 4,
          orderable: false,
          render: function (data, type, full, meta) {
            var $total_earnings = full['total_earnings'];
            return "<div class='text-sm-end'>" + $total_earnings + '</div>';
          }
        },
        {
          //status
          targets: 5,
          orderable: false,
          render: function (data, type, full, meta) {
            var status = full['status'];
            var statusBadge = '';
            if (status === 'publish') {
              statusBadge = '<span class="badge rounded-pill bg-light-success text-success">Active</span>';
            } else if (status === 'inactive') {
              statusBadge = '<span class="badge rounded-pill bg-light-warning text-warning">Inactive</span>';
            }
            else if (status === 'deleted') {
              statusBadge = '<span class="badge rounded-pill bg-light-danger text-danger">Deleted</span>';
            }
            return statusBadge;
          }
        },
        {
          // Actions
          targets: -1,
          title: 'Actions',
          searchable: false,
          orderable: false,
          render: function (data, type, full, meta) {
            let status = full['status'];
            let message = status === 'publish' ? 'Delete' : 'Republish';
            if (status === 'deleted') {

            }
            return (
              '<div class="d-flex align-items-sm-center justify-content-sm-center">' +
              '<button class="btn btn-sm btn-icon bt-edit btn-text-secondary waves-effect waves-light rounded-pill"><i class="ri-edit-box-line ri-20px"></i></button>' +
              '<button class="btn btn-sm btn-icon btn-text-secondary waves-effect waves-light rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ri-more-2-line ri-20px"></i></button>' +
              '<div class="dropdown-menu dropdown-menu-end m-0">' +
              '<a href="javascript:0;" class="dropdown-item">View</a>' +
              '<a href="#" class="dropdown-item dt-delete" data-status="status">' + message + '</a>' +
              '</div>' +
              '</div>'
            );
          }
        }
      ],
      order: [3, 'desc'], //set any columns order asc/desc
      dom:
        '<"card-header d-flex rounded-0 flex-wrap pb-md-0 pt-0"' +
        '<"me-5 ms-n2"f>' +
        '<"d-flex justify-content-start justify-content-md-end align-items-baseline"<"dt-action-buttons d-flex align-items-start align-items-md-center justify-content-sm-center mb-0 gap-4"lB>>' +
        '>t' +
        '<"row mx-1"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      lengthMenu: [7, 10, 20, 50, 70, 100], //for length of menu
      language: {
        sLengthMenu: '_MENU_',
        search: '',
        searchPlaceholder: 'Search Category',
        paginate: {
          next: '<i class="ri-arrow-right-s-line"></i>',
          previous: '<i class="ri-arrow-left-s-line"></i>'
        }
      },
      // Button for offcanvas
      buttons: [
        {
          extend: 'collection',
          className: 'btn btn-outline-secondary dropdown-toggle me-4 waves-effect waves-light',
          text: '<i class="ri-upload-2-line me-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
          buttons: [
            {
              extend: 'print',
              text: '<i class="ri-printer-line me-1"></i>Print',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5],
                // prevent avatar to be print
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('user-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              },
              customize: function (win) {
                //customize print view for dark
                $(win.document.body)
                  .css('color', headingColor)
                  .css('border-color', borderColor)
                  .css('background-color', bodyBg);
                $(win.document.body)
                  .find('table')
                  .addClass('compact')
                  .css('color', 'inherit')
                  .css('border-color', 'inherit')
                  .css('background-color', 'inherit');
              }
            },
            {
              extend: 'csv',
              text: '<i class="ri-file-text-line me-1" ></i>Csv',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5],
                // prevent avatar to be display
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('user-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'excel',
              text: '<i class="ri-file-excel-line me-1"></i>Excel',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5],
                // prevent avatar to be display
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('user-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'pdf',
              text: '<i class="ri-file-pdf-line me-1"></i>Pdf',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5],
                // prevent avatar to be display
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('user-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'copy',
              text: '<i class="ri-file-copy-line me-1"></i>Copy',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5],
                // prevent avatar to be display
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('user-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            }
          ]
        },
        {
          text: '<i class="ri-add-line ri-16px me-0 me-sm-1 align-baseline"></i><span class="d-none d-sm-inline-block">Add Category</span>',
          className: 'add-new btn btn-primary waves-effect waves-light',
          attr: {
            'data-bs-toggle': 'offcanvas',
            'data-bs-target': '#offcanvasEcommerceCategoryList'
          }
        }
      ],
      // For responsive popup
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Details of ' + data['categories'];
            }
          }),
          type: 'column',
          renderer: function (api, rowIdx, columns) {
            var data = $.map(columns, function (col, i) {
              return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                ? '<tr data-dt-row="' +
                col.rowIndex +
                '" data-dt-column="' +
                col.columnIndex +
                '">' +
                '<td> ' +
                col.title +
                ':' +
                '</td> ' +
                '<td class="ps-0">' +
                col.data +
                '</td>' +
                '</tr>'
                : '';
            }).join('');

            return data ? $('<table class="table"/><tbody />').append(data) : false;
          }
        }
      }
    });
    $('.dataTables_length').addClass('my-0');
    $('.dt-action-buttons').addClass('pt-0');
  }
}


function reloadDataTable() {
  if (dt_category) {
    dt_category.ajax.reload(null, false); // Reload data without resetting pagination
  }
}

initializeDataTable();

//For form validation
(function () {
  const eCommerceCategoryListForm = document.getElementById('eCommerceCategoryListForm');
  //Add New customer Form Validation
  const fv = FormValidation.formValidation(eCommerceCategoryListForm, {
    fields: {
      title: {
        validators: {
          notEmpty: {
            message: 'Please enter category title'
          }
        }
      },
      slug: {
        validators: {
          notEmpty: {
            message: 'Please enter slug'
          }
        }
      },
      description: {
        validators: {
          notEmpty: {
            message: 'Please enter description'
          }
        }
      }
    },
    plugins: {
      trigger: new FormValidation.plugins.Trigger(),
      bootstrap5: new FormValidation.plugins.Bootstrap5({
        // Use this for enabling/changing valid/invalid class
        eleValidClass: 'is-valid',
        rowSelector: function (field, ele) {
          // field is the field name & ele is the field element
          return '.mb-5';
        }
      }),
      submitButton: new FormValidation.plugins.SubmitButton(),
      // Submit the form when all fields are valid
      // defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
      autoFocus: new FormValidation.plugins.AutoFocus()
    }
  });

  fv.on('core.form.valid', function () {
    // Disable submit button to prevent multiple submits
    const submitButton = eCommerceCategoryListForm.querySelector('[type="submit"]');
    submitButton.disabled = true;

    // Collect form data
    const formData = new FormData(eCommerceCategoryListForm);

    // Send AJAX request
    fetch(eCommerceCategoryListForm.action, {
      method: eCommerceCategoryListForm.method, // GET or POST
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest' // Ensure it's an AJAX request
      }
    })
      .then(response => response.json())
      .then(data => {
        const options = (toastr.options = {
          positionClass: 'toast-top-left'
        });
        if (data.success) {
          toastr.success(options, data.message);
          reloadDataTable();
          // Optionally reset form
          fv.resetForm(true);
        } else {
          console.log(data);
          toastr.error(options, data.message);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('An unexpected error occurred.');
      })
      .finally(() => {
        submitButton.disabled = false; // Re-enable button after request
      });
  });
})();


$(document).ready(function () {
  // Delete row
  $('.datatables-category-list').on('click', '.dt-delete', function (e) {
    var $row = $(this).closest('tr');
    var data = $('.datatables-category-list').DataTable().row($row).data();
    var id = data.id;
    var status = data.status;
    var url = '/api/categories/delete/' + id;
    var $table = $('.datatables-category-list').DataTable();
    var $row = $(this).closest('tr');
    var data = $table.row($row).data();
    var message = '';
    var confirmButtonText = '';
    var type = 'DELETE'
    var responseMsg = 'Deleted!';
    if (status === 'publish') {
        message = 'All Products under this category will be deleted!'
        confirmButtonText = 'Yes, delete it!'
    } else {
      message = 'All Products under this category will be restored!'
      confirmButtonText = 'Yes, republish it!'
      url = '/api/categories/restore/' + id;
      type = 'POST';
      responseMsg = 'Restored!';
    }

    Swal.fire({
      title: 'Are you sure?',
      text: message,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#D94148',
      cancelButtonColor: '#536DE6',
      confirmButtonText: confirmButtonText,
      customClass: {
        confirmButton: 'btn btn-primary me-1 waves-effect waves-light',
        cancelButton: 'btn btn-outline-secondary waves-effect'
      },
      buttonsStyling: false
    }).then(function (result) {
      if (result.isConfirmed) {
        $.ajax({
          url: url,
          type: type,
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function (response) {
            if (response.success) {
              Swal.fire({
                title: responseMsg,
                text: 'The product has been ' + responseMsg.toLocaleLowerCase(),
                icon: 'success',
                showCancelButton: false,
                confirmButtonText: 'Okay',
              });
            } else {
              Swal.fire({
                title: 'Error!',
                text: 'The product has not been ' + responseMsg.toLocaleLowerCase(),
                icon: 'error',
                showCancelButton: false,
                confirmButtonColor: '#D94148',
                cancelButtonColor: '#536DE6',
                confirmButtonText: 'Okay',
                customClass: {
                  confirmButton: 'btn btn-primary me-1 waves-effect waves-light',
                  cancelButton: 'btn btn-outline-secondary waves-effect'
                },
                buttonsStyling: false
              });
            }
          }
        }).always(function () {
          $table.ajax.reload(null,false);
        });
      }
    });
  });
});

$(document).ready(function () {
  $('.datatables-category-list').on('click', '.bt-edit', function (e) {
    var $row = $(this).closest('tr');
    var data = $('.datatables-category-list').DataTable().row($row).data();
    var id = data.id;
    // var url = '/products/edit/' + id;
    console.log(id);
    $.ajax({
      url: '/api/categories/category-details/' + id,
      type: 'GET',
      success: function (response) {
        if (response) {
          $('#ecommerce-category-title').val(response.title);
          $('#ecommerce-category-slug').val(response.slug);
          // $('#ecommerce-category-description-input').val(category.description);
          quill.root.innerHTML = response.description;
          $('#ecommerce-category-parent-category').val(response.parent_id ?? '0').trigger('change');
          $('#ecommerce-category-status').val(response.status).trigger('change');
          var offcanvas = new bootstrap.Offcanvas($('#offcanvasEcommerceCategoryList')[0]);
          offcanvas.show();
          $('#eCommerceCategoryListForm').attr('action', '/category/update/' + id);
          $('#frmSubmit').text('Update');
        }
      }
    })
  });

  $('#offcanvasEcommerceCategoryList').on('hidden.bs.offcanvas', function () {
    $('#eCommerceCategoryListForm')[0].reset(); // Reset the form
    $('#ecommerce-category-parent-category').val(null).trigger('change'); // Reset Select2
    $('#ecommerce-category-status').val(null).trigger('change');
    quill.root.innerHTML = ''; // Reset Quill
    $('#frmSubmit').text('Add');
    $('#eCommerceCategoryListForm').attr('action', '/category');
  });
});
