@extends('admin.base.base')
@section('css')
@parent
{{-- <link rel="stylesheet" href="{{asset('admin_style/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('admin_style/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('admin_style/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('dist/datetime/jquery.datetimepicker.min.css')}}" />
<link rel="stylesheet" href="{{ asset('dist/css/yearpicker.css') }}"> --}}
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
</style>
@endsection

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3><a class="btn btn-app pull-bs-canvas-right" onclick="addThemes(this)">
                        <i class="fas fa-plus"></i> Add
                    </a></h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                    <li class="breadcrumb-item active">Topics</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
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
                                        <th>Office Code</th>
                                        <th>Roles</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <td>{{$user->name }}</td>
                                        <td>{{$user->office->code??'' }}</td>
                                         <td>
                                            @foreach( $user->roles as $role )
                                            {{  $role->name }}
                                            @endforeach
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                <label class="btn btn-secondary">
                                                    <input type="radio" name="options"
                                                        onclick="editSkyThemes({{ $user->id }}, this)"
                                                        autocomplete="off">
                                                    <i class="far fa-edit"></i>
                                                </label>
                                                <label class="btn btn-secondary">
                                                    <input type="radio" name="options"  onclick="deleteUser({{ $user->id }}, this)"
                                                        autocomplete="off"> <i class="fas fa-trash"></i>
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
            <button type="button" class="bs-canvas-close float-left close" aria-label="Close"><span aria-hidden="true"
                    class="text-light">&times;</span></button>
            <h4 class="d-inline-block text-light mb-0 float-right"><span class="topic_action">Add/Update</span> Admin User
            </h4>
        </header>
        <div class="bs-canvas-content px-3 py-1">
            <form action="{{route('role.store')}}" enctype="multipart/form-data" method="post">
                @csrf
                @if(old('id')!='')
                @method('put')
                @else
                @method('post')
                @endif
                <div class="form-group">
                    <label for="exampleInputName">User</label>

                    <input type="text" name="name" class="form-control" id="Name" value="{{ old('name')}}"
                        placeholder="">
                    <span style="color:red">@error('name'){{ $message }}@enderror</span>
                </div>
                <div class="form-group">
                    <label for="exampleInputName">Email</label>

                    <input type="text" name="email" class="form-control" id="email" value="{{ old('email')}}"
                        placeholder="">
                    <span style="color:red">@error('email'){{ $message }}@enderror</span>
                </div>
                <div class="form-group">
                    <label for="office_id">Office Name</label>
                    <select name="office_id" id="office_id" class="form-select form-select-lg mb-3 form-control objective"  aria-label=".form-select-lg example" >
                        <option value="">Select Office Name</option>
                        @foreach($offices as $item)
                        <option value="{{ $item->id }}">{{$item->name}}</option>
                        @endforeach
                    </select>
                    <span style="color:red">@error('office_id'){{ $message }}@enderror</span>
                </div>
                <div class="form-group">
                    <label for="exampleInputName">Password</label>

                    <input type="text" name="password" class="form-control" id="password" value="{{ old('password')}}"
                        placeholder="">
                    <span style="color:red">@error('password'){{ $message }}@enderror</span>
                </div>

                <div class="form-group">
                    <label for="exampleInputName">Roles</label>
                    <select name="role[]" id="role" class="form-select form-select-lg mb-3 form-control objective"  aria-label=".form-select-lg example" multiple> >
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{$role->name}}</option>
                        @endforeach
                    </select>
                    <span style="color:red">@error('role'){{ $message }}@enderror</span>
                </div>
                <div class="form-group">
                    <label for="exampleInputName">Permission</label>
                    <select name="permission[]" id="permission" class="form-select form-select-lg mb-3 form-control objective"  aria-label=".form-select-lg example" multiple> >
                        @foreach($permissions as $permission)
                        <option value={{$permission->id}}>{{$permission->name}}</option>
                        @endforeach
                    </select>
                    <span style="color:red">@error('permission'){{ $message }}@enderror</span>
                </div>
                <button type="submit" class="btn btn-dark"><span class="topic_action">Add</span></button>
            </form>
        </div>
    </div>
    @endsection
    @section('script')
    @parent
    <!-- DataTables  & Plugins -->
     {{-- <script src="{{ asset('dist/js/jquery.slim.min.js') }}"></script> --}}
    <script src="{{asset('admin_style/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    {{-- <script src="{{asset('admin_style/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script> --}}
    <script src="{{asset('admin_style/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('admin_style/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('admin_style/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('admin_style/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
    {{-- <script src="{{asset('admin_style/plugins/jszip/jszip.min.js')}}"></script>
    <script src="{{asset('admin_style/plugins/pdfmake/pdfmake.min.js')}}"></script>
    <script src="{{asset('admin_style/plugins/pdfmake/vfs_fonts.js')}}"></script> --}}
    <script src="{{asset('admin_style/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
    {{-- <script src="{{asset('admin_style/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{asset('admin_style/plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script> --}}
    <script src="{{asset('mselect/chosen.jquery.min.js') }}"></script>
    {{-- <script src="{{ asset('dist/js/yearpicker.js') }}"></script> --}}
    <script>
        $(function () {
            $("#example1").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
            $('#role').chosen();
            $('#permission').chosen();
        });

        $(document).on('click', '.bs-canvas-close, .bs-canvas-overlay', function(){
            var elm = $(this).hasClass('bs-canvas-close') ? $(this).closest('.bs-canvas') : $('.bs-canvas');
            elm.removeClass('mr-0 ml-0');
            $('.bs-canvas-overlay').remove();
            return false;
        });
        function editSkyThemes(id, ref){
            var url = "{{ route('adminuser.edit', ":id") }}";
            $("#role").find('option').attr("selected",false);
            $("#permission").find('option').attr("selected",false);
            url = url.replace(':id', id);
            $.ajax({
                type: "get",
                url: url,
                data: "data",
                dataType: "JSON",
                success: function (response) {
                    // console.log(response.role);
                    $.each(response.user, function (indexInArray, valueOfElement) {
                        $("input[type='text'][name='"+indexInArray+"']").val(valueOfElement);
                        if(indexInArray =='office_id'){
                            $("select[name="+indexInArray+"]").find('option[value="'+valueOfElement+'"]').attr("selected",true);
                        }
                        if(indexInArray =='roles'){
                            $.each(valueOfElement, function(index, value) {
                                $("#role").find('option[value="'+value['id']+'"]').attr("selected",true);
                            });
                        }
                        if(indexInArray =='permissions'){
                            $.each(valueOfElement, function(index, value) {
                                $("#permission").find('option[value="'+value['id']+'"]').attr("selected",true);
                            });
                        }

                    });
                    $('.bs-canvas-right').addClass('mr-0');
                    $('#role').trigger('chosen:updated');
                    $('#permission').trigger('chosen:updated');

                }
            });
            $(".topic_action").text('Update');
            if($(ref).hasClass('pull-bs-canvas-right')){
                $('.bs-canvas-right').addClass('mr-0');
            }
            var url = "{{ route('adminuser.update',":id") }}";
            url = url.replace(':id', id);
            $("form").attr('action',url);
            $("input[name='_method']").val('put');
            return false;
            // $(".summernote").summernote('destroy')
        }
        function addThemes(ref){
            $("input[type='text']").val('');
            $("#role").find('option').attr("selected",false);

            if($(ref).hasClass('pull-bs-canvas-right')){
                $('.bs-canvas-right').addClass('mr-0');
                $(".topic_action").text('Add');
            }
            var url = "{{ route('adminuser.store') }}";
            $("form").attr('action',url);
            $("input[name='_method']").val('post');
            $('#role').trigger('chosen:updated');
            return false;
        }
        function deleteUser(id,ref){
            var url = "{{ route('adminuser.destroy', ":id") }}";
            url = url.replace(':id', id);
            $.ajax({
                type: "delete",
                url: url,
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                dataType: "JSON",
                success: function (response) {
                alert("this field delete");

                }
            });
        }
    </script>






    @endsection
