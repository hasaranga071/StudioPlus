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
                <div class="col-md-4">
                    <label class="form-label" for="otype">Order Type (*)</label>
                    <select id="search-otype" name="otype" value="1" class="form-control">
           
                        @foreach ($orderTypes as $orderType)
                            <option value="{{ $orderType->ordertypekey }}">{{ $orderType->ordertype }}</option>
                        @endforeach
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
                        <label class="col-md-4 control-label" for="name">Customer Name,Phone or Order No.</label>
                        <input id="search-term" name="username" style="width: 80%;" type="text" class="form-control input-md" required="">
                        <span class="error-message text-danger" id="username-error"></span>
  
                </div>
                <div class="col-md-4">
                    <label _class="col-md-4 control-label">Delivery date-within</label>
                    <input class="form-control input-md" type="date" id="search-stdate" name="search-stdate">
                    <input class="form-control input-md" type="date" id="search-enddate" name="search-enddate">
                </div>
                <div class="col-md-4" style="padding-top: 30px;">
                    <button type="submit" id="searchBtn" class="btn btn-primary">Search</button>
                </div>

            </Form> 

        </div></br></br>
        <div>
            <div style="background-color:#aaa;" id="orderResults">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Order No.</th>
                            <th>Order Type</th>
                            <th>Date Time</th>
                            <th>Urgent</th>
                            <th>Created At</th>
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


    $('#searchBtn').click(function () {
            let otype = $('#search-otype').val(); 
            let query = $('#search-term').val(); 
            let startDate = $('#search-stdate').val(); 
            let endDate = $('#search-enddate').val();
            event.preventDefault(); // Prevent default form submission

            $.ajax({
                url: "/orders/search",
                type: "POST",
                data: {
                    query: query,
                    otype: otype,
                    start_date: startDate,
                    end_date: endDate,
                    _token: "{{ csrf_token() }}" // CSRF Token for security
                },
                success: function (response) {
                    //if (response.status) {
                        console.log('search result',response.orders)
                        displayOrderSearchResults(response.orders);
              //  }
                    // let results = "<ul>";
                    // console.log('search result',results)
                    // response.forEach(order => {
                    //     results += `<li>Order #${order.order_number}, Customer: ${order.customer.name}, Phone: ${order.customer.phone_number}, Date: ${order.created_at}</li>`;
                    // });
                    // results += "</ul>";
                    // $('#orderResults').html(results);
                }
            });
        });
    });
    
    function displayOrderSearchResults(orders) {
        
       // if (!orders.length) {
            $('#search-results').html(
                '<div class="alert alert-info">No Orders found.</div>'
            );
            return;
       // }

        let html = `
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Order No.</th>
                        <th>Type</th>
                        <th>Date Time</th>
                        // <th>Urgent</th>
                        // <th>Total Cost</th>
                        // <th>Paid Amt</th>
                    </tr>
                </thead>
                <tbody>
        `;

        orders.forEach(function(order) {
            html += `
                <tr>
                    <td>${order.orderno}</td>
                    <td>${order.ordertype}</td>
                    <td>${order.createdtime}</td>
                    // <td>${order.email || ''}</td>

                </tr>
            `;
        });

        html += '</tbody></table>';
        $('#search-results').html(html);
    }

</script>
@endpush
@endsection
