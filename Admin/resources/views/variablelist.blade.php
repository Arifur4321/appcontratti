@extends('layouts.master')
@section('title')
    @lang('translation.Variable-List')
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Projects
        @endslot
        @slot('title')
        Variable List 
        @endslot
    @endcomponent


    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> 
    <link rel="stylesheet" href="//cdn.datatables.net/2.0.2/css/dataTables.dataTables.min.css">
    <script src="//cdn.datatables.net/2.0.2/js/dataTables.min.js"></script>

    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
     <!--  Arifur change -->
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
            <button type="button" class="btn btn-primary" onclick="openModalNew()">Add New Variable</button>
        </div>
    </div>
</div>


<!-- variable add  Modal -->
<div class="modal" id="exampleModalNew" tabindex="-1" aria-labelledby="exampleModalLabelNew" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabelNew">New Variable</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="productFormNew">
              
                    <div class="mb-3">
                        <label for="product-name-new" class="col-form-label">Variable Name:</label>
                        <input type="text" class="form-control" id="product-name-new">
                    </div>
  
                    <style>
                        /* Style for the drag button */
                         /* .drag-button {
                            background-color: #007bff;
                            color: #fff;
                            border: none;
                            border-radius: 50%;
                            width: 24px;
                            height: 24px;
                            line-height: 24px;
                            font-size: 14px;
                            text-align: center;
                            cursor: grab;
                        }   */
 
                         
                    </style>
                                    
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="variable-type" class="col-form-label">Variable Type:</label>
                                <select class="form-select" id="variable-type">
                                                        <option value="Single Line Text">Single Line Text</option>
                                                        <option value="Multiple Line Text">Multiple Line Text</option>
                                                        <option value="Dates">Dates</option>
                                                        <option value="Multiple Box">Multiple new Box</option>
                                                        <option value="Single Box">Single Box</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-6" id="dynamic-fields-container" ondrop="drop(event)" ondragover="allowDrop(event)">
                            <!-- Dynamic fields will be added here -->
                        </div>
                    </div>

              

                    <div class="mb-3">
                        <label for="description-new" class="col-form-label">Description:</label>
                        <textarea class="form-control" id="description-new"></textarea>
                    </div>
               
                     
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                <button type="button" class="btn btn-primary" onclick="saveVariable()">Save Variable</button>

            </div>
        </div>
    </div>
</div>

<script>

var inputFields = []; // Array to store input fields

