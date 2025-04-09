/**
 * Page Detail overview
 */

'use strict';

// Datatable (jquery)
$(function () {
  // Extract customer ID from URL
  var customerId = window.location.pathname.split('/').pop();

  // Variable declaration for table
  var dt_customer_order = $('.datatables-customer-order'),
    order_details = baseUrl + 'orders/details/',
    statusObj = {
      1: { title: 'Cancelled', class: 'bg-label-success' },
      2: { title: 'Completed', class: 'bg-label-primary' },
      3: { title: 'Processing', class: 'bg-label-info' },
      4: { title: 'Pending', class: 'bg-label-secondary' }
    },
    paymentObj = {
      1: { title: 'Paid', class: 'text-success' },
      2: { title: 'Pending', class: 'text-warning' },
      3: { title: 'Failed', class: 'text-danger' },
    };

  // orders datatable
  if (dt_customer_order.length) {
    $.ajax({
      url: '/api/admin/customer-order-list/' + customerId,
      method: 'GET',
      success: function (response) {
        var dt_order = dt_customer_order.DataTable({
          data: response.data,
          columns: [
            { data: 'id' },
            { data: 'order_number' },
            { data: 'created_at' },
            { data: 'status_id' },
            { data: 'total' },
            { data: ' ' }
          ],
          columnDefs: [
            {
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
              targets: 1,
              responsivePriority: 4,
              render: function (data, type, full, meta) {
                var $id = full['order_number'];

                return "<a href='" + order_details + $id + "'><span>#" + $id + '</span></a>';
              }
            },
            {
              targets: 2,
              render: function (data, type, full, meta) {
                var date = new Date(full.created_at);
                var formattedDate = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                return '<span class="text-nowrap">' + formattedDate + '</span>';
              }
            },
            {
              targets: 3,
              render: function (data, type, full, meta) {
                var $status = full['status_id'];
                return (
                  '<span class="badge rounded-pill ' +
                  statusObj[$status].class +
                  '" text-capitalized>' +
                  statusObj[$status].title +
                  '</span>'
                );
              }
            },
            {
              targets: 4,
              render: function (data, type, full, meta) {
                var $total = full.payment.amount;
                return '<span>₱ ' + $total + '</span>';
              }
            },
            {
              targets: -1,
              title: 'Actions',
              searchable: false,
              orderable: false,
              render: function (data, type, full, meta) {
                return (
                  '<div>' +
                  '<button class="btn btn-sm btn-icon btn-text-secondary waves-effect waves-light rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ri-more-2-line ri-20px"></i></button>' +
                  '<div class="dropdown-menu dropdown-menu-end m-0">' +
                  '<a href="javascript:;" class="dropdown-item">View</a>' +
                  // '<a href="javascript:;" class="dropdown-item delete-record">Delete</a>' +
                  '</div>' +
                  '</div>'
                );
              }
            }
          ],
          order: [[1, 'desc']],
          dom:
            '<"card-header d-flex flex-wrap py-0 pt-5 pt-sm-0 flex-column flex-sm-row"<"head-label text-center me-4 ms-1">f' +
            '>t' +
            '<"row mx-4"' +
            '<"col-md-12 col-xxl-6 text-center text-xxl-start pb-2 pb-xxl-0 pe-0"i>' +
            '<"col-md-12 col-xxl-6"p>' +
            '>',
          lengthMenu: [6, 30, 50, 70, 100],
          language: {
            sLengthMenu: '_MENU_',
            search: '',
            searchPlaceholder: 'Search order',
            paginate: {
              next: '<i class="ri-arrow-right-s-line"></i>',
              previous: '<i class="ri-arrow-left-s-line"></i>'
            }
          },
          responsive: {
            details: {
              display: $.fn.dataTable.Responsive.display.modal({
                header: function (row) {
                  var data = row.data();
                  return 'Details of ' + data['order_number'];
                }
              }),
              type: 'column',
              renderer: function (api, rowIdx, columns) {
                var data = $.map(columns, function (col, i) {
                  return col.title !== ''
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
          }
        });
        $('div.head-label').html('<h5 class="card-title mb-0 text-nowrap">Orders placed</h5>');
        $('.pagination').addClass('justify-content-xxl-end justify-content-center');
      }
    });
  }

  // Delete Record
  $('.datatables-orders tbody').on('click', '.delete-record', function () {
    dt_order.row($(this).parents('tr')).remove().draw();
  });
});

// Validation & Phone mask


