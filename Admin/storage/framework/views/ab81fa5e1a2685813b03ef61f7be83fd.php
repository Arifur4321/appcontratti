      

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('translation.Variable-List'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?>
            Projects
        <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?>
         Contract List   
    <?php echo $__env->renderComponent(); ?>


    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> 

    <link rel="stylesheet" href="//cdn.datatables.net/2.0.2/css/dataTables.dataTables.min.css">

    <script src="//cdn.datatables.net/2.0.2/js/dataTables.min.js"></script>

    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
     <!--  Arifur change  -->
     <div class="row">
            <div class="col-sm">
                <div class="search-box me-2 d-inline-block">
              <div class="position-relative">
                        <input type="text" class="form-control" autocomplete="off" id="searchInput" placeholder="Search...">
                        <i class="bx bx-search-alt search-icon"></i>
                    </div>  
                </div>
            </div>

            <div class="col-sm-auto">
                <div class="text-sm-end">
                    <button type="button" class="btn btn-primary" onclick="redirectTocreatecontract()">Add New Contract</button>
                </div>
            </div>
    </div>
    

<script>    
    // function redirectTocreatecontract() {
    //     // Redirect to the route associated with createcontract.blade.php
    //     window.location.href = "/createcontract"; // Replace with your actual route path
    // }
    function redirectTocreatecontract() {
        // Redirect to the route associated with createcontract.blade.php
        window.location.href = "/createcontractwithupdatepage"; // Replace with your actual route path
    }

    function redirectToEditContract(contractId) {
    window.location.href = "/edit-contract-list/" + contractId;
    }
</script>


 <!-- Table content -->
<div class="table-responsive" style="margin-top:10px;">
    <table id="ContractList" class="table">
        <!-- Table header -->
        <thead>
            <tr>
                <th style="text-align: left;">ID</th>
                <th style="text-align: left;">Contract Name</th>
                <th style="text-align: left;">User Name</th>
                <th style="text-align: left;">Created Date</th>
                <th style="text-align: left;">Updated Date</th>
                <th style="text-align: left; width: 18%">Action</th>
            </tr>
        </thead>
        <!-- Table body -->
        <tbody>
            <?php $__currentLoopData = $contracts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contract): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="d-sm-table-row">
                <td style="text-align: left;"><?php echo e($contract->id); ?></td>
                <td style="text-align: left;"><?php echo e($contract->contract_name); ?></td>
                <td style="text-align: left;"><?php echo e(Auth::user()->name); ?></td>
                <td style="text-align: left;"><?php echo e($contract->created_at); ?></td>
                <td style="text-align: left;"><?php echo e($contract->updated_at); ?></td>
                <td style="text-align: left;">
                    <!-- Action buttons -->
                    <div class="btn-toolbar">
                        <button class="btn btn-primary" onclick="redirectToEditContract('<?php echo e($contract->id); ?>')">Edit</button>
                        <form id="delete-form-<?php echo e($contract->id); ?>" action="<?php echo e(route('contracts.destroy', $contract->id)); ?>" method="POST" style="display: none;">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                        </form>
                        <button type="button" style="margin-left:2px;" onclick="confirmDelete('<?php echo e($contract->id); ?>');" class="btn btn-danger waves-effect waves-light">
                            <i class="bx bx-block font-size-16 align-middle me-2"></i> Delete
                        </button>
                    </div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>

 

<!-- pagination -->


<style>

.dataTables_wrapper .dataTables_paginate {
        margin-top: 10px;
    }

    .dataTables_wrapper .dataTables_length {
        margin: 8px;
        margin-left: 8px;
    }

    .float-start {
        float: left !important;
    }

    .float-end {
        float: right !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        display: inline-block;
        padding: 6px 12px;
        margin-left: 2px;
        margin-right: 2px;
        border: 1px solid #ddd;
        border-radius: 4px;
        color: #333;
        background-color: #fff;
        text-decoration: none;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background-color: #eee;
        border-color: #ddd;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background-color: #007bff;
        border-color: #007bff;
        color: #fff;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        color: #ddd;
    }

    
    </style>



            <script>
                       
                       
        function confirmDelete(contractId) {
                            // Display SweetAlert2 confirmation popup
                            Swal.fire({
                                title: 'Are you sure?',
                                text: "Do you want to delete this contract?",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes, delete it!',
                                cancelButtonText: 'No, cancel!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // If user clicks 'Yes', submit the form
                                    document.getElementById('delete-form-' + contractId).submit();
                                } else {
                                    // If user clicks 'No', do nothing
                                    return false;
                                }
                            });
        }
                
 
 $(document).ready(function() {
        let table = new DataTable('#ContractList', {
            pagingType: 'full_numbers',
            dom: '<"top"f>rt<"bottom"<"float-start"l><"float-end"p>><"clear">',
            language: {
                paginate: {
                    first: '<<',
                    last: '>>',
                    next: 'Next',
                    previous: 'Previous'
                },
                lengthMenu: "Show _MENU_ entries"
            }
        });

        $('.dt-search').hide();
        $('.dataTables_info').addClass('right-info');

        $('#searchInput').on('keyup', function() {
            table.search($(this).val()).draw();
        });
    });


 </script>

 