document.addEventListener("DOMContentLoaded", function() {
                            var variableTypeSelect = document.getElementById('variable-type');
                            var dynamicFieldsContainer = document.getElementById('dynamic-fields-container');
                            var addButton = null;
                          
                            variableTypeSelect.addEventListener('change', function() {
                                clearInputFields();
                                dynamicFieldsContainer.innerHTML = '';
                                var selectedOption = variableTypeSelect.value;

                                // Check if the selected option is "Multiple Box" or "Single Box"
                                if (selectedOption === 'Multiple Box' || selectedOption === 'Single Box') {
                                
                                    addButton = createAddButton();

                                    dynamicFieldsContainer.appendChild(addButton);
                                }
                            });

                            // Function to create the "Add" button 
                            function createAddButton() {
                                addInputFields();
                                var addButton = document.createElement('button');
                                addButton.textContent = 'Add';
                                addButton.classList.add('form-control');
                                addButton.style=   "width: 58px;";
                            
                                addButton.setAttribute('type', 'button');
                                addButton.addEventListener('click', function() {
                                    addInputField();
                                });
                                return addButton;
                            }

                            // Function to add input fields
                            function addInputFields() {
                                inputFields.forEach(function(inputField) {
                                    dynamicFieldsContainer.appendChild(inputField);
                                });
                            }

                            // Function to add input field, drag button, and delete button
                            function addInputField() {
                                // Create wrapper div for input field and buttons
                                var wrapperDiv = document.createElement('div');
                                wrapperDiv.classList.add('dynamic-field');

                                // Create input field
                                // var inputField = document.createElement('input');
                                // inputField.setAttribute('type', 'text');
                                // inputField.setAttribute('placeholder', 'Enter value');


                                // Create input field with custom styling
                                var inputField = document.createElement('input');
                                inputField.setAttribute('type', 'text');
                                inputField.setAttribute('placeholder', 'Enter value');
                            //    inputField.classList.add('form-control'); // Adding Bootstrap class
                                inputField.style.border = '1px solid #ccc'; // Add border
                                inputField.style.borderRadius = '5px'; // Add border radius
                                inputField.style.padding = '8px 12px'; // Add padding
                                inputField.style.width = '200px'; // Set width
                                inputField.style.boxSizing = 'border-box'; // Include padding and border in the width           
                                
                  

                          
                                // Create drag button
                                var dragButton = document.createElement('button');
                                dragButton.textContent = '☰'; // Drag icon
                                dragButton.setAttribute('type', 'button');
                                dragButton.classList.add('btn', 'btn-primary', 'btn-sm'); // Adding Bootstrap classes
                                dragButton.classList.add('drag-button'); // Apply CSS class for styling
                                
                                // Attach drag event listener to the wrapper div
                                wrapperDiv.addEventListener('dragstart', function(event) {
                                    event.dataTransfer.setData('text/plain', ''); // Required for drag-and-drop
                                    event.currentTarget.classList.add('dragging'); // Add class to the dragged element
                                    event.stopPropagation(); // Prevents parent drag event
                                });

                                // Set draggable attribute to true for the wrapper div
                                wrapperDiv.setAttribute('draggable', 'true');

                                // Create delete button
                                // var deleteButton = document.createElement('button');
                                // deleteButton.innerHTML = '&#10005;'; // Cross sign
                                // deleteButton.setAttribute('type', 'button');
                                // deleteButton.addEventListener('click', function() {
                                //     wrapperDiv.remove();
                                //     removeFromInputFields(wrapperDiv);
                                // });

                                // create delete button
                                var deleteButton = document.createElement('button');
                                deleteButton.innerHTML = '&#10005;'; // Cross sign
                                deleteButton.setAttribute('type', 'button');
                                deleteButton.classList.add('btn', 'btn-danger', 'btn-sm'); // Adding Bootstrap classes

                                deleteButton.addEventListener('click', function() {
                                    wrapperDiv.remove();
                                    removeFromInputFields(wrapperDiv);
                                });


                                // Append input field, delete button, and drag button to wrapper div
                                wrapperDiv.appendChild(inputField);
                                wrapperDiv.appendChild(dragButton);
                                wrapperDiv.appendChild(deleteButton);

                                // Append wrapper div to container
                                dynamicFieldsContainer.insertBefore(wrapperDiv, addButton);
                                inputFields.push(wrapperDiv); // Add input field to the array
                            }

                            // Function to remove input field from the array
                            function removeFromInputFields(element) {
                                var index = inputFields.indexOf(element);
                                if (index !== -1) {
                                    inputFields.splice(index, 1);
                                }
                            }

                            // Function to clear input fields array
                            function clearInputFields() {
                                inputFields.forEach(function(inputField) {
                                    inputField.remove();
                                });
                                inputFields = [];
                            }
                        });

                        // Function to allow dropping elements
                        function allowDrop(event) {
                            event.preventDefault();
                        }

                        // Function to handle dropping elements
                        function drop(event) {
                            event.preventDefault();
                            var data = event.dataTransfer.getData("text/plain");
                            var draggedElement = document.querySelector('.dragging');
                            var targetElement = event.target.closest('.dynamic-field');
                            if (targetElement) {
                                if (targetElement !== draggedElement) {
                                    if (draggedElement.compareDocumentPosition(targetElement) === Node.DOCUMENT_POSITION_FOLLOWING) {
                                        targetElement.parentNode.insertBefore(draggedElement, targetElement.nextSibling);
                                    } else {
                                        targetElement.parentNode.insertBefore(draggedElement, targetElement);
                                    }
                                }
                            } else {
                                event.target.appendChild(draggedElement);
                            }
                        }


function saveVariable() {
    var productName = $('#product-name-new').val();
    var description = $('#description-new').val();
    var variableType = $('#variable-type').val(); // Get the selected variable type

    if (!productName || !description || !variableType) {
        console.error('All fields must be filled out.');
        return;
    }

    var inputFieldValues = []; // Array to store values of dynamically added input fields

    // Loop through inputFields array to collect values
    inputFields.forEach(function(inputField) {
        var value = $(inputField).find('input[type="text"]').val();
        inputFieldValues.push(value);
    });

    console.log('inputFieldValues ----> :' , inputFieldValues);

    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: '/save-variable',
        type: 'POST',
        data: {
            VariableName: productName,
            description: description,
            variableType: variableType,
            inputFieldValues: inputFieldValues, // Include values of dynamically added input fields
            _token: csrfToken
        },
        success: function (response) {
            console.log('Data saved successfully.');
            $('#exampleModalNew').modal('hide');
            location.reload();
        },
        error: function (error) {
            console.error('Error saving data:', error);
        }
    });
}


