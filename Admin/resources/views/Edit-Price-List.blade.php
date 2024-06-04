@extends('layouts.master')
@section('title')
    @lang('translation.arifurtable')
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Projects
        @endslot
        @slot('title')
            Price List 
        @endslot
    @endcomponent
    <!--code starts here --> 
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> 
    <link rel="stylesheet" href="//cdn.datatables.net/2.0.2/css/dataTables.dataTables.min.css">
    <script src="//cdn.datatables.net/2.0.2/js/dataTables.min.js"></script>

    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


 <!--  Arifur change  -->
 <!-- <div class="row">
            <div class="col-sm">
                <div class="search-box me-2 d-inline-block">
              <div class="position-relative">
                        <input type="text" class="form-control" autocomplete="off" id="searchInput" placeholder="Search...">
                        <i class="bx bx-search-alt search-icon"></i>
                    </div>  
                </div>
            </div>
    </div> -->
<!-- two div one will be 75% of screen and another on will be 25%  of screen-->


 
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
 
 
    <div class="container-fluid">
        <div class="row">
            <div class="col-7 bg-light"> <!-- 75% width -->
      


  <!--Price Name and  Currency  -->

  <div class="row mt-3">
    <div class="col-sm">
        <div class="input-group">
            <span class="input-group-text">Price Name:</span>
            <input type="text" id="priceName" name="priceName" class="form-control" aria-label="Price Name" value="{{$priceList->pricename }}" required>
        </div>
    </div>
</div>

  <!-- <div class="row mt-3">
    <div class="col-sm d-flex align-items-center">
        <label for="priceName" class="col-form-label me-3">Price Name :</label>
        <input type="text" id="priceName" name="priceName" class="form-control w-25" value="{{$priceList->pricename }}">
    </div>
</div> -->

<!-- Added margin-top for spacing -->

<!-- <div class="row mt-3">
    <div class="col-sm">
        <div class="input-group">
            <label class="input-group-text" for="currency">Select currency:</label>
            <select class="form-select" id="currency" name="currency">
                <option value="EUR" {{ old('currency', 'EUR') === 'EUR' ? 'selected' : '' }}>Euro (EUR)</option>
                <option value="USD" {{ old('currency') === 'USD' ? 'selected' : '' }}>US Dollar (USD)</option>
                <option value="GBP" {{ old('currency') === 'GBP' ? 'selected' : '' }}>British Pound (GBP)</option>
                <option value="JPY" {{ old('currency') === 'JPY' ? 'selected' : '' }}>Japanese Yen (JPY)</option>
              
            </select>
        </div>
    </div>
</div> -->

<div class="row mt-3">
    <div class="col-sm">
        <div class="input-group">
            <label class="input-group-text" for="currency">Select currency:</label>
            <select class="form-select" id="currency" name="currency">
            <option value="EUR" {{ $priceList->currency === 'EUR' ? 'selected' : '' }}>Euro (EUR)</option>
            <option value="USD" {{ $priceList->currency === 'USD' ? 'selected' : '' }}>US Dollar (USD)</option>
            <option value="GBP" {{ $priceList->currency === 'GBP' ? 'selected' : '' }}>British Pound (GBP)</option>
            <option value="JPY" {{ $priceList->currency === 'JPY' ? 'selected' : '' }}>Japanese Yen (JPY)</option>
            <!-- Add more currency options as needed -->
            </select>
        </div>
    </div>
</div>


<style>
    .hidden {
        display: none;
    }
    input[type="range"] {
        width: 80%; /* Adjust the width as needed */
    }
</style>

<div class="row mt-3">
    <div class="col-sm">
        <div class="input-group">
            <label class="input-group-text" for="selection">Total Price:</label>
            <select class="form-select" id="selection" name="selection">
                
                    <option value="dynamic" selected>Dynamic</option>
                    <option value="fixed">Fixed</option>
     
            </select>
        </div>
    </div>
</div>


<!--     
<div class="row mt-3">
    <div class="col-sm d-flex align-items-center">
        <label for="selection">Select an option : </label>
        <select id="selection" name="selection">
            <option value="fixed">Fixed</option>
            <option value="dynamic">Dynamic</option>
        </select>
    </div>
</div> -->

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.7.0/nouislider.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.7.0/nouislider.min.js"></script>
 
