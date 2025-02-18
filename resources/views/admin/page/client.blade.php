@extends('admin.base.base')
@section('css')
@parent
    <link rel="stylesheet" href="{{asset('admin_style/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('admin_style/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('admin_style/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
    
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
                @can('create',\App\Models\Client::class)
                <h3>
                    <a class="pull-bs-canvas-right btn btn-app" onclick="addCourse(this)">
                    <i class="fas fa-plus"></i> Add
                </a></h3>
                @endcan
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                <li class="breadcrumb-item active">Course</li>
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
                {{-- <div class="card-header">
                <h3 class="card-title">DataTable with minimal features & hover style</h3>
                </div> --}}
                <!-- /.card-header -->
                <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Clide Code</th>
                            <th>abbr</th>
                            <th>Branch</th>
                            <th>Remark</th>
                            <th>Action </th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach( $clients as $crse)
                        <tr>
                        <td>{{ $crse->name }}</td>
                        <td>{{ $crse->panel_type == 0 ? 'Both' : ($crse->panel_type == 1 ? 'Architect' : ($crse->panel_type == 2 ? 'None' :'') )}}</td>
                        <td>{{ $crse->code }}</td>
                        <td>{{ $crse->abbr }}</td>
                        {{-- <td>{{ $crse->remark }}</td> --}}
                        <td>
                            <a class="btn btn-app" href="{{ route('clientdetails', ['id' => $crse->id]) }}">
                                <span class="badge bg-teal">{{count($crse->detail->toArray())}}</span>
                                <i class="fas fa-inbox"></i> Branch
                            </a>
                        </td>
                        <td>{{ $crse->remark }}</td>

                        <td>
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-secondary ">
                                <input type="radio" name="options" autocomplete="off" onclick="viewCourse({{ $crse->id }}, this)" > <i class="far fa-eye"></i>
                                </label>
                                @can('update',$crse)
                                <label class="btn btn-secondary">
                                 <input type="radio" name="options" class="pull-bs-canvas-right" id="option_a2" autocomplete="off" onclick="editCourse({{ $crse->id }}, this)"> <i class="far fa-edit"></i>
                                </label>
                                @endcan
                                @can('delete',$crse)
                                <label class="btn btn-secondary">
                                    <input type="radio" name="options"  onclick="deleteRecord({{ $crse->id }}, this)" autocomplete="off"> <i class="fas fa-trash"></i>
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
    <div class="bs-canvas bs-canvas-left position-fixed bg-light h-100 ">
        <header class="bs-canvas-header p-3 bg-dark overflow-auto">
            <button type="button" class="bs-canvas-close float-right close" aria-label="Close"><span aria-hidden="true"
                    class="text-light">&times;</span></button>
            <h4 class="d-inline-block text-light mb-0 float-left"><span class="course_action">View</span> Clients</h4>
        </header>
        <div class="bs-canvas-content px-3 py-2">
            <dl>
                <dt>Name</dt>
                <dd id="name"></dd>
                <dt>Title</dt>
                <dd id="abstract_title"></dd>
                <dt>Type</dt>
                <dd id="typeofsubmission"></dd>
                <dt>Domain</dt>
                <dd id="submissiondomain"></dd>
                <dt>Abstract</dt>
                <dd id="abstract"></dd>

                <dt>About</dt>
                <dd id="about_abstract"></dd>
                {{-- <dt class="col-sm-4">file</dt>
                <dd id="file"> </dd> --}}

            </dl>

        </div>

    </div>
    <div class="bs-canvas bs-canvas-right position-fixed bg-light h-100 {{ old() ? ' mr-0': '' }}">
        <header class="bs-canvas-header p-3 bg-dark overflow-auto">
            <button type="button" class="bs-canvas-close float-left close" aria-label="Close"><span aria-hidden="true" class="text-light">&times;</span></button>
            <h4 class="d-inline-block text-light mb-0 float-right"><span class="course_action">{{old('id')!= '' ? 'Update' : 'Add'}}</span> Client</h4>
        </header>
        <div class="bs-canvas-content px-3 py-1">
           

                <form action="{{ route('client.store') }}" enctype="multipart/form-data" method="post">
                    @csrf
                    @if(old('id')!='')
                    @method('put')
                    @else
                    @method('post')
                    @endif
                    <div class="form-group">
                        <input type="hidden" name="id" value="{{old('id')}}" >
                        <label for="exampleInputName">Name of the Client</label>
        
                        <input type="text" name="name" class="form-control" id="Name" value="{{ old('name')}}"
                            placeholder="">
                        <span style="color:red">@error('name'){{ $message }}@enderror</span>
                    </div>
                    <div class="form-group">
                        <label for="panel_type">Panel Type</label>
                        <select class="form-control " id="panel_type" name="panel_type" aria-label=".form-select-lg example" required >
                            <option  value="">Select Panel</option>
                            <option  value="1" {{ old('panel_type') == 1 ? 'selected': '' }}>Architect</option>
                            <option  value="2" {{ old('panel_type') == 2 ? 'selected': '' }}>Valuer</option>
                            <option  value="0" {{ old('panel_type') == 0 ? 'selected': '' }}>Both</option>
                            <option  value="-1" {{ old('panel_type') == 0 ? 'selected': '' }}>None</option>
                        </select>
                        <span style="color:red">@error('panel_type'){{ $message }}@enderror</span>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputName">Client Code</label>
        
                        <input type="text" name="code" class="form-control" id="code" value="{{ old('code')}}"
                            placeholder="">
                        <span style="color:red">@error('code'){{ $message }}@enderror</span>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputName">Abbreviation</label>
        
                        <input type="text" name="abbr" class="form-control" id="abbr" value="{{ old('abbr')}}"
                            placeholder="">
                        <span style="color:red">@error('abbr'){{ $message }}@enderror</span>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputName">Remark</label>
        
                        <input type="text" name="remark" class="form-control" id="remark" value="{{ old('remark')}}"
                            placeholder="">
                        <span style="color:red">@error('remark'){{ $message }}@enderror</span>
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
<script src="{{asset('admin_style/plugins/inputmask/jquery.inputmask.min.js')}}"></script>

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
    // $(document).on('click', '.pull-bs-canvas-right, .pull-bs-canvas-left', function(){
	// 	$('body').prepend('<div class="bs-canvas-overlay bg-dark position-fixed w-100 h-100"></div>');
	// 	if($(this).hasClass('pull-bs-canvas-right')){
    //         $('.bs-canvas-right').addClass('mr-0');
    //     }

	// 	else
	// 		$('.bs-canvas-left').addClass('ml-0');
	// 	return false;
	// });

	$(document).on('click', '.bs-canvas-close, .bs-canvas-overlay', function(){
		var elm = $(this).hasClass('bs-canvas-close') ? $(this).closest('.bs-canvas') : $('.bs-canvas');
		elm.removeClass('mr-0 ml-0');
		$('.bs-canvas-overlay').remove();
		return false;
	});
    function editCourse(id, ref){
        var url = "{{ route('client.edit', ":id") }}";
        url = url.replace(':id', id);

        $.ajax({
            type: "get",
            url: url,
            data: "data",
            dataType: "JSON",
            success: function (response) {
                $.each(response.client, function (indexInArray, valueOfElement) {
                    $("input[type='text'][name='"+indexInArray+"']").val(valueOfElement);
                    $("input[type='hidden'][name='"+indexInArray+"']").val(valueOfElement);
                    $("select[name="+indexInArray+"]").find('option[value="'+valueOfElement+'"]').attr("selected",true);
                });
               
                //
            }
        });
        $(".course_action").text('Update');
        if($(ref).hasClass('pull-bs-canvas-right')){
            $('.bs-canvas-right').addClass('mr-0');
        }
        var url = "{{ route('client.update', ":id") }}";
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
        var url = "{{ route('client.store') }}";
        $("form").attr('action',url);
        $("input[name='_method']").val('post');

        return false;
    }
    function deleteRecord(id,ref){
        if(confirm("Are you sure you want to delete this?")){
            var url = "{{ route('client.destroy', ":id") }}";
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


