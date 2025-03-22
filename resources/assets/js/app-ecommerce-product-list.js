/**
 * app-ecommerce-product-list
 */

'use strict';

// Datatable (jquery)
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

  // Variable declaration for table
  var categoryObjApi = {};

  $.ajax({
    url: '/api/categories/category-title',
    type: 'GET',
    success: function (response) {
      response.forEach(function (category) {
        categoryObjApi[category.id] = { title: category.title };
      });
    }
  });
  console.log(categoryObjApi);
  var dt_product_table = $('.datatables-products'),
    productAdd = baseUrl + 'products/add',
    statusObj = {
      1: { title: 'Deleted', class: 'bg-label-danger' },
      2: { title: 'Publish', class: 'bg-label-success' },
      3: { title: 'Inactive', class: 'bg-label-warning' }
    },
    categoryObj = categoryObjApi,
    stockObj = {
      0: { title: 'Out_of_Stock' },
      1: { title: 'In_Stock' }
    },
    stockFilterValObj = {
      0: { title: 'Out of Stock' },
      1: { title: 'In Stock' }
    };

  // E-commerce Products datatable

  if (dt_product_table.length) {
    var dt_products = dt_product_table.DataTable({
      ajax: {
        url: '/api/products/admin-list/true', // Your server-side endpoint
        type: 'GET',
        dataSrc: 'data'
      },
      columns: [
        // columns according to JSON
        { data: 'id' },
        { data: 'id' },
        { data: 'product_name' },
        { data: 'category_title' },
        { data: 'stock' },
        { data: 'sku' },
        { data: 'price' },
        { data: 'status_id' },
        { data: '' }
      ],
      columnDefs: [
        {
          // For Responsive
          className: 'control',
          searchable: false,
          orderable: false,
          responsivePriority: 2,
          targets: 0,
          render: function (data, type, full, meta) {
            return '';
          }
        },
        {
          // For Checkboxes
          targets: 1,
          orderable: false,
          checkboxes: {
            selectAllRender: '<input type="checkbox" class="form-check-input">'
          },
          render: function () {
            return '<input type="checkbox" class="dt-checkboxes form-check-input" >';
          },
          searchable: false
        },
        {
          // Product name and product_brand
          targets: 2,
          responsivePriority: 1,
          render: function (data, type, full, meta) {
            var name = full['name'],
              id = full['id'],
              product_brand = full['description'] ?? '',
              image = full['image'];
              status = statusObj[full['status_id']].title;
            if (image) {
              // For Product image

              var output =
                '<img src="' +
                storagePath +
                image +
                '" alt="Product-' +
                id +
                '" class="rounded-2">';
            } else {
              // For Product badge
              var stateNum = Math.floor(Math.random() * 6);
              var states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
              var state = states[stateNum],
                name = full['name'] ,
                initials = name.match(/\b\w/g) || [];
              initials = ((initials.shift() || '') + (initials.pop() || '')).toUpperCase();
              output = '<span class="avatar-initial img-fluid h-auto rounded-2 bg-label-' + state + '">' + initials + '</span>';
            }
            // Creates full output for Product name and product_brand
            var row_output =
              '<div class="d-flex justify-content-start align-items-center product-name">' +
              '<div class="avatar-wrapper me-3">' +
              '<div class="avatar rounded-3 bg-label-secondary">' +
              output +
              '</div>' +
              '</div>' +
              '<div class="d-flex flex-column">' +
              '<span class="text-nowrap text-heading fw-medium">' +
              name +
              '</span>' +
              '<small class="text-truncate d-none d-sm-block">' +
              product_brand +
              '</small>' +
              '</div>' +
              '</div>';
            return row_output;
          }
        },
        {
          // Product Category
          targets: 3,
          responsivePriority: 5,
          render: function (data, type, full, meta) {

            var category = full['category_title'];
            return (
              "<h6 class='text-truncate d-flex align-items-center mb-0 fw-normal'>" +
              category +
              '</h6>'
            );
          }
        },
        {
          // Stock
          targets: 4,
          render: function (data, type, full, meta) {
            var $sku = full['stock'];

            return '<span>' + $sku + '</span>';
          }
        },
        {
          // Sku
          targets: 5,
          render: function (data, type, full, meta) {
            var $sku = full['sku'];

            return '<span>' + $sku + '</span>';
          }
        },
        {
          // price
          targets: 6,
          render: function (data, type, full, meta) {
            var $price = full['price'];
            return '<span>' + $price + '</span>';
          }
        },
        {
          // Status
          targets: -2,
          render: function (data, type, full, meta) {
            var status = statusObj[full['status_id']];
            return (
              '<span class="badge rounded-pill ' +
              status.class +
              '" text-capitalized>' +
              status.title +
              '</span>'
            );
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
            let btnEditClass = status === 'deleted' ? 'disabled' : '';
            let btnDeleteText = status === 'deleted' ? 'Restore' : 'Delete';

            return (
              '<div class="d-inline-block text-nowrap">' +
              '<button class="btn btn-sm btn-icon btn-text-secondary waves-effect waves-light ' + btnEditClass + ' rounded-pill me-50 dt-edit"><i class="ri-edit-box-line ri-20px"></i></button>' +
              '<button class="btn btn-sm btn-icon btn-text-secondary waves-effect waves-light rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ri-more-2-line ri-20px"></i></button>' +
              '<div class="dropdown-menu dropdown-menu-end m-0">' +
              '<a href="javascript:0;" class="dropdown-item ' + btnEditClass + ' dt-edit">View</a>' +
              '<a href="#" class="dropdown-item dt-delete">' + btnDeleteText + '</a>' +
              '</div>' +
              '</div>'
            );
          }
        }
      ],
      order: [2, 'asc'], //set any columns order asc/desc
      dom:
        '<"card-header d-flex border-top rounded-0 flex-wrap pb-md-0 pt-0"' +
        '<"me-5 ms-n2"f>' +
        '<"d-flex justify-content-start justify-content-md-end align-items-baseline"<"dt-action-buttons d-flex align-items-start align-items-md-center justify-content-sm-center gap-4"lB>>' +
        '>t' +
        '<"row mx-1"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      lengthMenu: [7, 10, 20, 50, 70, 100], //for length of menu
      language: {
        sLengthMenu: '_MENU_',
        search: '',
        searchPlaceholder: 'Search',
        info: 'Displaying _START_ to _END_ of _TOTAL_ entries',
        paginate: {
          next: '<i class="ri-arrow-right-s-line"></i>',
          previous: '<i class="ri-arrow-left-s-line"></i>'
        }
      },
      // Buttons with Dropdown
      buttons: [
        {
          extend: 'collection',
          className: 'btn btn-outline-secondary dropdown-toggle me-4 waves-effect waves-light',
          text: '<i class="ri-upload-2-line ri-16px me-2"></i><span class="d-none d-sm-inline-block">Export </span>',
          buttons: [
            {
              extend: 'print',
              text: '<i class="ri-printer-line me-1" ></i>Print',
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
                      if (item.classList !== undefined && item.classList.contains('product-name')) {
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
                      if (item.classList !== undefined && item.classList.contains('product-name')) {
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
                      if (item.classList !== undefined && item.classList.contains('product-name')) {
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
                      if (item.classList !== undefined && item.classList.contains('product-name')) {
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
                      if (item.classList !== undefined && item.classList.contains('product-name')) {
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
          text: '<i class="ri-add-line ri-16px me-0 me-sm-2 align-baseline"></i><span class="d-none d-sm-inline-block">Add Product</span>',
          className: 'add-new btn btn-primary waves-effect waves-light',
          action: function () {
            window.location.href = productAdd;
          }
        }
      ],
      // For responsive popup
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              console.log(data)
              return 'Details of ' + data['name'];
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
                    '<td>' +
                    col.title +
                    ':' +
                    '</td> ' +
                    '<td>' +
                    col.data +
                    '</td>' +
                    '</tr>'
                : '';
            }).join('');
            console.log(data)
            return data ? $('<table class="table productTable"/><tbody />').append(data) : false;
          }
        }
      },
      initComplete: function () {
        // Adding status filter once table initialized


        $(document).on('click', '.productTable .dt-edit', function (e) {
          var $row = $(this).closest('tr');
          var data = $('.datatables-products').DataTable().row($row).data();
          var id = data.id;
          var url = '/products/edit/' + id;

          window.location.href = url;
        });

        $(document).on('click', '.productTable .dt-delete', function (e) {
          var $row = $(this).closest('tr');
          var data = $('.datatables-products').DataTable().row($row).data();
          var id = data.id;
          var url = '/api/products/delete/' + id;
          var status = data.status;
          var responseMsg = status === 'deleted' ? 'Restore' : 'Delete';
          url = status === 'deleted' ? '/api/products/restore/' + id : url;
          var type = status === 'deleted' ? 'POST' : 'DELETE';
          var table = $('.datatables-products').DataTable();

          Swal.fire({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#D94148',
            cancelButtonColor: '#536DE6',
            confirmButtonText: 'Yes, ' + responseMsg.toLowerCase() + ' it!',
            customClass: {
              confirmButton: 'btn btn-primary me-1 waves-effect waves-light',
              cancelButton: 'btn btn-outline-secondary waves-effect'
            },
            buttonsStyling: false
          }).then(function (result) {
            if (result.isConfirmed) {
              $.ajax({
                url: url,
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: type,
                success: function (response) {
                  if (response.success) {
                    Swal.fire({
                      title: responseMsg + '!',
                      text: 'The product has been '+ responseMsg.toLowerCase() +'.',
                      icon: 'success',
                      showCancelButton: false,
                      confirmButtonText: 'Okay',
                    });
                  } else {
                    Swal.fire({
                      title: 'Error!',
                      text: 'The product has not been '+ responseMsg.toLowerCase() +'.',
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
                table.ajax.reload();
              })
            }
          });
        });


        this.api()
          .columns(-2)
          .every(function () {
            var column = this;
            var select = $(
              '<select id="ProductStatus" class="form-select text-capitalize"><option value="">Status</option></select>'
            )
              .appendTo('.product_status')
              .on('change', function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                column.search(val ? '^' + val + '$' : '', true, false).draw();
              });

            column
              .data()
              .unique()
              .sort()
              .each(function (d, j) {
                select.append('<option value="' + statusObj[d].title + '">' + statusObj[d].title + '</option>');
              });
          });
        // Adding category filter once table initialized
        this.api()
          .columns(3)
          .every(function () {
            var column = this;
            var select = $(
              '<select id="ProductCategory" class="form-select text-capitalize"><option value="">Category</option></select>'
            )
              .appendTo('.product_category')
              .on('change', function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                column.search(val ? '^' + val + '$' : '', true, false).draw();
              });

            column
              .data()
              .unique()
              .sort()
              .each(function (d, j) {
                select.append('<option value="' + d + '">' + d + '</option>');
              });
          });
        // Adding stock filter once table initialized
        this.api()
          .columns(4) // Adjust column index as needed
          .every(function () {
            var column = this;
            var select = $(
              `<select id="ProductStock" class="form-select text-capitalize">
        <option value="">Stock</option>
        <option value="in_stock">Have Stock</option>
        <option value="out_of_stock">Out of Stock</option>
      </select>`
            )
              .appendTo('.product_stock')
              .on('change', function () {
                var val = $(this).val();

                if (val === "in_stock") {
                  column.search("^(?!0$).*$", true, false).draw(); // Matches any number > 0
                } else if (val === "out_of_stock") {
                  column.search("^0$", true, false).draw(); // Matches exactly 0
                } else {
                  column.search("").draw(); // Reset filter
                }
              });
          });

      }
    });
    $('.dt-action-buttons').addClass('pt-0');
    $('.dt-buttons').addClass('d-flex flex-wrap');




  }
});

//document after render
$(document).ready(function () {


  // Edit row
});
