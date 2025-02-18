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
                <li class="breadcrumb-item active">Owners</li>
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
                <table id="example2" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Name of the city</th>
                            <th>Link</th>
                            <th>Date of Revise</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach( $circle_rate as $crse)
                        <tr>
                        <td>{{ $crse->city_name }}</td>
                        <td><a href="{{ $crse->link }}" target="_blank">{{ $crse->link }}</a></td>
                        <td>{{ date('j F, Y', strtotime( $crse->date_of_revise )) }}</td>
                        

                        <td>
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-secondary ">
                                <input type="radio" name="options" autocomplete="off" onclick="viewCourse({{ $crse->id }}, this)" > <i class="far fa-eye"></i>
                                </label>
                                <label class="btn btn-secondary">
                                <input type="radio" name="options" class="pull-bs-canvas-right" id="option_a2" autocomplete="off" onclick="editCourse({{ $crse->id }}, this)"> <i class="far fa-edit"></i>
                                </label>
                                <label class="btn btn-secondary">
                                    <input type="radio" name="options"  onclick="deleteRecord({{ $crse->id }}, this)" autocomplete="off"> <i class="fas fa-trash"></i>
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
    <div class="bs-canvas bs-canvas-left position-fixed bg-light h-100 ">
        <header class="bs-canvas-header p-3 bg-dark overflow-auto">
            <button type="button" class="bs-canvas-close float-right close" aria-label="Close"><span aria-hidden="true"
                    class="text-light">&times;</span></button>
            <h4 class="d-inline-block text-light mb-0 float-left"><span class="course_action">View</span> Owners</h4>
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
            <h4 class="d-inline-block text-light mb-0 float-right"><span class="course_action">{{old('id')!= '' ? 'Update' : 'Add'}}</span> Office</h4>
        </header>
        <div class="bs-canvas-content px-3 py-1">
           

                <form action="{{ route('circlerate.store') }}" enctype="multipart/form-data" method="post">
                    @csrf
                    @if(old('id')!='')
                    @method('put')
                    @else
                    @method('post')
                    @endif
                    <div class="form-group">
                        <label for="state_name">state_name</label>
        
                        <select name="state_name" id="state_name" class="form-control chosen-select form-select-lg mb-3" required>
                            <option value="">Select State</option>
                            @foreach($states as $state)
                                <option value="{{$state->name}}">{{$state->name}}</option>
                            @endforeach
                        </select>
                        <span style="color:red">@error('state_name'){{ $message }}@enderror</span>
                    </div>
                    <div class="form-group">
                        <label for="distt">District</label>
        
                        <select name="distt" id="distt" class="form-control chosen-select form-select-lg mb-3" required>
                            <option value="">Select District</option>
                        </select>
                        <span style="color:red">@error('distt'){{ $message }}@enderror</span>
                    </div>
                    <div class="form-group">
                        <label for="city_name">city/Village</label>
        
                        <input type="text" name="city_name" class="form-control" id="city_name" value="{{ old('city_name')}}"
                            placeholder="" required>
                        <span style="color:red">@error('city_name'){{ $message }}@enderror</span>
                    </div>
                    <div class="form-group">
                        <label for="link">Link</label>
        
                        <input type="text" name="link" class="form-control" id="link" value="{{ old('link')}}"
                            placeholder="" required>
                        <span style="color:red">@error('link'){{ $message }}@enderror</span>
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
        // $('#state').chosen();
       
        $('.isExist').on('blur', function () {
            var itemName = $(this).val();
            var field_name = $(this).attr('name');
            var id = $("#owner_id").val();
            var ref = $(this);
            // AJAX request to check if the item already exists
            $.ajax({
                url: '/check-item',
                type: 'GET',
                data: { 
                    table_name: 'owners',
                    'field_name': field_name,
                    'value':itemName,
                    'id':id
                },
                success: function (response) {
                    if(response.exists){
                        alert("This Value already exist for this owner "+ response.owner.name);
                        $(ref).val('');
                    }
                }
            });
        });
        $("#state_name").change(function(){
            id = $(this).val();
            var url = "{{ route('districts', ":id") }}";
            url = url.replace(':id', id);

            $.ajax({
                type: "get",
                url: url,
                data: "data",
                dataType: "JSON",
                success: function (response) {
                    $("#distt").empty();
                    var html_op ='';
                    $.each(response.districts, function (indexInArray, valueOfElement) {
                        html_op = html_op + '<option value="'+valueOfElement['district']+'">'+valueOfElement['district']+'</option>';
                    });
                    $("#distt").append(html_op);
                    // $('#client').trigger('chosen:updated');
                    //
                }
            });
        })
    });
   

	$(document).on('click', '.bs-canvas-close, .bs-canvas-overlay', function(){
		var elm = $(this).hasClass('bs-canvas-close') ? $(this).closest('.bs-canvas') : $('.bs-canvas');
		elm.removeClass('mr-0 ml-0');
		$('.bs-canvas-overlay').remove();
		return false;
	});
    function editCourse(id, ref){
        var url = "{{ route('circlerate.edit', ":id") }}";
        url = url.replace(':id', id);

        $.ajax({
            type: "get",
            url: url,
            data: "data",
            dataType: "JSON",
            success: function (response) {
                $.each(response.circle_rate, function (indexInArray, valueOfElement) {
                    $("input[type='text'][name='"+indexInArray+"']").val(valueOfElement);
                    $("input[type='hidden'][name='"+indexInArray+"']").val(valueOfElement);
                    $("select[name="+indexInArray+"]").find('option[value="'+valueOfElement+'"]').attr("selected",true);
                    if(indexInArray =='distt'){
                        $("#distt").empty();
                        var html_op = html_op + '<option value="'+valueOfElement+'">'+valueOfElement+'</option>';
                        $("#distt").append(html_op);
                    }
                });
                $('#state').trigger('chosen:updated');
                //
            }
        });
        
        $(".course_action").text('Update');
        if($(ref).hasClass('pull-bs-canvas-right')){
            $('.bs-canvas-right').addClass('mr-0');
        }
        var url = "{{ route('circlerate.update', ":id") }}";
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
        var url = "{{ route('circlerate.store') }}";
        $("form").attr('action',url);
        $("input[name='_method']").val('post');
        $("select").find('option').prop("selected", false);
        return false;
    }
    function deleteRecord(id,ref){
        if(confirm("Are you sure you want to delete this?")){
            var url = "{{ route('owner.destroy', ":id") }}";
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


