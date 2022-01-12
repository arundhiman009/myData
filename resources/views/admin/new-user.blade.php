@extends('layouts.admin.admin-app')

@section('title')
    
    New User - {{ Config::get('app.name') }}
@endsection






@section('content')

 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>New User</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">New User</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
@if(session()->has('success'))
<div class="alert alert-success alert-block">

    <button type="button" class="close" data-dismiss="alert">×</button>    

    <strong>{{ session()->get('success') }}</strong>

</div>
@endif
    <!-- Main content -->
   <style>
    
.row.field-box {
    text-align: left;
    margin-top: 20px;
    margin-left: 13px;
    padding-left: 8px;
    font-weight: 770px;
}
.control-label.col-sm-2.ml-5 {
    font-size: 23px;
}
   </style>
    <!-- /.content -->
  <section class="content">
      <div class="container-fluid">
        <div class="row field-box">

          <div class="col-6 col-offset-3">

 <div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">New User</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
               <form class="form-horizontal" method="post"  id="saveUser">
                 @csrf
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">First Name:</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" placeholder="First Name" required="" name="firstname">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Last Name</label>
                    <input type="text" class="form-control" id="exampleInputPassword1"  placeholder="Last Name" name="lastname">
                  </div>
                 <div class="form-group">
                    <label for="exampleInputPassword1">Username</label>
                    <input type="text" class="form-control" id="exampleInputPassword1" required="" placeholder="Username" name="username">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Email</label>
                    <input type="email" class="form-control" id="exampleInputPassword1" required="" placeholder="Email" name="email">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="text" class="form-control" id="exampleInputPassword1" required="" placeholder="Password" name="password">
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-warning btn-submit">Submit</button>
                </div>
              </form>
            </div>
            <!-- /.card -->



            <!-- /.card -->
          </div>

     

          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    

    
</div>

@endsection

@section('page-scripts')
<script>

$(document).ready(function(){
    
    $('#saveUser').on('submit',function(e){
      e.preventDefault();
    });   

    $('#saveUser').validate({        
        errorClass: 'is-invalid', 
        submitHandler: function(form) {
                l = Ladda.create( document.querySelector('#saveUser .btn-submit') );                
                l.start();
                $.ajax({
                    url: "{{route('admin.create-users')}}",
                    method: "POST",                   
                    data: $("#saveUser").serialize(),
                    success: function (resultData) {
                        l.stop();       
           
                         var msg = resultData.message;
                         if(resultData.success)
                        {                           
                            toastr.success(msg);
                            $('#saveUser').trigger("reset");                          
  
                        }
                        else if(!resultData.success)
                        {
                            if(resultData.type=="validation-error")
                            {    console.log(resultData.error);                                               
                                $.each( resultData.error, function( key, value ) {
                  if(key=="hidden_endtime"){
                  $('#saveslot input[name='+key+']').after('<label id="endtime-error" class="is-invalid" for="endtime">End time should be greater than start time.</label>');
                  }else{
                    $('#saveslot input[name='+key+']').after('<label id="endtime-error" class="is-invalid" for="endtime">'+value+'</label>');
                  }
                  });
                            }
                            else {
                                toastr.error(msg);
                            }
                        }
                    },
                    error: function (jqXHR, exception) {
                        var msg = '';
                        if (jqXHR.status === 0) {
                            msg = 'Not connect.\n Verify Network.';
                        } else if (jqXHR.status == 401) {
                                window.location.reload();
                        } else if (jqXHR.status == 404) {
                            msg = 'Requested page not found. [404]';
                        } else if (jqXHR.status == 500) {
                            msg = 'Internal Server Error [500].';
                        } else if (exception === 'parsererror') {
                            msg = 'Requested JSON parse failed.';
                        } else if (exception === 'timeout') {
                            msg = 'Time out error.';
                        } else if (exception === 'abort') {
                            msg = 'Ajax request aborted.';
                        } else {
                            msg = 'Uncaught Error.\n' + jqXHR.responseText;
                        }
                        l.stop();
                        toastr.error(msg);
                    }
                });
                return false;
            }
    });
  });
  </script>
    @endsection