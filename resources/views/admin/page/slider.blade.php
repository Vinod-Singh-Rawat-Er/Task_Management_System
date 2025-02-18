@extends('admin.base.base')
@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="animated fadeIn mt-1">
                    <div class=" mb-4">
                        <h1 class="h3 mb-3 font-weight-normal">Add Slider</h1>
                    </div>
                    <form method="POST"  enctype="multipart/form-data" action="{{ route('addSlider')}}">
                        @csrf
                        
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="type">Title</label>
                                <input name="id" type="hidden" value="{{  $slider->id ?? ''  }}">
                                <input class="form-control" name="title" type="text" value="{!! $slider->title ?? '' !!}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="applicationNumber">Sub Title</label>
                                <input class="form-control" name="subTitle" type="text" value="{{ $slider->sub_title ?? '' }}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="applicationNumber">About</label>
                                <textarea class="form-control" name="about">{{ $slider->about ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="applicationNumber">Image</label>
                                @if (isset($slider))
                                    <button class="btn " type="button" onClick="editImage(this)"><i class="align-middle me-2 fas fa-edit" ></i></button><a href="{{ asset('storage/slider/'.$slider->src) }}" target="_blank"><i class="align-middle me-2 fas fa-eye" ></i></a>
                                @endif
                                <input class="form-control" name="slider_image" type="{{ isset($slider->src) ? 'hidden' :'file' }}" onchange="checkImageSize(this)" image-width="1894" image-height="1020" value="{{ $slider->src ?? ''}}">
                                <div id="emailHelp" class="form-text">Image width:1894 and height:1020.</div>
                                <div class="invalid-feedback">Image width:1894 and height:1020</div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="applicationNumber">URL</label>
                                <input class="form-control" name="url" type="text" value="{!! $slider->url ?? '' !!}">
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
<script type="text/javascript">
    var _URL = window.URL || window.webkitURL;
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