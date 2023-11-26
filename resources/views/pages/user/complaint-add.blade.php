@extends('layouts.main', [
    'menuActive' => 'user.complaint-add'
])

@section('before-head-end')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/custom/filepond/filepond.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/custom/filepond/FilePondPluginImagePreview.min.css') }}">
    <style>
        @media only screen and (min-width: 1200px) {
            .filepond--panel-root {
            border-radius: 2em;
            background-color: #edf0f4;
            height: 1em;
            }

            .filepond--item {
                width: 18rem!important;
                height: 18rem!important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="app-container my-3">
        @include('components.error-message')
    </div>
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <a href="{{ route('user.complaint') }}" class="btn btn-dark">Back</a>
                    </div>
                </div>
            </div>
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <div id="kt_app_content_container" class="app-container container-xxl">
                    <div class="card">
                        <div class="card-header">
							<h2 class="card-title fw-bold">New Complaint</h2>
						</div>
                        <div class="card-body">
                            <form action="{{ route('user.complaint.add') }}" method="POST" enctype="multipart/form-data" class="row">
                                @csrf
                                <div class="col-md-12 my-3">
                                    <label class="form-label">Complaint</label>
                                    <textarea rows="5" placeholder="Write Complaint Here" class="form-control" name="complaint"></textarea>
                                </div>
                                <div class="col-md-12 my-3">
                                    <label class="form-label">Add Image</label>
                                    <input type="file" name="images[]" multiple>
                                </div>
                                <div class="d-flex justify-content-end my-3">
                                    <button class="btn btn-dark">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('before-body-end')
    <script src="{{ asset('assets/plugins/custom/filepond/filepond.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/filepond/FilePondPluginImagePreview.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            FilePond.registerPlugin(FilePondPluginImagePreview);
            FilePond.create(document.querySelector('input[name="images[]"]'), {
                server: {
                    process: {
                        url: '{{ route("user.complaint.upload-images") }}',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                    }
                }
            });
        });
    </script>
@endsection