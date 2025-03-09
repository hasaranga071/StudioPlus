<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<div class="modal fade" id="orderModal_SS" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
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
                    <button onClick="addnew()" type="button" style="float:left;font-size:15px" class="btn btn-info">+ Add Item</button></br></br>
                    
                   

                    <div id="orderitemResults" class="overflow-x-auto">
                       
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
<script>
        //document.getElementById("editBtn").addEventListener("click", function () {
        function edititem(ssorderitemmapkey,hcopy,scopy,edittype){

            document.getElementById("editBtn_"+ssorderitemmapkey).style.display = "none";
            document.getElementById("saveBtn_"+ssorderitemmapkey).style.display = "inline-block";

            document.getElementById("hcopy_"+ssorderitemmapkey).innerHTML='<div class="col-md-4" id="scopymain"> <input style="border-color: orange;" id="hcopy" value="'+hcopy+'" name="hcopy" type="text" class="form-control input-md" required=""> </div>'
            document.getElementById("scopy_"+ssorderitemmapkey).innerHTML='<div class="col-md-4" id="scopymain"> <input style="border-color: orange;" id="scopy" value="'+scopy+'" name="scopy" type="text" class="form-control input-md" required=""> </div>'
            document.getElementById("edittype_"+ssorderitemmapkey).innerHTML=
            '<div class="col-md-4" id="edittypemain"><select  style="width:150px;border-color: orange;" id="edittype" name="edittype" class="form-control"> <option value="">'+edittype+'</option> @foreach ($editTypes as $editType) <option value="{{ $editType->edittypekey }}">{{ $editType->edittype }}</option> @endforeach </select> </div>'
        }



        document.getElementById("saveBtn").addEventListener("click", function () {
            let cells = document.querySelectorAll("tbody td");
            let editedData = [];

            cells.forEach(cell => {
                if (cell.cellIndex !== 0) { 
                    cell.contentEditable = "false";
                    cell.classList.remove("edit-mode");
                }
            });

            document.getElementById("editBtn").style.display = "inline-block";
            document.getElementById("saveBtn").style.display = "none";

            // Collect data to send to the backend (if needed)
            let rows = document.querySelectorAll("tbody tr");
            rows.forEach(row => {
                let rowData = {
                    order_id: row.cells[0].innerText,
                    customer_name: row.cells[1].innerText,
                    total: row.cells[2].innerText
                };
                editedData.push(rowData);
            });

            console.log("Updated Data:", editedData); // Send this to the backend via AJAX if required
        });
        
    </script>