<!-- variable Modal -->
<!-- product list Modal -->
<div class="modal" id="exampleModalNew" tabindex="-1" aria-labelledby="exampleModalLabelNew" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabelNew">Variable List</h5>
                <div class="col-sm">
                    <div class="search-box me-2 d-inline-block">
                        <div class="position-relative">
                            <input type="text" class="form-control" autocomplete="off" id="" placeholder="Search...">
                            <i class="bx bx-search-alt search-icon"></i>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="productFormNew" action="/createcontract" method="POST" >
                    <!-- for table -->
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <!-- Wrap the table inside a div with fixed height and auto scroll -->
                    <div style="max-height: 400px; overflow-y: auto;">
                        <table id=" " action="/createcontract" method="POST" class="table">
                            <thead>
                                <tr>
                                    <th>VariableID</th>
                                    <th>Var Name</th>
                                    <th>Var Type</th>
                                
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $variables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contract): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($contract->VariableID); ?></td>
                                        <td><?php echo e($contract->VariableName); ?></td>
                                        <td><?php echo e($contract->VariableType); ?></td>
                                    
                                        <td>
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input add-checkbox"
                                                 onchange="checkCheckbox(this, '<?php echo e($contract->VariableName); ?>') ">
                                            </label>
                                            <button type="button" class="btn btn-primary add-button"
                                             data-bs-dismiss="modal" onclick="insertVariable('<?php echo e($contract->VariableName); ?>')" disabled> Add </button>
                                        </td>


                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                <!-- <button type="button" class="btn btn-primary" onclick="">Save Product</button> -->
            </div>
        </div>
    </div>
</div>
 
  <style>
    #exampleModalNew .modal-content {
        background-color: black;
        color: white; /* Optionally, change the text color */
    }
