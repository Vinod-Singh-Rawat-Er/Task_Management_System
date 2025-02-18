@extends('admin.base.base')
@section('css')
@parent
<link rel="stylesheet" href="{{asset('admin_style/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('admin_style/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('admin_style/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" href=" {{asset('mselect/chosen.min.css')}}" />
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
            @can('create',\App\Models\AssignValuation::class)
                <h3><a class="pull-bs-canvas-right btn btn-app" onclick="addCourse(this)">
                    <i class="fas fa-plus"></i> Assign
                </a></h3>
            @endcan
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
                            <th>Id</th>
                            <th>Job Id</th>
                            <th>Job Name</th>
                            <th>Surveyor</th>
                            <th>Purpose</th>
                            <th>Properties</th>
                            <th>Created</th>
                            <th>Last Date Survey</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach( $jobs as $crse)
                    <tr>
                        <td>{{ $crse->id }}</td>
                        <td>{{ $crse->job_id}}</td>
                        <td>{{ $crse->job_name}}</td>
                        <td>{{ $crse->surveyor->name  }}</td>
                        <td>{{ $crse->purpose_of_valuation}}</td>
                        <td>
                            <a class="btn btn-app" href="{{ route('valuations.property.index', [ $crse->id]) }}">
                                <span class="badge bg-teal">{{count($crse->valuations)}}</span>
                                <i class="fas fa-inbox"></i> Property
                            </a>
                        </td>
                        <td>{{ date('j F, Y', strtotime( $crse->created_at ))  }}</td>
                        <td> {{date('j F, Y', strtotime( $crse->last_date )) }}</td>
                        <td>
                            @if($crse->hold)
                            <span class="float-right badge bg-danger"> Hold </span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                @can('view',$crse)
                                <label class="btn btn-secondary ">
                                    <input type="radio" name="options" autocomplete="off" onclick="viewCourse({{ $crse->id }}, this)" > <i class="far fa-eye"></i>
                                </label>
                                @endcan
                                @can('update',$crse)
                                <label class="btn btn-secondary">
                                <input type="radio" name="options" class="pull-bs-canvas-right" id="option_a2" autocomplete="off" onclick="editCourse({{ $crse->id }}, this)"> <i class="far fa-edit"></i>
                                </label>
                                @endcan
                                @can('delete',$crse)
                                <label class="btn btn-secondary">
                                <input type="radio" name="options" onclick="deleteRecord({{ $crse->id }}, this)" autocomplete="off"> <i class="fas fa-trash"></i>
                                </label>
                                @endcan
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
            <h4 class="d-inline-block text-light mb-0 float-right"><span class="course_action">Update </span>Assign Valuation</h4>
        </header>
        <div class="bs-canvas-content px-3 py-1">
           

                <form action="{{ route('assignval.store') }}" enctype="multipart/form-data" method="post">
                    @csrf
                    @if(old('id')!='')
                    @method('put')
                    @else
                    @method('post')
                    @endif
                    <div class="form-group">
                        <label for="type">Job Name</label>
                        <input class="form-control " type="text" id="job_name" name="job_name" required >
                        <span style="color:red">@error('job_name'){{ $message }}@enderror</span>
                    </div>
                    <div class="form-group">
                        <label for="type">Property Name</label>
                        <input type="hidden" name="id" value="" >
                        <select class="form-control mselect " id="property_id" name="property_id[]" aria-label=".form-select-lg example" required multiple >
                            <option  value="">Select Property</option>
                            @foreach( $property as $val)
                            <option  value="{{$val->id}}" {{ old('property_id') == $val->id ? 'selected': '' }}>{{$val->property_name}}|{{$val->owner->name}}|{{$val->owner->father_name}}|{{$val->owner->distt}}</option>
                            @endforeach
                        </select>
                        <span style="color:red">@error('property_id'){{ $message }}@enderror</span>
                    </div>
                    <div class="form-group">
                        <label for="type">PURPOSE OF VALIDATION</label>
                        <input class="form-control " type="text" id="purpose_of_valuation" name="purpose_of_valuation" required >
                        <span style="color:red">@error('purpose_of_valuation'){{ $message }}@enderror</span>
                    </div>
                    <div class="form-group">
                        <label for="type">Assign Surveyor</label>
                        <select class="form-control " id="surveyor_id" name="surveyor_id" aria-label=".form-select-lg example" required >
                            <option  value="">Select Surveyor User</option>
                            @foreach( $users as $val)
                                @if($val->name =='Surveyur')
                                    @foreach( $val->users as $item)
                                    <option  value="{{$item->id}}" {{ old('surveyor_id') == $item->id ? 'selected': '' }}>{{$item->name}}</option>
                                    @endforeach
                                @endif
                            @endforeach
                        </select>
                        <span style="color:red">@error('surveyor_id'){{ $message }}@enderror</span>
                    </div>
                    <div class="form-group">
                        <label for="type">Assign DEO</label>
                        <select class="form-control " id="deo_id" name="deo_id" aria-label=".form-select-lg example" required >
                            <option  value="">Select DEO User</option>
                            @foreach( $users as $val)
                                @if($val->name =='DEO')
                                    @foreach( $val->users as $item)
                                    <option  value="{{$item->id}}" {{ old('deo_id') == $item->id ? 'selected': '' }}>{{$item->name}}</option>
                                    @endforeach
                                @endif
                            @endforeach
                        </select>
                        <span style="color:red">@error('deo_id'){{ $message }}@enderror</span>
                    </div>
                    <div class="form-group">
                        <label for="type">Assign Valuer</label>
                        <select class="form-control " id="valuer_id" name="valuer_id" aria-label=".form-select-lg example" required >
                            <option  value="">Select Valuer User</option>
                            @foreach( $users as $val)
                                @if($val->name =='Valuer')
                                    @foreach( $val->users as $item)
                                    <option  value="{{$item->id}}" {{ old('valuer_id') == $item->id ? 'selected': '' }}>{{$item->name}}</option>
                                    @endforeach
                                @endif
                            @endforeach
                        </select>
                        <span style="color:red">@error('valuer_id'){{ $message }}@enderror</span>
                    </div>
                    <div class="form-group">
                        <label for="type">Assign Checker</label>
                        <select class="form-control " id="checker_id" name="checker_id" aria-label=".form-select-lg example" required >
                            <option  value="">Select Valuer User</option>
                            @foreach( $users as $val)
                                @if($val->name =='Checker')
                                    @foreach( $val->users as $item)
                                    <option  value="{{$item->id}}" {{ old('checker_id') == $item->id ? 'selected': '' }}>{{$item->name}}</option>
                                    @endforeach
                                @endif
                            @endforeach
                        </select>
                        <span style="color:red">@error('checker_id'){{ $message }}@enderror</span>
                    </div>
                    <div class="form-group">
                        <label for="type">Last Date Valuation</label>
                        <input type="text" name="last_date" class="form-control customDate" value="">
                        <span style="color:red">@error('last_date'){{ $message }}@enderror</span>
                    </div>
                    <!-- <div class="form-group">
                        <label for="type">Renewal Date Valuation</label>
                        <input type="text" name="renewal_valuation_date" class="form-control customDate">
                        <span style="color:red">@error('renewal_valuation_date'){{ $message }}@enderror</span>
                    </div> -->
                    <button type="submit" class="btn btn-primary"><span class="course_action">Assign Valuation</span></button>
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
<script src="{{asset('admin_style/plugins/inputmask/jquery.inputmask.min.js')}}"></script>
<script src="{{asset('dist/datetime/jquery.datetimepicker.full.min.js') }}"></script>

