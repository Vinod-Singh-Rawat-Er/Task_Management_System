@extends('admin.base.base')
@section('css')
@parent
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
<section class="content">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h3 class="m-0">{{ $activitie->name }}</h3>
      </div><!-- /.col -->
      <div class="col-sm-3  mt-2">
        <button  class="btn btn-block btn-outline-success pull-bs-canvas-right " onclick="addAttracion(this)">Add Image</button>
      </div>
      <div class="col-sm-3">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/">Dashbord</a></li>
          <li class="breadcrumb-item"><a href="{{ route('activities')}}">Activity</a></li>
          <li class="breadcrumb-item active">Images</li>
        </ol>
      </div><!-- /.col -->
    </div>
    <div class="row">
        <table  id="myTable" style="width:100%" class="table">
          <thead>
            <tr>
                <th scope="col">#</th>
                {{-- <th scope="col">Title</th> --}}
                <th scope="col">Image</th>
               
                <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach ( $images as $image)
            <tr>
                <td scope="col">{{ $loop->index + 1 }}</td>
                {{-- <td scope="col">{{$attraction->title}}</td> --}}
                <td><img src="{{ route('getImage',['folder_name'=> 'activity','file_name'=>$image])  }}" class=" img img-thumbnail  w-20" alt="..."></td>
                <td scope="col">
                    @if ( $loop->index != 0)
                        <a class="btn btn-danger" href="{{ route('deleteactivityimage',['id'=>$activitie->id,'image'=>$image])}}">
                            <i class="fas fa-trash"></i>
                        </a>   
                    @endif
                 
                </td>
            </tr>  
            @endforeach
          </tbody>
        </table>
    </div>
    <div class="bs-canvas bs-canvas-right position-fixed bg-light h-100 {{ old() ? ' mr-0': '' }}">
      <header class="bs-canvas-header p-3 bg-dark overflow-auto">
          <button type="button" class="bs-canvas-close float-left close" aria-label="Close"><span aria-hidden="true"
                  class="text-light">&times;</span></button>
          <h4 class="d-inline-block text-light mb-0 float-right">New/Update Itinerary</h4>
      </header>
      <div class="bs-canvas-content px-3 py-1">
      <form method="POST" action="{{ route('activite_image',['id'=> $activitie->id])}}" enctype="multipart/form-data">
          <fieldset class="uk-fieldset">
            @csrf
            {{-- <legend class="uk-legend">Itinerary</legend> --}}
            <div class="mb-3">
              <label for="image" class="form-label">Image</label>
              <input type="file" class="form-control {{ $errors->has('image') ? 'is-invalid' : ''}}" id="image" name="image"  image-width="579" image-height="785" onchange="checkImageSize(this)">
              <div id="dayHelp" class="form-text">Image width:579 and height:785.</div>
              <div class="invalid-feedback">{{ collect($errors->get('image') )->first(); }}</div>
            </div>
            <button class="button" id="subbtn">Add</button>
          </fieldset>
      </form>
    </div>
  </div>
</section>
@endsection

@section('script')
@parent
<script>
  
  var _URL = window.URL || window.webkitURL;
  $(document).on('click', '.bs-canvas-close, .bs-canvas-overlay', function(){
		var elm = $(this).hasClass('bs-canvas-close') ? $(this).closest('.bs-canvas') : $('.bs-canvas');
		elm.removeClass('mr-0 ml-0');
		$('.bs-canvas-overlay').remove();
		return false;
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
   
  function addAttracion(ref){
    $("form")[0].reset();
  
    $("#subbtn").text("Add");
    if($(ref).hasClass('pull-bs-canvas-right')){
      $('.bs-canvas-right').addClass('mr-0');
    }
  }
</script>
@endsection
