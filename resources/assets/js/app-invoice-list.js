/**
 * App Invoice List (jquery)
 */

'use strict';

$(function () {
  // Variable declaration for table
  var dt_invoice_table = $('.invoice-list-table');

  // Invoice datatable
  if (dt_invoice_table.length) {
    var dt_invoice = dt_invoice_table.DataTable({
      ajax: '/api/admin/payment-list', // API endpoint to fetch data
      columns: [
        // columns according to API response
        { data: 'id' },
        { data: 'order.order_number' },
        { data: 'order.customer.full_name' },
        { data: 'method' },
        { data: 'status' },
        { data: 'amount' },
        { data: 'created_at' },
        { data: 'updated_at' },
        { data: 'action' }
      ],
      columnDefs: [
        {
          // For Responsive
          className: 'control',
          responsivePriority: 2,
          searchable: false,
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
            return '<input type="checkbox" class="dt-checkboxes form-check-input">';
          },
          searchable: false
        },
        {
          // Order Number
          targets: 2,
          render: function (data, type, full, meta) {
            var $order_number = full['order']['order_number'];
            // Creates full output for row
            var $row_output = '<a href="' + baseUrl + 'invoice/details/16"><span>#' + $order_number + '</span></a>';
            return $row_output;
          }
        },
        {
          // Customer Name
          targets: 3,
          render: function (data, type, full, meta) {
            var $name = full['order']['customer']['full_name'],
              $id = full.order.customer.id,
              $image = full['order']['customer']['profile_picture'];
            var $output = '<img src="' + $image + '" alt="Avatar" class="rounded-circle">';
            // Creates full output for row
            var $row_output =
              '<div class="d-flex justify-content-start align-items-center">' +
              '<div class="avatar-wrapper">' +
              '<div class="avatar avatar-sm me-3">' +
              $output +
              '</div>' +
              '</div>' +
              '<div class="d-flex flex-column">' +
              '<a href="' +
              baseUrl +
              'customers/details/overview/' + $id + '" class="text-truncate text-heading"><p class="mb-0 fw-medium">' +
              $name +
              '</p></a>' +
              '</div>' +
              '</div>';
            console.log($row_output);
            return $row_output;
          }
        },
        {
          // Payment Method
          targets: 6,
          render: function (data, type, full, meta) {
            var $method = 'cod';
            var $method_number = 'Cash on Delivery';

            if ($method == 'paypal') {
              $method_number = '@gmail.com';
            }
            return (
              '<div class="d-flex align-items-center text-nowrap">' +
              '<img src="' +
              assetsPath +
              'img/icons/payments/' +
              $method +
              '.png" alt="' +
              $method +
              '" class="me-2" width="29">' +
              '<span><i class=""></i>' +
              $method_number +
              '</span>' +
              '</div>'
            );
          }
        },
        {
          // Payment Status
          targets: 7,
          render: function (data, type, full, meta) {
            var $status = full['status'];
            var statusBadgeObj = {
              pending: '<span class="badge bg-label-warning">Pending</span>',
              success: '<span class="badge bg-label-success">Success</span>',
              failed: '<span class="badge bg-label-danger">Failed</span>'
            };
            return statusBadgeObj[$status];
          }
        },
        {
          // Amount
          targets: 4,
          render: function (data, type, full, meta) {
            return '<span>₱ ' + full['amount'] + '</span>';
          }
        },
        {
          // Created At
          targets: 5,
          render: function (data, type, full, meta) {
            var $created_at = new Date(full['updated_at']);
            return moment($created_at).format('DD MMM YYYY');
          }
        },

        {
          // Actions
          targets: -1,
          title: 'Actions',
          searchable: false,
          orderable: false,
          render: function (data, type, full, meta) {
            return (
              '<div class="d-flex align-items-center">' +
              '<a href="javascript:;" data-bs-toggle="tooltip" class="btn btn-sm btn-icon btn-text-secondary waves-effect waves-light rounded-pill delete-record" data-bs-placement="top" title="Delete Payment"><i class="ri-delete-bin-7-line ri-20px"></i></a>' +
              '<a href="' +
              baseUrl +
              'app/invoice/preview" data-bs-toggle="tooltip" class="btn btn-sm btn-icon btn-text-secondary waves-effect waves-light rounded-pill"  data-bs-placement="top" title="Preview Payment"><i class="ri-eye-line ri-20px"></i></a>' +
              '<div class="dropdown">' +
              '<a href="javascript:;" class="btn btn-icon btn-sm btn-text-secondary waves-effect waves-light rounded-pill dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown"><i class="ri-more-2-line ri-20px"></i></a>' +
              '<div class="dropdown-menu dropdown-menu-end">' +
              '<a href="javascript:;" class="dropdown-item">Download</a>' +
              '<a href="' +
              baseUrl +
              'app/invoice/edit" class="dropdown-item">Edit</a>' +
              '<a href="javascript:;" class="dropdown-item">Duplicate</a>' +
              '</div>' +
              '</div>' +
              '</div>'
            );
          }
        }
      ],
      order: [[2, 'desc']],
      dom:
        '<"row mx-1"' +
        '<"col-12 col-md-6 d-flex align-items-center justify-content-center justify-content-md-start gap-4 mt-md-0 mt-5"l<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start"B>>' +
        '<"col-12 col-md-6 d-flex align-items-center justify-content-end flex-column flex-md-row pe-3 gap-md-4"f<"invoice_status mb-5 mb-md-0">>' +
        '>t' +
        '<"row mx-2"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      language: {
        sLengthMenu: 'Show _MENU_',
        search: '',
        searchPlaceholder: 'Search Payment',
        paginate: {
          next: '<i class="ri-arrow-right-s-line"></i>',
          previous: '<i class="ri-arrow-left-s-line"></i>'
        }
      },
      // Buttons with Dropdown
      buttons: [
        {
          text: '<i class="ri-add-line ri-16px me-md-2 align-baseline"></i><span class="d-md-inline-block d-none">Create Payment</span>',
          className: 'btn btn-primary waves-effect waves-light',
          action: function (e, dt, button, config) {
            window.location = baseUrl + 'app/invoice/add';
          }
        }
      ],
      // For responsive popup
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Details of ' + data['order']['customer']['full_name'];
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

            return data ? $('<table class="table"/><tbody />').append(data) : false;
          }
        }
      },
      initComplete: function () {
        // Adding role filter once table initialized
        // this.api()
        //   .columns(4)
        //   .every(function () {
        //     var column = this;
        //     var select = $(
        //       '<select id="PaymentStatus" class="form-select"><option value=""> Select Status </option></select>'
        //     )
        //       .appendTo('.invoice_status')
        //       .on('change', function () {
        //         var val = $.fn.dataTable.util.escapeRegex($(this).val());
        //         console.log(val);
        //         column.search(val ? '^' + val + '$' : '', true, false).draw();
        //       });
        //
        //     column
        //       .data()
        //       .unique()
        //       .sort()
        //       .each(function (d, j) {
        //         select.append('<option value="' + d + '" class="text-capitalize">' + d + '</option>');
        //       });
        //   });
      }
    });
  }

  // On each datatable draw, initialize tooltip
  dt_invoice_table.on('draw.dt', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl, {
        boundary: document.body
      });
    });
  });

  // Delete Record
  $('.invoice-list-table tbody').on('click', '.delete-record', function () {
    // To hide tooltip on clicking delete icon
    $(this).closest($('[data-bs-toggle="tooltip"]').tooltip('hide'));
    // To delete the whole row
    dt_invoice.row($(this).parents('tr')).remove().draw();
  });

  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  setTimeout(() => {
    $('.invoice_status .form-select').addClass('form-select-sm');
  }, 300);
});
