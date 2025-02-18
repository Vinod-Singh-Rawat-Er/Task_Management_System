@extends('admin.base.base')
@section('css')
@parent
 <!-- summernote -->
<link rel="stylesheet" href="{{ asset('admin_style/plugins/summernote/summernote-bs4.min.css') }}">
<!-- CodeMirror -->
<link rel="stylesheet" href="{{ asset('admin_style/plugins/codemirror/codemirror.css') }}">
<link rel="stylesheet" href="{{ asset('admin_style/plugins/codemirror/theme/monokai.css') }}">
 <!-- Select2 -->
 <link rel="stylesheet" href="{{ asset('admin_style/plugins/select2/css/select2.min.css')}}">
 <link rel="stylesheet" href="{{ asset('admin_style/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
 <link rel="stylesheet" href="{{ asset('admin_style/plugins/dropzone/min/dropzone.min.css')}}">
@endsection
@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="animated fadeIn mt-1">
                    <div class=" mb-4">
                        <h1 class="h3 mb-3 font-weight-normal">Add Activity</h1>
                    </div>
                    <form method="POST"  enctype="multipart/form-data" action="{{ route('addActivity')}}">
                        @csrf
                        
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="type">Name</label>
                                <input name="id" type="hidden" value="{{  $activitie->id ?? ''  }}">
                                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : ''}}" name="name" type="text" value="{!! $activitie->name ?? '' !!}">
                                <div class="invalid-feedback">{{ collect($errors->get('best_period') )->first(); }}</div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="type">URL</label>
                                <input class="form-control {{ $errors->has('url') ? 'is-invalid' : ''}}" name="url" type="text" value="{!! $activitie->url ?? '' !!}">
                                <div class="invalid-feedback">{{ collect($errors->get('url') )->first(); }}</div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="applicationNumber">About</label>
                                <textarea class="form-control summernote {{ $errors->has('about') ? 'is-invalid' : ''}}" name="about">{{ $activitie->about ?? '' }}</textarea>
                                <div class="invalid-feedback">{{ collect($errors->get('about') )->first(); }}</div>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="applicationNumber">Highlights</label>
                                <textarea class="form-control summernote {{ $errors->has('highlights') ? 'is-invalid' : ''}}" name="highlights">{{ $activitie->highlights ?? '' }}</textarea>
                                <div class="invalid-feedback">{{ collect($errors->get('highlights') )->first(); }}</div>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="applicationNumber">Overview</label>
                                <textarea class="form-control summernote {{ $errors->has('overview') ? 'is-invalid' : ''}}" name="overview">{{ $activitie->overview ?? '' }}</textarea>
                                <div class="invalid-feedback">{{ collect($errors->get('overview') )->first(); }}</div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="applicationNumber">PRICE INCLUDES</label>
                                <textarea class="form-control summernote {{ $errors->has('price_includes') ? 'is-invalid' : ''}}" name="price_includes">{{ $activitie->price_includes ?? '' }}</textarea>
                                <div class="invalid-feedback">{{ collect($errors->get('price_includes') )->first(); }}</div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="applicationNumber">ADDITIONAL (ON DEMAND)</label>
                                <textarea class="form-control summernote {{ $errors->has('additional') ? 'is-invalid' : ''}}" name="additional">{{ $activitie->additional ?? '' }}</textarea>
                                <div class="invalid-feedback">{{ collect($errors->get('additional') )->first(); }}</div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="type">Cost(Rs.)</label>
                                <input class="form-control {{ $errors->has('cost') ? 'is-invalid' : ''}}" name="cost" type="text" value="{!! $activitie->cost ?? '' !!}">
                                <div class="invalid-feedback">{{ collect($errors->get('cost') )->first(); }}</div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="type">Available</label>
                                <input class="form-control {{ $errors->has('available') ? 'is-invalid' : ''}}" name="available" type="text" value="{!! $activitie->available ?? '' !!}">
                                <div class="invalid-feedback">{{ collect($errors->get('available') )->first(); }}</div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="applicationNumber">Background Image</label>
                                @if (isset($activitie))
                                    <button class="btn " type="button" onClick="editImage(this)"><i class="align-middle me-2 fas fa-edit" ></i></button><a href="{{ route('getImage',['folder_name' =>'slider','file_name'=>$activitie->activity_bg_image ??"none"]) }}" target="_blank" download><i class="align-middle me-2 fas fa-eye" ></i></a>
                                @endif
                                <input class="form-control {{ $errors->has('activity_bg_image') ? 'is-invalid' : ''}}" name="activity_bg_image" type="{{ isset($activitie->activity_bg_image) ? 'hidden' :'file' }}" onchange="checkImageSize(this)" image-width="1894" image-height="1020" value="{{ $activitie->activity_bg_image ?? ''}}">
                                <div id="activity_bg_image" class="form-text">Image width:1894 and height:1020.</div>
                                <div class="invalid-feedback">Image width:1894 and height:1020</div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="applicationNumber">Image</label>
                                @if (isset($activitie))
                                    <button class="btn " type="button" onClick="editImage(this)"><i class="align-middle me-2 fas fa-edit" ></i></button><a href="{{ route('getImage',['folder_name' =>'slider','file_name'=>$activitie->image]) }}" target="_blank" download><i class="align-middle me-2 fas fa-eye" ></i></a>
                                @endif
                                <input class="form-control {{ $errors->has('activity_image') ? 'is-invalid' : ''}}" name="activity_image" type="{{ isset($activitie->image) ? 'hidden' :'file' }}" onchange="checkImageSize(this)" image-width="579" image-height="785" value="{{ $activitie->image ?? ''}}">
                                <div id="activity_image" class="form-text">Image width:579 and height:785.</div>
                                <div class="invalid-feedback">Image width:579 and height:785</div>
                            </div>
                        </div>
                        <button class="btn btn-primary my_submit_button">Add</button>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
@parent
<script src="{{ asset('admin_style/plugins/codemirror/codemirror.js') }}"></script>
<script src="{{ asset('admin_style/plugins/codemirror/mode/css/css.js') }}"></script>
<script src="{{ asset('admin_style/plugins/codemirror/mode/xml/xml.js') }}"></script>
<script src="{{ asset('admin_style/plugins/codemirror/mode/htmlmixed/htmlmixed.js') }}"></script>
<script src="{{ asset('admin_style/plugins/summernote/summernote-bs4.min.js') }}"></script>
<script src="{{ asset('admin_style/plugins/dropzone/min/dropzone.min.js')}}"></script>
<script type="text/javascript">
    var _URL = window.URL || window.webkitURL;
    $(document).ready(function() {
        $('.summernote').summernote();
        
    });
    function checkImageSize(ref){
        var imgwidth = 0;
        var imgheight = 0;
        image_fix_height = $(ref).attr('image-height');
        image_fix_width = $(ref).attr('image-width');
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
    function editImage(ref){
        type = $(ref).siblings('input').attr('type');
        if(type == 'hidden'){
            type = 'file'; 
        }else{
            type = 'hidden'; 
        }
        type = $(ref).siblings('input').attr('type',type);
        return false;
    }
    
    </script>
@endsection