</style>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('js/newckeditor/build/ckeditor.js')); ?>"></script>
<script>


            let editormodal;  // Declare editormodal as a global variable

            document.addEventListener('DOMContentLoaded', function() {
            editormodal = document.getElementById('editormodal');

            if (editormodal) {
                ClassicEditor
                    .create(editormodal)
                    .then(editor => {
                        // Your initialization code here
                        editormodal = editor;

                             // Define a function to insert text into CKEditor 5
                    window.insertVariable = function(variableName) {
                        // Insert variableName at the current cursor position
                        const currentPosition = editor.model.document.selection.getLastPosition();
                        if (currentPosition) {
                            editor.model.change(writer => {
                                writer.insertText("%"+variableName+"%", currentPosition);
                            });
                        }
                    }
                    })
                    .catch(error => {
                        console.error(error);
                    });
            } else {
                console.error('Textarea element with ID "editormodal" not found.');
            }
        });

        function openModal(id, name, contractContent) {
            // Using Bootstrap's JavaScript to open the modal
            var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
            
            // Set values in the modal form
            document.getElementById('contract-id').value = id;
            document.getElementById('project-name-new').value = name;
            // Use setData to set content in CKEditor
            editormodal.setData(contractContent);
            
            // Show the modal
            myModal.show();
        }

        function saveEditedContent() {
            // Get the CKEditor instance and its data
            var editorData = editormodal.getData();
            
            // Get the contract name and ID
            const contractId = document.querySelector('#contract-id').value;
            const contractName = document.querySelector('#project-name-new').value;
            
            // Send the edited content to the server using AJAX
            saveContent(contractId, contractName, editorData);
            // Close the modal
            $('#exampleModal').modal('hide');
        }

        function saveContent(id, title, content) {
            $.ajax({
                url: '/updatecontract',
                type: 'POST',
                data: {
                    _token: "<?php echo e(csrf_token()); ?>",
                    id: id,
                    contract_name: title,
                    editor_content: content
                },
                success: function(response) {
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }


        // Function to save data when Save button is clicked
        function saveData() {
            const title = document.querySelector('#title').value; // Get title
            const content = editor.getData(); // Get content from CKEditor
            saveContent(title, content); // Call saveContent function to save data
        }

        function saveDatamodal() {
            const title = document.querySelector('#titlemodal').value; // Get title
            const content = editormodal.getData(); // Get content from CKEditor
           // saveContent(title, content); // Call saveContent function to save data
        }


        // for variable button  
        function openModalNew() {
        // Using Bootstrap's JavaScript to open the product list modal
        var myModal = new bootstrap.Modal(document.getElementById('exampleModalNew'));
        myModal.show();
        }
 

    $('#exampleModalNew').modal('hide');

    //Function to insert a variable into CKEditor
    function insertVariable(variableName) {
            // Get the current content of the editor
            const currentContent = editor.getData();
            // Append the variable to the content with % signs
            const newContent = currentContent + `%${variableName}%`;
            // Set the updated content back to the editor
            editor.setData(newContent);
        }
 

    function countOccurrences(str, searchValue) {
        var regex = new RegExp(searchValue, 'g');
        var matches = str.match(regex);
        return matches ? matches.length : 0;
        }

        // Function to delete a substring from a string
        function deleteSubstring(str, searchValue) {
            var regex = new RegExp(searchValue, 'g');
            return str.replace(regex, '');
        }

        // Function to handle checkbox change event
        // Function to handle checkbox change event
            function checkCheckbox(checkbox, variableName) {
                if (!checkbox.checked) {
                    var editorData = editormodal.getData();
                    var count = countOccurrences(editorData, variableName);

                    if (count > 0) {
                        Swal.fire({
                            title: 'Variable Found!',
                            text: 'The variable ' + variableName + ' appears ' + count + ' times in the editor content. Do you want to delete it?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, delete it',
                            cancelButtonText: 'No, cancel',
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Perform delete operation
                                var latestvar = "%"+ variableName + "%"
                                editorData = deleteSubstring(editorData, latestvar);
                                editor.setData(editorData);
                            } else {
                                // If user cancels deletion, recheck the checkbox
                                checkbox.checked = true;
                            }
                        });
                    }
                }
            }
       

    </script>

 
 


<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
    #spinner-overlay {
        display: none;
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 9999;
    }

    #spinner {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: flex;
        align-items: center;
        justify-content: center;
        width: 120px;
        height: 120px;
    }

    .ring {
        border: 8px solid transparent;
        border-radius: 50%;
        position: absolute;
        animation: spin 1.5s linear infinite;
    }

    .ring:nth-child(1) {
        width: 120px;
        height: 120px;
        border-top: 8px solid #3498db;
        animation-delay: -0.45s;
    }

    .ring:nth-child(2) {
        width: 100px;
        height: 100px;
        border-right: 8px solid #f39c12;
        animation-delay: -0.3s;
    }

    .ring:nth-child(3) {
        width: 80px;
        height: 80px;
        border-bottom: 8px solid #e74c3c;
        animation-delay: -0.15s;
    }

    .ring:nth-child(4) {
        width: 60px;
        height: 60px;
        border-left: 8px solid #9b59b6;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<!-- Spinner Overlay -->
<div id="spinner-overlay">
    <div id="spinner">
        <div class="ring"></div>
        <div class="ring"></div>
        <div class="ring"></div>
        <div class="ring"></div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const spinnerOverlay = document.getElementById("spinner-overlay");

        // Show the spinner when the page is loading
        spinnerOverlay.style.display = "block";

        window.addEventListener("load", function() {
            // Hide the spinner when the page has fully loaded
            spinnerOverlay.style.display = "none";
        });

        document.addEventListener("ajaxStart", function() {
            // Show the spinner when an AJAX request starts
            spinnerOverlay.style.display = "block";
        });

        document.addEventListener("ajaxStop", function() {
            // Hide the spinner when the AJAX request completes
            spinnerOverlay.style.display = "none";
        });
    });
</script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Giacometti\Skote_Html_Laravel_v4.2.1\Laravel\Server-Backup\New-Branch-Work-6-10-2024\working-one\appcontratti\resources\views/ContractList.blade.php ENDPATH**/ ?>