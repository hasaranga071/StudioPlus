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
                        <input type="hidden" name="studiokeynew" id="studiokeynew">


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
<input type="hidden" id="customerkey" name="customerkey">
<input type="hidden" id="studiokeyex" name="studiokeyex">
<!-- Order Details Form -->
<form id="orderDetailsForm" class="form-horizontal" _style="height: 600px;">
    <fieldset>
        <div style="display:flex">
            <div class="section_logo"><img width="30px" height="30px" src="{{ asset('images/order.png') }}"/></div>
            <div class="section_title">Order Information</div>
        </div>



        <div class="form-group" style="display:inline-flex;padding-top: 15px;gap:10%">
            <div class="col-md-4">
                <label class="form-label" for="otype">Order Type (*)</label>
                <select id="otype" name="otype" class="form-control" value='1'>

                    @foreach ($orderTypes as $orderType)
                        <option value="{{ $orderType->ordertypekey }}">{{ $orderType->ordertype }}</option>
                    @endforeach
                </select>
            </div>
              <!-- Display Customer Name -->
          {{-- @if(Session::has('customer_name')) --}}

            <div class="col-md-4">
                <label _class="col-md-4 control-label" >Customer Name</label><br>
                <span id="customer-name"  class="label label-primary">  {{ Session::get('customer_name') ?? 'Not set' }}</span>
            </div>
         {{-- @endif --}}
            <div class="col-md-4">
                <label _class="col-md-4 control-label" for="phone">Order No.</label><br>
                <span id="order-id" class="label label-primary">{{ Session::get('order_id') ?? 'Not set' }}</span>
            </div>
            <div class="col-md-4">
                <label _class="col-md-4 control-label">Delivery date</label>
                <input class="form-control input-md" type="date" id="deldate" name="deldate">
            </div>
        </div></br></br>
        <div class="row">
            <div class="column2" style="background-color:#bbb;">
                <div class="form-group" style="display:flex;gap: 50px">
                    <div class="col-md-4" id="Sittings">
                        <label class="col-md-4 control-label" for="item">Item (*)</label>
                        <select id="sittingitem" name="item" class="form-control" _style="width: 57%;">
                            <option value="">Select an Item</option> <!-- Placeholder -->
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="col-md-4 control-label">Urgent</label><br>
                        <input type="checkbox" id="urgent" name="urgent" style="zoom: 350%;">
                    </div>
                </div>
                <div class="form-group" style="display:flex;gap: 50px">
                    <div class="col-md-4" id="edittypemain">
                        <label class="form-label" for="edittype">Edit Type (*)</label>
                        <select id="edittype" name="edittype" class="form-control">
                        <option value="">Select Edit Type</option>
                            @foreach ($editTypes as $editType)
                                <option value="{{ $editType->edittypekey }}">{{ $editType->edittype }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4" id="lamtypemain">
                        <label class="form-label" for="lamtype">Laminate Type (*)</label>
                        <select id="lamtype" name="lamtype" class="form-control">
                        <option value="">Select Laminating Type</option>
                            @foreach ($lamTypes as $lamType)
                                <option value="{{ $lamType->lamtypekey }}">{{ $lamType->laminatetype }}</option>
                            @endforeach
                        </select>
                    </div>
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
                        <label class="col-md-4 control-label">Paid Amount</label>
                        <input id="paidamount" name="paidamount" type="text" class="form-control input-md" required="">
                    </div>
                    <div class="col-md-4">
                        <label class="col-md-4 control-label">Discount</label>
                        <input id="discount" name="discount" type="text" class="form-control input-md" required="">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="col-md-4 control-label">Comments</label><br>
                    <textarea id="comments" name="comments" rows="2" cols="50"></textarea>
                </div>
                <div class="col-md-4" style="padding-top: 30px;">

                    <button id="add-order" class="btn btn-primary">Add</button>
                    {{-- <button id="testStoreOrder" class="btn btn-primary">Test Order</button> --}}
                </div>
            </div>
            <div class="column1" style="background-color:#aaa;" id="order-summary-tb">
                <h2>Order Summary</h2>
                <table class="table table-bordered order-summary-table">
                    <thead>
                        <tr>
                            <th>Order Type</th>
                            <th>Order Item</th>
                            <th>H-Copies</th>
                            <th>S-Copies</th>
                            <th>Delivery Date</th>
                            <th>Urgent</th>
                            <th>Total Cost</th>
                            <th>Comments</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="order-summary">
                        <!-- Orders will be dynamically added here -->
                    </tbody>
                </table>

                <div class="order-summary-totals" style="margin-top: 20px; margin-left: auto; margin-right: auto;">
                    <table class="table table-bordered" style="background: #9c9c9c; border-radius: 8px; overflow: hidden;">
                        <tbody id="order-summary-total">

                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </fieldset>
</form>
</div>

@push('scripts')
<script>
    $(document).ready(function () {
       // console.log("Script Loaded in neworder");


        axios.post('/get_cached_data', {
        key: 'studiokey', // Cache key
        value: skey,          // Cache value
        //minutes: 10                // Cache duration (optional)
        })
        .then(response => {

            $('#studiokeyex').text(response.data['studiokey']);
            $('#studiokeynew').val(response.data['studiokey']);

        })
        .catch(error => {
            console.error('Error caching data:', error);
        });

        // Toggle between new and existing customer forms
        $('input[name="client-radio"]').click(function () {
            const isNew = $(this).val() == "1";
            $('#newCustomerForm').toggle(isNew);
            $('#existingCustomerForm').toggle(!isNew);
        });

        // Toggle sittings section and generate Order ID based on order type
        $("#otype").change(function () {
            generateOrderId();
           // toggleField();
           //  loadOrderTypeItems();
        });

        toggleField(); // Run function on page load

        function toggleField() {
            var selectedOrderType = $("#otype option:selected").text();

            $('#edittypemain').toggle(selectedOrderType !== "Frames");
            $('#lamtypemain').toggle(selectedOrderType === "Media");
        }

        function loadOrderTypeItems() {
            var ordertypekey = $("#otype").val();
            if (ordertypekey) {
                $.ajax({
                    url: '/ordertypeitem/' + ordertypekey,
                    type: 'GET',
                    success: function (data) {
                        $('#sittingitem').empty().append('<option value="">Select an Item</option>');
                        $.each(data, function (key, item) {
                            $('#sittingitem').append('<option value="' + item.ordertypeitemkey + '">' + item.itemname + '</option>');
                        });
                    },
                    error: function () {
                        alert('Failed to fetch items. Please try again.');
                    }
                });
            }
        }

        // New Customer Registration
        $(document).on('submit', '#newCustomerForm', function (e) {
            e.preventDefault();
            $('.error-message').text('');
            $('#new-customer-message').hide();
            $("#address_text").val($("#town option:selected").text());

            $.ajax({
                url: "{{ route('customers.store') }}",
                method: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.status) {
                        $('#new-customer-message').removeClass('alert-danger').addClass('alert-success').html(response.message).show();
                        $('#newCustomerForm')[0].reset();
                        updateCustomerName();
                        setTimeout(() => $('#new-customer-message').fadeOut(), 3000);
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        $.each(xhr.responseJSON.errors, function (field, messages) {
                            $(`#${field}-error`).text(messages[0]);
                        });
                    } else {
                        $('#new-customer-message').removeClass('alert-success').addClass('alert-danger').html('An error occurred while registering the customer.').show();
                    }
                }
            });
        });

        // Add order to summary table
        $(document).on("click", "#add-order", function (event) {
            event.preventDefault();


            // order insert
            orderstore();


        });

        // Remove order from summary table
        $(document).on('click', '.remove-order', function() {
            event.preventDefault();
            let orderItemId = $(this).data('id');
            let orderkey = $(this).data('orderid');

            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to delete this order item?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/delete-order-item/" + orderItemId,  // Laravel route
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                          //  Swal.fire("Deleted!", "The order item has been deleted.", "success");
                          $("body").prepend(`
                            <div id="flash-message" class="alert alert-success"
                                style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);
                                z-index: 9999; padding: 15px 20px; font-size: 16px; text-align: center;
                                background-color: #434844; color: white; border-radius: 5px; box-shadow: 0px 4px 6px rgba(0,0,0,0.1);">
                                Order item deleted !
                            </div>
                        `);
                            // Automatically remove the message after 2 seconds
                            setTimeout(function() {
                                $("#flash-message").fadeOut("slow", function() {
                                    $(this).remove();
                                });
                            }, 2000);
                            ordersummarytable(orderkey);
                        },
                        error: function() {
                            Swal.fire("Error!", "Something went wrong.", "error");
                        }
                    });
                }
            });
        });


        // Existing Customer Search
        $('#existingCustomerForm').on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('customers.search') }}",
                method: 'POST',
                data: {
                    username: $('#search-name').val(),
                    phonenumber: $('#search-phone').val(),
                    _token: $('input[name="_token"]').val()
                },
                dataType: 'json',
                success: function (response) {
                    if (response.status) displaySearchResults(response.customers);
                },
                error: function () {
                    $('#search-results').html('<div class="alert alert-danger">Error performing search.</div>');
                }
            });
        });

        function displaySearchResults(customers) {
            let html = customers.length ? `<table class="table table-bordered">
                <thead><tr><th>Name</th><th>Phone</th><th>Address</th><th>Email</th><th>Action</th></tr></thead><tbody>` :
                '<div class="alert alert-info">No customers found.</div>';

            $.each(customers, function (index, customer) {
                html += `<tr>
                    <td>${customer.username}</td>
                    <td>${customer.phonenumber}</td>
                    <td>${customer.address || ''}</td>
                    <td>${customer.email || ''}</td>
                    <td><button type="button" class="btn btn-sm btn-primary select-customer" data-studiokey="${customer.studiokey}" data-id="${customer.customerkey}" data-name="${customer.username}">Select</button></td>
                </tr>`;
            });
            html += '</tbody></table>';
            $('#search-results').html(html);
        }

        $(document).on('click', '.select-customer', function () {
            const customerId = $(this).data('id');
            const customerName = $(this).data('name');
            const studiokey = $(this).data('studiokey');
            $('#selected-customer-id').val(customerId);
            $('#selected-customer-name').text(customerName);
            $('#customerkey').text(customerId);
          //  $('#studiokeyex').text(studiokey);

            $.ajax({
                url: "{{ url('/set-customer-session') }}",
                type: "POST",
                data: { _token: "{{ csrf_token() }}", customer_id: customerId, customer_name: customerName },
                success: function () { updateCustomerName(); },
                error: function (xhr) { console.error("Error setting session:", xhr); }
            });
        });

        function updateCustomerName() {
            $.ajax({
                url: "{{ url('/get-customer-session') }}",
                type: "GET",
                success: function (response) {
                    $("#customer-name").text(response.customer_name || "No customer selected");
                    $("#customerkey").text(response.customer_id);
                    $("#studiokey").text(response.studio_key);
                    generateOrderId();
                }
            });
        }

        function generateOrderId() {
            $.ajax({
                url: "{{ url('/set-order-session') }}",
                type: "POST",
                data: { _token: "{{ csrf_token() }}", ordertype: $("#otype option:selected").text() },
                success: function (response) {
                    if (response.status === 'success') $("#order-id").text(response.order_id);
                }
            });
            toggleField();
            loadOrderTypeItems();
            clearOrderFields();
        }

        $(document).on('click', '.edit-order', function () {
         event.preventDefault();
        let row = $(this).closest('tr'); // Get the clicked row
        let ssorderitemmapkey = row.data('ssorderitemmapkey'); // Get the ID
        $("#add-order").text("Update");
        $(".highlighted-row").removeClass("highlighted-row");

        // Highlight the row of the clicked edit button
        $(this).closest("tr").addClass("highlighted-row");

        // Fetch existing order details (example: using AJAX)
        $.ajax({
            url: "/order-item-details/"+ssorderitemmapkey, // Route for fetching details
            type: "GET",
            success: function (response) {
                if (response.status === 'success') {
                    let item = response.orderItems[0];
                    console.log('orderitem',item);
                    // Populate the input fields


                    $("#urgent").prop('checked', item.isurgent == 1);
                    $("#comments").val(item.remarks).change();

                    $("#discount").val(item.discount);
                    $("#hcopy").val(item.hardcopyquantity);
                    $("#scopy").val(item.softcopyquantity);
                    $("#paidamount").val(item.paidcost);
                    let deliveryDate = item.deliverydate.split(" ")[0]; // Extracts "2025-02-26"
                    $("#deldate").val(deliveryDate).change();
                    if ($("#sittingitem option[value='" + item.ordertypeitemkey + "']").length === 0) {
                        $("#sittingitem").append(`<option value="${item.ordertypeitemkey}">${item.itemname}</option>`);
                    }
                    $("#sittingitem").val(item.ordertypeitemkey).change();
                    if ($("#edittype option[value='" + item.edittypekey + "']").length === 0) {
                        $("#edittype").append(`<option value="${item.edittypekey}">${item.edittype}</option>`);
                    }
                    $("#edittype").val(item.edittypekey).change();

                    // Store the ID for updating later
                    $("#ssorderitemmapkey").val(item.ssorderitemmapkey);
                }
            },
            error: function (xhr) {
                console.error("Error fetching order details:", xhr.responseText);
            }
            });
        });


    });


    // Test Order
    function orderstore () {

        var studiokey = $("#studiokeyex").text();

        var ordertypekey = $("#otype option:selected").val();
        var ordertypeitemkey = $("#sittingitem option:selected").val();
        var edittypekey = $("#edittype option:selected").val();
        var lamtypekey = $("#lamtype option:selected").val();
        var isurgent = $("#urgent").prop("checked") ? 1 : 0;
        var discount = $("#discount").val() || 0;
        var hcopycount = $("#hcopy").val() || 0;;
        var scopycount = $("#scopy").val() || 0;;
        var paidcost = $("#paidamount").val() || 0;;
        var comments = $("#comments").val() || "";
        var customerkey = $("#customerkey").text();
        var customername = $('#customer-name').text();
        var deliverydate = $("#deldate").val();
        if (customername === '  Not set') {
            alert('Please select a customer before adding an order.');
            return;
        }

        if(!ordertypekey){
            alert('Please select the order type');
            return false;
        }

        if(!ordertypeitemkey){
            alert('Please select the order item');
            return false;
        }
        if(!deliverydate){
            alert('Please select the Diliver Date !');
            return false;
        }


        $.ajax({
            url: "{{ route('storeOrder_ss') }}",
            type: "POST",
            data: {
                studiokey: studiokey,
                orderid: $("#order-id").text(),
                ordertypekey: ordertypekey,
                ordertypeitemkey:ordertypeitemkey,
                edittypekey:edittypekey,
                lamtypekey:lamtypekey,
                customerkey: customerkey,
                isurgent: isurgent,
                discount: discount,
                paidcost: paidcost,
                softcopycount:scopycount,
                hardcopycount:hcopycount,
                deliverydate: $("#deldate").val(),
                remarks: comments,
                iscompleted:0,
                _token: "{{ csrf_token() }}" // Required for Laravel AJAX requests
            },
            success: function (response) {
                console.log("Order Created Successfully:", response);
                // render table
                let flashbody = '<div id="flash-message" class="alert alert-success" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);z-index: 9999; padding: 15px 20px; font-size: 16px; text-align: center;background-color: #434844; color: white; border-radius: 5px; box-shadow: 0px 4px 6px rgba(0,0,0,0.1);">'
                + response.message +'!</div>';

                $("body").prepend(flashbody);
                            // Automatically remove the message after 2 seconds
                            setTimeout(function() {
                                $("#flash-message").fadeOut("slow", function() {
                                    $(this).remove();
                                });
                            }, 2000);
                ordersummarytable(response.order_id);
                clearOrderFields();
              //  alert(response.message);
            },
            error: function (xhr, status, error) {
                console.error("Error:", xhr.responseText);
                alert("Failed to create order.");
            }
        });
    }

    function ordersummarytable (orderkey){
        let orderType = $("#otype option:selected").text();
            let sittingitem = orderType === 'Studio Sittings' ? $("#sittingitem option:selected").text() : '';

            $.ajax({
        url: "/order-itemsummary/" + orderkey,
        type: "GET",
        success: function (response) {
            if (response.status === "success") {
                let orderSummaryHtml = "";
                let orderSummaryTotalHtml = "";
                let ordertotalcost = 0;
                let orderdiscount = 0;
                let discountamount = 0;
                let paidamount = 0;
                response.orderItems.forEach(item => {
                    ordertotalcost += parseFloat(item.totalcost) || 0; // Add item cost
                    orderdiscount = parseFloat(item.discount) || 0; // Add item cost
                    paidamount = parseFloat(item.paidcost) || 0; // Add item cost
                    orderSummaryHtml += `
                        <tr data-ssorderitemmapkey="${item.ssorderitemmapkey}">
                            <td>${item.ordertype}</td>
                            <td>${item.itemname}</td>
                            <td>${item.softcopyquantity}</td>
                            <td>${item.hardcopyquantity}</td>
                            <td>${item.deliverydate}</td>
                            <td>${item.isurgent == 1 ? 'Yes' : 'No'}</td>
                            <td>Rs ${item.totalcost}</td>
                            <td>${item.remarks}</td>
                             <td class="order-actions">
                <button class="btn btn-edit edit-order"><i class="fas fa-edit"></i></button>
                <button class="btn btn-delete remove-order" data-orderid="${item.orderkey}" data-id="${item.ssorderitemmapkey}"><i class="fas fa-trash"></i></button>
            </td>
                        </tr>
                    `;
                });
                discountamount =  (ordertotalcost * orderdiscount) / 100;
                let balancedue = (ordertotalcost - discountamount) - paidamount;
                orderSummaryTotalHtml = `<tr>
                                <th style="width: 50%;">Total Cost</th>
                                <td><span id="total-cost">Rs ${ordertotalcost.toFixed(2)}</span></td>
                            </tr>
                            <tr>
                                <th>Discount (${orderdiscount}%)</th>
                                <td><span id="total-cost">Rs ${discountamount.toFixed(2)}</span></td>
                            </tr>
                            <tr>
                                <th>Paid Amount</th>
                                <td><span id="total-cost">Rs ${paidamount.toFixed(2)}</span></td>
                            </tr>
                            <tr>
                                <th>Balance Due</th>
                                <td><span id="balance-due" style="font-weight:700;">Rs ${balancedue.toFixed(2)}</span></td>
                            </tr> `;
                $("#order-summary").html(orderSummaryHtml);
                $("#order-summary-total").html(orderSummaryTotalHtml);
            }
        },
        error: function (xhr) {
            console.error("Error fetching order summary:", xhr);
        }
    });
    }



        function clearOrderFields() {
            $("#order-form").find("input, select, textarea").val("");
            $("#discount").val("");
            $("#hcopy").val("");
            $("#scopy").val("");
            $("#paidamount").val("");

        }


</script>
@endpush
@endsection
