@extends('layouts.admin.admin-app')

@section('title')
    Load Money - {{ Config::get('app.name') }}
@endsection



@section('page-css')
<style>
.custom_popup .btn.btn-block.btn-warning.btn-sm {
  max-width: 200px;
  color: #fff;
  height:38px;
}
.pac-container.pac-logo {
    z-index: 9999;
}
.select2-container.select2-container--default.select2-container--open {
  /* z-index: 9999 !important; */
  width: 100%;
  height: auto;
  min-height: 100%;
}
.select2-container {
    width: 100% !important;
}
.checkbox{

}
</style>
  

@section('page-css')



@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Users</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Users</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

@if ($message = Session::get('success'))

<div class="alert alert-success alert-block">

    <button type="button" class="close" data-dismiss="alert">×</button>    

    <strong>{{ $message }}</strong>

</div>

@endif


    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

        <form class="form-horizontal" method="post" action="{{route('assigntouser')}}">
          @csrf
            <div class="card">
               <div class="card-header custom_popup">
           <select class="form-control select2" style="width: 20%; float: inline-start;text-transform: capitalize;float: left;" name="role_name" required>
          @if(sizeof($Users)==0){
           <option  >No Cashier</option>
           }@else{
            @foreach($Users as $user)
          
          
          
            <option value="{{$user->id}}" >{{$user->name}}</option>
           
            @endforeach
            }  
            @endif
          </select>
        
             <button type="submit" class="btn btn-block btn-warning btn-sm btn-submit">Perform Action</button>
             
              </div>

            <div class="card">

              <!-- /.card-header -->
              <div class="card-body">
                <table id="userlist" class="table table-bordered table-striped dataTable dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th class="all">Name</th>
                        <th>Email</th>
                        <th>Role Name</th>

                        <th>Username</th>                        
                        <th>Select <input id="checkall" style="transform : scale(1.2)" class='' type="checkbox" ></th>
                         <th>Action </th>
                        <!-- <th>Action</th> -->
                    </tr>

                </thead>
                <tbody>

                  @foreach($usersdata as $userdata)
                   <tr>
                    <td>{{$userdata->name}}</td>
                    <td>{{$userdata->email}}</td>
                    <td>@php
                      $data=$userdata->getRoleNames();
                  echo $data[0];
                    @endphp</td>
                    <td>{{$userdata->username}}</td>
                    <td><input type="checkbox" style="transform : scale(1.2)" name="user_id[]" value="{{$userdata->id}}" id="checkbox" class="checkboxes"> </td>
                    <td><a href="javascript:;" data-id="{{$userdata->id}}" class="editlocation"><i class="fa fa-edit" aria-hidden="true"></i></a></td>
                   </tr>
                   @endforeach

                    
                </tbody>

                       

                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>

          </form>

          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->

<!-- Update location Popup -->  
<div id="update_location" class="modal modal-info fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                    <div class="modal-header">
                         <h4 class="modal-title">Change Role</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>                    
                       
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" method="post" id="updatelocation">
                        <div class="box-body autocomplete_box">                                     
                        <input type="hidden" name="id" value="">
                        <input type="hidden" name="roles" value="">
                            <div class="form-group">
                                <label class="control-label col-sm-12" for="location">Role<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                  <select class="form-control select2"  name="user_role" required>
                                   
                                    @foreach($roles as $role)
                                    <option value="{{$role->name}}">{{$role->name}}</option>
                                    @endforeach
                                     </select>
                                </div>
                          
                        
                            </div>
                            
                            <div class="form-group">        
                            <div class="col-sm-offset-2 col-sm-12">
                                <button type="submit" class="btn btn-block btn-warning btn-sm btn-submit">Update</button>
                            </div>
                            </div>
                          </div>
                        </form>
                    </div>                    
                    </div>
                </div>
            </div>
<!-- End update location Popup -->   





</div>

@endsection

@section('page-scripts')
<script>
  var l = false;
  var table_instance;
  table_instance = $('#userlist').DataTable({

   
    searching: true,
    processing: true,
 
    retrieve: true,
    paging: true,
    responsive: true,
    'columnDefs': [ {
        'targets': [-1,-2], // column index (start from 0)
        'orderable': false, // set orderable false for selected columns
     }]
   
  });

  $("#checkall").click(function (){
     if ($("#checkall").is(':checked')){
        $(".checkboxes").each(function (){
           $(this).prop("checked", true);
           });
        }else{
           $(".checkboxes").each(function (){
                $(this).prop("checked", false);
           });
        }
 });
$(document).on("click",".editlocation", function(){
    var id = $(this).attr('data-id');
    $.ajax({
        url: "getUser",
        method: "get",                   
        data:{id:id},
        success: function (resultData) {

            var msg = resultData.message;
            if(resultData)
            {  
                $('#updatelocation input[name=id]').val(resultData.id);        
              
                 $('#update_location').modal('show');                                     
                                                          
            }
        }
    });
  
    });
    
    $(document).ready(function(){
  $('#updatelocation').validate({
        errorClass: 'is-invalid', 
        submitHandler: function(form) {         
                l = Ladda.create( document.querySelector('#updatelocation .btn-submit') );                
                l.start();
                $.ajax({
                    url: "{{route('assignPermissionUser')}}",
                    method: "POST",                   
                    data: $("#updatelocation").serialize(),
                    success: function (resultData) {    
                        console.log(resultData);
                        l.stop();       
                        var msg = resultData.message;
                        if(resultData.success)
                        {                           
                            toastr.success(msg);
                                                        
                            $('#update_location').modal('hide');
                            location.reload();                             
                        }
                        else if(!resultData.success)
                        {
                            if(resultData.type=="validation-error")
                            {                                                   
                                $.each( resultData.error, function( key, value ) {
                                    $('label.is-invalid').remove();
                                    $('#'+key).after('<label id="'+key+'-error" class="is-invalid" for="'+key+'">'+value+'</label>');
                                    $('#'+key).addClass('is-invalid');           
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
