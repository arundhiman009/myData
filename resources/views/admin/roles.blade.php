@extends('layouts.admin.admin-app')

@section('title')
    Roles - {{ Config::get('app.name') }}
@endsection
@section('page-css')
    <style>
        .custom_popup .btn.btn-block.btn-warning.btn-sm {
            max-width: 200px;
            color: #fff;
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
            <h1>Role</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Add Role</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
@if (Session::has('success'))

<div class="alert alert-success alert-block">

    <button type="button" class="close" data-dismiss="alert">×</button>    

    <strong>{{ Session::get('success') }}</strong>

</div>

@endif
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header custom_popup">
              <a href="javascript:;" class="btn btn-block btn-warning btn-sm" data-toggle="modal" data-target="#addlocation">Add Role</a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                  
                <table id="role" class="table table-bordered table-striped dataTable dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th class="all" style="white-space:normal;">Id
                        <th>Role Name</th>
                        <th>Creation Date</th>
                        <th>Action</th>
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

<!-- Add location Popup -->  
<div id="addlocation" class="modal modal-info fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
        <div class="modal-header">
                <h4 class="modal-title">Add Role</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>                    
            
        </div>
        <div class="modal-body">
            <form class="form-horizontal" method="post" id="savelocation" action="{{
            route('admin.saveRole') }}">
            @csrf
            <div class="box-body autocomplete_box">
                
                <div class="form-group">
                    <label class="control-label col-sm-12" for="location">Name<span class="text-danger">*</span></label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control input-lg" id="name" placeholder="Name" name="name" >
                    </div>
                </div>
                
               
              
              
                                       
                <div class="form-group">        
                <div class="col-sm-offset-2 col-sm-12">
                    <button type="submit" class="btn btn-block btn-warning btn-sm btn-submit">Add Role</button>
                </div>
                </div>
            </div>
            </form>
        </div>                    
        </div>
    </div>
</div>
<!-- End add location Popup -->   
<!-- Update location Popup -->  
<div id="update_location" class="modal modal-info fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                    <div class="modal-header">
                         <h4 class="modal-title">Update Role</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>                    
                       
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal"  method="post" id="updatelocation">
                        
                        <div class="box-body autocomplete_box">                                        
                        <input type="hidden" name="id" value="">
                            <div class="form-group">
                                <label class="control-label col-sm-12" for="location">Name<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control input-lg" id="name" placeholder="Loaction Name" name="name" required>
                                </div>
                            </div>
                      
                      
                        
                            
                            
                            <div class="form-group">        
                            <div class="col-sm-offset-2 col-sm-12">
                                <button type="submit" class="btn btn-block btn-warning btn-sm btn-submit">Update</button>
                            </div>
                            </div></div>
                        </form>
                    </div>                    
                    </div>
                </div>
            </div>
<!-- End update location Popup -->   


@endsection
@section('page-scripts')
<script>   
$('.select2').select2();
  var l = false;
  var table_instance;    
  table_instance = $('#role').DataTable({
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
        url: "{{route('admin.roles.rolelist')}}",
        method: 'get',
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
            "bSortable": false, 
            "aTargets": [ -1 ] // <-- gets last column and turns off sorting
         } 

    ],
    columns: [  
            {data: 'DT_RowIndex', name: 'id', className : "text-center"},
            {data: 'name', name: 'name', className : "text-center"},
           
           
            {data: 'created_at', name: 'created_at', className : "text-center"},
            {data: 'action', name: 'action', "searchable": false, "orderable": false, className : "text-center"},            
        ]
  });

$(document).on("click",".editlocation", function(){
    var id = $(this).attr('data-id');
    $.ajax({
        url: "{{route('admin.roles.getRole')}}",
        method: "POST",                   
        data:{id:id},
        success: function (resultData) {
            var msg = resultData.message;

            if(resultData.success)
            {  
                $('#updatelocation input[name=id]').val(resultData.data.id);
                
                
                $('#updatelocation input[name=name]').val(resultData.data.name);
                                                     
                $('#update_location').modal('show');                                            
            }
        }
    });
  
    });
 function removelocation(obj,id){
        Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to delete?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{route('admin.roles.deleteRole')}}",
                method: "POST",                   
                data:{id:id},
                success: function (resultData) {
                    var msg = resultData.message;
                    if(resultData.success)
                    {  
                        Swal.fire(
                        'Deleted!',
                        msg,
                        'success'
                        )
                        table_instance.ajax.reload( null, false );                                              
                    }
                }
            });            
        }
        })
    }

     $(document).ready(function(){
  $('#updatelocation').validate({
        errorClass: 'is-invalid', 
        submitHandler: function(form) {         
                l = Ladda.create( document.querySelector('#updatelocation .btn-submit') );                
                l.start();
                $.ajax({
                    url: "{{route('update-role-data')}}",
                    method: "POST",                   
                    data: $("#updatelocation").serialize(),
                    success: function (resultData) {    
                        console.log(resultData);
                        l.stop();       
                        var msg = resultData.message;
                        if(resultData.success)
                        {                           
                            toastr.success(msg);
                            table_instance.ajax.reload( null, false );                           
                            $('#update_location').modal('hide');                             
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