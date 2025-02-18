@extends('admin.base.base')
@section('content')
<div class="container-fluid p-0">
    <div class="row">
        <div class="col-12">
            <div class="animated fadeIn mt-1">
                <div class=" mb-4">
                    <h1 class="h3 mb-3 font-weight-normal">Add Vendor</h1>
                </div>
                <form method="POST" action="{{ route('vendors.store') }}">
                    @csrf
                    
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="type">Name</label>
                            <input name="id" type="hidden" value="{{  $user->id ?? ''  }}">
                            <input name="role_id" type="hidden" value="2">
                            <input name="is_admin" type="hidden" value="1">
                            <input class="form-control" name="name" type="text" value="{!! $user->name ?? '' !!}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="type">Email</label>
                            <input class="form-control" name="email" type="text" value="{!! $user->email ?? '' !!}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="type">Mobile No</label>
                            <input class="form-control" type="text" name="mobile" id="mobile" :value="old('mobile')" required autofocus placeholder="Mobile Number">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="applicationNumber">Company Name</label>
                            <input class="form-control" name="company" type="text" value="{{ $user->company ?? '' }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="applicationNumber">Passowrd</label>
                            <input class="form-control" type="password" name="password" required autocomplete="current-password"  id="password" placeholder="Password">
                        </div>
                    </div>
                     <button class="btn btn-primary my_submit_button">Update</button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

