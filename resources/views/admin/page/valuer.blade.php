@extends('admin.base.base')
@section('css')
@parent
    <link rel="stylesheet" href="{{asset('admin_style/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('admin_style/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('admin_style/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
    
    <link rel="stylesheet" href=" {{asset('mselect/chosen.min.css')}}" />
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
                <h3><a class="pull-bs-canvas-right btn btn-app" onclick="addCourse(this)">
                    <i class="fas fa-plus"></i> Add
                </a></h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                <li class="breadcrumb-item active">Valuer</li>
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
                <div class="card-header">
                {{-- <h3 class="card-title">{{$client->name ?? ''}}</h3> --}}
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Valuer Name</th>
                            <th>Mobile No.</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach( $valuers as $crse)
                        <tr>
                        <td>{{$crse->name ?? ''}}</td>
                        <td>{{ $crse->phone_no }}</td>
                        <td>{{ $crse->email }}</td>
                        <td>{{ $crse->address }}</td>
                        <td>{{ date('j F, Y', strtotime( $crse->created_at )) }}</td>

                        <td>
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-secondary ">
                                <input type="radio" name="options" autocomplete="off" onclick="viewCourse({{ $crse->id }}, this)" > <i class="far fa-eye"></i>
                                </label>
                                <label class="btn btn-secondary">
                                <input type="radio" name="options" class="pull-bs-canvas-right" id="option_a2" autocomplete="off" onclick="editCourse({{ $crse->id }}, this)"> <i class="far fa-edit"></i>
                                </label>
                                <label class="btn btn-secondary">
                                <input type="radio" name="options" onclick="deleteRecord({{ $crse->id }}, this)" autocomplete="off"> <i class="fas fa-trash"></i>
                                </label>
                            </div>
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
    <div class="bs-canvas bs-canvas-right position-fixed bg-light h-100 {{ old() ? ' mr-0': '' }}">
        <header class="bs-canvas-header p-3 bg-dark overflow-auto">
            <button type="button" class="bs-canvas-close float-left close" aria-label="Close"><span aria-hidden="true" class="text-light">&times;</span></button>
            <h4 class="d-inline-block text-light mb-0 float-right"><span class="course_action">Update</span> Client Detail</h4>
        </header>
        <div class="bs-canvas-content px-3 py-1">
           

                <form action="{{ route('valuer.store') }}" enctype="multipart/form-data" method="post">
                    @csrf
                    @if(old('id')!='')
                    @method('put')
                    @else
                    @method('post')
                    @endif
                    <div class="form-group">
                        <input type="hidden" name="id" value="" >
                        <label for="exampleInputName">Valuer Name</label>
        
                        <input type="text" name="name" class="form-control" id="name" value="{{ old('name')}}"
                            placeholder="">
                        <span style="color:red">@error('name'){{ $message }}@enderror</span>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputName">Mobile No.</label>
        
                        <input type="text" name="phone_no" class="form-control" id="phone_no" value="{{ old('phone_no')}}"
                            placeholder="">
                        <span style="color:red">@error('phone_no'){{ $message }}@enderror</span>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputName">Email</label>
        
                        <input type="text" name="email" class="form-control" id="email" value="{{ old('email')}}"
                            placeholder="">
                        <span style="color:red">@error('email'){{ $message }}@enderror</span>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputName">Address</label>
        
                        <input type="text" name="address" class="form-control" id="address" value="{{ old('address')}}"
                            placeholder="">
                        <span style="color:red">@error('address'){{ $message }}@enderror</span>
                    </div>
                    <div class="form-group">
                        <label for="account_no">Account Number</label>
        
                        <input type="text" name="account_no" class="form-control" id="account_no" value="{{ old('account_no')}}"
                            placeholder="">
                        <span style="color:red">@error('account_no'){{ $message }}@enderror</span>
                    </div>
                    <div class="form-group">
                        <label for="account_type">Account Type</label>
                        <input type="text" name="account_type" class="form-control" id="account_type" value="{{ old('account_type')}}"
                            placeholder="">
                        <span style="color:red">@error('account_type'){{ $message }}@enderror</span>
                    </div>
                    <div class="form-group">
                        <label for="ifsc_code">IFSC CODE</label>
                        <input type="text" name="ifsc_code" class="form-control" id="ifsc_code" value="{{ old('ifsc_code')}}"
                            placeholder="">
                        <span style="color:red">@error('ifsc_code'){{ $message }}@enderror</span>
                    </div>
                    <div class="form-group">
                        <label for="account_holder_name">Account Holder Name</label>
                        <input type="text" name="account_holder_name" class="form-control" id="account_holder_name" value="{{ old('account_holder_name')}}"
                            placeholder="">
                        <span style="color:red">@error('account_holder_name'){{ $message }}@enderror</span>
                    </div>
                    <div class="form-group">
                        <label for="bank_name">Bank Name</label>
                        <input type="text" name="bank_name" class="form-control" id="bank_name" value="{{ old('bank_name')}}"
                            placeholder="">
                        <span style="color:red">@error('bank_name'){{ $message }}@enderror</span>
                    </div>
                    <div class="form-group">
                        <label for="branch_name">Branch Name</label>
                        <input type="text" name="branch_name" class="form-control" id="branch_name" value="{{ old('branch_name')}}"
                            placeholder="">
                        <span style="color:red">@error('branch_name'){{ $message }}@enderror</span>
                    </div>
                    <button type="submit" class="btn btn-primary"><span class="course_action">Add</span></button>
                </form>
        </div>
    </div>
    

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

