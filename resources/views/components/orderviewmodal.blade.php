<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog  modal-xl">
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
                    </div></br></br>
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="px-4 py-2 text-left">Item Type</th>
                                        <th class="px-4 py-2 text-left">Hard Copies</th>
                                        <th class="px-4 py-2 text-left">Soft Copies</th>
                                        <th class="px-4 py-2 text-left">Comments</th>
                                    </tr>
                                </thead>
                               
                            </table> 
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>