@extends('admin.base.base')
@section('content')
<section class="content-header">
  <div class="container-fluid">
      <div class="row mb-2">
      <div class="col-sm-6">
          <h3><a class="pull-bs-canvas-right btn btn-app" href="{{ route('addSlider')}}">
              <i class="fas fa-plus"></i> Add
          </a></h3>
      </div>
      <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
          <li class="breadcrumb-item active">Sliders</li>
          </ol>
      </div>
      </div>
  </div><!-- /.container-fluid -->
</section>
<section class="content">
  <div class="container-fluid">
    <div class="row">
        <table  id="myTable" style="width:100%" class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Title</th>
              <th scope="col">Subtitle</th>
              {{-- <th scope="col">link</th> --}}
              <th scope="col">On Create</th>
              <th scope="col">Status</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach ( $sliders as $slider)
            <tr>
                <td scope="col">1</td>
                <td scope="col">{{$slider->title}}</td>
                <td scope="col">{{$slider->sub_title}}</td>
                {{-- <td scope="col"></td> --}}
                <td scope="col">{{$slider->created_at}}</td>
                <td scope="col" class="status" data-bind="text124">
                    @if($slider->status == 2)
                        <span class="badge bg-success">Publish</span> 
                    
                    @else
                        <span class="badge bg-danger">Unpublish</span>
                    @endif
                   
                </td>
                <td scope="col" >
                  @if ($slider->status == 2)
                    <button class="btn btn-danger statusBtn" bind-id="{{$slider->id}}" bind-data="1"> <i class="align-middle me-2" data-feather="eye"></i>  </button>
                  @else
                    <button class="btn btn-success statusBtn" bind-id="{{$slider->id}}" bind-data="2"> <i class="align-middle me-2" data-feather="eye-off"></i> </button>
                  @endif
                  
                  <a class="btn btn-info" href="{{ route('editSlider',['id'=>$slider->id])}}">
                    <i class="align-middle me-2" data-feather="edit"></i>
                  </a>
                  <a class="btn btn-danger" href="{{ route('deleteSlider',['id'=>$slider->id])}}">
                    <i class="align-middle me-2" data-feather="trash-2"></i>
                  </a>
                </td>
              </tr>  
            @endforeach
          </tbody>
        </table>
    </div>
  </div>
</section>
@endsection
@section('footerscript')
@parent
<script>
   $(".statusBtn").click(function(event) {
      /* Act on the event */
      let ref = this;
      status_value = $(this).attr('bind-data');
      var status_text ="Unpublish";
      var status = 2;
      var delClass ='bg-success';
      var addClass ="bg-danger";
      if(status_value == 2){
        status_text ="Publish";
        status = 1;
        var addClass ='bg-success';
        var delClass ="bg-danger";
      }
      id =$(this).attr('bind-id');
      $.ajax({
        url: "{{ route('changeSatus')}}",
        type: 'POST',
        dataType: 'HTML',
        data: {tableName: 'slider',
                fieldName:'status',id:id,value:status_value,
                "_token": "{{ csrf_token() }}",
              },
      })
      .done(function() {
        $(ref).attr('bind-data',status);
        $(ref).parent().siblings('.status').find('.badge').html(status_text);
        $(ref).parent().siblings('.status').find('.badge').removeClass(delClass);
        $(ref).parent().siblings('.status').find('.badge').addClass(addClass);
      })
      .fail(function() {
        console.log("error");
      })
      .always(function() {
        console.log("complete");
      });
      
    });
</script>
@endsection