// function saveVariable() {
//     var productName = $('#product-name-new').val();
//     var description = $('#description-new').val();
//     var variableType = $('#variable-type').val(); // Get the selected variable type


//     if (!productName || !description || !variableType) {
//         console.error('All fields must be filled out.');
//         return;
//     }

//     var csrfToken = $('meta[name="csrf-token"]').attr('content');

//     $.ajax({
//         url: '/save-variable',
//         type: 'POST',
//         data: {
//             VariableName: productName,
//             description: description,
//             variableType: variableType, // Use variable_type instead of variableType
//             _token: csrfToken
//         },
//         success: function (response) {
//             console.log('Data saved successfully.');
//             $('#exampleModalNew').modal('hide');
//             location.reload();
//         },
//         error: function (error) {
//             console.error('Error saving data:', error);
//         }
//     });
// }


</script>

    <!-- for table -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <div class="table-responsive" style="margin-top:10px;">
    <table id="ContractList" class="table">
    <thead>
        <tr>
            <th  style="text-align: left;">ID</th>
            <th  style="text-align: left;">Variable Name</th>
            <th  style="text-align: left;">Variable Type</th>
            <th  style="text-align: left;">Description</th>
            <th  style="text-align: left;">Created Date</th>
            <th  style="text-align: left;">Updated Date</th>
            <th  style="text-align: left;" >Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($variables as $contract)
            <tr>
                <td  style="text-align: left;">{{ $contract->VariableID }}</td>
                <td  style="text-align: left;">{{ $contract->VariableName }}</td>
                <td  style="text-align: left;">{{ $contract->VariableType }}</td>
                <td style="text-align: left;" >{{ $contract->Description }}</td>
                <td  style="text-align: left;">{{ $contract->created_at }}</td>
                <td  style="text-align: left;">{{ $contract->updated_at }}</td>
                <td  style="text-align: left;">



                <div class="btn-toolbar">
                                <button class="btn btn-primary" 
                                onclick="openModal('{{ $contract->VariableID }}', '{{ $contract->VariableName }}', '{{ $contract->VariableType }}', '{{ $contract->Description }}', '{{ json_encode($contract->VariableLabelValue) }}')"
                                >Edit</button>


                 <form id="deleteForm-{{ $contract->VariableID }}" action="{{ route('contract.delete', $contract->VariableID) }}" method="POST">
                        @csrf
                        @method('POST')
                        <button type="button" style="margin-left:2px;"   class="btn btn-danger waves-effect waves-light"
                        onclick="confirmDelete('{{ $contract->VariableID }}');">
                        <i class="bx bx-block font-size-16 align-middle me-2"></i> Delete
                        </button>
                    </form>
                                
                </div>
<!-- 
    <div class="dropdown">
        <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="mdi mdi-dots-horizontal font-size-18"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-end">
            <ul class="dropdown-menu dropdown-menu-end show" style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate3d(-31px, 27px, 0px);" data-popper-placement="bottom-end">
     

                <a href="#" class="dropdown-item edit-list" onclick="openModal('{{ $contract->VariableID }}', '{{ $contract->VariableName }}', '{{ $contract->VariableType }}', '{{ $contract->Description }}', '{{ json_encode($contract->VariableLabelValue) }}')">
                    <i class="mdi mdi-pencil font-size-16 text-success me-1"></i> Edit
                </a>

                <form id="deleteForm-{{ $contract->VariableID }}" action="{{ route('contract.delete', $contract->VariableID) }}" method="POST">
                    @csrf
                    @method('POST')
                    <a href="#" class="dropdown-item edit-list" onclick="confirmDelete('{{ $contract->VariableID }}');">
                        <i class="mdi mdi-trash-can font-size-16 text-danger me-1"></i> Delete
                    </a>
                </form>



            </ul>
        </div>
    </div> -->


</td>
        </tr>
 
        @endforeach
    </tbody>
</table>
 
</div>

 


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

    <!-- JavaScript for confirmation popup -->
