@extends('layouts.master')
@section('title')
    @lang('translation.App-Connection')
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Projects
        @endslot
        @slot('title')
        @lang('translation.App-Connection')
        @endslot
    @endcomponent

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
 
 

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>



    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="//cdn.datatables.net/2.0.2/css/dataTables.dataTables.min.css">
    <script src="//cdn.datatables.net/2.0.2/js/dataTables.min.js"></script>

    <div class="card-body">
        <h4 class="card-title">CRM APP</h4>
        <p class="card-title-desc"></p>

        <div class="row">
            <div class="col-md-3">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">

                    <a class="nav-link mb-2 active" id="v-pills-home-tab" data-bs-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Close</a>

                    <a class="nav-link mb-2" id="v-pills-activecampaign-tab" data-bs-toggle="pill" href="#v-pills-activecampaign" role="tab" aria-controls="v-pills-activecampaign" aria-selected="false" tabindex="-1">ActiveCampaign</a>

                    <a class="nav-link mb-2" id="v-pills-profile-tab" data-bs-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false" tabindex="-1">Zapier</a>
                    <a class="nav-link mb-2" id="v-pills-messages-tab" data-bs-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false" tabindex="-1">Salesforce</a>
                    <a class="nav-link mb-2" id="v-pills-settings-tab" data-bs-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false" tabindex="-1">HubSpot</a>
                    <a class="nav-link" id="v-pills-sms-tab" data-bs-toggle="pill" href="#v-pills-sms" role="tab" aria-controls="v-pills-sms" aria-selected="false">SMS</a>
                </div>
            </div>

            <div class="col-md-9">
                <div class="tab-content text-muted mt-4 mt-md-0" id="v-pills-tabContent">

                    <!-- Close Tab Content -->
                    <div class="tab-pane fade active show" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                        <form id="close-api-form" method="POST" action="{{ route('save.api.key') }}">
                            @csrf
                            <input type="hidden" name="type" value="Close">
                            <div class="mb-3">
                                <label for="api_key" class="form-label">Close API Key</label>
                                <input type="text" class="form-control" id="api_key" name="api_key" value="{{ $appConnections['Close']['api_key'] }}" required>
                            </div>

                            <!-- Pending Note -->
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="enable_pending" {{ !empty($appConnections['Close']['pending']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="enable_pending">@lang('translation.Enable Pending Note')</label>
                                </div>
                                <input type="text" class="form-control pending-note" id="pending" name="pending" value="{{ $appConnections['Close']['pending'] }}" style="{{ !empty($appConnections['Close']['pending']) ? 'opacity: 1;' : 'display: none; opacity: 0.5;' }}" {{ !empty($appConnections['Close']['pending']) ? '' : 'disabled' }}>
                            </div>

                            <!-- Signed Note -->
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="enable_signed" {{ !empty($appConnections['Close']['signed']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="enable_signed">@lang('translation.Enable Signed Note')</label>
                                </div>
                                <input type="text" class="form-control signed-note" id="signed" name="signed" value="{{ $appConnections['Close']['signed'] }}" style="{{ !empty($appConnections['Close']['signed']) ? 'opacity: 1;' : 'display: none; opacity: 0.5;' }}" {{ !empty($appConnections['Close']['signed']) ? '' : 'disabled' }}>
                            </div>

                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>


                     <!-- Active Campaign Tab Content will have to create similar like Close  
                  
                    -->
                     <div class="tab-pane fade" id="v-pills-activecampaign" role="tabpanel" aria-labelledby="v-pills-activecampaign-tab">
                        <form id="activecampaign-api-form" method="POST" action="{{ route('save.api.key') }}">
                            @csrf
                            <input type="hidden" name="type" value="ActiveCampaign">
                            <div class="mb-3">
                                <label for="api_key" class="form-label">ActiveCampaign API Key</label>
                                <input type="text" class="form-control" id="api_key" name="api_key" value="{{ $appConnections['ActiveCampaign']['api_key'] }}" required>
                            </div>

                            <!-- Pending Note -->
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="enable_pending_activecampaign" {{ !empty($appConnections['ActiveCampaign']['pending']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="enable_pending_activecampaign">@lang('translation.Enable Pending Note')</label>
                                </div>
                                <input type="text" class="form-control pending-note" id="pending_activecampaign" name="pending" value="{{ $appConnections['ActiveCampaign']['pending'] }}" style="{{ !empty($appConnections['ActiveCampaign']['pending']) ? 'opacity: 1;' : 'display: none; opacity: 0.5;' }}" {{ !empty($appConnections['ActiveCampaign']['pending']) ? '' : 'disabled' }}>
                            </div>

                            <!-- Signed Note -->
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="enable_signed_activecampaign" {{ !empty($appConnections['ActiveCampaign']['signed']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="enable_signed_activecampaign">@lang('translation.Enable Signed Note')</label>
                                </div>
                                <input type="text" class="form-control signed-note" id="signed_activecampaign" name="signed" value="{{ $appConnections['ActiveCampaign']['signed'] }}" style="{{ !empty($appConnections['ActiveCampaign']['signed']) ? 'opacity: 1;' : 'display: none; opacity: 0.5;' }}" {{ !empty($appConnections['ActiveCampaign']['signed']) ? '' : 'disabled' }}>
                            </div>

                      
                            <!-- <button type="button" id="fetch-leads" class="btn btn-secondary">Fetch Leads</button>   -->
                            <div class="mb-3">
                                <label for="fetch-leads" class="form-label"> @lang('translation.Select Pending Tags') </label>
                                <select id="fetch-leads" name="tags[]" multiple>
                                    <!-- Options will be populated dynamically with JavaScript -->
                                </select>
                            </div>

                            <!-- signed tags -->

                            <!-- New Select Signed Tags section -->
                            <div class="mb-3" style = "height:150px">
                                <label for="fetch-signed-tags" class="form-label"> @lang('translation.Select Signed Tags') </label>
                                <select id="fetch-signed-tags" name="signedTags[]" multiple>
                                    <!-- Options will be populated dynamically with JavaScript -->
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Save</button>

                        </form>              

                    </div>

                    <!-- Zapier Tab Content -------------------------->
                    <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                        <form id="zapier-api-form" method="POST" action="{{ route('save.api.key') }}">
                            @csrf
                            <input type="hidden" name="type" value="Zapier">
                            <div class="mb-3">
                                <label for="api_key" class="form-label">Zapier API Key</label>
                                <input type="text" class="form-control" id="api_key" name="api_key" value="{{ $appConnections['Zapier']['api_key'] }}" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>

                    <!-- Salesforce Tab Content -->
                    <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                        <form id="salesforce-api-form" method="POST" action="{{ route('save.api.key') }}">
                            @csrf
                            <input type="hidden" name="type" value="Salesforce">
                            <div class="mb-3">
                                <label for="api_key" class="form-label">Salesforce API Key</label>
                                <input type="text" class="form-control" id="api_key" name="api_key" value="{{ $appConnections['Salesforce']['api_key'] }}" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>

                    <!-- Pipedrive Tab Content -->
                    <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                        <form id="pipedrive-api-form" method="POST" action="{{ route('save.api.key') }}">
                            @csrf
                            <input type="hidden" name="type" value="Pipedrive">
                            <div class="mb-3">
                                <label for="api_key" class="form-label">HubSpot API Key</label>  <!--  actually it hubspot Tab Content -->
                                <input type="text" class="form-control" id="api_key" name="api_key" value="{{ $appConnections['Pipedrive']['api_key'] }}" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>

                    <!-- SMS Tab Content -->
                    <div class="tab-pane fade" id="v-pills-sms" role="tabpanel" aria-labelledby="v-pills-sms-tab">
                        <!-- SMS Toggle -->
                        <div class="sms-box" style="background: rgba(255, 255, 255, 0.8); padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                            <form id="sms-api-form" method="POST" action="{{ route('save.sms.toggle') }}">
                                @csrf
                                <div class="mb-3">
                                    <!-- SMS Toggle Switch -->
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="enable_sms" name="enable_sms" {{ $appConnections['SMS']['sms_enabled'] ? 'checked' : '' }}>
                                        <label class="form-check-label" for="enable_sms">@lang('translation.SMS-Connection')</label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </form>
                        </div>

                        <!-- Sales SMS Toggle -->
                        <div class="sales-sms-box" style="background: rgba(255, 255, 255, 0.8); padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                            <form id="sales-sms-api-form" method="POST" action="{{ route('save.sales.sms.toggle') }}">
                                @csrf
                                <div class="mb-3">
                                    <!-- Sales SMS Toggle Switch -->
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="enable_sales_sms" name="enable_sales_sms" {{ $appConnections['Sales_SMS']['sales_sms_enabled'] ? 'checked' : '' }}>
                                        <label class="form-check-label" for="enable_sales_sms">@lang('translation.Sales-SMS-Connection')</label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
<style>
    .select2-container .select2-results__options {
        max-height: 200px;
    }

    .choices__list--dropdown .choices__list {
            max-height: 400px; /* Increase this value as needed */
            overflow-y: auto;  /* Enables scrolling if there are more tags */
        }

</style>

<!-- JavaScript to handle the toggles for Pending and Signed notes -->

<script>
 
    document.addEventListener("DOMContentLoaded", function() {

        const enablePendingActiveCampaignSwitch = document.getElementById('enable_pending_activecampaign');
        const pendingActiveCampaignInput = document.getElementById('pending_activecampaign');
        const enableSignedActiveCampaignSwitch = document.getElementById('enable_signed_activecampaign');
        const signedActiveCampaignInput = document.getElementById('signed_activecampaign');

        if (enablePendingActiveCampaignSwitch.checked) {
            pendingActiveCampaignInput.style.display = 'block';
            pendingActiveCampaignInput.disabled = false;
            pendingActiveCampaignInput.style.opacity = 1;
        }

        if (enableSignedActiveCampaignSwitch.checked) {
            signedActiveCampaignInput.style.display = 'block';
            signedActiveCampaignInput.disabled = false;
            signedActiveCampaignInput.style.opacity = 1;
        }

        enablePendingActiveCampaignSwitch.addEventListener('change', function() {
            if (this.checked) {
                pendingActiveCampaignInput.style.display = 'block';
                pendingActiveCampaignInput.disabled = false;
                pendingActiveCampaignInput.required = true;
                pendingActiveCampaignInput.style.opacity = 1;
            } else {
                pendingActiveCampaignInput.style.display = 'none';
                pendingActiveCampaignInput.disabled = true;
                pendingActiveCampaignInput.required = false;
                pendingActiveCampaignInput.style.opacity = 0.5;
            }
        });

        enableSignedActiveCampaignSwitch.addEventListener('change', function() {
            if (this.checked) {
                signedActiveCampaignInput.style.display = 'block';
                signedActiveCampaignInput.disabled = false;
                signedActiveCampaignInput.required = true;
                signedActiveCampaignInput.style.opacity = 1;
            } else {
                signedActiveCampaignInput.style.display = 'none';
                signedActiveCampaignInput.disabled = true;
                signedActiveCampaignInput.required = false;
                signedActiveCampaignInput.style.opacity = 0.5;
            }
        });
    });



    document.addEventListener("DOMContentLoaded", function() {
        const enablePendingSwitch = document.getElementById('enable_pending');
        const pendingInput = document.getElementById('pending');
        const enableSignedSwitch = document.getElementById('enable_signed');
        const signedInput = document.getElementById('signed');

        if (enablePendingSwitch.checked) {
            pendingInput.style.display = 'block';
            pendingInput.disabled = false;
            pendingInput.style.opacity = 1;
        }

        if (enableSignedSwitch.checked) {
            signedInput.style.display = 'block';
            signedInput.disabled = false;
            signedInput.style.opacity = 1;
        }

        enablePendingSwitch.addEventListener('change', function() {
            if (this.checked) {
                pendingInput.style.display = 'block';
                pendingInput.disabled = false;
                pendingInput.required = true;
                pendingInput.style.opacity = 1;
            } else {
                pendingInput.style.display = 'none';
                pendingInput.disabled = true;
                pendingInput.required = false;
                pendingInput.style.opacity = 0.5;
            }
        });

        enableSignedSwitch.addEventListener('change', function() {
            if (this.checked) {
                signedInput.style.display = 'block';
                signedInput.disabled = false;
                signedInput.required = true;
                signedInput.style.opacity = 1;
            } else {
                signedInput.style.display = 'none';
                signedInput.disabled = true;
                signedInput.required = false;
                signedInput.style.opacity = 0.5;
            }
        });
    });
</script>

<!-- SweetAlert handling for form submissions -->
<script>
    $(document).ready(function() {

    //--------------------------------------------------------------------

        // Handle Close API form
        $('#close-api-form').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Close API Key saved successfully!',
                    });
                },
                error: function(response) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was an error saving the Close API key.',
                    });
                }
            });
        });



        // Active Camaign 
        $('#activecampaign-api-form').on('submit', function(e) {
            e.preventDefault();

            // Collect selected pending and signed tags
            const selectedPendingTags = $('#fetch-leads').val();
            const selectedSignedTags = $('#fetch-signed-tags').val();

            // Create a FormData object to handle form data
            const formData = new FormData(this);
            formData.append('tags', JSON.stringify(selectedPendingTags)); // Convert selected pending tags array to JSON string
            formData.append('signedTags', JSON.stringify(selectedSignedTags)); // Convert selected signed tags array to JSON string

            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'ActiveCampaign API Key and Tags saved successfully!',
                    });
                },
                error: function(response) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was an error saving the ActiveCampaign data.',
                    });
                }
            });
        });


       
        // $('#activecampaign-api-form').on('submit', function(e) {
        //         e.preventDefault();

        //         // Collect selected tags
        //         const selectedTags = $('#fetch-leads').val(); // This will be an array of selected tags

        //         // Create a FormData object to handle form data
        //         const formData = new FormData(this);
        //         formData.append('tags', JSON.stringify(selectedTags)); // Convert selected tags array to JSON string

        //         $.ajax({
        //             url: $(this).attr('action'),
        //             method: $(this).attr('method'),

        //             data: formData,
                    
        //             processData: false, // Prevent jQuery from processing the data
        //             contentType: false, // Prevent jQuery from setting contentType
        //             success: function(response) {
        //                 Swal.fire({
        //                     icon: 'success',
        //                     title: 'Success',
        //                     text: 'ActiveCampaign API Key saved successfully!',
        //                 });
        //             },
        //             error: function(response) {
        //                 Swal.fire({
        //                     icon: 'error',
        //                     title: 'Error',
        //                     text: 'There was an error saving the ActiveCampaign API key.',
        //                 });
        //             }
        //         });
        //     });





        // $('#activecampaign-api-form').on('submit', function(e) {
        //     e.preventDefault();
        //     const selectedTags = $('#fetch-leads').val(); // Get selected tags from Choices.js

        //     const formData = $(this).serializeArray();
        //     formData.push({ name: 'tags', value: selectedTags }); // Add tags to form data

        //     $.ajax({
        //         url: $(this).attr('action'),
        //         method: $(this).attr('method'),
        //         data: formData,
        //         success: function(response) {
        //             Swal.fire({
        //                 icon: 'success',
        //                 title: 'Success',
        //                 text: 'ActiveCampaign API Key saved successfully!',
        //             });
        //         },
        //         error: function(response) {
        //             Swal.fire({
        //                 icon: 'error',
        //                 title: 'Error',
        //                 text: 'There was an error saving the ActiveCampaign API key.',
        //             });
        //         }
        //     });
        // });


 


    //-------------------------for pending tags----------------------------------------------
    
    $(document).ready(function() {
        const choices = new Choices('#fetch-leads', {
            removeItemButton: true,
            searchEnabled: true,
            placeholderValue: "Search and select tags",
        });

        // Load previously selected tags from server-rendered variable
    
        const selectedTags = @json($appConnections['ActiveCampaign']['tags'] ?? []);

        console.log("adesso  selectedTags : ", selectedTags);

        // Ensure selectedTags is an array and not null
        if (Array.isArray(selectedTags) && selectedTags.length) {
            selectedTags.forEach(tag => {
                choices.setChoices([{ value: tag, label: tag, selected: true }], 'value', 'label', false);
            });
        }


        // Load tags via AJAX when user focuses on the select box
    
        loadLeadsBatch();
    
        function loadLeadsBatch() {
            $.ajax({
                url: '{{ route("get.activecampaign.leads.batch") }}',
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        response.data.forEach(tag => {
                            if (tag.tag && !selectedTags.includes(tag.tag)) { 
                                choices.setChoices([{ value: tag.tag, label: tag.tag, selected: false }], 'value', 'label', false);
                            }
                        });
                    } else {
                        console.error("Error fetching leads:", response.error);
                    }
                },
                error: function(response) {
                    console.error("Error fetching leads:", response);
                }
            });
        }
    });

        
