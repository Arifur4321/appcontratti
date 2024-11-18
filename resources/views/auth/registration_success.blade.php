@extends('layouts.master-without-nav')

@section('title')
    Registration Successful
@endsection

@section('css')
    <!-- SweetAlert CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
@endsection

@section('body')
    <body class="auth-body-bg">
    @endsection

@section('content')
    <!-- Content not needed here since we only show SweetAlert -->
@endsection

@section('script')
    <!-- SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Show the SweetAlert when the page loads
        Swal.fire({
            title: 'Success!',
            text: '{{ session('registration_success') }}',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to login page after the user clicks OK
                window.location.href = "{{ route('login') }}";
            }
        });
    </script>
@endsection