<script src="{{asset('mselect/chosen.jquery.min.js') }}"></script>
<script>
    $(function () {
        
        $("#example1").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

        $('.mselect').chosen();
        $(".customDate").datetimepicker();
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
        var url = "{{ route('assignval.edit', ":id") }}";
        url = url.replace(':id', id);

        $.ajax({
            type: "get",
            url: url,
            data: "data",
            dataType: "JSON",
            success: function (response) {
                $.each(response.client, function (indexInArray, valueOfElement) {
                    // console.log(indexInArray);
                    $("input[type='text'][name='"+indexInArray+"']").val(valueOfElement);
                    $("input[type='date'][name='"+indexInArray+"']").val(valueOfElement);
                    $("input[type='hidden'][name='"+indexInArray+"']").val(valueOfElement);
                    if(indexInArray =='valuations'){
                        
                        $.each(valueOfElement, function(index, value) {
                            $("#property_id").find('option[value="'+value['id']+'"]').attr("selected",true);
                        });
                    }
                    else{
                        $("select[name="+indexInArray+"]").find('option[value="'+valueOfElement+'"]').attr("selected",true);
                    }
                });
                $('.mselect').trigger('chosen:updated');
                //
            }
        });
       
        $(".course_action").text('Update');
        if($(ref).hasClass('pull-bs-canvas-right')){
            $('.bs-canvas-right').addClass('mr-0');
        }
        var url = "{{ route('assignval.update', ":id") }}";
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
        var url = "{{ route('assignval.store') }}";
        $("form").attr('action',url);
        $("input[name='_method']").val('post');

        return false;
    }
    function deleteRecord(id,ref){
        if(confirm("Are you sure you want to delete this?")){
            var url = "{{ route('assignval.destroy', ":id") }}";
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
    }
    function viewCourse(id, ref){
        
        $('.bs-canvas-left').addClass('ml-0');


    }
  </script>

@endsection