<script>


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
        
    function confirmDelete(contractId) {
                        $.ajax({
                            url: '/HowmanyVariable',
                            method: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                VariableID: contractId
                            },
                            success: function(response) {
                                var count = response.count;
                                var countContract = response.countContract;
                                console.log("Number of variable IDs: " + count);

                                // Display SweetAlert2 confirmation popup
                                Swal.fire({
                                    title: 'Are you sure?',
                                    text: "This Variable is " + count + " times checked. Are you sure you want to delete this Variable?",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Yes, delete it!',
                                    cancelButtonText: 'No, cancel!'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                         
                                        document.getElementById('deleteForm-' + contractId).submit();
                                    } else {
                                      
                                        return false;
                                    }
                                });
                            },
                            error: function(xhr, status, error) {
                                // Handle error
                                Swal.fire(
                                    'Error!',
                                    'There was an error processing your request.',
                                    'error'
                                );
                            }
                        });
                    }
</script>

 
  
 <!-- edit Modal  -->
<div class="modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Variable</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="variableForm">
                    <input type="hidden" id="variable-id" name="variable_id">
                    <div class="mb-3">
                        <label for="variable-name" class="col-form-label">Variable name:</label>
                        <input type="text" class="form-control" id="variable-name" name="variableName">
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="variable-type-two" class="col-form-label">Variable Type:</label>
                                <select class="form-select" id="variable-type-two">
                                    <option value="Single Line Text">Single Line Text</option>
                                    <option value="Multiple Line Text">Multiple Line Text</option>
                                    <option value="Dates">Dates</option>
                                    <option value="Multiple Box">Multiple Box</option>
                                    <option value="Single Box">Single Box</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-6" id="dynamic-fields-container-two">
                         
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="col-form-label">Description:</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="editVariable()">Save Variable</button>
            </div>
        </div>
    </div>
</div>



