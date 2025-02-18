@extends('admin.base.base')
@section('css')
@parent
<link rel="stylesheet" href="{{asset('admin_style/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('admin_style/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('admin_style/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('dist/datetime/jquery.datetimepicker.min.css')}}" />

    <style>
        .bs-canvas-overlay {
            opacity: 0.85;
            z-index: 1100;
        }

        .bs-canvas {
            top: 0;
            z-index: 1110;
            overflow-x: hidden;
            overflow-y: auto;
            width: 330px;
            transition: margin .4s ease-out;
            -webkit-transition: margin .4s ease-out;
            -moz-transition: margin .4s ease-out;
            -ms-transition: margin .4s ease-out;
        }

        .bs-canvas-left {
            left: 0;
            margin-left: -330px;
        }

        .bs-canvas-right {
            right: 0;
            margin-right: -330px;
        }

        /* Only for demo */
        body {
            min-height: 100vh;
        }
        .editImageOption{
            display: none;
        }
    </style>
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Approvel</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                <li class="breadcrumb-item active">Assign</li>
                </ol>
            </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
        <div class="row">
            <div class="col-12">
            <div class="card">
                <!-- /.card-header -->
                <div class="card-body">
                <table id="example1" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Owner Name</th>
                            <th>Property Name</th>
                            <th>Property Type</th>
                            <th>Surveyor</th>
                            <th>Created</th>
                            <th>Last Date Survey</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach( $assign as $crse)
                    <tr>
                        <td>{{ $crse->name }}</td>
                        <td>{{ $crse->property_name }}</td>
                        <td>{{ $crse->type  }}</td>
                        <td>{{ $crse->username  }}</td>
                        <td>{{ $crse->created_at  }}</td>
                        <td>{{ $crse->last_date  }}</td>
                        <td></td>
                        <td>
                            @if( $crse->status == 1 )
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                @can('update',$crse)
                                <label class="btn btn-secondary">
                                <input type="radio" name="options" class="pull-bs-canvas-right" id="option_a2" autocomplete="off" onclick="viewForms('multi-step-form/step/{{ $crse->id }}/1')"> <i class="far fa-eye"></i>
                                </label>
                                <label class="btn btn-secondary ">
                                <input type="radio" name="options" autocomplete="off" onclick="approveSurvey({{ $crse->id }}, this)" > <i class="fas fa-check"></i>
                                </label>
                                <label class="btn btn-secondary">
                                    <input type="radio" name="options" onclick="disApproveSurvey({{ $crse->id }}, this)" autocomplete="off"> <i class="fas fa-times"></i>
                                </label>
                                @endcan
                            </div>
                            @else
                            Approved
                            @endif
                        </td> 
                    
                    </tr>
                    @endforeach
                    </tbody>
                    
                </table>
                
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    
@endsection
@section('script')
@parent
<!-- DataTables  & Plugins -->
<script src="{{asset('admin_style/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('admin_style/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('admin_style/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('admin_style/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('admin_style/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('admin_style/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('admin_style/plugins/jszip/jszip.min.js')}}"></script>
<script src="{{asset('admin_style/plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{asset('admin_style/plugins/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{asset('admin_style/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('admin_style/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('admin_style/plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
<script src="{{asset('admin_style/plugins/inputmask/jquery.inputmask.min.js')}}"></script>
<script src="{{asset('dist/datetime/jquery.datetimepicker.full.min.js') }}"></script>

<script src="{{asset('mselect/chosen.jquery.min.js') }}"></script>
<script>
    $(function () {
        
        $("#example1").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

        $('#mselect').chosen();
        $(".customDate").datetimepicker();
    });
    
    // function deleteRecord(id,ref){
    //     var url = "{{ route('client.destroy', ":id") }}";
    //     url = url.replace(':id', id);
    //     $.ajax({
    //         type: "delete",
    //         url: url,
    //         data: {
    //             "_token": "{{ csrf_token() }}",
    //         },
    //         dataType: "JSON",
    //         success: function (response) {
    //             $(ref).parents('tr').remove();
    //         // alert("this field delete");

    //         }
    //     });
    // }
    function viewForms(url){
        window.location.href = url;
    }
    function approveSurvey(id,ref){
        Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "success",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Approve it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // alert("hello");
                var url = "{{ route('valuation.status', ":id") }}";
                url = url.replace(':id', id);
                $.ajax({
                    type: "put",
                    url: url,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "status" : 2,
                    },
                    dataType: "JSON",
                    success: function (response) {

                        Swal.fire({
                        title: "Approved!",
                        text: "Your file has been Approved.",
                        icon: "success"
                        });

                    }
                });
                
            }
        });
    }
    function disApproveSurvey(id,ref){
        Swal.fire({
        title: "Please Enter your remark",
        input: "text",
        inputAttributes: {
            autocapitalize: "off"
        },
        showCancelButton: true,
        confirmButtonText: "Disapprove",
        showLoaderOnConfirm: true,
        preConfirm: async (login) => {
            var url = "{{ route('valuation.status', ":id") }}";
            url = url.replace(':id', id);
            $.ajax({
                type: "put",
                url: url,
                data: {
                    "_token": "{{ csrf_token() }}",
                    "remark" : login,
                    "status" : 0,
                },
                dataType: "JSON",
                success: function (response) {
                    return response;

                }
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
            title: "Record updated Successfully"
            });
        }
        });
        
    }
    
  </script>

@endsection