<!-- new design paralally  -->
<div class="row mt-3">
    <div class="col-sm">

        <div class="row">
            <div class="col-3">
                <div id="fixedInput">
                    <div class="mb-3">
                        <label for="fixedValue" class="form-label">Enter Fixed value:</label>
                        <input type="number" class="form-control w-100" id="fixedValue" name="fixedValue" value="{{ $priceList->fixedvalue }}">
                    </div>
                </div>

            </div>
           
            <div class="col-2">
                <div class="form-check form-switch " dir="ltr" style="margin-left:-48px;">
                    <label class="form-check-label" for="SwitchCheckSizelg">Enable VAT</label>
                    <br>
                    <input style="margin-left:10px;margin-top:15px;" class="form-check-input" type="checkbox" id="vatCheckbox" name="vatCheckbox" onchange="toggleVATFields()" {{ $priceList->enableVat === 'true' ? 'checked' : '' }}>
                </div>
             </div>

             <div class="col-2" style ="margin-left:-28px;">
                <div id="vatFields" class="{{ $priceList->enableVat === 'true' ? '' : 'VAThidden' }}">
                      <div class="mb-3">
                        <label for="vatPercentage" class="form-label">Percentage:</label>
                        <div class="input-group" style="width: 105%;">
                            <input type="number" id="vatPercentage" name="vatPercentage" class="form-control" min="0" max="100" value="{{ $priceList->vatPercentage }}" {{ $priceList->enableVat === 'true' ? '' : 'disabled' }}>
                            <span class="input-group-text">%</span>
                        </div>
                      </div>
                </div> 
              </div>
                

                   <div class="col-2" >
                        <div class="form-check form-switch">
                        <label style ="margin-left:-36px;" class="form-check-label" for="priceTypeToggle" id="priceTypeLabel">{{ $priceList->price === 'true' ? 'Include on Price' : 'Include on Price' }}</label>
                           <br>
                            <input style ="margin-left:-26px;" class="form-check-input" type="checkbox" id="priceTypeToggle" name="priceTypeToggle" value="{{$priceList->price}}" {{ $priceList->enableVat === 'true' ? '' : 'disabled' }} {{ $priceList->price === 'true' ? 'checked' : '' }} onchange="togglePriceType(this)">
                        </div>
                    </div>
 



                    <script>

                    function togglePriceType(checkbox) {
                        var label = document.getElementById('priceTypeLabel');

                        if (checkbox.checked) {
                           
                            label.textContent = 'Include on Price';
                            label.setAttribute('for', 'includeOnPrice');
                        } else {
                            
                            label.textContent = 'Include on Price';
                            label.setAttribute('for', 'external');
                        }
                    }

                    function toggleVATFields() {
                        var vatCheckbox = document.getElementById('vatCheckbox');
                        var vatFields = document.getElementById('vatFields');
                        var vatPercentage = document.getElementById('vatPercentage');
                         var priceTypeToggle = document.getElementById('priceTypeToggle');
                      //  var external = document.getElementById('external');

                        if (vatCheckbox.checked) {
                            vatFields.classList.remove('VAThidden');
                            vatPercentage.disabled = false;
                            priceTypeToggle.disabled = false;
                          //  external.disabled = false;
                        } else {
                            vatFields.classList.add('VAThidden');
                            vatPercentage.disabled = true;
                            priceTypeToggle.disabled = true;
                          //  external.disabled = true;
                        }
                    }

                    </script>
                  
                
                  
            <div class="col-3">
                <div class="mb-3" style="margin-left: -20px;">
                    <label class="input-group" for="priceType">PriceType:</label>
                    <div class="input-group">
                        <select class="form-select" id="priceType" name="priceType" onchange="togglePriceOptions()" style="font-size: 16px;">
                            <option value="recurring" {{ $priceList->selectPriceType === 'recurring' ? 'selected' : '' }}>Recurring</option>
                            <option value="oneTime" {{ $priceList->selectPriceType === 'oneTime' ? 'selected' : '' }}>OneTime</option>
                        </select>
                    </div>
                </div>  
            </div>


        </div>
        
        <div id="dynamicInput" class="{{ $priceList->selection === 'dynamic' ? '' : 'hidden' }}">
                    <!-- <div class="mb-3">
                        <label for="fixedValue2" class="form-label">Enter a fixed value:</label>
                        <input type="number" class="form-control w-25" id="fixedValue2" name="fixedValue2" value="{{ $priceList->fixedvalue }}">
                    </div> -->
                    <label for="minRange">Min Range: <span id="minValue">{{ $priceList->dynamicminRange }}</span> </label>
                    <div id="minRangeSlider"></div>
                    <!-- <span id="minValue">{{ $priceList->dynamicminRange }}</span> --> <br>

                    <label for="maxRange">Max Range: <span id="maxValue">{{ $priceList->dynamicmaxRange }}</span> </label>
                    <div id="maxRangeSlider"></div>
                    <!-- <span id="maxValue">{{ $priceList->dynamicmaxRange }}</span> --> <br>
                </div>
            </div>
        </div>

<script>
  var fixedValue = document.getElementById('fixedValue').value;
// Initialize min range slider
var minRangeSlider = document.getElementById('minRangeSlider');
    noUiSlider.create(minRangeSlider, {
        start: ['{{ $priceList->dynamicminRange }}'],
        connect: 'lower',
        range: {
            'min': 1,
            'max':  1000000
        }
    });

    // Update min value span when slider value changes
    minRangeSlider.noUiSlider.on('update', function (values, handle) {
        var intValue = parseInt(values[handle]); // Convert to integer
        document.getElementById('minValue').innerHTML = intValue;
    });

    // Initialize max range slider
    var maxRangeSlider = document.getElementById('maxRangeSlider');
    noUiSlider.create(maxRangeSlider, {
        start: ['{{ $priceList->dynamicmaxRange }}'],
        connect: 'lower',
        range: {
            'min': 2,
            'max':  1000000
        }
    });

    // Update max value span when slider value changes
    maxRangeSlider.noUiSlider.on('update', function (values, handle) {
        var intValue = parseInt(values[handle]); // Convert to integer
        document.getElementById('maxValue').innerHTML = intValue;
    });




    // Update min value span when slider value changes
    var lastValidMinValue = parseInt(minRangeSlider.noUiSlider.get());
var lastValidMaxValue = parseInt(maxRangeSlider.noUiSlider.get());

minRangeSlider.noUiSlider.on('update', function (values, handle) {
    var intValue = parseInt(values[handle]); // Convert to integer
    document.getElementById('minValue').innerHTML = intValue;

    var maxRangeValue = parseInt(document.getElementById('maxValue').innerHTML);

    if (intValue >= maxRangeValue) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Min range value should be at least 1 lower than max range value. First Increase the Max Range',
        }).then(function() {
            // Revert to last valid value
            minRangeSlider.noUiSlider.set(lastValidMinValue);
            document.getElementById('minValue').innerHTML = lastValidMinValue;
        });
    } else {
        // Update last valid value
        lastValidMinValue = intValue;
    }
});

maxRangeSlider.noUiSlider.on('update', function (values, handle) {
    var intValue = parseInt(values[handle]); // Convert to integer
    document.getElementById('maxValue').innerHTML = intValue;

    var minRangeValue = parseInt(document.getElementById('minValue').innerHTML);

    if (intValue <= minRangeValue) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Max range value should be at least 1 greater than min range value. First Decrease the Min Range',
        }).then(function() {
            // Revert to last valid value
            maxRangeSlider.noUiSlider.set(lastValidMaxValue);
            document.getElementById('maxValue').innerHTML = lastValidMaxValue;
        });
    } else {
        // Update last valid value
        lastValidMaxValue = intValue;
    }
});


</script>


<script>
 document.addEventListener('DOMContentLoaded', function() {
    const selection = document.getElementById('selection');
    const fixedInput = document.getElementById('fixedInput');
    const dynamicInput = document.getElementById('dynamicInput');

    // Check if dynamicmaxRange is greater than 0
    const dynamicMaxRange = "{{ $priceList->dynamicmaxRange }}";
    
    // Set the initial state based on the presence of dynamicMaxRange
    if (dynamicMaxRange > 0) {
        selection.value = 'dynamic';
        dynamicInput.classList.remove('hidden');
        fixedInput.classList.add('hidden');
    } else {
        selection.value = 'fixed';
        dynamicInput.classList.add('hidden');
        fixedInput.classList.remove('hidden');
    }
 
    selection.addEventListener('change', function() {
        if (selection.value === 'dynamic') {
            dynamicInput.classList.remove('hidden');
            fixedInput.classList.add('hidden');
        } else if (selection.value === 'fixed') {
            dynamicInput.classList.add('hidden');
            fixedInput.classList.remove('hidden');
        }
    });
});

 

