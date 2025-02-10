@extends('layouts.app')

@section('content')
<div class='s-page-title'>Place New Order</div>
<div style='background-color: lightgrey; width: 90%; border: 2px solid green;padding-left:1%; margin-top: 1%; margin-right: 5%;margin-left: 5%;'>
<!-- Multiple Radios -->
<div class="customer-section">
    <fieldset>
        <!-- Form Name -->
        <div style="display:flex">
            <div class="section_logo"><img width="30px" height="30px" src="{{ asset('images/customer.png') }}"/></div>
            <div class="section_title">Customer Information</div>
        </div>

        <div class="form-group">
            <div class="col-md-4" style='display: flex'>
                <div class="radio">
                    <label for="radios-0">
                        <input type="radio" name="client-radio" id="radios-0" value="1" checked="checked">
                        New
                    </label>
                    <label for="radios-1">
                        <input type="radio" name="client-radio" id="radios-1" value="2">
                        Existing
                    </label>
                </div>
            </div>
        </div>

        <!-- New Customer Form -->
        <form id="newCustomerForm" class="form-horizontal" >
            @csrf
            <div id='newuser'>
                <div class="form-group" style="display:flex;gap: 50px">
                    <div class="col-md-4">
                        <label class="col-md-4 control-label" for="name">Name (*)</label>
                        <input id="name" name="username" type="text" class="form-control input-md" required="">
                        <span class="error-message text-danger" id="username-error"></span>
                    </div>
                    <div class="col-md-4">
                        <label class="col-md-4 control-label" for="phone">Phone (*)</label>
                        <input id="phone" name="phonenumber" type="text" class="form-control input-md" required="">
                        <span class="error-message text-danger" id="phonenumber-error"></span>
                    </div>
                </div>
                <div class="form-group" style="display:flex;gap: 50px">
                    <div class="col-md-4">
                        <label class="col-md-4 control-label" for="town">Town</label>
                        <select id="town" name="address" class="form-control">
                            <option value="1">Arangala</option>
                            <option value="2">Hokandara</option>
                            <option value="3">Malabe</option>
                        </select>
                        <span class="error-message text-danger" id="address-error"></span>
                        <input type="hidden" name="address_text" id="address_text">
                    </div>
                    <div class="col-md-4">
                        <label class="col-md-4 control-label" for="email">Email</label>
                        <input id="email" name="email" type="text" class="form-control input-md">
                        <span class="error-message text-danger" id="email-error"></span>
                    </div>
                    <div class="col-md-4" style="padding-top: 30px;">
                        <button type="submit" class="btn btn-primary">Register</button>
                    </div>
                </div>
                <div id="new-customer-message" class="alert" style="display: none; margin-top: 15px;"></div>
            </div>
        </form>

        <!-- Existing Customer Form -->
        <form id="existingCustomerForm" class="form-horizontal" style="display:none">
            @csrf
            <div id='olduser'>
                <div class="form-group" style="display:flex;gap: 50px">
                    <div class="col-md-4">
                        <label class="col-md-4 control-label" for="search-name">Name</label>
                        <input id="search-name" name="username" type="text" class="form-control input-md">
                    </div>
                    <div class="col-md-4">
                        <label class="col-md-4 control-label" for="search-phone">Phone</label>
                        <input id="search-phone" name="phonenumber" type="text" class="form-control input-md">
                    </div>
                    <div class="col-md-4" style="padding-top: 30px;">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </div>
                <div id="search-results" style="width: 70%; margin: 20px 5px; padding: 20px; border: 2px dotted green;"></div>
            </div>
        </form>
    </fieldset>
</div>

