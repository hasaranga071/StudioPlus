<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<div class="modal fade" id="orderModal_FR" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog  modal-xl">
    <input type="hidden" id="otk" name="otk" value="edititem">
    <span id="okey_fr" style="display:none;"></span>
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Order Details | <span id="onum_fr"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container mx-auto py-6">
                    <div class="bg-white rounded-lg shadow-md p-6" style="display:flex;gap:10%">
                        <div>
                            <p class="text-gray-600">Created Date</p>
                            <span class="font-semibold" id="odate_fr"></span>
                        </div>
                        <div>
                            <p class="text-gray-600">Customer Name</p>
                            <span class="font-semibold" id="customer_fr"></span>
                        </div>
                        <div>
                            <p class="text-gray-600">Total Amount</p>
                            <span class="font-semibold" id="total_fr"></span>
                        </div>
                        <div>
                            <p class="text-gray-600">Paid Amount</p>
                            <span class="font-semibold" id="paid_fr"></span>
                        </div>
                        <div>
                            <p class="text-gray-600">Urgent</p>
                            <span class="font-semibold" id="urgent_fr"></span>
                        </div>
                        <div>
                            <p class="text-gray-600">Status</p>
                            <span class="font-semibold" id="status_fr"></span>
                        </div>
                    </div></br>
                    <button onClick="addnew_fr(document.getElementById('okey_fr').textContent.trim())" type="button" style="float:left;font-size:15px" class="btn btn-info">+ Add Item</button></br></br>
                    
                   

                    <div id="orderitemResults_fr" class="overflow-x-auto">
                       
                        </div>
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