function updateMinValue(value) {
    document.getElementById('minValue').innerText = value;
}

function updateMaxValue(value) {
    document.getElementById('maxValue').innerText = value;
}
</script>

 
<script>


function handleExclusiveSelection(checkbox) {
    if (checkbox.checked) {
        if (checkbox.id === 'includeOnPrice') {
            document.getElementById('external').checked = false;
        } else if (checkbox.id === 'external') {
            document.getElementById('includeOnPrice').checked = false;
        }
    }
}
</script>

<!-- multiple payments -->

<style>
    .multiplepaymenthidden {
        display: none;
    }
</style>

 

<div id="oneTimeOptions" class="{{ $priceList->selectPriceType === 'oneTime' ? '' : 'hidden' }}">
    <label>Payment Options:</label><br>
    

    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" id="multiplePayments" name="multiplePayments" {{ $priceList->multiplePayments ? 'checked' : '' }} onchange="togglePaymentOption(this)">
        <label class="form-check-label" for="multiplePayments" id="multiplePaymentsLabel">
            {{ $priceList->multiplePayments ? 'Multiple Payment' : 'Single Payment' }}
        </label>
    </div>

    <script>
        // Call togglePriceTypePayment when the page loads
        window.onload = function() {
            togglePriceTypePayment(document.getElementById('multiplePayments'));
        };

        function togglePaymentOption(checkbox) {
            var multiplePaymentsCheckbox = document.getElementById('multiplePayments');
            var multiplePaymentOptions = document.getElementById('multiplePaymentOptions');

            if (checkbox.checked) {
                if (checkbox.id === 'multiplePayments') {
                    multiplePaymentOptions.classList.remove('multiplepaymenthidden');
                    togglePriceTypePayment(checkbox); // Update label text
                }
            } else {
                multiplePaymentOptions.classList.add('multiplepaymenthidden');
                togglePriceTypePayment(checkbox); // Update label text
            }
        }

        function togglePriceTypePayment(checkbox) {
            var label = document.getElementById('multiplePaymentsLabel');
            if (checkbox.checked) {
                label.textContent = 'Multiple Payment';
            } else {
                label.textContent = 'Multiple Payment';
            }
        }
    </script>

    
    <br>
      <!-- editable dates -->
    
      <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" id="EditableDates" name="EditableDates" {{ $priceList->EditableDates === 'true' ? 'checked' : '' }}>
        <label class="form-check-label" for="EditableDates" id="editableDatesLabel"> Editable Dates </label>
    </div>



    <br>
    
    <div id="multiplePaymentOptions" class="{{ $priceList->multiplePayments ? '' : 'multiplepaymenthidden' }}">
 
    <div class="col-5">
        <div class="mb-3">
            <div class="input-group">
                <label class="input-group-text" for="frequency">Frequency:</label>
                <select class="form-select" id="frequency" name="frequency">
                    <option value="daily" {{ $priceList->frequency == 'daily' ? 'selected' : '' }}>Daily</option>
                    <option value="biweekly" {{ $priceList->frequency == 'biweekly' ? 'selected' : '' }}>Biweekly</option>
                    <option value="weekly" {{ $priceList->frequency == 'weekly' ? 'selected' : '' }}>Weekly</option>
                    <option value="monthly" {{ $priceList->frequency == 'monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="annually" {{ $priceList->frequency == 'annually' ? 'selected' : '' }}>Annually</option>
                </select>
            </div>
        </div>
    </div>
        
        <label for="minPaymentRange">Min Range :  <span id="minPaymentValue">{{ $priceList->paymentMinRange }}</span> </label>
        <div id="minPaymentRangeSlider"></div>
       <br>
        
        <label for="maxPaymentRange">Max Range :  <span id="maxPaymentValue">{{ $priceList->paymentMaxRange }}</span> </label>
        <div id="maxPaymentRangeSlider"></div>
       <br>

  

        <!-- Additional input fields for min and max range -->
        <div class="row mt-3">
            <div class="col-sm">
                <div class="input-group">
                    <label class="input-group-text" for="selection">Example Text:</label>
                    <input type="text" class="form-control" id="minPayment" name="minPayment" value="{{ $priceList->paymentExampleText }}">
                </div>
            </div>
        </div>


    </div>
</div>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.7.0/nouislider.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.7.0/nouislider.min.js"></script>
    <script>

    // Initialize min payment range slider
    var minPaymentRangeSlider = document.getElementById('minPaymentRangeSlider');
    noUiSlider.create(minPaymentRangeSlider, {
        start: [ ' {{ $priceList->paymentMinRange }} ' ],
        connect: 'lower',
        range: {
            'min': 1,
            'max': 48
        }
    });

    // Update min payment value span when slider value changes
    minPaymentRangeSlider.noUiSlider.on('update', function (values, handle) {

        var intValue = parseInt(values[handle]); // Convert to integer
        document.getElementById('minPaymentValue').innerHTML = intValue;

        
    });

    // Initialize max payment range slider
    var maxPaymentRangeSlider = document.getElementById('maxPaymentRangeSlider');
    noUiSlider.create(maxPaymentRangeSlider, {
        start: [ '{{ $priceList->paymentMaxRange }}'],
        connect: 'lower',
        range: {
            'min': 2,
            'max': 48
        }
    });

    // Update max payment value span when slider value changes
    maxPaymentRangeSlider.noUiSlider.on('update', function (values, handle) {

        var intValue = parseInt(values[handle]); // Convert to integer
        document.getElementById('maxPaymentValue').innerHTML = intValue;
 
    });


    // 
    var lastValidMinPaymentValue = parseInt(minPaymentRangeSlider.noUiSlider.get());
    var lastValidMaxPaymentValue = parseInt(maxPaymentRangeSlider.noUiSlider.get());

    minPaymentRangeSlider.noUiSlider.on('update', function (values, handle) {
        var intValue = parseInt(values[handle]); // Convert to integer
        document.getElementById('minPaymentValue').innerHTML = intValue;

        var maxPaymentValue = parseInt(document.getElementById('maxPaymentValue').innerHTML);

        if (intValue >= maxPaymentValue) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Min payment range value should be at least 1 lower than max payment range value. First increase the Max Range',
            }).then(function() {
                // Revert to last valid value
                minPaymentRangeSlider.noUiSlider.set(lastValidMinPaymentValue);
                document.getElementById('minPaymentValue').innerHTML = lastValidMinPaymentValue;
            });
        } else {
            // Update last valid value
            lastValidMinPaymentValue = intValue;
        }
    });

    maxPaymentRangeSlider.noUiSlider.on('update', function (values, handle) {
        var intValue = parseInt(values[handle]); // Convert to integer
        document.getElementById('maxPaymentValue').innerHTML = intValue;

        var minPaymentValue = parseInt(document.getElementById('minPaymentValue').innerHTML);

        if (intValue <= minPaymentValue) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Max payment range value should be at least 1 greater than min payment range value. First decrease the Min Range',
            }).then(function() {
                // Revert to last valid value
                maxPaymentRangeSlider.noUiSlider.set(lastValidMaxPaymentValue);
                document.getElementById('maxPaymentValue').innerHTML = lastValidMaxPaymentValue;
            });
        } else {
            // Update last valid value
            lastValidMaxPaymentValue = intValue;
        }
    });

    </script>

