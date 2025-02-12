@extends('layouts.app')

@section('content')
<div class='s-page-title'>Orders</div>
<div style='background-color: lightgrey; width: 90%; border: 2px solid green;padding-left:1%; margin-top: 1%; margin-right: 5%;margin-left: 5%;'>
    <!-- Multiple Radios -->
    <div class="customer-section">
</div>

<!-- Order Details Form -->
<form id="orderDetailsForm" class="form-horizontal" style="height: 600px;">
    <fieldset>
        <div style="display:flex">
            <div class="section_logo"><img width="30px" height="30px" src="{{ asset('images/order.png') }}"/></div>
            <div class="section_title">Order Information</div>
        </div>
        <div style="display:inline-flex;padding-top: 15px;">
            <form id="order_search">
                <div class="col-md-4" id="Sittings">
                    <label class="col-md-4 control-label" >Order Type</label>
                    <select id="search-otype" name="otype" class="form-control" style="width: 80%;">
                        <option value="1">Studio Sittings</option>
                        <option value="2">Extra Copy</option>
                        <option value="3">Media</option>
                        <option value="4">Frames</option>
                    </select>
                </div>
                <!-- <div class="col-md-4" id="Sittings">
                    <label class="col-md-4 control-label" >Item</label>
                    <select id="sitting_item" name="item" class="form-control" style="width: 80%;">
                        <option value="0">ALL ITEMS</option>
                        <option value="1">Passport</option>
                        <option value="2">NIC</option>
                        <option value="3">Stamp</option>
                    </select>
                </div> -->
                <div class="col-md-4" id="cname">
                        <label class="col-md-4 control-label" for="name">Name</label>
                        <input id="search-name" name="username" style="width: 80%;" type="text" class="form-control input-md" required="">
                        <span class="error-message text-danger" id="username-error"></span>
  
                </div>
                <div class="col-md-4" id="cphone">
                        <label class="col-md-4 control-label" for="phone">Phone</label>
                        <input id="search-phone" name="phonenumber" style="width: 80%;" type="text" class="form-control input-md" required="">
                        <span class="error-message text-danger" id="phonenumber-error"></span>
                </div>
                <div class="col-md-4" style="padding-top: 30px;">
                        <button type="submit" class="btn btn-primary">Search</button>
                </div>

            </Form> 

        </div></br></br>
        <div>
            <div  style="background-color:#aaa;">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Order No.</th>
                            <th>Order Type</th>
                            <th>Date Time</th>
                            <th>Urgent</th>
                            <th>Comments</th>
                            <th>Total Cost (LKR)</th>
                            <th>Total Paid (LKR)</th>
                            <th>Comments</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="order-summary">
                        <!-- Orders will be dynamically added here -->
                    </tbody>
                </table>
            </div>
        </div>
    </fieldset>
</form>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Existing order Search
    $('#order_search').on('submit', function(e) {
        e.preventDefault();
        const searchData = {
            otype: $('#search-otype').val(),
            name: $('#search-name').val(),
            phone: $('#search-phone').val(),
            _token: $('input[name="_token"]').val()
        };

        $.ajax({
            url: "{{ route('orders.search') }}",
            method: 'POST',
            data: searchData,
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    displaySearchResults(response.customers);
                }
            },
            error: function() {
                $('#search-results').html(
                    '<div class="alert alert-danger">Error performing search.</div>'
                );
            }
        });
    });

    function displaySearchResults(customers) {
        if (!customers.length) {
            $('#search-results').html(
                '<div class="alert alert-info">No customers found.</div>'
            );
            return;
        }

        let html = `
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
        `;

        customers.forEach(function(customer) {
            html += `
                <tr>
                    <td>${customer.username}</td>
                    <td>${customer.phonenumber}</td>
                    <td>${customer.address || ''}</td>
                    <td>${customer.email || ''}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-primary select-customer"
                                data-id="${customer.id}" data-name="${customer.username}">
                            Select
                        </button>
                    </td>
                </tr>
            `;
        });

        html += '</tbody></table>';
        $('#search-results').html(html);
    }

    // Handle customer selection
    $(document).on('click', '.select-customer', function() {
    const customerId = $(this).data('id');
    const customerName = $(this).data('name');

    // Store selected customer info in the input fields
    $('#selected-customer-id').val(customerId);
    $('#selected-customer-name').text(customerName);

    // Send AJAX request to store customer info in session
    $.ajax({
        url: "{{ url('/set-customer-session') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            customer_id: customerId,
            customer_name: customerName
        },
        success: function(response) {
            console.log(response.message);
            updateCustomerName();
        },
        error: function(xhr) {
            console.error("Error setting session:", xhr);
        }
    });
});
});

function updateCustomerName() {
            $.ajax({
                url: "{{ url('/get-customer-session') }}",
                type: "GET",
                success: function (response) {
                    if (response.customer_name) {
                        $("#customer-name").text(response.customer_name);
                        generateOrderId();
                    } else {
                        $("#customer-name").text("No customer selected");
                    }
                }
            });
        }
function generateOrderId() {
    $.ajax({
        url: "{{ url('/set-order-session') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}"
        },
        success: function(response) {
            if (response.status === 'success') {
                $("#order-id").text(response.order_id);
            }
        },
        error: function(xhr) {
            console.error("Error generating order ID:", xhr);
        }
    });
}
</script>
@endpush
@endsection
