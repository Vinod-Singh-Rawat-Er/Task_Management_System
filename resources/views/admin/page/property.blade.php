@extends('admin.base.base')
@section('css')
@parent
<link rel="stylesheet" href="{{asset('admin_style/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('admin_style/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('admin_style/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" href=" {{asset('mselect/chosen.min.css')}}" />
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    
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
                <li class="breadcrumb-item active">Property</li>
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
                <h3 class="card-title">{{$client->name ?? ''}}</h3>
                </div> --}}
                <!-- /.card-header -->
                <div class="card-body">
                <table id="example1" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Property Name</th>
                            <th> Owner Name</th>
                            <th>Property Type</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Address</th>
                            <th>Date Of Entry</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach( $properties as $crse)
                    <tr>
                        <td>{{ $crse->property_name }}</td>
                        <td>{{ $crse->owner->name ??'' }}</td>
                        <td>{{ $crse->type }}</td>
                        <td>{{ $crse->latitude }}</td>
                        <td>{{ $crse->longitude }}</td>
                        <td>{{ $crse->address }}</td>
                        <td>{{ date('j F, Y', strtotime( $crse->created_at )) }}</td>
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
                                <input type="radio" name="options" onclick="deleteRecord({{ $crse->id }}, this)" autocomplete="off"> <i class="fas fa-trash"></i>
                                </label>
                                @endcan
                                {{-- @permission('property-update')
                                <label class="btn btn-secondary">
                                <input type="radio" name="options" onclick="deleteRecord({{ $crse->id }}, this)" autocomplete="off"> <i class="fas fa-trash"></i>
                                </label>
                                @endpermission --}}
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
            <h4 class="d-inline-block text-light mb-0 float-right"><span class="course_action">Update</span> Property</h4>
        </header>
        <div class="bs-canvas-content px-3 py-1">
           

                <form action="{{ old('id') ? route('property.update', old('id') ) :  route('property.store') }}" enctype="multipart/form-data" method="post">
                    @csrf
                    @if(old('id')!='')
                    @method('put')
                    @else
                    @method('post')
                    @endif
                   
                    <div class="form-group">
                        <label for="exampleInputName">Property Name</label>
                        <input type="text" name="property_name" class="form-control" id="property_name" value="{{ old('property_name')}}"
                            placeholder="">
                        <span style="color:red">@error('property_name'){{ $message }}@enderror</span>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="id" value="" >
                        <label for="exampleInputName">Owner</label>
                        <select name="owner_id" id="owner_id" class="form-control chosen-select form-select-lg mb-3">
                            <option value="">Select Owner</option>
                            @foreach($owners as $owner)
                                <option value="{{$owner->id}}">{{$owner->owner_id}}|{{$owner->name}}|{{$owner->father_name}}|{{$owner->city}}</option>
                            @endforeach
                        </select>
        
                        <!-- <input type="text" name="name" class="form-control" id="name" value="{{ old('name')}}"
                            placeholder=""> -->
                        <span style="color:red">@error('owner_id'){{ $message }}@enderror</span>
                    </div>
                                       
                    <div class="form-group">
                        <label for="type">Property Type</label>
                        <select class="form-control " id="type" name="type" aria-label=".form-select-lg example" required >
                            <option  value="">Select Type</option>
                            <option  value="Land Only" {{ old('type') == 'Land Only' ? 'selected' : '' }}>Land Only</option>
                            <option  value="Building Only" {{ old('type') == 1 ? 'Building Only': '' }}>Building</option>
                            <option  value="Flat Only" {{ old('type') == 1 ? 'Flat Only': '' }}>Flat Only</option>
                            <option  value="Land & Building" {{ old('type') == 1 ? 'Land & Building': '' }}>Land & Building</option>
                            <option  value="Land  with Foundation" {{ old('type') == 1 ? 'Land  with Foundation': '' }}>Land  with Foundation</option>
                            
                        </select>
                        <span style="color:red">@error('type'){{ $message }}@enderror</span>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputName">Client Name</label>
                        <select class="form-select form-select-lg mb-3 form-control " name="branch_waise[]" id="branch_waise" aria-label=".form-select-lg example"  >
                            <option  value="" disaled>Select Client</option>
                            @foreach($clients as $client)
                                <option value="{{$client->id}}">{{$client->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputName">Branch Name</label>
                        <select name="client[]" id="client" class="form-select form-select-lg mb-3 form-control "  aria-label=".form-select-lg example"  >
                            @foreach($clients as $client)
                                <optgroup label="{{$client->name}}">
                                    @foreach($client->detail as $val)
                                        <option value="{{ $val->id}}">{{ $val->branch_office}}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        <span style="color:red">@error('client'){{ $message }}@enderror</span>
                    </div>
                   
                    {{-- <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputName">Latitude</label>
                
                                <input type="text" name="latitude" class="form-control" id="latitude" value="{{ old('latitude')}}"
                                    placeholder="">
                                <span style="color:red">@error('latitude'){{ $message }}@enderror</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputName">Longitude</label>
                
                                <input type="text" name="longitude" class="form-control" id="longitude" value="{{ old('longitude')}}"
                                    placeholder="">
                                <span style="color:red">@error('longitude'){{ $message }}@enderror</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div id="map" style="width:100%;height:150px;"></div>
                        </div>
                    </div> --}}
                   
                    <div class="form-group">
                        <label for="exampleInputName">Property Address</label>
                        <textarea name="address" class="form-control" id="address">{{ old('address')}}</textarea>
                        <span style="color:red">@error('address'){{ $message }}@enderror</span>
                    </div>
                    <div class="form-group">
                        <label for="state">state</label>
        
                        <select name="state" id="state" class="form-control chosen-select form-select-lg mb-3" required>
                            <option value="">Select State</option>
                            @foreach($states as $state)
                                <option value="{{$state->name}}">{{$state->name}}</option>
                            @endforeach
                        </select>
                        <span style="color:red">@error('state'){{ $message }}@enderror</span>
                    </div>
                    <div class="form-group">
                        <label for="state">District</label>
        
                        <select name="distt" id="distt" class="form-control chosen-select form-select-lg mb-3" required>
                            <option value="">Select District</option>
                        </select>
                        <span style="color:red">@error('distt'){{ $message }}@enderror</span>
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
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script>
     
    var _URL = window.URL || window.webkitURL;
    $(document).ready(function () {
        $("#example1").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#owner_id').chosen();
        $('#client').chosen();
        $('#branch_waise').chosen();
        $("#branch_waise").change(function(){
            id = $(this).val();
            var url = "{{ route('client.branch', ":id") }}";
            url = url.replace(':id', id);

            $.ajax({
                type: "get",
                url: url,
                data: "data",
                dataType: "JSON",
                success: function (response) {
                    $("#client").empty();
                    var html_op ='';
                    $.each(response.client, function (indexInArray, valueOfElement) {
                        html_op =html_op + '<optgroup label="'+valueOfElement['name']+'">';
                        $.each(valueOfElement['detail'], function(index, value) {
                            html_op = html_op + '<option value="'+value['id']+'">'+value['branch_office']+'</option>';
                        });
                        
                        html_op = html_op + '</optgroup > ';
                    });
                    $("#client").append(html_op);
                    $('#client').trigger('chosen:updated');
                    //
                }
            });
        });
        $("#state").change(function(){
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
        var url = "{{ route('property.edit', ":id") }}";
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
                    $("textarea[name="+indexInArray+"]").val(valueOfElement);
                    $("select[name="+indexInArray+"]").find('option[value="'+valueOfElement+'"]').attr("selected",true);
                    if(indexInArray =='clientdetails'){
                        
                        $.each(valueOfElement, function(index, value) {
                            $("#client").find('option[value="'+value['id']+'"]').attr("selected",true);
                            $("#branch_waise").find('option[value="'+value['client_id']+'"]').attr("selected",true);
                        });
                    }
                    if(indexInArray =='distt'){
                        $("#distt").empty();
                        var html_op = html_op + '<option value="'+valueOfElement+'">'+valueOfElement+'</option>';
                        $("#distt").append(html_op);
                    }

                });
                $('#owner_id').trigger('chosen:updated');
                $('#client').trigger('chosen:updated');
                $('#branch_waise').trigger('chosen:updated');
                //
            }
        });
        $(".course_action").text('Update');
        if($(ref).hasClass('pull-bs-canvas-right')){
            $('.bs-canvas-right').addClass('mr-0');
        }
        var url = "{{ route('property.update', ":id") }}";
        url = url.replace(':id', id);
        $("form").attr('action',url);
        $("input[name='_method']").val('put');
        return false;
        // $(".summernote").summernote('destroy')
    }
    function addCourse(ref){
        $("input[type='text']").val('');
        $("input[name='id']").val('');
        $("select").find('option').prop("selected", false);
        if($(ref).hasClass('pull-bs-canvas-right')){
            $('.bs-canvas-right').addClass('mr-0');
        }
        var url = "{{ route('property.store') }}";
        $("form").attr('action',url);
        $("input[name='_method']").val('post');
        $('#owner_id').trigger('chosen:updated');
        $('#client').trigger('chosen:updated');
        $('#branch_waise').trigger('chosen:updated');
        return false;
    }
    function deleteRecord(id,ref){
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
    function viewCourse(id, ref){
        
        $('.bs-canvas-left').addClass('ml-0');


    }
    
    //Map Start
    // let marker = null;
    // var mapOptions = {
    //     center: [24.385044, 77.486671],
    //     zoom: 4
    // }
 
    // // Creating a map object
    // var map = new L.map('map', mapOptions);
 
    // // Creating a Layer object
    // var layer = new L.TileLayer("http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png");
 
    // // Adding layer to the map
    // map.addLayer(layer);
    // // container for address search results
    // const addressSearchResults = new L.LayerGroup().addTo(map);
    // /*** Geocoder ***/
    // // OSM Geocoder
    // const osmGeocoder = new L.Control.geocoder({
    //     collapsed: false,
    //     position: 'topright',
    //     text: 'Address Search',
    //     placeholder: 'Enter street address',
    // defaultMarkGeocode: false
    // }).addTo(map); 
    // // handle geocoding result event
    // osmGeocoder.on('markgeocode', e => {
    //     // to review result object
    //     console.log(e);
    //     // coordinates for result
    //     const coords = [e.geocode.center.lat, e.geocode.center.lng];
    //     // center map on result
    //     map.setView(coords, 16);
    //     // popup for location
    //     // todo: use custom icon
    //     marker = L.marker(coords).addTo(map);
    //     $("#latitude").val(e.geocode.center.lat);
    //     $("#longitude").val(e.geocode.center.lng);
    //     marker.bindPopup(e.geocode.name).openPopup();
    // });
    // map.on('click', (event)=> {    
    //     if(marker !== null){
    //         map.removeLayer(marker);    
    //     }
    //     marker = L.marker([event.latlng.lat , event.latlng.lng]).addTo(map);
    //     $("#latitude").val(event.latlng.lat);
    //     $("#longitude").val(event.latlng.lng);
    // });

    // // Map End
  </script>

@endsection