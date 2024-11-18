@extends('layouts.master')
@section('title')
    @lang('translation.Email-SMS-Template')
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Projects
        @endslot
        @slot('title')
            @lang('translation.Email-SMS-Template')
        @endslot
    @endcomponent

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="card-body">
        <h4 class="card-title">CRM APP - Email & SMS Templates</h4>

        <div class="row">
            <div class="col-md-3">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link mb-2 active" id="v-pills-email-template-tab" data-bs-toggle="pill" href="#v-pills-email-template" role="tab" aria-controls="v-pills-email-template" aria-selected="true">Email Template</a>

                    <a class="nav-link mb-2" id="v-pills-sms-template-tab" data-bs-toggle="pill" href="#v-pills-sms-template" role="tab" aria-controls="v-pills-sms-template" aria-selected="false">SMS Template</a>
                </div>
            </div>

            <div class="col-md-9">
                <div class="tab-content text-muted mt-4 mt-md-0" id="v-pills-tabContent">
                    <!-- Email Template Tab Content -->
                    <div class="tab-pane fade active show" id="v-pills-email-template" role="tabpanel" aria-labelledby="v-pills-email-template-tab">
                        <form id="email-template-form" method="POST" action="{{ route('save.template') }}">
                            @csrf
                            <div class="mb-3">
                                <!-- Container for Label and Save Button -->
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label for="email_template" class="form-label mb-0">
                                        Email Template: scrivi la tua parola chiave per generare il link della firma all'interno della tua email almeno su % %
                                    </label>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>

                                <!-- CKEditor for Email Template -->
                                <textarea id="email_template" name="email_template" rows="10" class="form-control">
                                    {!! old('email_template', $emailContent) !!}
                                </textarea>

                                <style>
                                    .ck.ck-editor__main>.ck-editor__editable:not(.ck-focused) {
                                        border-color: var(--ck-color-base-border);
                                        height: 400px !important;
                                        width: 100% !important;
                                    }
                                    .ck.ck-editor__editable_inline>:last-child {
                                        margin-bottom: var(--ck-spacing-large);
                                        height: 400px;
                                    }
                                    .ck-editor__editable {
                                        min-height: 400px;
                                    }
                                </style>
                            </div>
                        </form>
                    </div>

                    <!-- SMS Template Tab Content -->
                    <div class="tab-pane fade" id="v-pills-sms-template" role="tabpanel" aria-labelledby="v-pills-sms-template-tab">
                        <form id="sms-template-form" method="POST" action="{{ route('save.template') }}">
                            @csrf
                            <div class="mb-3">
                                <!-- Container for Label and Save Button -->
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label for="sms_template" class="form-label mb-0">SMS Template : scrivi la tua parola chiave per generare il link della firma all'interno della tua email almeno su % %</label>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>

                                <!-- CKEditor for SMS Template -->
                                <textarea id="sms_template" name="sms_template" rows="10" class="form-control">
                                    {!! old('sms_template', $smsContent) !!}
                                </textarea>

                                <style>
                                    .ck.ck-editor__main>.ck-editor__editable:not(.ck-focused) {
                                        border-color: var(--ck-color-base-border);
                                        height: 400px !important;
                                        width: 100% !important;
                                    }
                                    .ck.ck-editor__editable_inline>:last-child {
                                        margin-bottom: var(--ck-spacing-large);
                                        height: 400px;
                                    }
                                    .ck-editor__editable {
                                        min-height: 400px;
                                    }
                                </style>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include CKEditor Script -->
    <script src="{{ asset('js/newckeditor/build/ckeditor.js') }}"></script>

    <script>
        let emailEditor;
        let smsEditor;

        document.addEventListener("DOMContentLoaded", function() {
            // Initialize CKEditor for Email Template
            ClassicEditor
                .create(document.querySelector('#email_template'))
                .then(editor => {
                    emailEditor = editor;
                })
                .catch(error => {
                    console.error(error);
                });

            // Initialize CKEditor for SMS Template
            ClassicEditor
                .create(document.querySelector('#sms_template'))
                .then(editor => {
                    smsEditor = editor;
                })
                .catch(error => {
                    console.error(error);
                });

            // Handle the form submission for Email Template with SweetAlert
            $('#email-template-form').on('submit', function(e) {
                e.preventDefault();

                // Update the textarea with CKEditor's data
                $('#email_template').val(emailEditor.getData());

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Email template saved successfully!',
                        });
                    },
                    error: function(response) {
                        if (response.status === 422) {
                            var errors = response.responseJSON.errors;
                            var errorMessages = Object.values(errors).map(function(errorArray) {
                                return errorArray.join(' ');
                            }).join(' ');
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errorMessages,
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to save email template. Please try again.',
                            });
                        }
                    }
                });
            });

            // Handle the form submission for SMS Template with SweetAlert
            $('#sms-template-form').on('submit', function(e) {
                e.preventDefault();

                // Update the textarea with CKEditor's data
                $('#sms_template').val(smsEditor.getData());

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'SMS template saved successfully!',
                        });
                    },
                    error: function(response) {
                        if (response.status === 422) {
                            var errors = response.responseJSON.errors;
                            var errorMessages = Object.values(errors).map(function(errorArray) {
                                return errorArray.join(' ');
                            }).join(' ');
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errorMessages,
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to save SMS template. Please try again.',
                            });
                        }
                    }
                });
            });
        });
    </script>

@endsection
