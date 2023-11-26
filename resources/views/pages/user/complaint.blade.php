@extends('layouts.main', [
    'menuActive' => 'user.complaint'
])

@section('before-head-end')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
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
                        <h1 class="page-heading d-flex text-gray-900 fw-bold flex-column justify-content-center mb-3">List Complaint</h1>
                    </div>
                </div>
            </div>
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <div id="kt_app_content_container" class="app-container container-xxl">
                    <div class="card">
                        <div class="card-header">
							<h2 class="card-title fw-bold">Data Complaint</h2>
							<div class="card-toolbar">
								<a href="{{ route('user.complaint.add') }}" class="btn btn-flex btn-dark" data-kt-calendar="add">
								    <i class="ki-duotone ki-plus fs-2"></i>Create a new complaint
                                </a>
							</div>
						</div>
                        <div class="card-body">
                            <table class="table table-striped table-row-bordered gy-5 gs-7">
                                <thead>
                                    <tr class="fw-semibold fs-6 text-gray-800">
                                        <th>No</th>
                                        <th>Complaint</th>
                                        <th>Image</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Complaint Images</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" id="imageCarouselInner"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('before-body-end')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script>
        const table = $('.table').DataTable({
            "ajax":{
                url: "{{ route('user.complaint.data') }}",
                type: "GET"
            },
            columnDefs: [
                {
                    "targets": 0,
                    "render": function(data, type, row, meta){
                        return meta.row + 1;
                    }
                },
                {
                    "targets": 1,
                    "render": function(data, type, row, meta){
                        return row.complaint;
                    }
                },
                {
                    "targets": 2,
                    "render": function(data, type, row, meta){
                        return `
                            <button class="btn btn-dark" onclick="showImagesModal(${row.id})">Click to see images</button>
                        `;
                    }
                },
                {
                    "targets": 3,
                    "render": function (data, type, row) {
                        if (row.status === "waiting_for_approval") {
                            status = "<button class='btn btn-dark'>Waiting for approval</a>";
                        } else if (row.status === "approved") {
                            status = "<button class='btn btn-success'>Approved, the report will be followed up immediately</a>";
                        } else if (row.status === "complaint_resolved") {
                            status = "<button class='btn btn-primary'>Complaint resolved</a>";
                        }

                        return status
                    },
                },
            ]
        });

        function showImagesModal(complaintId) {
            $.ajax({
                url: "{{ route('user.complaint.get-images', ':id') }}".replace(':id', complaintId),
                type: "GET",
                success: function(response) {
                    populateImageCarousel(response);
                    $('#imageModal').modal('show');
                },
                error: function(error) {
                    console.error("Error fetching images", error);
                }
            });
        }

        function populateImageCarousel(images) {
            const carouselInner = $('#imageCarouselInner');
            carouselInner.empty();
            images.forEach((image, index) => {
                carouselInner.append(`
                    <div class="col-md-6">
                        <img src="${image}" class="w-100 my-3" alt="Complaint Image">
                    </div>
                `);
            });
        }
    </script>
@endsection