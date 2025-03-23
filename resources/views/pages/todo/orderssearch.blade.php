@extends('layouts.apppopup')
@include('components.orderviewmodalpopup_SS')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@section('content')
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
        <div class="search-container">
            <form id="order_search" class="row">
                <!-- Order Type (Hidden) -->
                <div class="form-group" style="display: none;">
                    <label class="form-label" for="otype">Order Type (*)</label>
                    <select id="search-otype" name="search-otype" class="form-control">
                        @foreach ($orderTypes as $orderType)
                            <option value="{{ $orderType->ordertypekey }}">{{ $orderType->ordertype }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Customer Name, Phone, or Order No. -->
                <div class="form-group">
                    <label class="form-label" for="name">Customer Name, Phone, or Order No.</label>
                    <input id="search-term" name="username" type="text" class="form-control">
                    <span class="error-message text-danger" id="username-error"></span>
                </div>

                <!-- Delivery Date Within -->
                <div class="form-group">
                    <label class="form-label">Delivery Date (Within)</label>
                    <div style="display: flex; gap: 10px;">
                        <input class="form-control" type="date" id="search-stdate" name="search-stdate">
                        <input class="form-control" type="date" id="search-enddate" name="search-enddate">
                    </div>
                </div>

                <!-- Search Button -->
                <div class="search-btn-container">
                    <button type="submit" onClick="loaddata()" id="searchBtn" class="btn btn-primary">Search</button>
                </div>
            </form>
        </div>
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
// Set default values
document.getElementById("search-stdate").value = getFormattedDate(-30);
document.getElementById("search-enddate").value = getFormattedDate(+1); // Today


function loaddata()
{
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

    setTimeout(function () {
    loaddata();
    }, 500); // 500ms delay ensures proper execution           // Trigger button click on page load

}

function displayOrderSearchResults(orders)
{

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
                    <td>${order.orderid}</td>
                    <td>${order.ordertype}</td>
                    <td>${order.createdtime}</td>
                    <td>${order.username}</td>
                    <td>${order.urgent_flag === 1 ? 'Yes' : 'No'}</td>
                    <td>${order.totalcost}</td>
                    <th>${order.discount}</th>
                    <td>${order.paidcost}</td>
                    <td>${order.salestatus}</td>
                    <td>
                        <button onClick="vieworder(${order.orderkey},'${order.orderid}','${order.createdtime}','${order.username}','${order.totalcost}','${order.discount}','${order.paidcost}','${order.urgent_flag === 1 ? 'Yes' : 'No'}','${order.salestatus}',${order.ordertypekey},'${order.ordertype}')" id="vieword" _data-orderkey="${order.orderkey}" type="button" class="btn btn-primary view-order"  >
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

function loaditemdata_SS(okey){
    let orderkey = okey;
    $.ajax({
        url: "/orders/itemsearch",
        type: "POST",
        data: {
            orderkey: orderkey,
            _token: "{{ csrf_token() }}" // CSRF Token for security
        },
        success: function (response) {
                ///console.log('Itemsearch result',response)
                displayOrderitemSearchResults(response);

        }
    });

}
function loaditemdata_EC(okey){
    let orderkey = okey;
    $.ajax({
        url: "/orders/itemsearch_EC",
        type: "POST",
        data: {
            orderkey: orderkey,
            _token: "{{ csrf_token() }}" // CSRF Token for security
        },
        success: function (response) {
                console.log('Itemsearch result EC',response)
                displayOrderitemSearchResults_EC(response);

        }
    });

}
function displayOrderitemSearchResults(orderitems) {
console.log('displayresults',orderitems);
                if (!orderitems.length) {
                        $('#orderitemResults').html(
                            '<div class="alert alert-info">No Order Items found.</div>'
                        );
                        return;
                    }

    let html = `
        <table id="itemtable" class="table table-bordered">
            <thead>
                <tr>
                    <th>Item Type</th>
                    <th>Hard Copied</th>
                    <th>Soft Copies</th>
                    <th>Edit Type</th>
                    <th>Cost (LKR)</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
    `;

    orderitems.forEach(function(orderitem) {

        html += `
            <tr data-id="${orderitem.ssorderitemmapkey}">
                <td id="name_${orderitem.ssorderitemmapkey}">${orderitem.order_type_item.itemname}</td>
                <td id="hcopy_${orderitem.ssorderitemmapkey}">${orderitem.hardcopyquantity}</td>
                <td id="scopy_${orderitem.ssorderitemmapkey}">${orderitem.softcopyquantity}</td>
                <td id="edittype_${orderitem.ssorderitemmapkey}">${orderitem.edit_type?.edittype || ''}</td>
                <td>${orderitem.totalcost}</td>
                <td>Inprogress</td>


                <td>

               <button id="editBtn_${orderitem.ssorderitemmapkey}"
                        type="button"
                        class="btn btn-primary btn-sm"
                        style="font-size: 12px; padding: 2px 6px;"
                        onClick="selectitem(${orderitem.ssorderitemmapkey},${orderitem.orderkey},${orderitem.order_type_item.ordertypeitemkey},'${orderitem.order_type_item.itemname}')">
                    Select
                </button>

                </td>

            </tr>
        `;
    });

    html += '</tbody></table>';
    $('#orderitemResults').html(html);
    // close modal

}

function addnew(){
    // Get the table body
    let table = document.getElementById("itemtable").getElementsByTagName('tbody')[0];

    // Create a new row
    let newRow = table.insertRow();
    newRow.style.backgroundColor = "lightblue";

    // Insert cells into the row
    let itemcell = newRow.insertCell(0);
    let hcopycell = newRow.insertCell(1);
    let scopycell = newRow.insertCell(2);
    let edittypecell = newRow.insertCell(3);
    let costcell = newRow.insertCell(4);
    let statuscell = newRow.insertCell(5);
    let actioncell = newRow.insertCell(6);


    // Add content to the new cells
    var otk=document.getElementById("otk").value;
    itemcell.innerHTML = '<div style="width:150px;border-color: blue;border-width: 2px;" class="col-md-4" id="Sittings"> <select id="sittingitem" name="item" class="form-control" _style="width: 57%;"> <option value="">Select Item Type</option></select> </div>';
    setTimeout(loadOrderTypeItems(otk), 3000)
    hcopycell.innerHTML ='<div class="col-md-4" id="hcopymain"> <input id="hcopy" name="hcopy" type="text" class="form-control input-md" required=""> </div>'
    scopycell.innerHTML ='<div class="col-md-4" id="scopymain"> <input id="scopy" name="scopy" type="text" class="form-control input-md" required=""> </div>'
    edittypecell.innerHTML='<div class="col-md-4" id="edittypemain"><select  style="width:150px;" id="edittype" name="edittype" class="form-control"> @foreach ($editTypes as $editType) <option value="">Select Edit Type</option><option value="{{ $editType->edittypekey }}">{{ $editType->edittype }}</option> @endforeach </select> </div>'
    costcell.innerHTML=''
    statuscell.innerHTML=''
    actioncell.innerHTML='<button id="addBtn" type="button" class="btn btn-primary" onClick="">Add</button>'


}

function loadOrderTypeItems(otk) {
    var ordertypekey = otk;
    if (ordertypekey) {
        $.ajax({
            url: '/ordertypeitem/' + ordertypekey,
            type: 'GET',
            success: function (data) {
                console.log('aaaaaaa='+data)
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

function vieworder(key,no,odate,customer,total,discount,paid,urgent,status,otk,ot) {
        event.preventDefault(); // Prevent default form submission
        let orderkey = $(this).data("orderkey"); // Get Order ID from button
        //alert("orderkey  no ="+key+":"+no)
        document.getElementById("otk").value=otk
        // Clear previous data and show loading placeholders
        $("#order-id").text("Loading...");
        $("#customer-name").text("Loading...");
        $("#order-total").text("Loading...");
        $("#order-items").html("<li>Loading items...</li>");

        // Show the modal first


        if (ot=='Studio Sittings')
        {
        $("#orderModalpopup_SS").modal("show");
        $("#onum").text(no);
        $("#odate").text(odate);
        $("#customer").text(customer);
        $("#total").text(total);
        $("#discount").text(discount);
        $("#paid").text(paid);
        $("#urgent").text(urgent);
        $("#status").text(status);
        loaditemdata_SS(key)
    }

    if (ot=='Extra Copy')
    {
        $("#orderModal_EC").modal("show");
        $("#onum").text(no);
        $("#odate").text(odate);
        $("#customer").text(customer);
        $("#total").text(total);
        $("#discount").text(discount);
        $("#paid").text(paid);
        $("#urgent").text(urgent);
        $("#status").text(status);
        loaditemdata_EC(key)

    }


        $(this).off("shown.bs.modal");

}

  // Function to get query parameters from the URL
  function getQueryParam(name) {
        let urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }

    // Set the "search-term" input field value from the query parameter
    document.addEventListener("DOMContentLoaded", function () {
        let customerName = getQueryParam("customername"); // Get customer name from query params
        if (customerName) {
            document.getElementById("search-term").value = decodeURIComponent(customerName);
        }
    });
   //document.getElementById("editBtn").addEventListener("click", function () {
    function selectitem(ssorderitemmapkey,orderkey,itemkey,itemname){
        // Send data to the parent window
        window.parent.postMessage(
            {
                ordertypeitemkey: itemkey,orderkey:orderkey,orderid:$("#onum").text(),itemname:itemname

            },
            "*"
        );

        }

</script>
@endpush
@endsection