//-------------------------for signed tags ------------------------------------------

 
$(document).ready(function() {
    // Initialize Choices.js for Signed Tags with search enabled
    const choicesSigned = new Choices('#fetch-signed-tags', {
        removeItemButton: true,
        searchEnabled: true,
        placeholderValue: "Search and select signed tags",
    });

    // Load preselected signed tags from server-rendered variable
    const selectedSignedTags = @json($appConnections['ActiveCampaign']['signedTags'] ?? []);

    console.log("adesso  selected signed Tags : ", selectedSignedTags);


    // Ensure selectedSignedTags is an array and not null, then set each as selected
    if (Array.isArray(selectedSignedTags) && selectedSignedTags.length) {
        selectedSignedTags.forEach(tag => {
            choicesSigned.setChoices([{ value: tag, label: tag, selected: true }], 'value', 'label', false);
        });
    }

    loadSignedTagsBatch();
    // Function to load available signed tags via AJAX
    function loadSignedTagsBatch() {
        // Clear existing choices to prevent duplicates
        choicesSigned.clearChoices();

        $.ajax({
            url: '{{ route("get.activecampaign.leads.batch") }}',
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    response.data.forEach(tag => {
                        // Only add tag if it isn't already selected
                        if (tag.tag && !selectedSignedTags.includes(tag.tag)) {
                            choicesSigned.setChoices([{ value: tag.tag, label: tag.tag, selected: false }], 'value', 'label', false);
                        }
                    });
                } else {
                    console.error("Error fetching signed tags:", response.error);
                }
            },
            error: function(response) {
                console.error("Error fetching signed tags:", response);
            }
        });
    }

    // Load signed tags when the user focuses on the select box
    $('#fetch-signed-tags').on('focus', function() {
        loadSignedTagsBatch();
    });
});


    // $(document).ready(function() {
    //         // Initialize Choices.js on #fetch-leads
    //         const choices = new Choices('#fetch-leads', {
    //             removeItemButton: true,
    //             searchEnabled: true,
    //             placeholderValue: "Search and select tags",
    //         });

    //         // Function to load tags from AJAX
    //         loadLeadsBatch();

    //         function loadLeadsBatch() {
    //             $.ajax({
    //                 url: '{{ route("get.activecampaign.leads.batch") }}',
    //                 method: 'GET',
    //                 success: function(response) {
    //                     if (response.success) {
                          
    //                       //  console.log("ActiveCampaign Leads Batch:", response.data);

    //                         // Add tags as options dynamically
    //                         response.data.forEach(function(tag) {
    //                             if (tag.tag) {
    //                                 choices.setChoices([{ value: tag.tag, label: tag.tag, selected: false }], 'value', 'label', false);
    //                             }
    //                         });

    //                         // Continue loading the next batch if more leads are available
    //                         if (response.data.length > 0) {
    //                             loadLeadsBatch();
    //                         } else {
    //                             console.log("All leads loaded.");
    //                         }
    //                     } else {
    //                         console.error("Error fetching leads:", response.error);
    //                     }
    //                 },
    //                 error: function(response) {
    //                     console.error("Error fetching leads:", response);
    //                 }
    //             });
    //         }
    //     });

  

      
        // Handle Zapier API form
        $('#zapier-api-form').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Zapier API Key saved successfully!',
                    });
                },
                error: function(response) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was an error/invalid saving the Zapier API key.',
                    });
                }
            });
        });

        // Handle Salesforce API form 
        $('#salesforce-api-form').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Salesforce API Key saved successfully!',
                    });
                },
                error: function(response) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was an error saving the Salesforce API key.',
                    });
                }
            });
        });

        // Handle Pipedrive API form
        $('#pipedrive-api-form').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Pipedrive API Key saved successfully!',
                    });
                },
                error: function(response) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was an error saving the Pipedrive API key.',
                    });
                }
            });
        });

        // Handle SMS Toggle form
        $('#sms-api-form').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'SMS settings saved successfully!',
                    });
                },
                error: function(response) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to save SMS settings.',
                    });
                }
            });
        });

        // Handle Sales SMS Toggle form
        $('#sales-sms-api-form').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Sales SMS settings saved successfully!',
                    });
                },
                error: function(response) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to save Sales SMS settings.',
                    });
                }
            });
        });
    });
</script>

<style>
    .pending-note, .signed-note {
        transition: opacity 0.3s ease-in-out;
    }
</style>
@endsection
