@extends('layouts.app')
@include('components.orderviewmodal_SS')
@include('components.orderviewmodal_EC')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@section('content')
<!-- <div class='s-page-title'>Orders</div> -->
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
        <div style="display:inline-flex;padding-top: 15px;gap: 75px;">
            <form id="order_search">
                <div class="col-md-4">
                    <label class="form-label" for="otype">Order Type (*)</label>
                    <select style="margin-top: -7px;" id="search-otype" name="search-otype" value="1" class="form-control">
           
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
                        <label class="col-md-4 control-label" for="name" >Search Text</label>
                        <input id="search-term" name="username" style="width: 80%;" type="text" placeholder="Customer Name,Phone or Order No." class="form-control">
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
            <div><span id="ocount"></span> order(s)</div>
            <div style="background-color:#aaa;height: 350px;overflow-y: auto;" id="orderResults">
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
    //document.getElementById('ocount').innerHTML='loading...'
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
            $('#orderResults').html(
                '<div class="alert alert-info">No Orders found.</div>'
            );
            document.getElementById('ocount').innerHTML=0
            return;
        }
        document.getElementById('ocount').innerHTML=orders.length

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
                <td id="edittype_${orderitem.ssorderitemmapkey}">${orderitem.edit_type.edittype}</td>
                <td>${orderitem.totalcost}</td>
                <td>Inprogress</td>
            

                <td>
            
                <button id="editBtn_${orderitem.ssorderitemmapkey}" type="button" class="btn btn-primary" onClick="edititem(${orderitem.ssorderitemmapkey},${orderitem.hardcopyquantity},${orderitem.softcopyquantity},'${orderitem.edit_type.edittype}')">
                    Edit
                </button>
                <button style="display:none" id="saveBtn_${orderitem.ssorderitemmapkey}" type="button" class="btn btn-primary" onClick="saveitem(${orderitem.ssorderitemmapkey},${orderitem.hardcopyquantity},${orderitem.softcopyquantity},'${orderitem.edit_type.edittype}')">
                    Save 
                </button>
                <button class="btn btn-delete remove-order" data-id="${orderitem.ssorderitemmapkey}"><i class="fas fa-trash"></i></button>
                </td>

            </tr>
        `;
    });

    html += '</tbody></table>';
    $('#orderitemResults').html(html);
}
      

function displayOrderitemSearchResults_EC(orderitems) {
       
       if (!orderitems.length) {
               $('#orderitemResults_EC').html(
                   '<div class="alert alert-info">No Order Items found.</div>'
               );
               return;
           }

let html = `
<table id="itemtable_EC" class="table table-bordered">
   <thead>
       <tr>
           <th>Item Type</th>
           <th>Original Order</th>
           <th>Hard Copied</th>
           <th>Edit Type</th>
           <th>Cost (LKR)</th>
           <th>Status</th>
       </tr>
   </thead>
   <tbody>
`;

orderitems.forEach(function(orderitem) {

html += `
   <tr data-id="${orderitem.ecorderitemmapkey}_EC">
       <td id="name_${orderitem.ecorderitemmapkey}_EC">${orderitem.order_type_item.itemname}</td>
       <td id="orionum_${orderitem.ecorderitemmapkey}_EC">${orderitem.original_order.orderid}</td>
       <td id="hcopy_${orderitem.ecorderitemmapkey}_EC">${orderitem.quantity}</td>
       <td id="edittype_${orderitem.ecorderitemmapkey}_EC">${orderitem.edit_type.edittype}</td>
       <td>${orderitem.totalcost}</td>
       <td>Inprogress</td>
   

       <td>
   
       <button id="editBtn_${orderitem.ecorderitemmapkey}_EC" type="button" class="btn btn-primary" onClick="edititem_EC(${orderitem.ecorderitemmapkey},${orderitem.quantity},0,'${orderitem.edit_type.edittype}','${orderitem.original_order.orderid}','${orderitem.original_order.orderkey}')">
           Edit 
       </button>
       <button style="display:none" id="saveBtn_${orderitem.ecorderitemmapkey}_EC" type="button" class="btn btn-primary" onClick="saveitem(${orderitem.ecorderitemmapkey},${orderitem.hardcopyquantity},${orderitem.softcopyquantity},'${orderitem.edit_type.edittype}')">
           Save 
       </button>
       <button class="btn btn-delete remove-order" data-id="${orderitem.ecorderitemmapkey}_EC"><i class="fas fa-trash"></i></button>
       </td>

   </tr>
`;
});

html += '</tbody></table>';
$('#orderitemResults_EC').html(html);
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
        $("#orderModal_SS").modal("show");
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
        $("#onum_EC").text(no);
        $("#odate_EC").text(odate);
        $("#customer_EC").text(customer);
        $("#total_EC").text(total);
        $("#discount_EC").text(discount);
        $("#paid_EC").text(paid);
        $("#urgent_EC").text(urgent);
        $("#status_EC").text(status);
        loaditemdata_EC(key)

    }
        

        $(this).off("shown.bs.modal");

}

function edititem_EC(ecorderitemmapkey,hcopy,scopy,edittype,oriorde){

document.getElementById("editBtn_"+ecorderitemmapkey+"_EC").style.display = "none";
document.getElementById("saveBtn_"+ecorderitemmapkey+"_EC").style.display = "inline-block";

document.getElementById("hcopy_"+ecorderitemmapkey+"_EC").innerHTML='<div class="col-md-4" id="scopymain"> <input style="border-color: orange;" id="hcopy" value="'+hcopy+'" name="hcopy" type="text" class="form-control input-md" required=""> </div>'
document.getElementById("edittype_"+ecorderitemmapkey+"_EC").innerHTML=
'<div class="col-md-4" id="edittypemain"><select  style="width:150px;border-color: orange;" id="edittype" name="edittype" class="form-control"> <option value="">'+edittype+'</option> @foreach ($editTypes as $editType) <option value="{{ $editType->edittypekey }}">{{ $editType->edittype }}</option> @endforeach </select> </div>'
}


</script>
@endpush
@endsection
