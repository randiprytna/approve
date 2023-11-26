@extends('layouts.main', [
    'menuActive' => 'admin.complaint'
])

@section('before-head-end')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
    <style>
        .timeline-label .timeline-label{
            width:185px!important;
        }
        .timeline-label:before{
            left:186px!important;
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
                        <h1 class="page-heading d-flex text-gray-900 fw-bold flex-column justify-content-center mb-3">List Complaint</h1>
                    </div>
                </div>
            </div>
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <div id="kt_app_content_container" class="app-container container-xxl">
                    <div class="card">
                        <div class="card-header">
							<h2 class="card-title fw-bold">Data Complaint</h2>
						</div>
                        <div class="card-body">
                            <table class="table table-striped table-row-bordered gy-5 gs-7">
                                <thead>
                                    <tr class="fw-semibold fs-6 text-gray-800">
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Complaint</th>
                                        <th>Image</th>
                                        <th>Status</th>
                                        <th>History</th>
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
    <div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Complaint Images</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" id="contentHistory"></div>
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
                url: "{{ route('admin.complaint.data') }}",
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
                        return row.name;
                    }
                },
                {
                    "targets": 2,
                    "render": function(data, type, row, meta){
                        return row.complaint;
                    }
                },
                {
                    "targets": 3,
                    "render": function(data, type, row, meta){
                        return `<button class="btn btn-dark" onclick="showImagesModal(${row.id})">Click to see images</button>`;
                    }
                },
                {
                    "targets": 4,
                    "render": function (data, type, row) {
                        if (row.status === "waiting_for_approval") {
                            status = `<button class='btn btn-dark btn-need-approval' onclick="needApproval(${row.id})">Click to approved</a>`;
                        } else if (row.status === "approved") {
                            status = `<button class='btn btn-success btn-approved' onclick="resolvedComplaint(${row.id})">Approved, click to mark this complaint as resolved</a>`;
                        } else if (row.status === "complaint_resolved") {
                            status = "<button class='btn btn-primary'>Complaint resolved</a>";
                        }

                        return status
                    },
                },
                {
                    "targets": 5,
                    "render": function(data, type, row, meta){
                        return `
                            <button class="btn btn-dark" onclick="showHistoryModal(${row.id})">Click to see History</button>
                        `;
                    }
                },
            ]
        });

        function showImagesModal(complaintId) {
            $.ajax({
                url: "{{ route('admin.complaint.get-images', ':id') }}".replace(':id', complaintId),
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

        function needApproval(complaintId) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to approve this complaint.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, approve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('admin.complaint.update-status') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            complaintId: complaintId,
                            newStatus: 'approved'
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Success',
                                text: 'Status updated successfully!',
                                icon: 'success'
                            }).then(() => {
                                table.ajax.reload();
                            });
                        },
                        error: function(error) {
                            Swal.fire({
                                title: 'Error',
                                text: 'Error',
                                icon: 'error'
                            })
                        }
                    });
                }
            });
        }

        function resolvedComplaint(complaintId) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to mark this complaint as resolved.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, mark it as resolved!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('admin.complaint.update-status') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            complaintId: complaintId,
                            newStatus: 'complaint_resolved'
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Success',
                                text: 'Status updated successfully!',
                                icon: 'success'
                            }).then(() => {
                                table.ajax.reload();
                            });
                        },
                        error: function(error) {
                            Swal.fire({
                                title: 'Error',
                                text: 'Error',
                                icon: 'error'
                            })
                        }
                    });
                }
            });
        }

        function showHistoryModal(complaintId) {
            $.ajax({
                url: "{{ route('admin.complaint.get-history', ':id') }}".replace(':id', complaintId),
                type: "GET",
                success: function(response) {
                    populateHistory(response);
                    $('#historyModal').modal('show');
                },
                error: function(error) {
                    console.error("Error fetching history", error);
                }
            });
        }

        function populateHistory(history) {
            const historyInner = $('#contentHistory');
            historyInner.empty();
            historyInner.append(`
                <div class="card-body pt-5 d-flex justify-content-center">
                    <div class="timeline-label">
                        <div class="timeline-item">
                            <div class="timeline-label fw-bold text-gray-800 fs-6">${history.created_at}</div>
                            <div class="timeline-badge">
                                <i class="fa fa-genderless text-dark fs-1"></i>
                            </div>
                            <div class="timeline-content d-flex">
                                <span class="fw-bold text-gray-800 ps-3">Complaint Created</span>
                            </div>
                        </div>
                        ${history.approved_at ? `
                            <div class="timeline-item">
                                <div class="timeline-label fw-bold text-gray-800 fs-6">${history.approved_at}</div>
                                <div class="timeline-badge">
                                    <i class="fa fa-genderless text-success fs-1"></i>
                                </div>
                                <div class="timeline-content d-flex">
                                    <span class="fw-bold text-gray-800 ps-3">Complaint Approved</span>
                                </div>
                            </div>
                        ` : ''}
                        ${history.resolved_at ? `
                            <div class="timeline-item">
                                <div class="timeline-label fw-bold text-gray-800 fs-6">${history.resolved_at}</div>
                                <div class="timeline-badge">
                                    <i class="fa fa-genderless text-primary fs-1"></i>
                                </div>
                                <div class="timeline-content d-flex">
                                    <span class="fw-bold text-gray-800 ps-3">Complaint Resolved</span>
                                </div>
                            </div>
                        ` : ''}
                    </div>
                </div>
            `);
        }
    </script>
@endsection