<script>
        // // Initial setup to reflect the data from the database
        document.addEventListener('DOMContentLoaded', function() {
          //  var singlePaymentCheckbox = document.getElementById('singlePayment');
            var multiplePaymentsCheckbox = document.getElementById('multiplePayments');
            
            // Reflect data for Single Payment checkbox
            if ("{{ $priceList->multiplePayments }}" === "false") {
               // singlePaymentCheckbox.checked = true;

                var multiplePaymentOptions = document.getElementById('multiplePaymentOptions');
                multiplePaymentOptions.classList.add('multiplepaymenthidden');
            
            }  
            // Reflecttion of data for Multiple Payments checkbox
            if ("{{ $priceList->multiplePayments }}" === "true") {
                multiplePaymentsCheckbox.checked = true;
            } else {
                multiplePaymentsCheckbox.checked = false;
                
            }
        });

       function togglePriceOptions() {
        var priceType = document.getElementById('priceType').value;
        var oneTimeOptions = document.getElementById('oneTimeOptions');

        if (priceType === 'oneTime') {
            oneTimeOptions.classList.remove('hidden');
        } else {
            oneTimeOptions.classList.add('hidden');
        }
    }
    
 



    // Function to ensure only one payment option is checked at a time
    function ensureSinglePaymentOption(checkbox) {
        var singlePaymentCheckbox = document.getElementById('singlePayment');
        var multiplePaymentsCheckbox = document.getElementById('multiplePayments');

        if (checkbox.checked && checkbox.id === 'singlePayment') {
            singlePaymentCheckbox.checked = true;
            multiplePaymentsCheckbox.checked = false;
        } else if (checkbox.checked && checkbox.id === 'multiplePayments') {
            singlePaymentCheckbox.checked = false;
            multiplePaymentsCheckbox.checked = true;
        }
    }

    // Function to update the min range value
    function updateMinValuepayment(value) {
        document.getElementById('minPaymentValue').innerText = value;
    }

    // Function to update the max range value
    function updateMaxValuepayment(value) {
        document.getElementById('maxPaymentValue').innerText = value;
    }
    </script>

<!--    ------------------------------------------------   -->

<div class="row">

</div>

<!-- Main update button to insert data in database -->
<button type="button" style="margin-top:4px;" id="saveButton" class="btn btn-success me-2 btn-lg"  >Update</button>

            </div>

             
                   <!-- 25% width --> 
            <div class="col-5 bg-light"> 
            

                <div class="row">
                    <p class="col-6">Price Name:</p>
                    <div class='col-6' id='selectedpricename1'></div>
                </div>

                <div class="row">
                    <p class="col-6">Selected currency:</p>
                    <div class='col-6' id='selectedcurrency1'></div>
                </div>

                <div class="row">
                    <p class="col-6">Calculated tax Value:</p>
                    <div class='col-6' id='selectedfoxedvalue1'></div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <p>Imposta l'Agenda dei pagamenti:</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 table-responsive" id="ImpostaTable1"></div>
                </div>


            
            </div>  
