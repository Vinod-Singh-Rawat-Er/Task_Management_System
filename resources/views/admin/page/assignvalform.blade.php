@extends('admin.base.base')
@section('css')
@parent
<link rel="stylesheet" href="{{asset('admin_style/plugins/bs-stepper/css/bs-stepper.min.css')}}">
<link rel="stylesheet" href="{{asset('mselect/chosen.min.css')}}" />
<link rel="stylesheet" href="{{asset('ui/jquery-ui.css')}}" />
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />

<style>
    .chosen-container{
        width:100% !important;
    }
    .select2{
        width: 100%;
    }
    .btn.active{
        background-color: #007bff !important;
    }
    
</style>

@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            <div class="col-sm-6">
               
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                <li class="breadcrumb-item active">Assign Valuation Form</li>
                </ol>
            </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="bs-stepper">
                <div class="bs-stepper-header" role="tablist">
                    <!-- your steps here -->
                    <div class="step" data-target="#logins-part">
                        <button type="button" class="step-trigger" role="tab" aria-controls="logins-part" id="logins-part-trigger">
                                <span class="bs-stepper-circle">1</span>
                                <span class="bs-stepper-label">Basic Information</span>
                        </button>
                    </div>
                    <div class="line"></div>
                    <div class="step" data-target="#information-part">
                        <button type="button" class="step-trigger" role="tab" aria-controls="information-part" id="information-part-trigger">
                        <span class="bs-stepper-circle">2</span>
                        <span class="bs-stepper-label">Land Information</span>
                        </button>
                    </div>
                   
                    @if($assign->property->type =='Land Only' )
                    <div class="line"></div>
                    <div class="step" data-target="#information-part3">
                        <button type="button" class="step-trigger" role="tab" aria-controls="information-part3" id="information-part-trigger3">
                            <span class="bs-stepper-circle">3</span>
                            <span class="bs-stepper-label">Documents</span>
                        </button>
                    </div>
                    @elseif($assign->property->type !='Flat Only')
                    <div class="line"></div>
                    <div class="step" data-target="#information-part10">
                        <button type="button" class="step-trigger" role="tab" aria-controls="information-part10" id="information-part-trigger2">
                            <span class="bs-stepper-circle">3</span>
                            <span class="bs-stepper-label">Building Informaton</span>
                        </button>
                    </div>
                    @else
                    <div class="line"></div>
                    <div class="step" data-target="#information-part11">
                        <button type="button" class="step-trigger" role="tab" aria-controls="information-part11" id="information-part-trigger4">
                            <span class="bs-stepper-circle">3</span>
                            <span class="bs-stepper-label">Apartment/Flat</span>
                        </button>
                    </div>
                    @endif
                    @if($assign->property->type !='Land Only')
                    <div class="line"></div>
                    <div class="step" data-target="#information-part3">
                        <button type="button" class="step-trigger" role="tab" aria-controls="information-part3" id="information-part-trigger3">
                        <span class="bs-stepper-circle">   4 </span>
                        <span class="bs-stepper-label">Documents</span>
                        </button>
                    </div>
                    @endif
                </div>
                <div class="bs-stepper-content">
                    <!-- your steps content here -->
                    <div id="logins-part" class="content" role="tabpanel" aria-labelledby="logins-part-trigger">
                        @include('admin.valuation-forms.step1')
                    </div>
                    <div id="information-part" class="content" role="tabpanel" aria-labelledby="information-part-trigger">
                        @include('admin.valuation-forms.step2')
                    </div>
                    @if($assign->property->type =='Flat Only' )
                    <div id="information-part11" class="content" role="tabpanel" aria-labelledby="information-part-trigger4">
                        @include('admin.valuation-forms.step4')
                    </div>
                    @else
                    <div id="information-part10" class="content" role="tabpanel" aria-labelledby="information-part-trigger2">
                        @include('admin.valuation-forms.step3')
                    </div>
                    @endif
                    <div id="information-part3" class="content" role="tabpanel" aria-labelledby="information-part-trigger3">
                        @include('admin.multi-step-form.step9')
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
        <div class="modal fade" id="modal-lg">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Approved map / plan issuing authority </h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <img src="" class="img-fluid" id="img-modal"/>
            </div>
           
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    </section>
@endsection
@section('script')
@parent
<!-- BS-Stepper -->
<script src="{{asset('admin_style/plugins/bs-stepper/js/bs-stepper.min.js')}}"></script>
<script src="{{asset('mselect/chosen.jquery.min.js') }}"></script>
<script src="{{asset('ui/jquery-ui.js') }}"></script>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script>
    
   
    // document.addEventListener('DOMContentLoaded', function () {
    //     stepper = new Stepper(document.querySelector('.bs-stepper'))
    // });
    var stepper = new Stepper(document.querySelector('.bs-stepper'), {
        linear: false,
        animation: true
      })
    $(document).ready(function() {
        
        switch ('{{$step}}') {
            case 'step2':
                stepper.to(2)
                break;
            case 'step3':
                stepper.to(3)
                break;
            case 'step4':
                stepper.to(4)
                break;
            case 'step5':
                stepper.to(5)
                break;
            case 'step6':
                stepper.to(6)
                break;
            case 'step7':
                stepper.to(7)
                break;
            case 'step8':
                stepper.to(8)
                break;
            case 'step9':
                stepper.to(9)
                break;
            default:
            stepper.to(1)
        }

        $('.select2').chosen();
        $('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });
     
        $('#clinet_name').chosen();
        $("#addOwner").click(function() {
            var newPlinthField = `<tr  data-id="0">
                                <td>
                                    <input type="text" class="form-control"  name="ownerName[]" value="" placeholder="" required>
                                    <br /><span style="color:red">@error('ownerName'){{ $message }}@enderror</span>
                                </td>
                                <td>
                                    <input type="text" class="form-control"  name="owner_father[]" value="" placeholder="" required>
                                    <br /><span style="color:red">@error('owner_father'){{ $message }}@enderror</span>
                                </td>
                                <td>
                                    <input type="text" class="form-control " name="owner_address[]" value="" placeholder="" required>
                                    <br /><span style="color:red">@error('owner_address'){{ $message }}@enderror</span>
                                </td>
                                <td>
                                    <input type="text" class="form-control "  name="owner_share[]" value="" placeholder="" required>
                                    <br /><span style="color:red">@error('owner_share'){{ $message }}@enderror</span>
                                </td>
                               
                                <td>
                                    <button type="button" class="btn btn-info btn-flat removeOwner">Remove!</button>
                                </td>
                            </tr> `;
            $("#owner").append(newPlinthField);
        });
        $("#addFlatFloor").click(function() {
            var newPlinthField = `
            <tr data-id ='0'>
                                <td>
                                    <input type="text" class="form-control" name="floor[]" value="{{ old('floor[]') ?? ''}}" placeholder="" >
                                    <br /><span style="color:red">@error('floor'){{ $message }}@enderror</span>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="super_area[]" value="{{ old('super_area[]')  ?? ''}}" placeholder="" >
                                    <br /><span style="color:red">@error('super_area'){{ $message }}@enderror</span>
                                </td>
                                <td>
                                    <input type="text" class="form-control  " name="carpet_area_of_flat[]" value="{{ old('carpet_area_of_flat[]')  ?? ''}}" placeholder="" >
                                    <br /><span style="color:red">@error('carpet_area_of_flat'){{ $message }}@enderror</span>
                                </td>
                                <td>
                                    <input type="text" class="form-control  allowNumber" name="covered_area_of_flat[]" value="{{ old('covered_area_of_flat[]') ?? ''}}" placeholder="" >
                                    <br /><span style="color:red">@error('covered_area_of_flat'){{ $message }}@enderror</span>
                                </td>
                                <td> 
                                    <select name="year_construction[]" class="form-control" required>
                                        <option value="">Select</option>
                                        @php
                                            $currentYearString = date('Y'); 
                                            $currentYearInt = intval($currentYearString); 
                                        @endphp
                                        @for ($i = $currentYearInt; $i > 1950; $i--)
                                        <option value="{{$i}}" {{ old('year_construction') ?? ($assign->surveyFlat->year_construction ?? '') == $i ? 'selected' : '' }}>{{$i}}</option>
                                
                                        @endfor
                                    </select>
                                    
                                    <br /><span style="color:red">@error('year_construction'){{ $message }}@enderror</span>
                                   
                                </td>
                                <td>
                                    <select name="year_modification[]" class="form-control" required>
                                        <option value="">Select</option>
                                        @php
                                            $currentYearString = date('Y'); 
                                            $currentYearInt = intval($currentYearString); 
                                        @endphp
                                        @for ($i = $currentYearInt; $i > 1950; $i--)
                                        <option value="{{$i}}" {{ old('year_modification') ?? ($assign->surveyFlat->year_modification ?? '') == $i ? ' selected' : '' }}>{{$i}}</option>
                                
                                        @endfor
                                    </select>
                            
                                    <br /><span style="color:red">@error('year_modification'){{ $message }}@enderror</span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-info btn-flat removeFlat">Remove!</button>
                                </td>
                            </tr>
             `;
            $("#flatFloor").append(newPlinthField);
        });
        $("#addPlinth").click(function() {
            
        var count=$('table.plinthTable tbody tr').length;
        var newPlinthField = `<tr id="`+(count+1)+`" data-id="0">
                                <td>
                                    <input type="text" class="form-control" id="floor`+count+`" name="floor[]" value="{{$assign->floor}}" placeholder="" required>
                                    <br /><span style="color:red">@error('floor'){{ $message }}@enderror</span>
                                </td>
                                <td>
                                    <select name="construction_year[]" id="construction_year`+count+`" class="form-control" required>
                                        <option value="">Select</option>
                                        @php
                                            $currentYearString = date('Y'); 
                                            $currentYearInt = intval($currentYearString); 
                                        @endphp
                                        @for ($i = $currentYearInt; $i > 1950; $i--)
                                        <option value={{$i}} {{ old('construction_year[]') ?? ($assign->surevyBulding->construction_year ?? '') == $i ? 'selected' : '' }}>{{$i}}</option>
                                    
                                        @endfor
                                    </select>
                                    <br /><span style="color:red">@error('construction_year'){{ $message }}@enderror</span>
                                </td>
                                <td>
                                    <select name="modification_year[]" id="modification_year`+count+`" class="form-control" required>
                                        <option value="">Select</option>
                                        @php
                                            $currentYearString = date('Y'); 
                                            $currentYearInt = intval($currentYearString); 
                                        @endphp
                                        @for ($i = $currentYearInt; $i > 1950; $i--)
                                        <option value={{$i}} {{ old('modification_year[]') ?? ($assign->surevyBulding->modification_year ?? '') == $i ? 'selected' : '' }}>{{$i}}</option>
                                    
                                        @endfor
                                    </select>
                                    <br /><span style="color:red">@error('modification_year'){{ $message }}@enderror</span>
                                </td>
                                <td>
                                    <input type="text" class="form-control bulding_area allowNumber" name="area_depriciation[]" value="" placeholder="" required>
                                    <br /><span style="color:red">@error('area_depriciation'){{ $message }}@enderror</span>
                                </td>
                                
                                <td>
                                    <button type="button" class="btn btn-info btn-flat removePlinth">Remove!</button>
                                </td>
                            </tr> `;
        $("#plinthFields").append(newPlinthField);
        });

        $("#addPhotographs").click(function() {
        var newOwnerField = '<div class="input-group mt-2 photographs_name"><label for="property_photographs_desc" class="col-sm-2 col-form-label">Description</label>            <input type="text" class="form-control" name="property_photographs_desc[]" value="{{old('property_photographs_desc[]') ?? $image->content ?? ''}}"  required>   <label for="property_photographs" class="col-sm-2 col-form-label">Photograph</label>                  <input type="file" class="col-sm-4 form-control" name="property_photographs[]"  ><span class="input-group-append"><button type="button" class="btn btn-info btn-flat remove_photographs">Remove!</button></span></div>';
        // newOwnerField.find("input").val("");
        $("#photographs").append(newOwnerField);
        });
        //Map Start
        let marker = null;
        var latitude_site = parseFloat({{$assign->latitude_site ==''?20.593683:$assign->latitude_site}});
        var longitude_site = parseFloat({{$assign->longitude_site =='' ? 78.962883 : $assign->longitude_site}});
        var mapOptions = {
            center: [latitude_site, longitude_site],
            zoom: 10
        }
        // Creating a map object
        var map = new L.map('map', mapOptions);
    
        // Creating a Layer object
        var layer = new L.TileLayer("http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png");
    
        // Adding layer to the map
        map.addLayer(layer);
        marker = L.marker([latitude_site , longitude_site]).addTo(map);
        // container for address search results
        const addressSearchResults = new L.LayerGroup().addTo(map);
        /*** Geocoder ***/
        // OSM Geocoder
        const osmGeocoder = new L.Control.geocoder({
            collapsed: false,
            position: 'topright',
            text: 'Address Search',
            placeholder: 'Enter street address',
        defaultMarkGeocode: false
        }).addTo(map); 
        // handle geocoding result event
        osmGeocoder.on('markgeocode', e => {
            // to review result object
            console.log(e);
            // coordinates for result
            const coords = [e.geocode.center.lat, e.geocode.center.lng];
            // center map on result
            map.setView(coords, 16);
            // popup for location
            // todo: use custom icon
            marker = L.marker(coords).addTo(map);
            $("#latitude").val(e.geocode.center.lat);
            $("#longitude").val(e.geocode.center.lng);
            marker.bindPopup(e.geocode.name).openPopup();
        });
        map.on('click', (event)=> {    
            if(marker !== null){
                map.removeLayer(marker);    
            }
            marker = L.marker([event.latlng.lat , event.latlng.lng]).addTo(map);
            $("#latitude").val(event.latlng.lat);
            $("#longitude").val(event.latlng.lng);
        });

        // Map End
        // Remove owner field
        $(document).on("click", ".removeOwner", function() {
            var ref = this;
            id = $(ref).parents('tr').attr('data-id');
            if(id !=0){
                
                var url = "{{ route('owner.delete', ":id") }}";
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
                    }
                });
            }
            else{
                $(ref).parents('tr').remove();
            }
            
           
        });
        //Remove Flat
        $(document).on("click", ".removeFlat", function() {
            var ref = this;
            id = $(ref).parents('tr').attr('data-id');
            if(id !=0){
                
                var url = "{{ route('flat.delete', ":id") }}";
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
                    }
                });
            }
            else{
                $(ref).parents('tr').remove();
            }
            
           
        });
        // Remove Plinth area field
        $(document).on("click", ".removePlinth", function() {
            var ref = this;
            id = $(ref).parents('tr').attr('data-id');
            if(id !=0){
                
                var url = "{{ route('plinth.delete', ":id") }}";
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
                    }
                });
            }
            else{
                $(ref).parents('tr').remove();
            }
           
        });
        // Remove Photoghraphs field
        $(document).on("click", ".remove_photographs", function() {
            var ref = this;
            filed_value = $(ref).parents('.photographs_name').find("input[name='property_photographs[]']").val();
            $(ref).parents('.photographs_name').remove();
            
           
        });
    });
    function showImage(url){
        $("#img-modal").attr('src',url)
        $('#modal-lg').modal('toggle');
    }
    function fileHideshow(field_id){
        let file_type = $('#' + field_id).attr('type');
    
        switch(file_type){
            case 'hidden':
                $('#' + field_id).attr('type','file');
                break;
            default:
                $('#' + field_id).attr('type','hidden');
                break;

        }

    }

     // Check if the browser supports Geolocation
    //  if (navigator.geolocation) {
    //         // Get the current position
    //         navigator.geolocation.getCurrentPosition(showPosition, showError);
    //     } else {
    //         // Browser doesn't support Geolocation
    //         document.getElementById("location").innerHTML = "Geolocation is not supported by this browser.";
    //     }

    //     // Callback function to handle successful position retrieval
    //     function showPosition(position) {
    //         var latitude = position.coords.latitude;
    //         var longitude = position.coords.longitude;

    //         // Display the location
    //         document.getElementById("location").innerHTML = "Latitude: " + latitude + "<br>Longitude: " + longitude;
    //     }

        // Callback function to handle errors
        // function showError(error) {
        //     switch (error.code) {
        //         case error.PERMISSION_DENIED:
        //             document.getElementById("location").innerHTML = "User denied the request for Geolocation.";
        //             break;
        //         case error.POSITION_UNAVAILABLE:
        //             document.getElementById("location").innerHTML = "Location information is unavailable.";
        //             break;
        //         case error.TIMEOUT:
        //             document.getElementById("location").innerHTML = "The request to get user location timed out.";
        //             break;
        //         case error.UNKNOWN_ERROR:
        //             document.getElementById("location").innerHTML = "An unknown error occurred.";
        //             break;
        //     }
        // }

        function toggleInputField(radio) {

            var fieldName = radio.name;

            if(fieldName == 'occupied_by'){
                var inputField = document.getElementById('inputField');
                var textInput1 = document.getElementById('leaser');
                var textInput2= document.getElementById('leasee');
                var textInput3= document.getElementById('commencement_date');
                var textInput4= document.getElementById('renewal_term');
                var textInput5= document.getElementById('initial_premium');
                var textInput6= document.getElementById('payable_rent');
                
                if(radio.value === 'Yes'){
                    textInput3.setAttribute('required', 'required');
                    textInput4.setAttribute('required', 'required');
                    textInput5.setAttribute('required', 'required');
                    textInput6.setAttribute('required', 'required');
                }else{
                    textInput3.removeAttribute('required');
                    textInput4.removeAttribute('required');
                    textInput5.removeAttribute('required');
                    textInput6.removeAttribute('required');
                }
            }else if(fieldName == 'lifts'){
                var inputField = document.getElementById('inputLiftField');
                var textInput1 = document.getElementById('lifts_no');
                var textInput2= document.getElementById('lifts_capacity');
            }else if(fieldName == 'water_tanks'){
                var inputField = document.getElementById('inputTankField');
                var textInput1 = document.getElementById('water_tanks_no');
                var textInput2= document.getElementById('water_tanks_capacity');
            }
            else if(fieldName == 'roads_paving'){
                var inputField = document.getElementById('inputPavingField');
                var textInput1 = document.getElementById('roads_paving_area');
                var textInput2= document.getElementById('roads_paving_type');
            }
            else if(fieldName == 'tenant'){
                var inputField = document.getElementById('inputTenantField');
                var textInput1 = document.getElementById('tenant_how_long');
                var textInput2= document.getElementById('tenant_rent');
            }
            else if(fieldName == 'sewage_tank'){
                var inputField = document.getElementById('inputSewageTankField');
                var textInput1 = document.getElementById('sewage_tank_type');
                
                if (radio.value === 'Yes') {
                inputField.style.display = 'block';
                textInput1.setAttribute('required', 'required'); // Optionally, add 'required' attribute
                    
                } else {
                    inputField.style.display = 'none';
                    textInput1.removeAttribute('required'); // Optionally, remove 'required' attribute
                }
            }
            else if(fieldName == 'underground_pump'){
                var inputField = document.getElementById('inputUndergroundPumpField');
                var textInput1 = document.getElementById('underground_pump_type');
                
                if (radio.value === 'Yes') {
                inputField.style.display = 'block';
                textInput1.setAttribute('required', 'required'); // Optionally, add 'required' attribute
                    
                } else {
                    inputField.style.display = 'none';
                    textInput1.removeAttribute('required'); // Optionally, remove 'required' attribute
                }
            }
            else if(fieldName == 'compound_walls'){
               
                var inputField = document.getElementById('inputComoundField');
                var textInput1 = document.getElementById('compound_walls_height');
                var textInput2 = document.getElementById('compound_walls_height_unit');
                var textInput3 = document.getElementById('compound_walls_thickness');
                var textInput4 = document.getElementById('compound_walls_thickness_unit');
                var textInput5 = document.getElementById('compound_walls_type');
                var textInput6 = document.getElementById('compound_walls_length');
                var textInput7 = document.getElementById('compound_walls_length_unit');
                
                if (radio.value === 'Yes') {
                inputField.style.display = 'block';
                textInput1.setAttribute('required', 'required'); // Optionally, add 'required' attribute
                textInput2.setAttribute('required', 'required');
                textInput3.setAttribute('required', 'required');
                textInput4.setAttribute('required', 'required');
                textInput5.setAttribute('required', 'required');
                textInput6.setAttribute('required', 'required');
                textInput7.setAttribute('required', 'required');
                    
                } else {
                    inputField.style.display = 'none';
                    textInput1.removeAttribute('required'); // Optionally, remove 'required' attribute
                    textInput2.removeAttribute('required');
                    textInput3.removeAttribute('required');
                    textInput4.removeAttribute('required');
                    textInput5.removeAttribute('required');
                    textInput6.removeAttribute('required');
                    textInput7.removeAttribute('required');
                }
            }
            
            if (radio.value === 'Yes') {
                inputField.style.display = 'block';
                textInput1.setAttribute('required', 'required'); // Optionally, add 'required' attribute
                textInput2.setAttribute('required', 'required'); // Optionally, add 'required' attribute
                
            } else {
                inputField.style.display = 'none';
                textInput1.removeAttribute('required'); // Optionally, remove 'required' attribute
                textInput2.removeAttribute('required'); // Optionally, remove 'required' attribute
            }
            
        }
        function toggleInputAllField(radio,id) {
            
            var inputField = document.getElementById(id);
            if (radio.value === 'Yes') {
                $('#'+id).siblings().show();
                inputField.style.display = 'block';
                inputField.setAttribute('required', 'required'); // Optionally, add 'required' attribute
            } else {
                $('#'+id).siblings().hide();
                inputField.style.display = 'none';
                inputField.removeAttribute('required'); // Optionally, remove 'required' attribute
            }
        }
        
        $(document).ready(function(){
            $(".final").click(function (event) {
            // $("#property_form_final_submit").submit(function (event) {
                event.preventDefault();
                Swal.fire({
                    title: "Are you sure?",
                    text: "After final submit, the form cannot be edited !",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, Submit it!"
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    // Swal.fire("Submitted!", "Please wait your form will be submitted shortly!", "success", 500);
                    $('#property_form_final_submit').submit();
                    // var id = $(this).parent('div').siblings('input').val();
                    // var url = "{{ route('valuation.submittedval', ':id') }}";
                    // url = url.replace(':id', id);
                    // $.ajax({
                    //     type: "put",
                    //     url: url,
                    //     data: {
                    //         "_token": "{{ csrf_token() }}",
                            
                    //     },
                    //     dataType: "JSON",
                    //     success: function (response) {
                    //         if(response.status == 0){
                    //             window.location.href = "/multi-step-form/step/"+response.id+"/"+response.steps;
                    //         }
                    //         else{
                    //             window.location.href = "{{ route('valuationform')}}";
                    //         }
                    //     //    
                    //     }
                    // });
                    
                }
                });
            });

            $(".allowNumber").on("keypress", function(event) {
                // Get the character code of the pressed key
                    var charCode = event.which;

                // Check if the pressed key is a number (0-9), the backspace key, or the period (.)
                if ((charCode < 48 || charCode > 57) &&  charCode !== 46) {
                    // Prevent the input if the key is not a number, backspace, or period
                    event.preventDefault();
                }
            });
            $(".sameas").click(function(event){
                var currentCell = $(this).closest("td");
                prev_val = currentCell.prev("td").find('input').val();
                prev_val_selected = currentCell.prev("td").find('select').val();
                if($(this).is(':checked')){
                    currentCell.next("td").next("td").find('input').val(prev_val).attr('readonly','true');
                    currentCell.next("td").next("td").find('select').val(prev_val_selected).attr('readonly','true');
                    currentCell.next("td").find('input').val(prev_val).attr('readonly','true');
                    currentCell.next("td").find('select').val(prev_val_selected).attr('readonly','true');
                }else{
                    currentCell.next("td").next("td").find('input').val('').removeAttr('readonly');
                    currentCell.next("td").next("td").find('select').val(prev_val_selected).removeAttr('readonly');
                    currentCell.next("td").find('input').val('').removeAttr('readonly');
                    currentCell.next("td").find('select').val(prev_val_selected).removeAttr('readonly');
                }
                
            });
            $(".not_mention").click(function(event){
                console.log("clic not mention");
                if($(this).is(':checked')){
                    $(this).closest("td").next("td").find('input').val('NA').prop('readonly', true);
                }else{
                    $(this).closest("td").next("td").find('input').val('').removeAttr('readonly');
                }
                
            });

            $(".onlyDigits").on("keypress", function(event) {
                // Get the character code of the pressed key
                    var keyCode = event.which;

                // Check if the pressed key is a number (0-9), the backspace key, or the period (.)
                if ((keyCode < 48 || keyCode > 57)) {
                    // Prevent the input if the key is not a number, backspace, or period
                    event.preventDefault();
                }
            });

            $("#share_each_owner").change(function (e) { 
                var share_type = $(this).val();
                if(share_type =='Sole Proprietor'){
                    $("#addOwner").hide();
                    $('#ownerTable tr:not(:eq(0),:eq(1))').hide();
                    $('#ownerTable .allowNumber').val('100').attr('readOnly','true');
                    $('#ownerTable tr:not(:eq(0),:eq(1))').find('input[type="text"]').prop('disabled', true);
                }else{
                    $("#addOwner").show();
                    $('#ownerTable tr:not(:eq(0),:eq(1))').show();
                    $('#ownerTable .allowNumber').val('0').removeAttr('readOnly');
                    $('#ownerTable tr:not(:eq(0),:eq(1))').find('input[type="text"]').prop('disabled', false);
                }
               
               
            });
        
        });
        $(document).on("blur", ".bulding_area, .bulding_rate", function() {
            var $row = $(this).closest('tr');  
            var area = $row.find('.bulding_area').val();
            var amount = $row.find('.bulding_rate').val();
            if (!isNaN(area) && !isNaN(amount)) {
                var result = parseFloat(area) * parseFloat(amount);
                $row.find('.bulding_amount').val(result);
            } else {
                $row.find('.bulding_amount').val('');
            }
        });
    
</script>
@endsection