<script src="{{asset('mselect/chosen.jquery.min.js') }}"></script>
<script>
    $(function () {
        
        $("#example2").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

        $('#mselect').chosen();
    });
    var _URL = window.URL || window.webkitURL;
    function checkImageSize(ref){
        var imgwidth = 0;
        var imgheight = 0;
        image_fix_height = 400;//$(ref).attr('image-height');
        image_fix_width = 600;//$(ref).attr('image-width');
        file = $(ref).prop("files")[0];
        img = new Image();
        img.src = _URL.createObjectURL(file);
        img.onload = function() {
            imgwidth = this.width;
            imgheight = this.height;
            if(imgwidth==parseInt(image_fix_width) && imgheight == parseInt(image_fix_height)){

            }
            else{
                $(ref).addClass('is-invalid');
                $(ref).replaceWith($(ref).val('').clone(true));
                return;
            }
        }
    }
	$(document).on('click', '.bs-canvas-close, .bs-canvas-overlay', function(){
		var elm = $(this).hasClass('bs-canvas-close') ? $(this).closest('.bs-canvas') : $('.bs-canvas');
		elm.removeClass('mr-0 ml-0');
		$('.bs-canvas-overlay').remove();
		return false;
	});
    function editCourse(id, ref){
        var url = "{{ route('valuer.edit', ":id") }}";
        url = url.replace(':id', id);

        $.ajax({
            type: "get",
            url: url,
            data: "data",
            dataType: "JSON",
            success: function (response) {
                $.each(response.valuer, function (indexInArray, valueOfElement) {
                    $("input[type='text'][name='"+indexInArray+"']").val(valueOfElement);
                    $("input[type='hidden'][name='"+indexInArray+"']").val(valueOfElement);
                   
                });
               
                //
            }
        });
        $(".course_action").text('Update');
        if($(ref).hasClass('pull-bs-canvas-right')){
            $('.bs-canvas-right').addClass('mr-0');
        }
        var url = "{{ route('valuer.update', ":id") }}";
        url = url.replace(':id', id);
        $("form").attr('action',url);
        $("input[name='_method']").val('put');
        return false;
        // $(".summernote").summernote('destroy')
    }
    function addCourse(ref){
        $("input[type='text']").val('');
        $("input[name='id']").val('');
        
        if($(ref).hasClass('pull-bs-canvas-right')){
            $('.bs-canvas-right').addClass('mr-0');
        }
        var url = "{{ route('valuer.store') }}";
        $("form").attr('action',url);
        $("input[name='_method']").val('post');

        return false;
    }
    function deleteRecord(id,ref){
        if(confirm("Are you sure you want to delete this?")){
            var url = "{{ route('valuer.destroy', ":id") }}";
            url = url.replace(':id', id);
            $.ajax({
                type: "delete",
                url: url,
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                dataType: "JSON",
                success: function (response) {
                    $(ref).parents('tr').remove();
                // alert("this field delete");

                }
            });
        }
        else{
            return false;
        }
        
    }
   
    function viewCourse(id, ref){
        
        $('.bs-canvas-left').addClass('ml-0');


    }
  </script>

@endsection


