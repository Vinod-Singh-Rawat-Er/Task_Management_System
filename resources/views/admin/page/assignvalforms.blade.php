@extends('admin.base.base')
@section('css')
@parent
<link rel="stylesheet" href="{{asset('admin_style/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('admin_style/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('admin_style/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Forms</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                <li class="breadcrumb-item active">Assign Forms</li>
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
                            <th>Job Id</th>
                            <th>Owner Name</th>
                            <th>Owner Phone No.</th>
                            <th>Property Name</th>
                            <th>Property Type</th>
                            <th>Address</th>
                            <th>Assing Date</th>
                            <th>Hold</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach( $assign as $crse)
                    <tr>
                        <td>{{ $crse->job->job_id ??'' }}</td>
                        <td>{{ $crse->owner->name }}</td>
                        <td>{{ $crse->owner->phone_no }}</td>
                        <td>{{ $crse->property->property_name }}</td>
                        <td>{{ $crse->property->type  }}</td>
                        <td>{{ $crse->property->address  }}</td>
                        <td>{{ date('j F, Y', strtotime( $crse->created_at ))  }}</td>
                        <td>
                            @if($crse->status > 0)
                            Submitted
                            @else
                            <input id="{{$crse->id}}" type="checkbox" class="onhold"  data-toggle="toggle" {{ $crse->hold ? 'checked':'' }} >
                            @endif
                        </td>
                        <td> 
                        @if($crse->status == 0 ) 
                            <a  class="pull-bs-canvas-right" href="{{ route('steps', ['id'=> $crse->id,'step'=>1] )}}" > <i class="far fa-edit"></i></a>
                        @endif
                        </td>
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
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
<script>
    $(function () {
        
        $("#example1").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

        $('.onhold').change(function() {
            var id = $(this).attr('id');
            var url = "{{ route('valuationform.hold', ":id") }}";
            url = url.replace(':id', id);
            if($(this).prop('checked')){
                var ref= this;
                
                Swal.fire({
                    title: "Submit your reason",
                    input: "text",
                    inputAttributes: {
                        autocapitalize: "off"
                    },
                    showCancelButton: true,
                    confirmButtonText: "Submit",
                    showLoaderOnConfirm: true,
                    preConfirm: async (login) => {
                        $.ajax({
                            type: "post",
                            url: url,
                            data: {
                                "_token": "{{ csrf_token() }}",
                                'remark':login,
                                'hold': $(ref).prop('checked')
                            },
                            dataType: "JSON",
                            success: function (response) {
                            

                            }
                        });
                        return 1;
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                    }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Submitted'
                        });
                        // location.reload();
                    }else{
                       $(ref).bootstrapToggle('toggle')
                    }
                });
            }else{
                $.ajax({
                    type: "post",
                    url: url,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'remark':'',
                        'hold': $(ref).prop('checked')
                    },
                    dataType: "JSON",
                    success: function (response) {
                    }
                });
            }
            // $('#console-event').html('Toggle: ' + $(this).prop('checked'))
        })

    });
</script>
@endsection