<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<div class="modal fade" id="orderModalpopup_SS" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog  modal-xl">
    <input type="hidden" id="otk" name="otk" value="edititem">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Order Details | <span id="onum"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container mx-auto py-6">
                    <div class="bg-white rounded-lg shadow-md p-6" style="display:flex;gap:10%">
                        <div>
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
                    </div></br>

                    <div id="orderitemResults" class="overflow-x-auto">

                        </div>
                    </div>
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

