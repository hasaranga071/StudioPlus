<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<div class="modal fade" id="orderModal_EC" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog  modal-xl">
    <input type="hidden" id="otk" name="otk" value="edititem">
        <span id="okey_EC" style="display:none;"></span>
        <div class="modal-content">
        <div class="modal-header">
                <div style='display:flex'>
                    <h5 class="modal-title" id="modalTitle">Order Details | <span id="onum_EC"></span></h5>
                    
                </div>
                <div>
                        
                    <button style="border-radius: 5px;" onClick="printDiv('printArea_EC',document.getElementById('onum_EC').textContent,document.getElementById('odate_EC').textContent,'odate_EC')">
                    Print
                    </button>
                </div>
            </div>
            <div class="modal-body" id="printArea_EC">
                <div class="container mx-auto py-6">
                    <div class="bg-white rounded-lg shadow-md p-6" style="display:flex;gap:10%">
                        <div  id="date">
                            <p class="text-gray-600">Created Date</p>
                            <span class="font-semibold" id="odate_EC"></span>
                        </div>
                        <div>
                            <p class="text-gray-600">Customer Name</p>
                            <span class="font-semibold" id="customer_EC"></span>
                        </div>
                        <div>
                            <p class="text-gray-600">Total Amount</p>
                            <span class="font-semibold" id="total_EC"></span>
                        </div>
                        <div>
                            <p class="text-gray-600">Paid Amount</p>
                            <span class="font-semibold" id="paid_EC"></span>
                        </div>
                        <div>
                            <p class="text-gray-600">Urgent</p>
                            <span class="font-semibold" id="urgent_EC"></span>
                        </div>
                        <div>
                            <p class="text-gray-600">Status</p>
                            <span class="font-semibold" id="status_EC"></span>
                        </div>
                    </div></br>
                    <!-- <button id="addnew" onClick="addnew_EC(document.getElementById('okey_EC').textContent.trim())" type="button" style="float:left;font-size:15px" class="btn btn-info">+ Add Item</button></br></br> -->
                    
                   

                    <div id="orderitemResults_EC" class="overflow-x-auto"></div>
                    <div id="ordersummary_EC" class="overflow-x-auto"> </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
        .edit-mode {
            background-color:rgb(188, 24, 139);
        }
    </style>