<!-- Full width on mobile, 25% width on medium and larger screens -->
            <!-- <div class="col-12 col-md-4 bg-light"> 
                <p>Example text for live view</p>

                <div class="row">
                    <p class="col-6 col-md-12">Price Name:</p>
                    <div class="col-6 col-md-12" id="selectedpricename1"></div>
                </div>

                <div class="row">
                    <p class="col-6 col-md-12">Selected currency:</p>
                    <div class="col-6 col-md-12" id="selectedcurrency1"></div>
                </div>

                <div class="row">
                    <p class="col-6 col-md-12">Calculated tax Value:</p>
                    <div class="col-6 col-md-12" id="selectedfoxedvalue1"></div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <p>Imposta l'Agenda dei pagamenti:</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12" id="ImpostaTable1"></div>
                </div>
            </div> -->

        <script>


             document.addEventListener("DOMContentLoaded", function() {

                        var includeOnPrice = document.getElementById('priceTypeToggle').checked;
                        var enableVat = document.getElementById('vatCheckbox').checked;
                        var vatPercentage = parseFloat(document.getElementById('vatPercentage').value);
                        var calculatedValue =""; // Initialize calculated value with fixed value
                        var fixedValueInput = parseFloat(document.getElementById("fixedValue").value);
                        var selectedFixedValueDiv = document.getElementById("ImpostaTable1");
                        var currency = document.getElementById("currency");
 
                        var minRangeSlider = document.getElementById('minRangeSlider');
                        var tbody; 


        
                  //  calculatedValue =  fixedValueInput + (vatPercentage * fixedValueInput) / 100  +  getCurrencySymbol(currency.value); // Calculate VAT if applicable
                    calculatedVal = parseFloat(fixedValueInput) + (vatPercentage * parseFloat(fixedValueInput)) / 100 +  " "  + " IVA Compresa (" +  getCurrencySymbol(currency.value)  + (vatPercentage * parseFloat(fixedValueInput)) / 100 +") "  ;
                    calculatedValue =  getCurrencySymbol(currency.value) + calculatedVal ;

                        // Declare tbody as a global variable
                        function updateCalculatedValue() {
                            var maxRange = 0;
                            var selectionValue = document.getElementById('selection').value;
                            
                            var includeOnPrice = document.getElementById('priceTypeToggle').checked;
                            
                            var vatPercentage = parseFloat(document.getElementById('vatPercentage').value);
                            var enableVat = document.getElementById('vatCheckbox').checked;
                            var fixedValueInput = parseFloat(document.getElementById("fixedValue").value);
                            var EditableDates = document.getElementById('EditableDates').checked;;
                          

                            var minRangeSlider = parseInt(document.getElementById('minRangeSlider').noUiSlider.get());



                            var newcalculation = parseFloat(fixedValueInput) + (vatPercentage * parseFloat(fixedValueInput)) / 100 ;

                            var multiplePayments = document.getElementById('multiplePayments').checked;

                            if (multiplePayments === false){
                                maxRange =1 ;
                               
                            }else {
                                maxRange = parseInt(document.getElementById('maxPaymentRangeSlider').noUiSlider.get());
                            }
                         
                     
                            
                                    
                            var frequency = document.getElementById("frequency").value;
                            var currency = document.getElementById("currency").value;
                        
                            // Clear previous table content
                            selectedFixedValueDiv.innerHTML = '';
                        
                            // Create table
                            var table = document.createElement('table');
                            table.className = 'table table-responsive';
                        
                            // Create table header
                            var thead = table.createTHead();
                            var headerRow = thead.insertRow();

                            if ( selectionValue === 'dynamic'  ){
                                var colName = 'Il costo totale di ' + getCurrencySymbol(currency) + ' ' + minRangeSlider + ' + IVA sarà corrisposto con le seguenti modalità valuta in :'+ getCurrencySymbol(currency);
                            }else {

                                var colName = 'Il costo totale di ' + getCurrencySymbol(currency) + ' ' + fixedValueInput + ' + IVA sarà corrisposto con le seguenti modalità valuta in :' + getCurrencySymbol(currency);
                            }
                            
                            var th = document.createElement('th');
                            th.textContent = colName;
                        
                            // Count the number of columns in the table body
                            var numColumns = 5; // You have two columns: importoInput and dovutoIl
                            th.colSpan = numColumns;
                        
                            headerRow.appendChild(th);
                        
                            // Create table body
                            var tbody = table.createTBody();
                            for (var i = 1; i <= maxRange; i++) {
                                var row = tbody.insertRow();
                                var descrizione = 'Rata' + i ;
                                
                                
                                if ( selectionValue === 'dynamic'  ){
                         
                                    var minRangeSliderCAL = parseFloat(minRangeSlider) + (vatPercentage * parseFloat(minRangeSlider)) / 100 ;
                                    
                                    if (includeOnPrice && enableVat === true ){
                                        var importo = (minRangeSliderCAL / maxRange).toFixed(2);

                                    } 
                                      
                                    else {
                                        var importo = (minRangeSlider / maxRange).toFixed(2);
                                    }

                                   
                                }
                                
                                else {
                                    if (includeOnPrice && enableVat === true ){
                                     var importo = (newcalculation / maxRange).toFixed(2);
                                    } else {
                                        var importo = (fixedValueInput / maxRange).toFixed(2);
                                    }
                                
                                }
                                
                        
                                // Create input field for the first row
                                var importoInput = document.createElement('input');

                                importoInput.classList.add('form-control');
                                importoInput.style = "width: 118px;"; 
                                importoInput.type = 'number';
                                
                                importoInput.value = importo; 
                                
                                var newImporto ;
                                // Update other rows' importo values based on the input field value
                                importoInput.addEventListener('input', function() {
                                    var inputVal = parseFloat(this.value);
                                     
                                    if ( selectionValue === 'dynamic' ){
                                        var minRangeSliderCAL = parseFloat(minRangeSlider) + (vatPercentage * parseFloat(minRangeSlider)) / 100 ;

                                        if (includeOnPrice && enableVat === true ){
                                        newImporto = (minRangeSliderCAL - inputVal) / (maxRange - 1);
                                        }else {
                                            newImporto = (minRangeSlider - inputVal) / (maxRange - 1);
                                        }
                                    }
                                    

                                    else  {
                                        if (includeOnPrice && enableVat === true ){
                                            newImporto = (newcalculation - inputVal) / (maxRange - 1);

                                        }else {

                                             newImporto = ( fixedValueInput- inputVal) / (maxRange - 1);
                                        }
                                         
                                     
                                    }
                                    
                                    var rows = tbody.rows;
                                    for (var j = 0; j < rows.length; j++) {
                                        var cell = rows[j].cells[1];
                                        if (cell.firstChild !== this) {
                                            cell.firstChild.value = newImporto.toFixed(2);
                                        }
                                    }
                                });
                        
                                var dovutoIl = getDateByFrequency(frequency, i);

                                // Create input field for the date
                                var dovutoIlInput = document.createElement('input');
                                dovutoIlInput.classList.add('form-control');
                                dovutoIlInput.style = "width: 126px;"; 
                                dovutoIlInput.type = 'date';


                                // Format the date to "yyyy-MM-dd"
                                var parts = dovutoIl.split('/');
                                var yyyy_mm_dd = parts[2] + '-' + parts[1].padStart(2, '0') + '-' + parts[0].padStart(2, '0');
                                dovutoIlInput.value = yyyy_mm_dd;


                                // Add event listener to the date input field
                                dovutoIlInput.addEventListener('change', function() {
                                    // Get the index of the current row
                                    var rowIndex = Array.from(this.parentNode.parentNode.parentNode.children).indexOf(this.parentNode.parentNode);
                                    var selectedDate = new Date(this.value); // Get the selected date
                                    
                                    // Update subsequent date fields based on frequency
                                    for (var k = rowIndex + 1; k < tbody.rows.length; k++) {
                                    var nextDate = new Date(selectedDate);
                                    switch (frequency) {
                                        case 'daily':
                                            nextDate.setDate(nextDate.getDate() + 1);
                                            break;
                                        case 'biweekly':
                                            nextDate.setDate(nextDate.getDate() + (14 * 1)); // Assuming biweekly means every two weeks
                                            break;
                                        case 'weekly':
                                            nextDate.setDate(nextDate.getDate() + (7 * 1));
                                            break;
                                        case 'monthly':
                                            nextDate.setMonth(nextDate.getMonth() + 1);
                                            break;
                                        case 'annually':
                                            nextDate.setFullYear(nextDate.getFullYear() + 1);
                                            break;
                                        default:
                                            break;
                                    }
                                    // Format the next date as "yyyy-MM-dd"
                                var yyyy_mm_dd = nextDate.toISOString().split('T')[0];

                                
                                    tbody.rows[k].cells[4].querySelector('input[type="date"]').value = yyyy_mm_dd;
                                    selectedDate = nextDate; 
                                    // Update selectedDate for the next iteration

                                }
                                });

                                if (enableVat &&  includeOnPrice ===true){ 
                                    calculatedValnew = " IVA Inc. "    ;
                                    console.log('" IVACompresa " +  getCurrencySymbol(currency.value)  + (vatPercentage * parseFloat(fixedValueInput)) / 100 '); 
                                }else if (enableVat ===false ) {

                                  //  calculatedValnew = "+ IVA " + vatPercentage + "%"   ;
                                    calculatedValnew = " "   ;
                                    console.log('"+ IVA " + vatPercentage + "%" ');
                                }else {
                                    calculatedValnew = "+ IVA " + vatPercentage + "%"   ;
                                }
                              
                   

                                if (EditableDates === true){
                                    var cells = [descrizione, importoInput , calculatedValnew , 'entro il' , dovutoIlInput  ];

                                }else {
                                 var cells = [descrizione, importoInput , calculatedValnew , 'entro il' , dovutoIl  ]; //'entro il '+
                                }
                                

                                cells.forEach(function(cellData ) {
                                    var cell = row.insertCell();
                                    if (typeof cellData === 'object') {
                                        cell.appendChild(cellData);
                                    } else {
                                        cell.textContent = cellData;
                                    }
                                });

                              

                            }
                        
                            // Append table to container
                            selectedFixedValueDiv.appendChild(table);
                        }


                    // Add event listeners
                    document.getElementById("selection").addEventListener("change", updateCalculatedValue);
                    document.getElementById("fixedValue").addEventListener("input", updateCalculatedValue);
                    document.getElementById("EditableDates").addEventListener("input", updateCalculatedValue);

                    document.getElementById("maxPaymentRangeSlider").addEventListener("change", updateCalculatedValue);
                  
                    document.getElementById("minRangeSlider").addEventListener("change", updateCalculatedValue);
          
                    document.getElementById("multiplePayments").addEventListener("input", updateCalculatedValue);

                    document.getElementById("vatCheckbox").addEventListener("change", updateCalculatedValue);
                    document.getElementById("vatPercentage").addEventListener("input", updateCalculatedValue);
                    document.getElementById("currency").addEventListener("change", updateCalculatedValue);
                    document.getElementById("frequency").addEventListener("change", updateCalculatedValue);

                   document.getElementById("priceTypeToggle").addEventListener("change", updateCalculatedValue);
 
                    
                      // Update table values when slider values change
                   minRangeSlider.noUiSlider.on('change', updateCalculatedValue);
                   // maxRangeSlider.noUiSlider.on('change', updateTableValues);
                   maxPaymentRangeSlider.noUiSlider.on('change', updateCalculatedValue);
                    // Initial calculation
                    updateCalculatedValue();
                    
         

            

         
 
 
                    function updateTableValues() {
                        var fixedValueInput = parseFloat(document.getElementById("fixedValue").value);
                        var minValue = parseInt(minRangeSlider.noUiSlider.get());
                        var maxValue = parseInt(maxRangeSlider.noUiSlider.get());
                        var frequency = document.getElementById('frequency').value;
                        var currency = document.getElementById('currency').value;
                        var tableDiv = document.getElementById('ImpostaTable1');

                        // Clear existing table content
                        tableDiv.innerHTML = '';

                        // Create table
                        var table = document.createElement('table');
                        table.className = 'table';

                        // Create table header
                      /*  var thead = table.createTHead();
                        var headerRow = thead.insertRow();
                        var colName = 'Il costo totale di ' + getCurrencySymbol(currency) + ' ' + fixedValueInput + ' + IVA sarà corrisposto con le seguenti modalità:';
                        var th = document.createElement('th');
                        th.textContent = colName;
                        headerRow.appendChild(th);*/
                        
                            // Create table header
                        var thead = table.createTHead();
                        var headerRow = thead.insertRow();
                        var colName = 'Il costo totale di ' + getCurrencySymbol(currency) + ' ' + fixedValueInput + ' + IVA sarà corrisposto con le seguenti modalità:';
                        var th = document.createElement('th');
                        th.textContent = colName;
                    
                        // Count the number of columns in the table body
                        var numColumns = 5; // You have two columns: importoInput and dovutoIl
                        th.colSpan = numColumns;
                    
                        headerRow.appendChild(th);
                                            
    


                        // Create table body
                        tbody = table.createTBody(); // Assign tbody here
                        for (var i = 1; i <= maxValue; i++) {
                            var row = tbody.insertRow();
                            var descrizione = 'Pagamento' + i+''+ getCurrencySymbol(currency) ;
                            var importoInput = document.createElement('input');
                            importoInput.type = 'number';
                            // importoInput.className = 'form-control';
                            importoInput.style = "width: 93px;"; // Adjust the width here
                            importoInput.value = (fixedValueInput / maxValue).toFixed(2);
                            importoInput.addEventListener('input', function() {
                               // updateTableValues();
                                var inputVal = parseFloat(this.value);
                                var newImporto = (fixedValueInput - inputVal) / (maxValue - 1);
                                var rows = tbody.rows;
                                for (var j = 0; j < rows.length; j++) {
                                    var cell = rows[j].cells[1];
                                    if (cell.firstChild !== this) {
                                        cell.firstChild.value = newImporto.toFixed(2);
                                    }
                                }
                            });

                            var dovutoIl = getDateByFrequency(frequency, i);

                                              // Create input field for the date
                            var dovutoIlInput = document.createElement('input');
                                dovutoIlInput.type = 'date';

                                // Format the date to "yyyy-MM-dd"
                                var parts = dovutoIl.split('/');
                                var yyyy_mm_dd = parts[2] + '-' + parts[1].padStart(2, '0') + '-' + parts[0].padStart(2, '0');
                                dovutoIlInput.value = yyyy_mm_dd;

                                // Add event listener to the date input field
                                dovutoIlInput.addEventListener('change', function() {
                                    // Get the index of the current row
                                    var rowIndex = Array.from(this.parentNode.parentNode.parentNode.children).indexOf(this.parentNode.parentNode);
                                    var selectedDate = new Date(this.value); // Get the selected date
                                    
                                    // Update subsequent date fields based on frequency
                                    for (var k = rowIndex + 1; k < tbody.rows.length; k++) {
                                    var nextDate = new Date(selectedDate);
                                    switch (frequency) {
                                        case 'daily':
                                            nextDate.setDate(nextDate.getDate() + 1);
                                            break;
                                        case 'biweekly':
                                            nextDate.setDate(nextDate.getDate() + (14 * 1)); // Assuming biweekly means every two weeks
                                            break;
                                        case 'weekly':
                                            nextDate.setDate(nextDate.getDate() + (7 * 1));
                                            break;
                                        case 'monthly':
                                            nextDate.setMonth(nextDate.getMonth() + 1);
                                            break;
                                        case 'annually':
                                            nextDate.setFullYear(nextDate.getFullYear() + 1);
                                            break;
                                        default:
                                            break;
                                    }
                                    // Format the next date as "yyyy-MM-dd"
                                    var yyyy_mm_dd = nextDate.toISOString().split('T')[0];
                                    tbody.rows[k].cells[2].querySelector('input[type="date"]').value = yyyy_mm_dd;
                                    selectedDate = nextDate; // Update selectedDate for the next iteration
                                }
                                });

                            // Create cell for description
                            var descrizioneCell = row.insertCell();
                            descrizioneCell.textContent = descrizione   ;

                            // Create cell for importo input field
                            var importoCell = row.insertCell();
                            importoCell.appendChild(importoInput);

                            // Create cell for dovutoIl
                        //    var dovutoIlCell = row.insertCell();
                         //   dovutoIlCell.textContent =  ' entro il ' +dovutoIl; //' entro il ' +

                          var dovutoIlCell = row.insertCell();
                          dovutoIlCell.appendChild(dovutoIl) ; 
                        }

                        // Append table to container
                        tableDiv.appendChild(table);
                    }



                  

                    function getCurrencySymbol(currencyCode) {
                        switch (currencyCode) {
                            case 'EUR':
                                return '€';
                            case 'USD':
                                return '$';
                            case 'GBP':
                                return '£';
                            case 'JPY':
                                return '¥';
                            default:
                                return ''; // Default to empty string if currency code is not recognized
                        }
                    }

               
                    function getDateByFrequency(frequency, offset) {
                    var offset = offset -1;    
                    var currentDate = new Date();
                   // console.log ('currentDate :',currentDate );
                        switch (frequency) {
                            case 'daily':
                                currentDate.setDate(currentDate.getDate() + offset);
                                break;
                            case 'biweekly':
                                currentDate.setDate(currentDate.getDate() + (14 * offset)); // Assuming biweekly means every two weeks
                                break;
                            case 'weekly':
                                currentDate.setDate(currentDate.getDate() + (7 * offset));
                                break;
                            case 'monthly':
                                currentDate.setMonth(currentDate.getMonth() + offset);
                                break;
                            case 'annually':
                                currentDate.setFullYear(currentDate.getFullYear() + offset);
                                break;
                            default:
                                break;
                        }
                        // Get day, month, and year parts
                        var day = currentDate.getDate().toString().padStart(2, '0'); // Add leading zero if needed
                        var month = (currentDate.getMonth() +1).toString().padStart(2, '0'); // Months are zero-based, so we add 1 and pad with zero
                        var year = currentDate.getFullYear();

                     //  console.log('day + '-' + month + '-' + year :' , day + '-' + month + '-' + year);
                        return day + '/' + month + '/' + year; // Format date as dd/mm/yyyy
                    }



 


                });

 

                //------------------------------------

               //for display pricename
                document.addEventListener("DOMContentLoaded", function() {
                    var priceNameInput = document.getElementById("priceName");
                    var selectedPriceNameDiv = document.getElementById("selectedpricename1");

                    // Set initial value
                    selectedPriceNameDiv.textContent = priceNameInput.value;

                    // Update value as user types
                    priceNameInput.addEventListener("input", function() {
                        selectedPriceNameDiv.textContent = priceNameInput.value;
                    });
                });


              // For display currency
              document.addEventListener("DOMContentLoaded", function() {
                var inputBox1 = document.getElementById("currency");
                var selectedCurrencyDiv = document.getElementById("selectedcurrency1");

                // Set initial value
                selectedCurrencyDiv.textContent = getCurrencySymbol(inputBox1.value);

                inputBox1.addEventListener("change", function() {
                    selectedCurrencyDiv.textContent = getCurrencySymbol(inputBox1.value);
                });

                // Function to get currency symbol based on currency code
                function getCurrencySymbol(currencyCode) {
                    switch (currencyCode) {
                        case 'EUR':
                            return '€';
                        case 'USD':
                            return '$';
                        case 'GBP':
                            return '£';
                        case 'JPY':
                            return '¥';
                        // Add more cases for other currency codes if needed
                        default:
                            return ''; // Default to empty string if currency code is not recognized
                    }
                }
            });

          // For display fixed value according to conditions  
          document.addEventListener("DOMContentLoaded", function() {

            

            var fixedValueInput = parseFloat(document.getElementById("fixedValue").value); // Parse input value as float
            var selectedFixedValueDiv = document.getElementById("selectedfoxedvalue1");

            function updateCalculatedValue() {
                
                  var selectionValue = document.getElementById('selection').value;
             
                
                   var fixedValueInput = parseFloat(document.getElementById("fixedValue").value);     
             
                 
                
                var enableVat = document.getElementById('vatCheckbox').checked;
                var vatPercentage = parseFloat(document.getElementById('vatPercentage').value);
                var includeOnPrice = document.getElementById('priceTypeToggle').checked;
               // var external = document.getElementById('external').checked;
                var currency = document.getElementById("currency");
                var calculatedValue =""; // Initialize calculated value with fixed value
                var minRangeSlider = parseInt(document.getElementById('minRangeSlider').noUiSlider.get());

                if (selectionValue === 'dynamic' && includeOnPrice) {
                    calculatedVal = parseFloat(minRangeSlider) + (vatPercentage * parseFloat(fixedValueInput)) / 100 +  " "  + " IVA Compresa (" +  getCurrencySymbol(currency.value)  + (vatPercentage * parseFloat(minRangeSlider)) / 100 +") "  ;
                    calculatedValue =calculatedVal;

                } else if (selectionValue === 'dynamic' && includeOnPrice ===false) {
                    calculatedVal = parseFloat(minRangeSlider)  + " + IVA " + vatPercentage + "%";
                    calculatedValue =calculatedVal;

                }

                else if ( enableVat && includeOnPrice) {
                  //  calculatedValue =  fixedValueInput + (vatPercentage * fixedValueInput) / 100  +  getCurrencySymbol(currency.value); // Calculate VAT if applicable
                    calculatedVal = parseFloat(fixedValueInput) + (vatPercentage * parseFloat(fixedValueInput)) / 100 +  " "  + " IVA Compresa (" +  getCurrencySymbol(currency.value)  + (vatPercentage * parseFloat(fixedValueInput)) / 100 +") "  ;
                    calculatedValue =  getCurrencySymbol(currency.value) + calculatedVal ;

             

                }else if (  enableVat ||  includeOnPrice ===false) {
                    calculatedVal = parseFloat(fixedValueInput).toFixed(2)   + " + IVA " + vatPercentage + "%"; // Include VAT percent
                    calculatedValue=   getCurrencySymbol(currency.value) + calculatedVal; 
                }
                else {
                    calculatedVal = parseFloat(fixedValueInput).toFixed(2) ; // Convert fixedValueInput to number and display with 2 decimal places
                    calculatedValue = getCurrencySymbol(currency.value) +calculatedVal;
                }

                // Display calculated value
                selectedFixedValueDiv.textContent = calculatedValue; // Display with 2 decimal places
            }
            
            // Add event listeners for input fields and checkboxes 
           
            document.getElementById("selection").addEventListener("change", updateCalculatedValue);
            document.getElementById("fixedValue").addEventListener("input", updateCalculatedValue);
           
           
           
            document.getElementById("vatCheckbox").addEventListener("change", updateCalculatedValue);
            document.getElementById("priceTypeToggle").addEventListener("change", updateCalculatedValue);
          //  document.getElementById("external").addEventListener("change", updateCalculatedValue);
            document.getElementById("vatPercentage").addEventListener("input", updateCalculatedValue);
            document.getElementById("currency").addEventListener("input", updateCalculatedValue);
          
            minRangeSlider.noUiSlider.on('change', updateCalculatedValue);
            // Initial calculation for every listener check 
            updateCalculatedValue();

            function getCurrencySymbol(currencyCode) {
                    switch (currencyCode) {
                        case 'EUR':
                            return '€';
                        case 'USD':
                            return '$';
                        case 'GBP':
                            return '£';
                        case 'JPY':
                            return '¥';
                        // Add more cases for other currency codes if needed
                        default:
                            return ''; // Default to empty string if currency code is not recognized
                    }
                }
        });

        </script>
        </div>
    </div>
 
