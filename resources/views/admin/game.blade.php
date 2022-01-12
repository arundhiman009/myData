@extends('layouts.admin.admin-app')

@section('title')

    Game - {{ Config::get('app.name') }}
@endsection
@section('page-css')
<style>
.custom_popup .btn.btn-block.btn-warning.btn-sm {
    max-width: 200px;
    color: #fff;
}

</style>

@endsection

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Game</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Games</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header custom_popup text-center">
              <a href="javascript:;" class="btn btn-success btn-submit px-4 float-sm-right" data-toggle="modal" data-target="#addcode"><i class="mr-1 fa fa-plus" aria-hidden="true"></i> Add Game </a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="gametable" class="table table-bordered table-striped dataTable dt-responsive nowrap w-100">
                <thead>
                    <tr>

                        <th>Image</th>
                        <th>Name</th>
                        <th>Game Path</th>
                        <th>Created</th>
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
<!-- Add slot Popup -->

<div id="addcode" class="modal modal-info fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Game </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" enctype="multipart/form-data" method="post" id="savepromocode">
                    <div class="box-body">

                        <div class="form-group">
                            <label class="control-label col-sm-12" for="name">Name<span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control input-lg" id="name" placeholder="Game Name" name="name" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-12" for="status">Image<span class="text-danger">*</span></label>
                            <div class="col-sm-12">

                             <input type="file" class="form-control input-lg" id="file_image" accept="image/png,  image/jpeg" name="file_image" required >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-12" for="limit">Download Path<span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <input type="url" class="form-control input-lg" id="download_path" placeholder="Paste link here " name="download_path" required>
                            </div>
                        </div>



                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-12 text-center">
                                <button type="submit" data-style="zoom-in" class="btn btn-primary btn-submit px-4">Add Game</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End add location Popup -->
<!-- Update location Popup -->
<div id="update_promo" class="modal modal-info fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update game</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="post" id="updatepromo">
                    <div class="box-body">
                        <input type="hidden" name="id" value="">
                        <div class="form-group">
                            <label class="control-label col-sm-12" for="name">Name<span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control input-lg" id="name" placeholder="Name" name="name" required>
                            </div>
                        </div>
                        <input type="hidden" name="image_name" id="image_name"/>
                        <div class="form-group">
                            <label class="control-label col-sm-12" for="limit">Image<span class="text-danger"></span></label>
                            <div class="col-sm-12">
                                <input type="file" class="form-control input-lg" id="url" placeholder="Enter download path here" name="image" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-12" for="limit">Download Path<span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <input type="url" class="form-control input-lg" id="url" placeholder="Enter download path here" name="download_path" required>
                            </div>
                        </div>



                        <div class="form-group text-center">
                            <div class="col-sm-offset-2 col-sm-12">
                                <button type="submit" data-style="zoom-in" class="btn px-4 btn-primary btn-submit">Update</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



@endsection
@section('page-scripts')
<script>

  var l = false;
  var url = 'http://localhost:8000/assets/images/';
  var table_instance;
  table_instance = $('#gametable').DataTable({
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
        url: "{{route('admin.gamelist')}}",
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
        }
    ],
     columns: [
            {data: 'image', name: 'image', className : "text-center"},
            {data: 'name', name: 'name', className : "text-center"},

            {data: 'url', name: 'name', className : "text-center"},
            {data: 'created_at', name: 'name', className : "text-center"},
            {data: 'action', name: 'name', className : "text-center"},
        ]
  });
 function removepromo(obj,id){
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
                url: "{{route('admin.deletegame')}}",
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


    //  $('#savepromocode').on('submit',function(e){
    //     e.preventDefault();
    // });

      $('#savepromocode').validate({


        errorClass: 'is-invalid',
        submitHandler: function(form) {
                l = Ladda.create( document.querySelector('#savepromocode .btn-submit') );

                  var formData = new FormData($("form#savepromocode")[0]);

                l.start();
                $.ajax({
                    url: "{{route('admin.savegame')}}",
                    method: "POST",
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: function (resultData) {

                        l.stop();
                         var msg = resultData.message;
                        if(resultData.success)
                        {
                            toastr.success(msg);
                            table_instance.ajax.reload( null, false );
                            $('#savepromocode').trigger("reset");

                            $('#addcode').modal('hide');
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

      $(document).on("click",".editpromo", function(){
    var id = $(this).attr('data-id');
    $.ajax({
        url: "{{route('admin.getgame')}}",
        method: "POST",
        data:{id:id},
        success: function (resultData) {
            var msg = resultData.message;
            console.log(resultData.success);
            if(resultData.success)
            {


                $('#updatepromo input[name=id]').val(resultData.data.id);
                $('#updatepromo input[name=name]').val(resultData.data.name);
                $('#updatepromo input[name=image_name]').val(resultData.data.image);
                $('#updatepromo input[name=download_path]').val(resultData.data.download_path);


                $('#update_promo').modal('show');
            }
        }
    });

    });

       $('#updatepromo').validate({
        errorClass: 'is-invalid',

        submitHandler: function(form) {
                l = Ladda.create( document.querySelector('#updatepromo .btn-submit') );
                 var formData = new FormData($("form#updatepromo")[0]);
                l.start();
                $.ajax({
                    url: "{{route('admin.updategame')}}",
                    method: "POST",
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: function (resultData) {
                        l.stop();
                         var msg = resultData.message;
                        if(resultData.success)
                        {
                            toastr.success(msg);
                            table_instance.ajax.reload( null, false );
                            $('#update_promo').modal('hide');
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
  </script>
@endsection