<!-- arifur for search -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>

 
    var inputFieldsTwo = []; // Array to store input fields
    
    document.addEventListener("DOMContentLoaded", function() {
                            
                            var variableTypeSelectTwo = document.getElementById('variable-type-two');
                            var dynamicFieldsContainerTwo = document.getElementById('dynamic-fields-container-two');
                            var addButtonTwo = null;
                   
                            
                            variableTypeSelectTwo.addEventListener('change', function() {
                                clearInputFieldsTwo();
                                dynamicFieldsContainerTwo.innerHTML = '';
                                var selectedOptionTwo = variableTypeSelectTwo.value;

                                // Check if the selected option is "Multiple Box" or "Single Box"
                                if (selectedOptionTwo === 'Multiple Box' || selectedOptionTwo === 'Single Box') {
                                
                                    addButtonTwo = createAddButtonTwo();

                                    dynamicFieldsContainerTwo.appendChild(addButtonTwo);
                                }
                            });

                            // Function to create the "Add" button 
                            function createAddButtonTwo() {
                                addInputFieldsTwo();
                                var addButtonTwo = document.createElement('button');
                                addButtonTwo.textContent = 'Add';
                                addButtonTwo.classList.add('form-control');
                                addButtonTwo.style=   "width: 58px;";

                                addButtonTwo.setAttribute('type', 'button');
                                addButtonTwo.addEventListener('click', function() {
                                    addInputFieldTwo();
                                });
                                return addButtonTwo;
                            }

                            // Function to add input fields
                            function addInputFieldsTwo() {
                                inputFieldsTwo.forEach(function(inputField) {
                                    dynamicFieldsContainerTwo.appendChild(inputField);
                                });
                            }

                            function addInputFieldTwo() {
                                // Create wrapper div for input field and buttons
                                var wrapperDiv = document.createElement('div');
                                wrapperDiv.classList.add('dynamic-field');

                                // Create input field
                                // var inputField = document.createElement('input');
                                // inputField.setAttribute('type', 'text');
                                // inputField.setAttribute('placeholder', 'Enter value');

                                var inputField = document.createElement('input');
                                inputField.setAttribute('type', 'text');
                                inputField.setAttribute('placeholder', 'Enter value');
                                inputField.style.border = '1px solid #ccc'; // Add border
                                inputField.style.borderRadius = '5px'; // Add border radius
                                inputField.style.padding = '8px 12px'; // Add padding
                                inputField.style.width = '200px'; // Set width
                                inputField.style.boxSizing = 'border-box'; // Include padding and border in the width

                                // Create drag button
                                var dragButton = document.createElement('button');
                                dragButton.textContent = '☰'; // Drag icon
                                dragButton.setAttribute('type', 'button');
                                dragButton.classList.add('btn', 'btn-primary', 'btn-sm'); 
                                dragButton.classList.add('drag-button'); // Apply CSS class for styling

                                // Attach drag event listener to the wrapper div
                                wrapperDiv.addEventListener('dragstart', function(event) {
                                    event.dataTransfer.setData('text/plain', ''); // Required for drag-and-drop
                                    event.currentTarget.classList.add('dragging'); // Add class to the dragged element
                                    event.stopPropagation(); // Prevents parent drag event
                                });

                                // Set draggable attribute to true for the wrapper div
                                wrapperDiv.setAttribute('draggable', 'true');

                                // Create delete button
                                var deleteButton = document.createElement('button');
                                deleteButton.innerHTML = '&#10005;'; // Cross sign
                                deleteButton.setAttribute('type', 'button');
                                deleteButton.classList.add('btn', 'btn-danger', 'btn-sm'); 
                                deleteButton.addEventListener('click', function() {
                                    wrapperDiv.remove();
                                    removeFromInputFieldsTwo(wrapperDiv);
                                });

                                // Append input field, delete button, and drag button to wrapper div
                                wrapperDiv.appendChild(inputField);
                                wrapperDiv.appendChild(dragButton);
                                wrapperDiv.appendChild(deleteButton);

                                // Append wrapper div to container
                                dynamicFieldsContainerTwo.insertBefore(wrapperDiv, addButtonTwo);
                                inputFieldsTwo.push(wrapperDiv); // Add input field to the array

                                // Function to handle dropping elements
                                wrapperDiv.addEventListener('drop', function(event) {
                                    event.preventDefault();
                                    var data = event.dataTransfer.getData("text/plain");
                                    var draggedElement = document.querySelector('.dragging');
                                    var targetElement = event.currentTarget;
                                    if (targetElement) {
                                        if (targetElement !== draggedElement) {
                                            if (draggedElement.compareDocumentPosition(targetElement) === Node.DOCUMENT_POSITION_FOLLOWING) {
                                                targetElement.parentNode.insertBefore(draggedElement, targetElement.nextSibling);
                                            } else {
                                                targetElement.parentNode.insertBefore(draggedElement, targetElement);
                                            }
                                        }
                                    } else {
                                        event.target.appendChild(draggedElement);
                                    }
                                });

                                // Function to allow dropping elements
                                wrapperDiv.addEventListener('dragover', function(event) {
                                    event.preventDefault();
                                });
                            }

                            // Function to remove input field from the array
                            function removeFromInputFieldsTwo(element) {
                                var index = inputFieldsTwo.indexOf(element);
                                if (index !== -1) {
                                    inputFieldsTwo.splice(index, 1);
                                }
                            }

                            // Function to clear input fields array
                            function clearInputFieldsTwo() {
                                inputFieldsTwo.forEach(function(inputField) {
                                    inputField.remove();
                                });
                                inputFieldsTwo = [];
                            }
                        });

                        // Function to allow dropping elements
                        function allowDrop(event) {
                            event.preventDefault();
                        }

                        // Function to handle dropping elements
                        function drop(event) {
                            event.preventDefault();
                            var data = event.dataTransfer.getData("text/plain");
                            var draggedElement = document.querySelector('.dragging');
                            var targetElement = event.target.closest('.dynamic-field');
                            if (targetElement) {
                                if (targetElement !== draggedElement) {
                                    if (draggedElement.compareDocumentPosition(targetElement) === Node.DOCUMENT_POSITION_FOLLOWING) {
                                        targetElement.parentNode.insertBefore(draggedElement, targetElement.nextSibling);
                                    } else {
                                        targetElement.parentNode.insertBefore(draggedElement, targetElement);
                                    }
                                }
                            } else {
                                event.target.appendChild(draggedElement);
                            }
                        }
             



    $(document).ready(function () {
        // Reference to the input field and the table
        var $searchInput = $('#searchInput');
        var $table = $('#ContractList');

        // Event listener for keyup on the search input
        $searchInput.on('keyup', function () {
            var searchText = $(this).val().toLowerCase();

            // Filter the table rows based on the search text
            $table.find('tbody tr').filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(searchText) > -1);
            });
        });
    });

    function openModalNew() {
        // Using Bootstrap's JavaScript to open the product list modal
        var myModal = new bootstrap.Modal(document.getElementById('exampleModalNew'));
        myModal.show();
    }

 

