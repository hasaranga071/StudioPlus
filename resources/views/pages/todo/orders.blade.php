@extends('layouts.app')
@include('components.orderviewmodal')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
                    <select id="search-otype" name="search-otype" value="1" class="form-control">
           
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
                        <input id="search-term" name="username" style="width: 80%;" type="text" class="form-control input-md">
                        <span class="error-message text-danger" id="username-error"></span>
  
                </div>
                <div class="col-md-4">
                    <label _class="col-md-4 control-label">Delivery date-within</label>
                    <input class="form-control input-md" type="date" id="search-stdate" name="search-stdate" value="">
                    <input class="form-control input-md" type="date" id="search-enddate" name="search-enddate">
                </div>
                <div class="col-md-4" style="padding-top: 30px;">
                    <button type="submit" onClick="loaddata()" id="searchBtn" class="btn btn-primary">Search</button>
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

window.onload = function() {
    loaddata()
};


//$(document).ready(function() {
    // Existing order Search
function loaddata()
{
    //$('#searchBtn').click(function () {
            let otype = $('#search-otype').val(); 
            let query = $('#search-term').val(); 
            let startDate = $('#search-stdate').val(); 
            let endDate = $('#search-enddate').val();
            event.preventDefault(); // Prevent default form submission
            $('#orderResults').html("");
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
                        console.log('search result',response)
                        displayOrderSearchResults(response);

                }
            });

       // });
        setTimeout(function () {
            loaddata();
            }, 500); // 500ms delay ensures proper execution           // Trigger button click on page load
                      
        }
 //   });
    
 function loadpoup(key){
    alert(111);
    let url = "popup.html?key=" + encodeURIComponent(key); // Pass key in URL
            let popupWindow = window.open(url, "PopupWindow", "width=500,height=400,resizable=no");
 }


    function displayOrderSearchResults(orders) {
       
      if (!orders.length) {
            $('#search-results').html(
                '<div class="alert alert-info">No Orders found.</div>'
            );
            return;
        }

        let html = `
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Order No.</th>
                        <th>Type</th>
                        <th>Date Time</th>
                        <th>Customer Name</th>
                        <th>Urgent</th>
                        <th>Total Cost (LKR)</th>
                        <th>Discount(%)</th>
                        <th>Paid Amt (LKR)</th>
                        <th>Status</th>
                        <th></th>
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
                    <td>${order.username}</td>
                    <td>${order.urgent_flag === 1 ? 'Yes' : 'No'}</td>
                    <td>${order.totalcost}</td>
                    <th>${order.discount}</th>
                    <td>${order.paidcost}</td>
                    <td>${order.salestatus}</td>
                    <td>
                  
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#orderModal"
                        data-order-id="${order.orderno}" data-order-key="${order.orderkey}" data-order-date="${order.createdtime}"
                        data-order-otype="${order.ordertype}" data-order-customer="${order.username}" data-order-total="${order.totalcost}"
                        data-order-paid="${order.paidcost}" data-order-status="${order.salestatus}" data-order-urgent_flag="${order.urgent_flag === 1 ? 'Yes' : 'No'}" >
    View
</button>
                    </td>

                </tr>
            `;
        });

        html += '</tbody></table>';
        $('#orderResults').html(html);
    }

    function getFormattedDate(offset = 0) {
    let date = new Date();
    date.setDate(date.getDate() + offset); // Add offset days
    return date.toISOString().split('T')[0]; // Convert to YYYY-MM-DD
  }

  // Set default values
  document.getElementById("search-stdate").value = getFormattedDate(-30); 
  document.getElementById("search-enddate").value = getFormattedDate(); // Today


//modal load event listner 
orderModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget; // Button that triggered the modal

    var orderId = button.getAttribute('data-order-id'); // Get Order ID
    var odate = button.getAttribute('data-order-date'); 
    var orderKey = button.getAttribute('data-order-key'); 
    var otype = button.getAttribute('data-order-otype'); 
    var status = button.getAttribute('data-order-status'); 
    var urgent = button.getAttribute('data-order-urgent_flag'); 
    var total = button.getAttribute('data-order-total'); 
    var paid = button.getAttribute('data-order-paid'); 
    var customer = button.getAttribute('data-order-customer'); 

    // Insert values into modal
    //document.getElementById('modalOrderKey').textContent = orderKey;
    document.getElementById('onum').textContent = orderId;
    document.getElementById('odate').textContent = odate;
    document.getElementById('customer').textContent = customer;
    document.getElementById('total').textContent = total;
    document.getElementById('paid').textContent = paid;
    document.getElementById('urgent').textContent = urgent;
    document.getElementById('status').textContent = status;
   
})

    

</script>
@endpush
@endsection
