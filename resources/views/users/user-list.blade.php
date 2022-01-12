@extends('layouts.admin.admin-app')

@section('title')
    Customers - {{ Config::get('app.name') }}
@endsection

@section('page-css')
<style>

.select2-container.select2-container--default.select2-container--open {
    /* z-index: 9999 !important; */
    width: 100%;
    height: auto;
    min-height: 100%;
}
.select2-container {
    width: 100% !important;
}
</style>

@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Customers</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Customers</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
           <!--  <div class="card-header custom_popup">
               <select class="form-control select2" style="width: 20%; float: inline-start;text-transform: capitalize" name="role_name" required>
                <option value="" selected>All Users</option>
      @if(sizeof($Users)==0){
           <option disabled >No Cashier</option>
           }@else{
            @foreach($Users as $user)
          
          
          
            <option value="{{$user->id}}" >{{$user->name}}</option>
           
            @endforeach
            }  
            @endif 
          </select>
            
              </div>
            -->
            <input type="hidden" value="{{Auth::user()->getRoleNames()->first()}}" class="adminStatus"/>
              <div class="card-body">
                <table id="userlist" class="table table-bordered table-striped dataTable dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th class="all text-center">Customer Info</th>
                        <th class="text-center">Account Status</th>
                        <th class="text-center">Amount History</th>
                       
            
                        <th class="text-center">Host Name</th>
                        <th class="text-center">Creation Date</th>
                       
                    
                         <th id="actionUser" class="text-center">Action </th>
                       
                         
                    </tr>
                </thead>
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
    <!-- /.content -->



</div>

<!-- Update User approval section Popup -->  
<div id="admin_approve" class="modal modal-info fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                    <div class="modal-header">
                         <h4 class="modal-title">User Approve section</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>                    
                       
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" method="post" id="approveSection">
                        <div class="box-body autocomplete_box">                                     
                        <input type="hidden" name="id" value="">
                        
                            <div class="form-group">
                                <label class="control-label col-sm-12" for="location">User status<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                <select class="form-control select2 status" id="userStatus" name="user_status" required>
                                </select>

                                </div>
                          
                        
                            </div>
                            
                            <div class="form-group text-center">        
                            <div class="col-sm-offset-2 col-sm-12">
                                <button type="submit" class="btn px-4 btn-primary btn-submit">Update</button>
                            </div>
                            </div>
                          </div>
                        </form>
                    </div>                    
                    </div>
                </div>
            </div>
<!-- End update approve Popup --> 

<!-- Update Cashier section Popup -->  
<div id="cashier_approve" class="modal modal-info fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                    <div class="modal-header">
                         <h4 class="modal-title">Assign Cashier</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>                    
                       
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" method="post" id="cashierSection">
                        <div class="box-body autocomplete_box">                                     
                        <input type="hidden" name="id" value="">
                        
                            <div class="form-group">
                                <label class="control-label col-sm-12" for="location">All Cashier<span class="text-danger">*</span></label>
                                <div class="col-sm-12 ">
                                 <select class="form-control select2 Cashier" name="cashier_id" >
                               
                            </select>
                                </div>
                          
                        
                            </div>
                            
                            <div class="form-group text-center">        
                            <div class="col-sm-offset-2 col-sm-12">
                                <button type="submit" class="btn px-4 btn-primary btn-submit">Update</button>
                            </div>
                            </div>
                          </div>
                        </form>
                    </div>                    
                    </div>
                </div>
            </div>
<!-- End update approve Popup --> 
@endsection

@section('page-scripts')
<script>
  $('.select2').select2();   