// Function to make a row draggable and droppable

function makeRowDraggable(row) {
    var dragButton = row.querySelector('.drag-button');

    // Attach drag event listeners to the drag button
    dragButton.addEventListener('dragstart', function(event) {
        event.dataTransfer.setData('text/plain', ''); // Required for drag-and-drop
        row.classList.add('dragging'); // Add class to the dragged element
        event.stopPropagation(); // Prevents parent drag event
    });

    // Attach dragover event listener to row to allow dropping
    row.addEventListener('dragover', function(event) {
        event.preventDefault(); // Prevent default behavior to allow drop
    });

    // Attach dragenter event listener to row to indicate potential drop target
    row.addEventListener('dragenter', function(event) {
        row.classList.add('drag-over'); // Add class to indicate potential drop target
    });

    // Attach dragleave event listener to row to remove indication of potential drop target
    row.addEventListener('dragleave', function(event) {
        row.classList.remove('drag-over'); // Remove class to indicate no longer potential drop target
    });

    // Attach drop event listener to row to handle the dropped element
    row.addEventListener('drop', function(event) {
        var draggedRow = document.querySelector('.dragging');
        var dropTargetRow = event.currentTarget;

        // Check if the dragged row is being dropped above or below the drop target row
        if (draggedRow.offsetTop < dropTargetRow.offsetTop) {
            dropTargetRow.parentNode.insertBefore(draggedRow, dropTargetRow.nextSibling);
        } else {
            dropTargetRow.parentNode.insertBefore(draggedRow, dropTargetRow);
        }

        // Remove dragging and drag-over classes after drop
        draggedRow.classList.remove('dragging');
        dropTargetRow.classList.remove('drag-over');
    });
}


// Function to open modal and populate fields
function openModal(variableID, variableName, variableType, description, variableLabelValue) {
    // Populate modal fields with data
    document.getElementById('variable-id').value = variableID;
    document.getElementById('variable-name').value = variableName;
    document.getElementById('variable-type-two').value = variableType;
    document.getElementById('description').value = description;

    // Parse and populate VariableLabelValue JSON
    var variableLabelValueObj = JSON.parse(variableLabelValue);
    var dynamicFieldsContainer = document.getElementById('dynamic-fields-container-two');
    dynamicFieldsContainer.innerHTML = ''; // Clear previous content

    // Create input fields, drag buttons, and delete buttons for each value in the JSON object
    for (var key in variableLabelValueObj) {
        if (variableLabelValueObj.hasOwnProperty(key)) {
            // Create input field
            // var inputField = document.createElement('input');
            // inputField.setAttribute('type', 'text');
            // inputField.setAttribute('value', variableLabelValueObj[key]);

            var inputField = document.createElement('input');
            inputField.setAttribute('type', 'text');
            inputField.setAttribute('value', variableLabelValueObj[key]);
            inputField.style.border = '1px solid #ccc'; // Add border
            inputField.style.borderRadius = '5px'; // Add border radius
            inputField.style.padding = '8px 12px'; // Add padding
            inputField.style.width = '200px'; // Set width
            inputField.style.boxSizing = 'border-box'; // Include padding and border in the width

            // Create drag button
            var dragButton = document.createElement('button');
            dragButton.textContent = '☰'; // Drag icon
            dragButton.setAttribute('type', 'button');
            dragButton.classList.add('btn', 'btn-primary', 'btn-sm'); 
            dragButton.classList.add('drag-button'); // Apply CSS class for styling
            dragButton.setAttribute('draggable', 'true'); // Make drag button draggable

            // Create delete button
            var deleteButton = document.createElement('button');
            deleteButton.innerHTML = '&#10005;'; // Cross sign
            deleteButton.setAttribute('type', 'button');
            deleteButton.classList.add('btn', 'btn-danger', 'btn-sm'); 
            
            deleteButton.addEventListener('click', function() {
                var wrapperDiv = this.parentNode;
                wrapperDiv.remove(); // Remove the input field row when delete button is clicked
            });

            // Create wrapper div for input field, drag button, and delete button
            var wrapperDiv = document.createElement('div');
            wrapperDiv.setAttribute('class', 'dynamic-field');
            wrapperDiv.appendChild(inputField);
            wrapperDiv.appendChild(dragButton);
            wrapperDiv.appendChild(deleteButton);

            dynamicFieldsContainer.appendChild(wrapperDiv);
            makeRowDraggable(wrapperDiv); // Make the row draggable
        }
    }

    // Create "Add" button at the bottom
    if (variableType === 'Single Box' || variableType === 'Multiple Box' ) {

        var addButton = document.createElement('button');
        addButton.textContent = 'Add';
        addButton.classList.add('form-control');
        addButton.style = "width: 58px;"; 
    
        addButton.setAttribute('type', 'button');
        addButton.addEventListener('click', function() {
            addInputField();
        });

        dynamicFieldsContainer.appendChild(addButton);

    }
 

    // Show the modal
    var modal = new bootstrap.Modal(document.getElementById('exampleModal'));
    modal.show();
}
 