<script>
    

   document.getElementById('saveButton').addEventListener('click', function() {
    // Gather data from form all fields 
    var priceName = document.getElementById('priceName').value;
    var currency = document.getElementById('currency').value;

    var enableVat = document.getElementById('vatCheckbox').checked;
    var vatPercentage = document.getElementById('vatPercentage').value;
    var includeOnPrice = document.getElementById('priceTypeToggle').checked;

    console.log('includeOnPrice :' , includeOnPrice);
    
    var external = false;

    // Validation flag
    var isValid = true;
    var isValidone = true;
    // to Get the values for the fixedInput and dynamicInput based on selection
    
    var minRange = '';
    var maxRange = '';
    var selectionValue = document.getElementById('selection').value;
    var fixedValue = document.getElementById('fixedValue').value;
    if (selectionValue === 'fixed') {
        fixedValue = document.getElementById('fixedValue').value;
 
     }
    if (selectionValue === 'dynamic') {
         
        var minRangeSlider = document.getElementById('minRangeSlider');
        minRange = minRangeSlider.noUiSlider.get();
        var maxRangeSlider = document.getElementById('maxRangeSlider');
        maxRange = maxRangeSlider.noUiSlider.get();
      // Convert values to numbers before comparison
   
        var minRangeNum = parseFloat(minRange);
        var maxRangeNum = parseFloat(maxRange);

        fixedValue = null ;

        if ( minRangeNum  > maxRangeNum) {
            
            console.log('minRange:', minRangeNum);
            console.log('maxRange:', maxRangeNum);

            isValidone = false;
            console.log('1st range isValid:', isValidone);
            alert('Minimum Payment Range should be lower than Maximum Payment Range.');
        }


    }
 
   

    var priceType = document.getElementById('priceType').value;
    if (priceType === 'oneTime'){
        var multiplePayments = document.getElementById('multiplePayments').checked;
        var EditableDates =  document.getElementById('EditableDates').checked; 
        if (multiplePayments === true){
           // var minPaymentRange = document.getElementById('minPaymentRange').value;
            //var maxPaymentRange = document.getElementById('maxPaymentRange').value;
            var paymentExampleText = document.getElementById('minPayment').value; 
            var frequency = document.getElementById('frequency').value;
        
                var minPaymentRangeSlider = document.getElementById('minPaymentRangeSlider');
                var maxPaymentRangeSlider = document.getElementById('maxPaymentRangeSlider');

                // Get the values from the slider objects
                var minPaymentRange = minPaymentRangeSlider.noUiSlider.get();
                var maxPaymentRange = maxPaymentRangeSlider.noUiSlider.get();

                    // Convert values to numbers before comparison
                var minPaymentRangeNum = parseFloat(minPaymentRange);
                var maxPaymentRangeNum = parseFloat(maxPaymentRange);
                console.log('minPaymentRange nummmm:', minPaymentRangeNum);
                console.log('maxPaymentRange nummmm:', maxPaymentRangeNum);
                console.log('2nd range check isValid:', isValid);

                // Validate if payment range is valid
                if (minPaymentRangeNum>maxPaymentRangeNum) {
                    isValid = false;
                    console.log('minPaymentRange:', minPaymentRangeNum);
                    console.log('maxPaymentRange:', maxPaymentRangeNum);
                    console.log('2nd range check isValid:', isValid);
                    alert('Minimum Payment Range should be lower than Maximum Payment Range.');
                }

        } else{
        var singlePayment =false;
        // var singlePayment = document.getElementById('singlePayment').checked;
        // if (singlePayment === true)
       
          // do nothing 
          var minPaymentRange = '';
          var maxPaymentRange = '';
          var paymentExampleText = '';
        }
    }
  
    console.log('isValid :' , isValid );  
 
    if(isValid && isValidone) {
    var data = {
        pricename: priceName,
        currency: currency,
        fixedvalue: fixedValue,
        dynamicminRange: minRange,
        dynamicmaxRange: maxRange,
        enableVat: enableVat,
        vatPercentage: vatPercentage,
        includeOnPrice: includeOnPrice,
        external: external,
        priceType: priceType,
        singlePayment: singlePayment,
        multiplePayments: multiplePayments,
        paymentMinRange: minPaymentRange,
        paymentMaxRange: maxPaymentRange,
        frequency : frequency,
        EditableDates : EditableDates,
        paymentExampleText: paymentExampleText
    };

    // Send AJAX request to update the price
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
        url: '/update-price/' + {{ $priceList->id }},
        type: 'POST',
        data: data,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        success: function(response) {
            console.log(response);
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Price updated successfully!',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/edit-price/' + {{ $priceList->id }};
                }
            });
        },
        error: function(xhr, status, error) {
            console.error(error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Fill all the Form while updating price.',
                confirmButtonText: 'OK'
            });
        }
    });

 }
});

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