var userAdmin = $('.adminStatus').val();
if(userAdmin == 'Cashier' || userAdmin == 'User'){
    var i = -1;
}
 var l = false;
  var table_instance;
  table_instance = $('#userlist').DataTable({
    lengthChange: false,
    searching: true,
    processing: true,
    serverSide: true,
    retrieve: true,
    paging: true,
    responsive: true,
    pageLength: 10,
    order: [[ 0, 'desc' ]], //Initial no order.
    ajax: {
        url: "{{route('admin.userlist')}}",
        method: 'POST',
        data:function(d)
        {

        },
        complete: function(res) {
          if(l) {
              l.stop();
          }
      }
    },
    columnDefs: [
        {
            "responsivePriority": 1,
            "targets": -1
        },
        {
            "responsivePriority": 2,
            "targets": 1
        },
        {
             "targets": [ i ],
            "visible": false
        }
    ],
    columns: [
            {data: 'userinfo', name: 'email'},
            {data: 'account_status', name: 'account_status', "searchable": false, "orderable": false},
            {data: 'amount_history', name: 'amount_history', "searchable": false, "orderable": false},
            {data: 'cashier', name: 'email'},            
            {data: 'created_at', name: 'email'},
          
            {data: 'action', name:'action',"searchable": false, "orderable": false },

        ],
        fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
          
            if(aData['status']=='0' || aData['status']=='2'){
                $('td', nRow).css('background-color', 'rgb(217, 83, 79)').css('color','white');
            }

            if(aData['cashier']=='No Host Assigned Yet'){
                $('td', nRow).css('background-color', 'rgb(255, 171, 53)').css('color','white');

            }

            
        } 

  });

  $(document).on("click",".edituser", function(){
    var id = $(this).attr('data-id');
    $.ajax({
        url: "getUser",
        method: "get",                   
        data:{id:id},
        success: function (resultData) {
         
            var msg = resultData.message;
            if(resultData)
            {  
                $('.status').html(resultData.data);
                $('#approveSection input[name=id]').val(resultData.user.id);
                $('#approveSection select[name=user_status]').val(resultData.user.status);
              
                                       
              
                $('#admin_approve').modal('show');                                                   
            }
        }
    });
    });
     
  $('#approveSection').validate({
        errorClass: 'is-invalid', 
        submitHandler: function(form) {         
                l = Ladda.create( document.querySelector('#approveSection .btn-submit') );                
                l.start();
                $.ajax({
                    url: "{{route('admin_approve')}}",
                    method: "POST",                   
                    data: $("#approveSection").serialize(),
                    success: function (resultData) {    
                       
                        l.stop();       
                        var msg = resultData.message;
                        if(resultData.success)
                        {                           
                           toastr.success(msg);
                           $('#admin_approve').modal('hide');
                             table_instance.ajax.reload(null, false);
                                                        
                            
                                                   
                        }
                        else if(!resultData.success)
                        {

                            if(resultData.type=="validation-error")
                            {       
                            console.log(resultData);                                            
                                $.each( resultData.error, function( key, value ) {
                                    toastr.error(value);
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

    
  $(document).on("click",".assignUser", function(){
    var id = $(this).attr('data-id');
    $.ajax({
        url: "getAllCashier",
        method: "get",                   
        data:{id:id},
        success: function (resultData) {
   
            var msg = resultData.message;
            if(resultData)
            {  
                $('.Cashier').html(resultData.data);
                $('#cashierSection input[name=id]').val(resultData.user.id);
                if(resultData.user.cashier_id != 0 ){
                   $('#cashierSection select[name=cashier_id]').val(resultData.user.cashier_id); 
                }

                                                      
                
                $('#cashier_approve').modal('show');                                     
                                                          
            }
        }
    });
    });

$('#cashierSection').validate({
        errorClass: 'is-invalid', 
        submitHandler: function(form) {         
                l = Ladda.create( document.querySelector('#cashierSection .btn-submit') );                
                l.start();
                $.ajax({
                    url: "{{route('cashierAssign')}}",
                    method: "POST",                   
                    data: $("#cashierSection").serialize(),
                    success: function (resultData) {    
                       
                        l.stop();       
                        var msg = resultData.message;
                        if(resultData.success)
                        {                           
                           toastr.success(msg);
                           $('#cashier_approve').modal('hide');
                             table_instance.ajax.reload(null, false);
                                                        
                            
                                                   
                        }
                        else if(!resultData.success)
                        {
                            if(resultData.type=="validation-error")
                            {                                                   
                                $.each( resultData.error, function( key, value ) {
                                     toastr.error(value);
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

</script>
@endsection