// Function to add new input field row
    function addInputField() {
    // Create input field
    // var inputField = document.createElement('input');
    // inputField.setAttribute('type', 'text');
    // inputField.setAttribute('placeholder', 'Enter value');

    var inputField = document.createElement('input');
    inputField.setAttribute('type', 'text');
    inputField.setAttribute('placeholder', 'Enter value');
    inputField.style.border = '1px solid #ccc'; // Add border
    inputField.style.borderRadius = '5px'; // Add border radius
    inputField.style.padding = '8px 12px'; // Add padding
    inputField.style.width = '200px'; // Set width
    inputField.style.boxSizing = 'border-box'; // Include padding and border in the width

    // Create drag button
    var dragButton = document.createElement('button');
    dragButton.textContent = '☰'; // Drag icon
    dragButton.setAttribute('type', 'button');
    dragButton.classList.add('drag-button'); // Apply CSS class for styling
    dragButton.classList.add('btn', 'btn-primary', 'btn-sm'); 
    dragButton.setAttribute('draggable', 'true'); // Make drag button draggable

    // Create delete button
    var deleteButton = document.createElement('button');
    deleteButton.innerHTML = '&#10005;'; // Cross sign
    deleteButton.setAttribute('type', 'button');
    deleteButton.classList.add('btn', 'btn-danger', 'btn-sm'); 
    deleteButton.addEventListener('click', function() {
        var wrapperDiv = this.parentNode;
        wrapperDiv.remove(); // Remove the input field row when delete button is clicked
    });

    // Create wrapper div for input field, drag button, and delete button
    var wrapperDiv = document.createElement('div');
    wrapperDiv.setAttribute('class', 'dynamic-field');
    wrapperDiv.appendChild(inputField);
    wrapperDiv.appendChild(dragButton);
    wrapperDiv.appendChild(deleteButton);

    var dynamicFieldsContainer = document.getElementById('dynamic-fields-container-two');
    dynamicFieldsContainer.insertBefore(wrapperDiv, dynamicFieldsContainer.lastChild); // Insert before the last child (Add button)

    makeRowDraggable(wrapperDiv); // Make the row draggable
}
 
function editVariable() {
    var VariableID = $('#variable-id').val();
    var VariableName = $('#variable-name').val();
    var VariableType = $('#variable-type-two').val();
    var Description = $('#description').val();

    var inputFieldValues = []; // Initialize as empty array if needed

    // Check if VariableType is 'Multiple Box' or 'Single Box'
    if (VariableType === 'Multiple Box' || VariableType === 'Single Box') {
        // Collect values from dynamically added input fields
        $('#dynamic-fields-container-two .dynamic-field input[type="text"]').each(function() {
            var value = $(this).val(); // Get the value of the current input field
            inputFieldValues.push(value); // Push the value into the array
        });
    } else if ( VariableType === 'Single Line Text' || VariableType === 'Multiple Line Text'  || VariableType === 'Dates'  ){
        
            var value = ''; // Get the value of the current input field
            inputFieldValues.push(value); // Push the value into the array
   
                 
    }

    console.log('inputFieldValues ----> :', inputFieldValues);

    // AJAX request to update the variable
    $.ajax({
        url: '/update-variable/' + VariableID,
        method: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'), // Updated to use meta tag for CSRF token
            'VariableName': VariableName,
            'VariableType': VariableType,
            'Description': Description,
            'inputFieldValues': inputFieldValues
        },
        success: function(data) {
            // Handle success, for example, close the modal
            $('#exampleModal').modal('hide'); // Using jQuery to hide modal
            location.reload(); // Reload the page to reflect changes
        },
        error: function(error) {
            console.error('Error updating variable:', error);
        }
    });
}

</script>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

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


@endsection