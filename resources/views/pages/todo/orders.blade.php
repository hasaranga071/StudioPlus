@extends('layouts.app')
@include('components.orderviewmodal_SS')
@include('components.orderviewmodal_EC')
@include('components.orderviewmodal_ME')
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
                    <!-- <div class="col-md-4">
                        <label _class="col-md-4 control-label">Delivery date-within</label>
                        <input class="form-control input-md" type="date" id="search-stdate" name="search-stdate" value="">
                        <input class="form-control input-md" type="date" id="search-enddate" name="search-enddate">
                    </div> -->
                    <div class="form-group">
                        <label class="form-label">Order Create Date (Within)</label>
                        <div style="display: flex; gap: 10px;">
                            <input class="form-control" type="date" id="search-stdate" name="search-stdate">
                            <input class="form-control" type="date" id="search-enddate" name="search-enddate">
                        </div>
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


    // ******************* Studio sitting Retated ************************

    function loaditemdata_SS(okey){
        let orderkey = okey;
        $.ajax({
            url: "/order-itemsummary/" + orderkey,
            type: "GET",
            data: {
                orderkey: orderkey,
                _token: "{{ csrf_token() }}" // CSRF Token for security
            },
            success: function (response) {
                console.log('Server response:', response); // Debugging

                if (!response || response.length === 0) {
                    console.log("No order items returned from server.");
                } else {
                    displayOrderitemSearchResults(response,orderkey);
                }
            }
        });

    }

    function displayOrderitemSearchResults(orderitems,okey) {
        if (!orderitems.orderItems.length) {
            $('#orderitemResults').html(
                '<div class="alert alert-info">No Order Items found.</div>'
            );
            return;
        }

        //show order summary
        $.ajax({
            url: "/order-itemsummary/" + okey,
            type: "GET",
            success: function (response) {
                if (response.status === "success") {
                    let orderSummaryHtml = "";
                    let orderSummaryTotalHtml = "";
                    let orderTotalCost = 0;
                    let orderDiscount = 0;
                    let discountAmount = 0;
                    let paidAmount = 0;

                    response.orderItems.forEach(item => {
                        orderTotalCost += parseFloat(item.totalcost) || 0;
                        orderDiscount = parseFloat(item.order.discount) || 0;
                        paidAmount = parseFloat(item.order.paidcost) || 0;
                    });

                    discountAmount = (orderTotalCost * orderDiscount) / 100;
                    let balanceDue = (orderTotalCost - discountAmount) - paidAmount;

                    orderSummaryTotalHtml = `
                    </br><table>    
                        <tr>
                            <th style="width: 50%;">Total Cost</th>
                            <td><span id="total-cost">Rs ${orderTotalCost.toFixed(2)}</span></td>
                        </tr>
                        <tr>
                            <th>Discount (${orderDiscount}%)</th>
                            <td><span id="total-cost">Rs ${discountAmount.toFixed(2)}</span></td>
                        </tr>
                        <tr>
                            <th>Paid Amount</th>
                            <td><span id="total-cost">Rs ${paidAmount.toFixed(2)}</span></td>
                        </tr>
                        <tr>
                            <th>Balance Due</th>
                            <td><span id="balance-due" style="font-weight:700;">Rs ${balanceDue.toFixed(2)}</span></td>
                        </tr>
                    </table> `;
                    
                    $("#ordersummary").html(orderSummaryTotalHtml);
                }
            },
            error: function (xhr) {
                console.error("Error fetching order summary:", xhr);
            }
        });

        // end order summary
        let html = `
            <table id="itemtable" class="table table-bordered">
                <thead>
                    <tr id="row_0">
                        <th>Item Type</th>
                        <th>Hard Copied</th>
                        <th>Soft Copies</th>
                        <th>Edit Type</th>
                        <th>Cost (LKR)</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
        `;

        orderitems.orderItems.forEach(function(orderitem) {

            html += `
                <tr id="row_${orderitem.ssorderitemmapkey}">
                    <td id="name_${orderitem.ssorderitemmapkey}">${orderitem.order_type_item.itemname}</td>
                    <td id="hcopy_${orderitem.ssorderitemmapkey}">${orderitem.hardcopyquantity}</td>
                    <td id="scopy_${orderitem.ssorderitemmapkey}">${orderitem.softcopyquantity}</td>
                    <td id="edittype_${orderitem.ssorderitemmapkey}">${orderitem.edit_type.edittype}</td>
                    <td>${orderitem.totalcost}</td>
                    <td>Inprogress</td>


                    <td id="tdeditBtn_${orderitem.ssorderitemmapkey}">

                        <button id="editBtn_${orderitem.ssorderitemmapkey}" type="button" class="btn btn-primary" onClick="edititem(${orderitem.ssorderitemmapkey},${orderitem.hardcopyquantity},${orderitem.softcopyquantity},'${orderitem.edit_type.edittype}')">
                            Edit
                        </button>
                        <button style="display:none" id="saveBtn_${orderitem.ssorderitemmapkey}"
                            type="button" class="btn btn-primary"
                            onClick="saveitem(${orderitem.ssorderitemmapkey},'${orderitem.order.order_type.ordertype}',${orderitem.order.order_type.ordertypekey},${orderitem.order_type_item.ordertypeitemkey},'${orderitem.lam_type?.lamtypekey || ''}',${orderitem.order.customerkey},${orderitem.order.isurgent},'${orderitem.order.discount}','${orderitem.order.paidcost}',${orderitem.order.studiokey},'${orderitem.order.orderid}','${orderitem.order.deliverydate}','${orderitem.order.remarks}')">
                            Save
                        </button>
                    </td>

                </tr>
            `;
        });

        html += '</tbody></table>';
        $('#orderitemResults').html(html);
    }

    function edititem(ssorderitemmapkey) {
        // Get latest values from the table before editing
        let hcopyElement = document.getElementById(`hcopy_${ssorderitemmapkey}`);
        let scopyElement = document.getElementById(`scopy_${ssorderitemmapkey}`);
        let edittypeElement = document.getElementById(`edittype_${ssorderitemmapkey}`);

        // Ensure elements exist before accessing properties
        if (!hcopyElement || !scopyElement || !edittypeElement) {
            console.error(`Error: One or more elements missing for item ${ssorderitemmapkey}`);
            return;
        }

        let hcopy = hcopyElement.textContent.trim();
        let scopy = scopyElement.textContent.trim();
        let edittype = edittypeElement.textContent.trim(); // Get displayed edit type text

        // Hide Edit Button, Show Save Button
        document.getElementById(`editBtn_${ssorderitemmapkey}`).style.display = "none";
        document.getElementById(`saveBtn_${ssorderitemmapkey}`).style.display = "inline-block";

        // Convert Hard Copy to Input Field
        hcopyElement.innerHTML =
            `<div class="col-md-4">
                <input style="border-color: orange;" id="input_hcopy_${ssorderitemmapkey}" value="${hcopy}" name="hcopy" type="text" class="form-control input-md" required="">
            </div>`;

        // Convert Soft Copy to Input Field
        scopyElement.innerHTML =
            `<div class="col-md-4">
                <input style="border-color: orange;" id="input_scopy_${ssorderitemmapkey}" value="${scopy}" name="scopy" type="text" class="form-control input-md" required="">
            </div>`;

        // Convert Edit Type to Dropdown
        edittypeElement.innerHTML =
            `<div class="col-md-4">
                <select style="width:150px;border-color: orange;" id="input_edittype_${ssorderitemmapkey}" name="edittype" class="form-control">
                    <option value="">Select Edit Type</option>
                    @foreach ($editTypes as $editType)
                        <option value="{{ $editType->edittypekey }}" ${edittype === '{{ $editType->edittype }}' ? 'selected' : ''}>{{ $editType->edittype }}</option>
                    @endforeach
                </select>
            </div>`;
    }

    function saveitem(ssorderitemmapkey, ordertype, ordertypekey, ordertypeitemkey, lamtypekey, customerkey, isurgent, discount, paidcost, studiokey, orderid, deliverydate, remarks) {
        dataarray=[]

        setTimeout(() => {
            let hcopy = document.querySelector(`#input_hcopy_${ssorderitemmapkey}`)?.value || "";
            let scopy = document.querySelector(`#input_scopy_${ssorderitemmapkey}`)?.value || "";
            let edittypeElement = document.querySelector(`#input_edittype_${ssorderitemmapkey}`);
            if (!edittypeElement) {
                console.error("Edit type dropdown not found!");
                return;
            }
            let edittype = edittypeElement.options[edittypeElement.selectedIndex].value;
            let edittypeText = edittypeElement.options[edittypeElement.selectedIndex].text;

            if (!edittype) {
                console.error("Error: edittype is not defined or empty!");
                return; // Prevent the function from executing further if edittype is missing.
            }

            console.log("Hard Copy:", hcopy);
            console.log("Soft Copy:", scopy);
            console.log("Edit Type:", edittype);
            let dataarray = {
                studiokey: studiokey,
                orderid: orderid,
                ordertypekey: ordertypekey,
                ordertype: ordertype,
                ordertypeitemkey: ordertypeitemkey,
                edittypekey: edittype,
                lamtypekey: lamtypekey,
                customerkey: customerkey,
                isurgent: isurgent,
                discount: discount,
                paidcost: paidcost,
                softcopycount: scopy,  // Fix: Use correct variable
                hardcopycount: hcopy,   // Fix: Use correct variable
                deliverydate: deliverydate,
                remarks: remarks,
                iscompleted: 0,
                _token: "{{ csrf_token() }}" // Required for Laravel AJAX requests
            };
            console.log('Data Sent:', dataarray);

            let cells = document.querySelectorAll(`#row_${ssorderitemmapkey} td`);
            cells.forEach(cell => {
                if (cell.cellIndex !== 0) {
                    cell.contentEditable = "false";
                    cell.classList.remove("edit-mode");
                }
            });

            $.ajax({
                    url: "{{ route('storeOrder_ss') }}",
                    type: "POST",
                    data: dataarray,
                    success: function (response) {
                        console.log("Order Updated Successfully:", response);

                        // Update the table row with latest values
                        document.getElementById(`hcopy_${ssorderitemmapkey}`).innerHTML = hcopy;
                        document.getElementById(`scopy_${ssorderitemmapkey}`).innerHTML = scopy;
                        document.getElementById(`edittype_${ssorderitemmapkey}`).innerHTML = edittypeText;

                        // Show Edit Button Again
                        document.getElementById(`editBtn_${ssorderitemmapkey}`).style.display = "inline-block";
                        document.getElementById(`saveBtn_${ssorderitemmapkey}`).style.display = "none";
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
                    },
                    error: function (xhr, status, error) {
                    console.error("Error:", xhr.responseText);

                    // Attempt to parse the JSON response
                    try {
                        var response = JSON.parse(xhr.responseText);

                        // Display the error message using SweetAlert2
                        Swal.fire({
                            title: 'Failed to Create Order',
                            text: response.message || 'An unexpected error occurred.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    } catch (e) {
                        // If parsing fails, display a generic error message
                        Swal.fire({
                            title: 'Failed to Create Order',
                            text: 'An unexpected error occurred.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });
        }, 300);
    }
    
    function addnew_ss(orderkey){
        if (!orderkey) {
            alert("Order key is missing!");
            return;
        }
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

        // Get order type key
        var otk = document.getElementById("otk")?.value || "";

        // Add content to the new cells
        itemcell.innerHTML = `
            <div style="width:150px;border-color: blue;border-width: 2px;" class="col-md-4">
                <select id="sittingitem" name="item" class="form-control">
                    <option value="">Select Item Type</option>
                </select>
            </div>
        `;

        setTimeout(() => loadOrderTypeItems(otk), 300);

        hcopycell.innerHTML = `
            <div class="col-md-4">
                <input id="hcopy" name="hcopy" type="text" class="form-control input-md" required="">
            </div>
        `;

        scopycell.innerHTML = `
            <div class="col-md-4">
                <input id="scopy" name="scopy" type="text" class="form-control input-md" required="">
            </div>
        `;

        edittypecell.innerHTML = `
            <div class="col-md-4">
                <select style="width:150px;" id="edittype" name="edittype" class="form-control">
                    <option value="">Select Edit Type</option>
                    @foreach ($editTypes as $editType)
                        <option value="{{ $editType->edittypekey }}">{{ $editType->edittype }}</option>
                    @endforeach
                </select>
            </div>
        `;

        costcell.innerHTML = '';
        statuscell.innerHTML = '';

        actioncell.innerHTML = `
            <button id="addBtn" type="button" class="btn btn-primary" onClick="additem(this)" data-orderkey="${orderkey}">
                Add
            </button>
        `;
        document.getElementById("addBtn").style.display = "inline-block";
    }

    function additem(btn) {
        let orderkey = btn.getAttribute("data-orderkey");
        // Get the row (parent of the button)
        let row = btn.closest("tr");

        // Extract input values
        let hcopy = row.querySelector("input[name='hcopy']").value;
        let scopy = row.querySelector("input[name='scopy']").value;
        let edittype = row.querySelector("select[name='edittype']").value;
        let item = row.querySelector("select[name='item']").value;

        $.ajax({
            url: "/order-itemsummary/" + orderkey,
            type: "GET",
            data: {
                orderkey: orderkey,
                hcopy: hcopy,
                scopy: scopy,
                edittype: edittype,
                item: item,
                _token: "{{ csrf_token() }}" // CSRF Token for security
            },
            success: function (response) {
                if (!response || response.length === 0) {
                    console.log("No order items returned from server.");
                } else {
                    console.log('data call working ...............')
                    createitem_ss(response,{ orderkey, hcopy, scopy, edittype, item});
                }
            }
        });
    }

    function createitem_ss(orderdata,formData) {
        if (!orderdata.orderItems.length) {
            $('#orderitemResults').html(
                '<div class="alert alert-info">No Order Items found.</div>'
            );
            return;
        }

        let orderdetail=orderdata.orderItems[0];

        //dataarray=[]

        let dataarray = {
            studiokey: orderdetail.order.studiokey,
            orderid: orderdetail.order.orderid,
            ordertypekey: orderdetail.order.ordertypekey,
            ordertype: orderdetail.order.order_type.ordertype,
            ordertypeitemkey: formData.item,
            edittypekey: formData.edittype,
            lamtypekey: orderdetail.lamtypekey,
            customerkey: orderdetail.order.customerkey,
            isurgent: orderdetail.order.isurgent,
            discount: orderdetail.order.discount,
            paidcost: orderdetail.order.paidcost,
            softcopycount: formData.scopy,  // Fix: Use correct variable
            hardcopycount: formData.hcopy,   // Fix: Use correct variable
            deliverydate: orderdetail.order.deliverydate,
            remarks: orderdetail.order.remarks,
            iscompleted: 0,
            _token: "{{ csrf_token() }}" // Required for Laravel AJAX requests
        };

        $.ajax({
                url: "{{ route('storeOrder_ss') }}",
                type: "POST",
                data: dataarray,
                success: function (response) {
                    console.log("Order Item Created Successfully:", response);

                    setTimeout(() => {
                        loaditemdata_SS(formData.orderkey);
                        $('orderviewmodal_SS').modal('show');
                    }, 500);

                    document.getElementById("addBtn").style.display = "none";
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
                },
                error: function (xhr, status, error) {
                console.error("Error:", xhr.responseText);

                // Attempt to parse the JSON response
                try {
                    var response = JSON.parse(xhr.responseText);

                    // Display the error message using SweetAlert2
                    Swal.fire({
                        title: 'Failed to Create Order',
                        text: response.message || 'An unexpected error occurred.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                } catch (e) {
                    // If parsing fails, display a generic error message
                    Swal.fire({
                        title: 'Failed to Create Order',
                        text: 'An unexpected error occurred.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            }
        });

    }

    // ******************* END Studio sitting Retated ************************

    // ******************* Media Related *****************************

    function loaditemdata_ME(okey){
        let orderkey = okey;
        $.ajax({
            url: "/order-itemsummary/" + orderkey,
            type: "GET",
            data: {
                orderkey: orderkey,
                _token: "{{ csrf_token() }}" // CSRF Token for security
            },
            success: function (response) {
                console.log('Server response:', response); // Debugging

                if (!response || response.length === 0) {
                    console.log("No order items returned from server.");
                } else {
                    displayOrderitemSearchResults_ME(response,orderkey);
                }
            }
        });
                        
    }  

    function displayOrderitemSearchResults_ME(orderitems) {
        if (!orderitems.orderItems.length) {
            $('#orderitemResults_me').html(
                '<div class="alert alert-info">No Order Items found.</div>'
            );
            return;
        }

        let html = `
            <table id="itemtable_me" class="table table-bordered">
                <thead>
                    <tr id="row_0">
                        <th>Item Type</th>
                        <th>Laminate Type</th>
                        <th>Hard Copied</th>
                        <th>Soft Copies</th>
                        <th>Edit Type</th>
                        <th>Cost (LKR)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
        `;

        orderitems.orderItems.forEach(function(orderitem) {
        
            html += `
                <tr id="row_me_${orderitem.ssorderitemmapkey}">
                    <td id="name_me_${orderitem.ssorderitemmapkey}">${orderitem.order_type_item.itemname}</td>
                    <td id="lamtype_me_${orderitem.ssorderitemmapkey}">${orderitem.lam_type.laminatetype}</td>
                    <td id="hcopy_me_${orderitem.ssorderitemmapkey}">${orderitem.hardcopyquantity}</td>
                    <td id="scopy_me_${orderitem.ssorderitemmapkey}">${orderitem.softcopyquantity}</td>
                    <td id="edittype_me_${orderitem.ssorderitemmapkey}">${orderitem.edit_type.edittype}</td>
                    <td>${orderitem.totalcost}</td>
                    <td>Inprogress</td>
                

                    <td>
                
                    <button id="editBtn_me_${orderitem.ssorderitemmapkey}" type="button" class="btn btn-primary" onClick="edititem_me(${orderitem.ssorderitemmapkey},${orderitem.hardcopyquantity},${orderitem.softcopyquantity},'${orderitem.edit_type.edittype}')">
                        Edit 
                    </button>
                    <button style="display:none" id="saveBtn_me_${orderitem.ssorderitemmapkey}" 
                        type="button" class="btn btn-primary" 
                        onClick="saveitem_me(${orderitem.ssorderitemmapkey},'${orderitem.order.order_type.ordertype}',${orderitem.order.order_type.ordertypekey},${orderitem.order_type_item.ordertypeitemkey},'${orderitem.lam_type?.lamtypekey || ''}',${orderitem.order.customerkey},${orderitem.order.isurgent},'${orderitem.order.discount}','${orderitem.order.paidcost}',${orderitem.order.studiokey},'${orderitem.order.orderid}','${orderitem.order.deliverydate}','${orderitem.order.remarks}')">
                        Save 
                    </button>
                    </td>

                </tr>
            `;
        });

        html += '</tbody></table>';
        $('#orderitemResults_me').html(html);
    }  

    function edititem_me(ssorderitemmapkey) {
        // Get latest values from the table before editing
        let lamtypeElement = document.getElementById(`lamtype_me_${ssorderitemmapkey}`);
        let hcopyElement = document.getElementById(`hcopy_me_${ssorderitemmapkey}`);
        let scopyElement = document.getElementById(`scopy_me_${ssorderitemmapkey}`);
        let edittypeElement = document.getElementById(`edittype_me_${ssorderitemmapkey}`);

        // Ensure elements exist before accessing properties
        if (!hcopyElement || !scopyElement || !edittypeElement || !lamtypeElement) {
            console.error(`Error: One or more elements missing for item ${ssorderitemmapkey}`);
            return;
        }

        let lamtype = lamtypeElement.textContent.trim();
        let hcopy = hcopyElement.textContent.trim();
        let scopy = scopyElement.textContent.trim();
        let edittype = edittypeElement.textContent.trim(); // Get displayed edit type text

        // Hide Edit Button, Show Save Button
        document.getElementById(`editBtn_me_${ssorderitemmapkey}`).style.display = "none";
        document.getElementById(`saveBtn_me_${ssorderitemmapkey}`).style.display = "inline-block";

        // Convert Laminate Type to Input Field
        lamtypeElement.innerHTML =
            `<div class="col-md-4">
                <select style="width:150px;border-color: orange;" id="input_lamtype_me_${ssorderitemmapkey}" name="lamtype" class="form-control">
                    <option value="">Select Laminate Type</option>
                    @foreach ($lamTypes as $lamType) 
                        <option value="{{ $lamType->lamtypekey }}" ${lamtype === '{{ $lamType->laminatetype }}' ? 'selected' : ''}>{{ $lamType->laminatetype }}</option>
                    @endforeach
                </select>
            </div>`;

        // Convert Hard Copy to Input Field
        hcopyElement.innerHTML =
            `<div class="col-md-4">
                <input style="border-color: orange;" id="input_hcopy_me_${ssorderitemmapkey}" value="${hcopy}" name="hcopy" type="text" class="form-control input-md" required="">
            </div>`;

        // Convert Soft Copy to Input Field
        scopyElement.innerHTML =
            `<div class="col-md-4">
                <input style="border-color: orange;" id="input_scopy_me_${ssorderitemmapkey}" value="${scopy}" name="scopy" type="text" class="form-control input-md" required="">
            </div>`;

        // Convert Edit Type to Dropdown
        edittypeElement.innerHTML =
            `<div class="col-md-4">
                <select style="width:150px;border-color: orange;" id="input_edittype_me_${ssorderitemmapkey}" name="edittype" class="form-control">
                    <option value="">Select Edit Type</option>
                    @foreach ($editTypes as $editType) 
                        <option value="{{ $editType->edittypekey }}" ${edittype === '{{ $editType->edittype }}' ? 'selected' : ''}>{{ $editType->edittype }}</option>
                    @endforeach
                </select>
            </div>`;
    }

    function saveitem_me(ssorderitemmapkey, ordertype, ordertypekey, ordertypeitemkey, lamtypekey, customerkey, isurgent, discount, paidcost, studiokey, orderid, deliverydate, remarks) {
        dataarray=[]       

        setTimeout(() => {
            let hcopy = document.querySelector(`#input_hcopy_me_${ssorderitemmapkey}`)?.value || "";
            let scopy = document.querySelector(`#input_scopy_me_${ssorderitemmapkey}`)?.value || "";

            let lamtypeElement = document.querySelector(`#input_lamtype_me_${ssorderitemmapkey}`);
            if (!lamtypeElement) {
                console.error("Edit type dropdown not found!");
                return;
            }
            let lamtype = lamtypeElement.options[lamtypeElement.selectedIndex].value;
            let lamtypeText = lamtypeElement.options[lamtypeElement.selectedIndex].text;

            if (!lamtype) {
                console.error("Error: laminating type is not defined or empty!");
                return; // Prevent the function from executing further if edittype is missing.
            }

            let edittypeElement = document.querySelector(`#input_edittype_me_${ssorderitemmapkey}`);
            if (!edittypeElement) {
                console.error("Edit type dropdown not found!");
                return;
            }
            let edittype = edittypeElement.options[edittypeElement.selectedIndex].value;
            let edittypeText = edittypeElement.options[edittypeElement.selectedIndex].text;

            if (!edittype) {
                console.error("Error: edittype is not defined or empty!");
                return; // Prevent the function from executing further if edittype is missing.
            }

            let dataarray = {
                studiokey: studiokey,
                orderid: orderid,
                ordertypekey: ordertypekey,
                ordertype: ordertype,
                ordertypeitemkey: ordertypeitemkey,
                edittypekey: edittype,
                lamtypekey: lamtype,
                customerkey: customerkey,
                isurgent: isurgent,
                discount: discount,
                paidcost: paidcost,
                softcopycount: scopy,  // Fix: Use correct variable
                hardcopycount: hcopy,   // Fix: Use correct variable
                deliverydate: deliverydate,
                remarks: remarks,
                iscompleted: 0,
                _token: "{{ csrf_token() }}" // Required for Laravel AJAX requests
            };
            console.log('Data Sent:', dataarray);
            
            let cells = document.querySelectorAll(`#row_me_${ssorderitemmapkey} td`);
            cells.forEach(cell => {
                if (cell.cellIndex !== 0) { 
                    cell.contentEditable = "false";
                    cell.classList.remove("edit-mode");
                }
            });

            $.ajax({
                    url: "{{ route('storeOrder_ss') }}",
                    type: "POST",
                    data: dataarray,
                    success: function (response) {
                        console.log("Order Updated Successfully:", response);

                        // Update the table row with latest values
                        document.getElementById(`hcopy_me_${ssorderitemmapkey}`).innerHTML = hcopy;
                        document.getElementById(`scopy_me_${ssorderitemmapkey}`).innerHTML = scopy;
                        document.getElementById(`edittype_me_${ssorderitemmapkey}`).innerHTML = edittypeText;
                        document.getElementById(`lamtype_me_${ssorderitemmapkey}`).innerHTML = lamtypeText;

                        // Show Edit Button Again
                        document.getElementById(`editBtn_me_${ssorderitemmapkey}`).style.display = "inline-block";
                        document.getElementById(`saveBtn_me_${ssorderitemmapkey}`).style.display = "none";
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
                    },
                    error: function (xhr, status, error) {
                    console.error("Error:", xhr.responseText);

                    // Attempt to parse the JSON response
                    try {
                        var response = JSON.parse(xhr.responseText);

                        // Display the error message using SweetAlert2
                        Swal.fire({
                            title: 'Failed to Create Order',
                            text: response.message || 'An unexpected error occurred.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    } catch (e) {
                        // If parsing fails, display a generic error message
                        Swal.fire({
                            title: 'Failed to Create Order',
                            text: 'An unexpected error occurred.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });
        }, 300);        
    }

    function addnew_me(orderkey){
        if (!orderkey) {
            alert("Order key is missing!");
            return;
        }
        // Get the table body
        let table = document.getElementById("itemtable_me").getElementsByTagName('tbody')[0];

        // Create a new row
        let newRow = table.insertRow();
        newRow.style.backgroundColor = "lightblue";

        // Insert cells into the row
        let itemcell = newRow.insertCell(0);
        let lamtypecell = newRow.insertCell(1);
        let hcopycell = newRow.insertCell(2);
        let scopycell = newRow.insertCell(3);
        let edittypecell = newRow.insertCell(4);
        let costcell = newRow.insertCell(5);
        let statuscell = newRow.insertCell(6);
        let actioncell = newRow.insertCell(7);

        // Get order type key
        var otk = document.getElementById("otk")?.value || "";

        // Add content to the new cells
        itemcell.innerHTML = `
            <div style="width:150px;border-color: blue;border-width: 2px;" class="col-md-4">
                <select id="sittingitem" name="item" class="form-control">
                    <option value="">Select Item Type</option>
                </select>
            </div>
        `;
        
        setTimeout(() => loadOrderTypeItems(otk), 300);

        lamtypecell.innerHTML = `
            <div class="col-md-4">
                <select style="width:150px;" id="edittype_me" name="lamtype" class="form-control">
                    <option value="">Select Edit Type</option>
                    @foreach ($lamTypes as $lamType)
                        <option value="{{ $lamType->lamtypekey }}">{{ $lamType->laminatetype }}</option>
                    @endforeach
                </select>
            </div>
        `;

        hcopycell.innerHTML = `
            <div class="col-md-4">
                <input id="hcopy_me" name="hcopy" type="text" class="form-control input-md" required="">
            </div>
        `;

        scopycell.innerHTML = `
            <div class="col-md-4">
                <input id="scopy_me" name="scopy" type="text" class="form-control input-md" required="">
            </div>
        `;
        
        edittypecell.innerHTML = `
            <div class="col-md-4">
                <select style="width:150px;" id="edittype_me" name="edittype" class="form-control">
                    <option value="">Select Edit Type</option>
                    @foreach ($editTypes as $editType)
                        <option value="{{ $editType->edittypekey }}">{{ $editType->edittype }}</option>
                    @endforeach
                </select>
            </div>
        `;

        costcell.innerHTML = '';
        statuscell.innerHTML = '';

        actioncell.innerHTML = `
            <button id="addBtn_me" type="button" class="btn btn-primary" onClick="additem_me(this)" data-orderkey="${orderkey}">
                Add
            </button>
        `;
        document.getElementById("addBtn_me").style.display = "inline-block";
    }

    function additem_me(btn) {
        let orderkey = btn.getAttribute("data-orderkey"); 
        // Get the row (parent of the button)
        let row = btn.closest("tr");

        // Extract input values
        let hcopy = row.querySelector("input[name='hcopy']").value;
        let scopy = row.querySelector("input[name='scopy']").value;
        let edittype = row.querySelector("select[name='edittype']").value;
        let lamtype = row.querySelector("select[name='lamtype']").value;
        let item = row.querySelector("select[name='item']").value;

        $.ajax({
            url: "/order-itemsummary/" + orderkey,
            type: "GET",
            data: {
                orderkey: orderkey,
                hcopy: hcopy,
                scopy: scopy,
                edittype: edittype,
                lamtype: lamtype,
                item: item,
                _token: "{{ csrf_token() }}" // CSRF Token for security
            },
            success: function (response) {
                if (!response || response.length === 0) {
                    console.log("No order items returned from server.");
                } else {
                    console.log('data call working ...............')
                    createitem_me(response,{ orderkey, hcopy, scopy, edittype, lamtype, item});
                }
            }
        });
    }

    function createitem_me(orderdata,formData) {
        if (!orderdata.orderItems.length) {
            $('#orderitemResults').html(
                '<div class="alert alert-info">No Order Items found.</div>'
            );
            return;
        }

        let orderdetail=orderdata.orderItems[0];

        //dataarray=[]       
        
        let dataarray = {
            studiokey: orderdetail.order.studiokey,
            orderid: orderdetail.order.orderid,
            ordertypekey: orderdetail.order.ordertypekey,
            ordertype: orderdetail.order.order_type.ordertype,
            ordertypeitemkey: formData.item,
            edittypekey: formData.edittype,
            lamtypekey: formData.lamtype,
            customerkey: orderdetail.order.customerkey,
            isurgent: orderdetail.order.isurgent,
            discount: orderdetail.order.discount,
            paidcost: orderdetail.order.paidcost,
            softcopycount: formData.scopy,  // Fix: Use correct variable
            hardcopycount: formData.hcopy,   // Fix: Use correct variable
            deliverydate: orderdetail.order.deliverydate,
            remarks: orderdetail.order.remarks,
            iscompleted: 0,
            _token: "{{ csrf_token() }}" // Required for Laravel AJAX requests
        };

        $.ajax({
                url: "{{ route('storeOrder_ss') }}",
                type: "POST",
                data: dataarray,
                success: function (response) {
                    console.log("Order Item Created Successfully:", response);

                    setTimeout(() => {
                        loaditemdata_ME(formData.orderkey);
                        $('orderviewmodal_ME').modal('show');
                    }, 500);

                    document.getElementById("addBtn_me").style.display = "none";
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
                },
                error: function (xhr, status, error) {
                console.error("Error:", xhr.responseText);

                // Attempt to parse the JSON response
                try {
                    var response = JSON.parse(xhr.responseText);

                    // Display the error message using SweetAlert2
                    Swal.fire({
                        title: 'Failed to Create Order',
                        text: response.message || 'An unexpected error occurred.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                } catch (e) {
                    // If parsing fails, display a generic error message
                    Swal.fire({
                        title: 'Failed to Create Order',
                        text: 'An unexpected error occurred.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            }
        });
               
    }

    // ******************* End Media Related *************************

    // ******************* Extra Copy Retated ************************

    function loaditemdata_EC(okey){
        let orderkey = okey;
        $.ajax({
            url: "/order-itemsummary/" + orderkey,
            type: "GET",
            data: {
                orderkey: orderkey,
                _token: "{{ csrf_token() }}" // CSRF Token for security
            },
            success: function (response) {
                    console.log('Itemsearch result EC',response)
                    displayOrderitemSearchResults_EC(response,orderkey);

            }
        });

    }

    function displayOrderitemSearchResults_EC(orderitems,okey) {

        if (!orderitems.orderItems.length) {
            $('#orderitemResults_EC').html(
                '<div class="alert alert-info">No Order Items found.</div>'
            );
            return;
        }

        let html = `
            <table id="itemtable_EC" class="table table-bordered">
            <thead>
                <tr id="row_0">
                    <th>Item Type</th>
                    <th>Original Order</th>
                    <th>Hard Copied</th>
                    <th>Edit Type</th>
                    <th>Cost (LKR)</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
        `;

        orderitems.orderItems.forEach(function(orderitem) {
            html += `
                <tr id="row_${orderitem.ecorderitemmapkey}" data-id="${orderitem.ecorderitemmapkey}_EC">
                    <td id="name_${orderitem.ecorderitemmapkey}_EC">${orderitem.order_type_item.itemname}</td>
                    <td id="orionum_${orderitem.ecorderitemmapkey}_EC">${orderitem.original_order.orderid}</td>
                    <td id="hcopy_${orderitem.ecorderitemmapkey}_EC">${orderitem.hardcopyquantity}</td>
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
        //show order summary
        $.ajax({
            url: "/order-itemsummary/" + okey,
            type: "GET",
            success: function (response) {
                if (response.status === "success") {
                    let orderSummaryHtml = "";
                    let orderSummaryTotalHtml = "";
                    let orderTotalCost = 0;
                    let orderDiscount = 0;
                    let discountAmount = 0;
                    let paidAmount = 0;

                    response.orderItems.forEach(item => {
                        orderTotalCost += parseFloat(item.totalcost) || 0;
                        orderDiscount = parseFloat(item.order.discount) || 0;
                        paidAmount = parseFloat(item.order.paidcost) || 0;
                    });

                    discountAmount = (orderTotalCost * orderDiscount) / 100;
                    let balanceDue = (orderTotalCost - discountAmount) - paidAmount;

                    orderSummaryTotalHtml = `
                    </br><table>    
                        <tr>
                            <th style="width: 50%;">Total Cost</th>
                            <td><span id="total-cost">Rs ${orderTotalCost.toFixed(2)}</span></td>
                        </tr>
                        <tr>
                            <th>Discount (${orderDiscount}%)</th>
                            <td><span id="total-cost">Rs ${discountAmount.toFixed(2)}</span></td>
                        </tr>
                        <tr>
                            <th>Paid Amount</th>
                            <td><span id="total-cost">Rs ${paidAmount.toFixed(2)}</span></td>
                        </tr>
                        <tr>
                            <th>Balance Due</th>
                            <td><span id="balance-due" style="font-weight:700;">Rs ${balanceDue.toFixed(2)}</span></td>
                        </tr>
                    </table> `;
                 
                    $("#ordersummary_EC").html(orderSummaryTotalHtml);
                }
            },
            error: function (xhr) {
                console.error("Error fetching order summary:", xhr);
            }
        });

        // end order summary

    }

    function edititem_EC(ecorderitemmapkey,hcopy,scopy,edittype,oriorde){
        document.getElementById("editBtn_"+ecorderitemmapkey+"_EC").style.display = "none";
        document.getElementById("saveBtn_"+ecorderitemmapkey+"_EC").style.display = "inline-block";
        document.getElementById("hcopy_"+ecorderitemmapkey+"_EC").innerHTML='<div class="col-md-4" id="scopymain"> <input style="border-color: orange;" id="hcopy" value="'+hcopy+'" name="hcopy" type="text" class="form-control input-md" required=""> </div>'
        document.getElementById("edittype_"+ecorderitemmapkey+"_EC").innerHTML=
        '<div class="col-md-4" id="edittypemain"><select  style="width:150px;border-color: orange;" id="edittype" name="edittype" class="form-control"> <option value="">'+edittype+'</option> @foreach ($editTypes as $editType) <option value="{{ $editType->edittypekey }}">{{ $editType->edittype }}</option> @endforeach </select> </div>'
    }

    // ******************* End Extra Copy Retated ************************

    // function addnew(){
    //     // Get the table body
    //     let table = document.getElementById("itemtable").getElementsByTagName('tbody')[0];

    //     // Create a new row
    //     let newRow = table.insertRow();
    //     newRow.style.backgroundColor = "lightblue";

    //     // Insert cells into the row
    //     let itemcell = newRow.insertCell(0);
    //     let hcopycell = newRow.insertCell(1);
    //     let scopycell = newRow.insertCell(2);
    //     let edittypecell = newRow.insertCell(3);
    //     let costcell = newRow.insertCell(4);
    //     let statuscell = newRow.insertCell(5);
    //     let actioncell = newRow.insertCell(6);


    //     // Add content to the new cells
    //     var otk=document.getElementById("otk").value;
    //     itemcell.innerHTML = '<div style="width:150px;border-color: blue;border-width: 2px;" class="col-md-4" id="Sittings"> <select id="sittingitem" name="item" class="form-control" _style="width: 57%;"> <option value="">Select Item Type</option></select> </div>';
    //     setTimeout(loadOrderTypeItems(otk), 3000)
    //     hcopycell.innerHTML ='<div class="col-md-4" id="hcopymain"> <input id="hcopy" name="hcopy" type="text" class="form-control input-md" required=""> </div>'
    //     scopycell.innerHTML ='<div class="col-md-4" id="scopymain"> <input id="scopy" name="scopy" type="text" class="form-control input-md" required=""> </div>'
    //     edittypecell.innerHTML='<div class="col-md-4" id="edittypemain"><select  style="width:150px;" id="edittype" name="edittype" class="form-control"> @foreach ($editTypes as $editType) <option value="">Select Edit Type</option><option value="{{ $editType->edittypekey }}">{{ $editType->edittype }}</option> @endforeach </select> </div>'
    //     costcell.innerHTML=''
    //     statuscell.innerHTML=''
    //     actioncell.innerHTML='<button id="addBtn" type="button" class="btn btn-primary" onClick="">Add</button>'
    // }

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
            $("#okey").text(key);
            $("#odate").text(odate);
            $("#customer").text(customer);
            $("#total").text(total);
            $("#discount").text(discount);
            $("#paid").text(paid);
            $("#urgent").text(urgent);
            $("#status").text(status);
            loaditemdata_SS(key)
        }

        if (ot=='Media') 
        {        
            $("#orderModal_ME").modal("show");
            $("#onum_me").text(no);
            $("#okey_me").text(key);
            $("#odate_me").text(odate);
            $("#customer_me").text(customer);
            $("#total_me").text(total);
            $("#discount_me").text(discount);
            $("#paid_me").text(paid);
            $("#urgent_me").text(urgent);
            $("#status_me").text(status);
            loaditemdata_ME(key)
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
function printDiv(divId,onum,odate,dateDiv) {
    
    let basicDetailsElement = document.getElementById(divId);
    let printWindow = window.open('', '', 'width=800,height=600');

    let clonedContent = basicDetailsElement.cloneNode(true);
    clonedContent.querySelector("#addnew").remove();
    clonedContent.querySelector("#"+dateDiv).remove();
    alert(divId+'-'+dateDiv)

    clonedContent.querySelectorAll("tr[id^='row_']").forEach(row => {
       //if (row.cells.length > 5) { // Ensure the cell exists before deleting
       //alert(1)
          row.deleteCell(6);
       //}
    });
  
    printWindow.document.write(`
    <html>
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
            background: #f5f5f5;
        }
        .invoice-container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .company-logo img {
            max-width: 100px;
        }
        .invoice-details {
            text-align: right;
        }
        .invoice-details h2 {
            margin: 0;
            color: #333;
        }
        .client-info, .invoice-summary {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
   
        .total {
            text-align: right;
        }
        .print-btn {
            display: block;
            width: 100px;
            margin: 20px auto;
            padding: 10px;
            background: #007bff;
            color: white;
            text-align: center;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .print-btn:hover {
            background: #0056b3;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        @media print {
            .print-btn {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="invoice-container" id="invoice">
    <div class="invoice-header">
        <div class="company-logo">
            <img id="slogo" src="/logo/s_`+skey+`.png" alt="Company Logo">
        </div>
        <div class="">
            <h3>`+sname+` Studio</h3>
            <h5>`+saddress+`</h5>
            <h5>`+sphone+`</h5>

        </div>
        <div class="invoice-details">
            <h2>INVOICE</h2>
            <p>Invoice #: `+onum+`</p>
            <p>Date: `+odate+`</p>
        </div>
    </div>
    ${clonedContent.innerHTML}  
    <div class="invoice-summary">
        <!-- <h3 class="total">Grand Total: $180.00</h3> -->
    </div>
</div>



</body>
</html>

    `);


    printWindow.document.close();
    printWindow.focus();
    
    // Wait for the new window to load before printing
    printWindow.onload = function () {
        
        //$('#slogo').attr('src','/logo/s_'+skey+'.png')
        printWindow.print();
        printWindow.onafterprint = function () {
            printWindow.close();
        };
    };
}
       
</script>
@endpush
@endsection
