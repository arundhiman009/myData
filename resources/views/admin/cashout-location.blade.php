@extends('layouts.admin.admin-app')

@section('title')
   Cashout Location - {{ Config::get('app.name') }}
@endsection

@section('page-css')
<style>
.pac-container.pac-logo {
    z-index: 9999;
}
.select2-container.select2-container--default.select2-container--open {
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
            <h1>Cashout Location</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Cashout Location</li>
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
              <div class="card-header custom_popup">
              <a href="javascript:;" class="btn px-4 btn-success btn-sm float-sm-right" data-toggle="modal" data-target="#addlocation"> <i class=" mr-1 fa fa-plus" aria-hidden="true"></i>  Add Location</a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">

                <table id="location" class="table table-bordered table-striped dataTable dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th class="all" style="white-space:normal;">Location Name</th>
                        <th>Address</th>
                        <th>City</th>
                        <th>State</th>
                        <th>Pincode</th>
						 <th>Status</th>
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
                <h4 class="modal-title">Add Location</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="post" id="savelocation">
                    <div class="box-body autocomplete_box">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-12" for="location">Name<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control required input-lg" id="name" placeholder="Name" name="name" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-12" for="address">Address<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="hidden" id="lat" class="lat" name="lat" />
                                        <input type="hidden" id="lng" class="lng" name="lng" />
                                        <input type="text" id="address" name="address" placeholder="Enter a Address" class="form-control address required" />
                                        <label id="address-error" class="address-error is-invalid" for="address" style="display:none"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-12" for="city">City<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control required input-lg city" id="city" placeholder="City" name="city" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-12" for="state">State<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control required input-lg state" id="state" placeholder="State" name="state" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-12" for="zip_code">Zip Code<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control required input-lg zip_code" id="pincode" placeholder="Zip Code" name="pincode" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-12" for="status">Status<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                    <select class="form-control required select2" style="width: 100%;" name="status">
                                        <option value="0">Inactive</option>
                                        <option value="1" selected>Active</option>
                                    </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 text-center">
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-12 text-center">
                                        <button type="submit" data-style="zoom-in" class="btn px-4 btn-primary btn-submit"> Save</button>
                                    </div>
                                </div>
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
                         <h4 class="modal-title">Update Loaction</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" method="post" id="updatelocation">
                        <div class="box-body autocomplete_box">
                        <input type="hidden" name="id" value="">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-12" for="location">Name<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control required input-lg" id="name" placeholder="Loaction Name" name="name" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-12" for="address">Address<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="hidden" id="lat" class="lat" name="lat" />
                                        <input type="hidden" id="lng" class="lng" name="lng" />
                                        <input type="text" id="address" placeholder="Enter a Address" name="address" class="form-control address required" />
                                        <label id="address-error" class="address-error is-invalid" for="address" style="display:none"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-12" for="city">City<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control  input-lg city" id="city" placeholder="City" name="city" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-12" for="state">State<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control  input-lg state" id="state" placeholder="State" name="state" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-12" for="pincde">Zip Code<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control input-lg zip_code" id="pincode" placeholder="Zip Code" name="pincode" required>
                                    </div>
                                </div>
                            </div>   
                            <div class="col-sm-6">           
                                <div class="form-group">
                                    <label class="control-label col-sm-12" for="status">Status<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                    <select class="form-control select2" style="width: 100%;" name="status" required>
                                        <option value="0" >Inactive</option>
                                        <option value="1" >Active</option>
                                    </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 text-center">
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-12">
                                        <button type="submit" data-style="zoom-in" class="btn px-4 btn-primary btn-submit">Update</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </form>
                    </div>
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
  table_instance = $('#location').DataTable({
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
        url: "{{route('admin.locationlist')}}",
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
            "bSortable": false,
            "aTargets": [ -1 ] // <-- gets last column and turns off sorting
         }

    ],
    columns: [
            {data: 'name', name: 'name', className : "text-center"},
            {data: 'address', name: 'address', className : "text-center"},
            {data: 'city', name: 'city', className : "text-center"},
            {data: 'state', name: 'state', className : "text-center"},
            {data: 'pincode', name: 'pincode', className : "text-center"},
			{data: 'status', name: 'status', className : "text-center"},
            {data: 'created_at', name: 'created_at', className : "text-center"},
            {data: 'action', name: 'action', "searchable": false, "orderable": false, className : "text-center"},
        ]
  });

// Add record
 $(document).ready(function(){
    var Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000
    });
    $('#savelocation').on('submit',function(e){
        e.preventDefault();
    });

	    $('.address').keydown(function (e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            return false;
        }
    });
    jQuery.validator.addMethod("zipcode", function(value, element) {
    return this.optional(element) || /^\d{5}(?:-\d{4})?$/.test(value);
    }, "Please provide a valid zipcode.");

    $('#savelocation').validate({
        rules: {
            'pincode': {
            zipcode: false,
            minlength: 4,
        },
        },
        errorClass: 'is-invalid',
        submitHandler: function(form) {
                l = Ladda.create( document.querySelector('#savelocation .btn-submit') );
                l.start();
                $.ajax({
                    url: "{{route('admin.savelocation')}}",
                    method: "POST",
                    data: $("#savelocation").serialize(),
                    success: function (resultData) {
						l.stop();
                         var msg = resultData.message;
						 if(resultData.success)
                        {
                            toastr.success(msg);
                            table_instance.ajax.reload( null, false );
                            $('#savelocation').trigger("reset");
                            $('#addlocation').modal('hide');
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
    $('#updatelocation').validate({
        errorClass: 'is-invalid',
        rules: {
            'pincode': {
            zipcode: false,
            minlength: 4,
        },
        },
        submitHandler: function(form) {
                l = Ladda.create( document.querySelector('#updatelocation .btn-submit') );
                l.start();
                $.ajax({
                    url: "{{route('admin.updatelocation')}}",
                    method: "POST",
                    data: $("#updatelocation").serialize(),
                    success: function (resultData) {
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
 $(document).on("click",".editlocation", function(){
    var id = $(this).attr('data-id');
    $.ajax({
        url: "{{route('admin.getlocation')}}",
        method: "POST",
        data:{id:id},
        success: function (resultData) {
            var msg = resultData.message;
			if(resultData.success)
            {
                $('#updatelocation input[name=id]').val(resultData.data.id);
				$('#updatelocation select[name=status]').val(resultData.data.status);
                $('#updatelocation select[name=status]').select2().trigger('change');
                $('#updatelocation input[name=name]').val(resultData.data.name);
                $('#updatelocation input[name=city]').val(resultData.data.city);
                $('#updatelocation input[name=state]').val(resultData.data.state);
                $('#updatelocation input[name=pincode]').val(resultData.data.pincode);
                $('#updatelocation input[name=address]').val(resultData.data.address);
                $('#updatelocation input[name=lat]').val(resultData.data.lat);
                $('#updatelocation input[name=lng]').val(resultData.data.lng);
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
                url: "{{route('admin.deletelocation')}}",
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

</script>
@endsection