<!-- Order Details Form -->
<form id="orderDetailsForm" class="form-horizontal" style="height: 600px;">
    <fieldset>
        <div style="display:flex">
            <div class="section_logo"><img width="30px" height="30px" src="{{ asset('images/order.png') }}"/></div>
            <div class="section_title">Order Information</div>
        </div>



        <div class="form-group" style="display:flex;gap:2%;padding-top: 15px;">



            <div class="col-md-4">
                <label class="col-md-4 control-label" for="otype">Order Type (*)</label>
                <select id="otype" name="otype" class="form-control">
                    <option value="1">Studio Sittings</option>
                    <option value="2">Extra Copy</option>
                    <option value="3">Media</option>
                    <option value="4">Frames</option>
                </select>
            </div>



              <!-- Display Customer Name -->
          {{-- @if(Session::has('customer_name')) --}}

          <div class="col-md-4">
            <label class="col-md-4 control-label" >Customer Name</label><br>
            <span id="customer-name"  class="label label-primary">  {{ Session::get('customer_name') ?? 'Not set' }}</span>
        </div>
         {{-- @endif --}}



            <div class="col-md-4">
                <label class="col-md-4 control-label" for="phone">Order No.</label><br>
                <span id="order-id" class="label label-primary">{{ Session::get('order_id') ?? 'Not set' }}</span>
            </div>
        </div>
        <div class="row">
            <div class="column2" style="background-color:#bbb;">
                <div class="col-md-4" id="Sittings">
                    <label class="col-md-4 control-label" for="item">Item (*)</label>
                    <select id="sittingitem" name="item" class="form-control" style="width: 57%;">
                        <option value="1">Passport</option>
                        <option value="2">NIC</option>
                        <option value="3">Stamp</option>
                    </select>
                </div>
                <div class="form-group" style="display:flex;gap: 50px">
                    <div class="col-md-4">
                        <label class="col-md-4 control-label">H-Copies</label>
                        <input id="hcopy" name="hcopy" type="text" class="form-control input-md" required="">
                    </div>
                    <div class="col-md-4">
                        <label class="col-md-4 control-label">S-Copies</label>
                        <input id="scopy" name="scopy" type="text" class="form-control input-md" required="">
                    </div>
                </div>
                <div class="form-group" style="display:flex;gap: 50px">
                    <div class="col-md-4">
                        <label class="col-md-4 control-label">Delivery date</label>
                        <input class="form-control input-md" type="date" id="deldate" name="deldate">
                    </div>
                    <div class="col-md-4">
                        <label class="col-md-4 control-label">Urgent</label><br>
                        <input type="checkbox" id="urgent" name="urgent" style="zoom: 350%;">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="col-md-4 control-label">Comments</label><br>
                    <textarea id="comments" name="comments" rows="2" cols="50"></textarea>
                </div>
                <div class="col-md-4" style="padding-top: 30px;">

                    <button id="add-order" class="btn btn-primary">Add</button>
                </div>
            </div>
            <div class="column1" style="background-color:#aaa;">
                <h2>Order Summary</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Order Type</th>
                            <th>Order Item</th>
                            <th>H-Copies</th>
                            <th>S-Copies</th>
                            <th>Delivery Date</th>
                            <th>Urgent</th>
                            <th>Comments</th>
                            <th>Action</th>
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
    console.log("Script Loaded in neworder");

$(document).ready(function() {
    // Toggle between new and existing customer forms
    $('input[name="client-radio"]').click(function() {
        const isNew = $(this).val() == "1";
        $('#newCustomerForm').toggle(isNew);
        $('#existingCustomerForm').toggle(!isNew);
    });

    // Toggle sittings section based on order type
    $("#otype").change(function() {
        $("#Sittings").toggle($(this).val() == "1");
    });

    // New Customer Registration
    $(document).on('submit', '#newCustomerForm', function(e) {
        console.log('Form submitted'); // Add this line
        e.preventDefault();
        $('.error-message').text('');
        $('#new-customer-message').hide();
        $("#address_text").val($("#town option:selected").text());
        $.ajax({
            url: "{{ route('customers.store') }}",
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    $('#new-customer-message')
                        .removeClass('alert-danger')
                        .addClass('alert-success')
                        .html(response.message)
                        .show();
                    $('#newCustomerForm')[0].reset();
                    updateCustomerName();

                    setTimeout(function() {
                        $('#new-customer-message').fadeOut();
                    }, 3000);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        $(`#${field}-error`).text(messages[0]);
                    });
                } else {
                    $('#new-customer-message')
                        .removeClass('alert-success')
                        .addClass('alert-danger')
                        .html('An error occurred while registering the customer.')
                        .show();
                }
            }
        });

});

$(document).on("click", "#add-order", function () {
    console.log('Add function Triggerd..........')
    event.preventDefault();

    var customername = $('#customer-name').text();
    if (customername=='  Not set'){
        alert('Please select a customer before adding an order.');
        return;
    }
            let orderType = $("#otype option:selected").text();
            let hCopies = $("#hcopy").val();
            let sCopies = $("#scopy").val();
            let deliveryDate = $("#deldate").val();
            let sittingitem =  $("#sittingitem option:selected").text();
            let urgent = $("#urgent").is(":checked") ? "Yes" : "No";
            let comments = $("#comments").val();

            // Validate input
            if (!deliveryDate) {
                alert("Please select a delivery date.");
                return;
            }

            if(orderType !== 'Studio Sittings') {
                sittingitem = '';
            }

            // Append order details to the summary table
            let newRow = `
                <tr>
                    <td>${orderType}</td>
                    <td>${sittingitem}</td>
                    <td>${hCopies}</td>
                    <td>${sCopies}</td>
                    <td>${deliveryDate}</td>
                    <td>${urgent}</td>
                    <td>${comments}</td>
                    <td><button class="btn btn-danger btn-sm remove-order">✕</button></td>
                </tr>
            `;

            $("#order-summary").append(newRow);

            // Clear form fields after adding
            // $("#h_copies").val(1);
            // $("#s_copies").val(0);
            // $("#delivery_date").val("");
            // $("#urgent").prop("checked", false);
            // $("#comments").val("");
});
 // Remove order from the summary table
 $(document).on("click", ".remove-order", function () {
            $(this).closest("tr").remove();
        });


    // Existing Customer Search
    $('#existingCustomerForm').on('submit', function(e) {
        e.preventDefault();
        const searchData = {
            username: $('#search-name').val(),
            phonenumber: $('#search-phone').val(),
            _token: $('input[name="_token"]').val()
        };

        $.ajax({
            url: "{{ route('customers.search') }}",
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
