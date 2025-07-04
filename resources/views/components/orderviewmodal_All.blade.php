<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<div class="modal fade" id="orderModal_All" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog  modal-xl">
    <input type="hidden" id="otk" name="otk" value="edititem">
    <span id="okey" style="display:none;"></span>
        <div class="modal-content">
            <div class="modal-header">
            <div style='display:flex'>
                    <h5 class="modal-title" id="modalTitle">Bill Number | <span id="onum"></span></h5>
                    
                </div>
                <div>
                        
                    <button style="border-radius: 5px;" onClick="printDiv('ME','printArea_ME',document.getElementById('onum').textContent,document.getElementById('odate').textContent,'odate')">
                    Print
                    </button>
                </div>
            </div>
            <div class="modal-body" id="printArea">
                <div class="container mx-auto py-6">
                    <div class="bg-white rounded-lg shadow-md p-6" style="display:flex;gap:10%">
                        <div id="date">
                            <p class="text-gray-600">Created Date</p>
                            <span class="font-semibold" id="odate"></span>
                        </div>
                        <div>
                            <p class="text-gray-600">Customer Name</p>
                            <span class="font-semibold" id="customer"></span>
                        </div>
                        <div>
                            <p class="text-gray-600">Total Amount</p>
                            <span class="font-semibold" id="total"></span>
                        </div>
                        <div>
                            <p class="text-gray-600">Paid Amount</p>
                            <span class="font-semibold" id="paid"></span>
                        </div>
                        <div>
                            <p class="text-gray-600">Urgent</p>
                            <span class="font-semibold" id="urgent"></span>
                        </div>
                        <div>
                            <p class="text-gray-600">Status</p>
                            <span class="font-semibold" id="status"></span>
                        </div>
                    </div>
                    </br>
                    <div id="orderitemResults" class="overflow-x-auto"></div>
                    <div id="orderitemResults_me" class="overflow-x-auto"></div>
                    <div id="orderitemResults_fr" class="overflow-x-auto"></div>
                    <div id="orderitemResults_EC" class="overflow-x-auto"></div>
                    <div id="ordersummary" class="overflow-x-auto"></div>
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