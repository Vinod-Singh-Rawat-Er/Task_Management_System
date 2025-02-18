@extends('admin.base.base')
@section('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
@endsection
@section('content')
<div class="animated fadeIn">
    <a href="{{ route('vendors.create')}}" class="btn btn-outline-primary">Add Vendor</a><hr>
    <div class="row">
        <table  id="myTable" style="width:100%" class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Name</th>
              <th scope="col">company</th>
              {{-- <th scope="col">link</th> --}}
              <th scope="col">On Create</th>
              <th scope="col">Status</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach ( $users as $user)
            <tr>
                <td scope="col">1</td>
                <td scope="col">{{$user->name}}</td>
                <td scope="col">{{$user->mobile}}</td>
                {{-- <td scope="col"></td> --}}
                <td scope="col">{{$user->created_at}}</td>
                <td scope="col" class="status" data-bind="text124">
                    @if($user->status == 2)
                        <span class="badge bg-success">Active</span> 
                    
                    @else
                        <span class="badge bg-danger">Disabled</span>
                    @endif
                   
                </td>
                <td scope="col" >
                  @if ($user->status == 2)
                    <button class="btn btn-danger statusBtn" bind-id="{{$user->id}}" bind-data="1"> <i class="align-middle me-2" data-feather="eye"></i>  </button>
                  @else
                    <button class="btn btn-success statusBtn" bind-id="{{$user->id}}" bind-data="2"> <i class="align-middle me-2" data-feather="eye-off"></i> </button>
                  @endif
                  
                  <a class="btn btn-info" onclick="resetPassword({{$user->id}})">
                    <i class="align-middle me-2" data-feather="rotate-cw"></i>
                  </a>
                  <a class="btn btn-danger" href="{{ route('vendors.destroy',$user->id)}}">
                    <i class="align-middle me-2" data-feather="trash-2"></i>
                  </a>
                </td>
              </tr>  
            @endforeach
          </tbody>
        </table>
    </div>
</div>
<div class="offcanvas offcanvas-end {{ old('password') ? 'show': '' }}" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasExampleLabel">Rest Password</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <form method="POST" action="{{ route('vendors.update', auth()->user()->id) }}">
        <fieldset class="uk-fieldset">
            @csrf
            @method('PUT')
            {{-- <legend class="uk-legend">Itinerary</legend> --}}
            <input type="hidden" name="id" value="">
            <div class="mb-3">
              <label for="password" class="form-label">New Password</label>
              <input type="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : ''}}" id="password" name="password">
              <div class="invalid-feedback">{{ collect($errors->get('password') )->first(); }}</div>
            </div>
            
            <button class="button" id="subbtn">Add</button>
        </fieldset>
    </form>
  </div>
</div>
@endsection
@section('footerscript')
@parent
<script>
  var myOffcanvas = document.getElementById('offcanvasRight');
  var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas);
  function resetPassword(user_id){
    $("form")[0].reset();
    $("input[name='id']").val(user_id);
    $("input[name='password']").val('');
    bsOffcanvas.toggle()
  }
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
        data: {tableName: 'users',
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