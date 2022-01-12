@extends('layouts.admin.admin-app')

@section('title')
    Hosts - {{ Config::get('app.name') }}
@endsection

@section('page-css')
<style>
.custom_popup .btn.btn-block.btn-warning.btn-sm {
    max-width: 200px;
    color: #fff;
}
.card-header.custom_popup a{padding: 6px 16px;}

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
            <h1>Host</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Host</li>
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
           <div class="card-header custom_popup text-center">
              <a href="javascript:;" class="btn btn-inline-block btn-success btn-sm float-sm-right" data-toggle="modal" data-target="#addcode"><i class="mr-1 fa fa-plus" aria-hidden="true"></i> Add Host </a>
              </div>
              <div class="card-body">
                <table id="cashierlist" class="table table-bordered table-striped dataTable dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th class="all text-center">Host Info</th>
                        <th class="text-center">Amount History</th>
                        <th class="text-center">Account Status</th>
                        <th class="text-center">Customer Assign</th>
                        <th class="text-center">Creation Date</th>
                         <th class="text-center">Action</th>
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
                <h4 class="modal-title">Add Host </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" enctype="multipart/form-data" method="post" id="addcashier">
                    <div class="box-body">

                        <div class="form-group">
                            <label class="control-label col-sm-12" for="name">Username<span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control required input-lg" id="name" placeholder="Enter Your Username" name="username" />
                            </div>
                        </div>

                       <div class="form-group">
                            <label class="control-label col-sm-12" for="name">Email Address<span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <input type="email" class="form-control required input-lg" id="email" placeholder="Enter Your Email" name="email" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-12" for="name">Password<span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <input type="password" class="form-control required input-lg" id="password" placeholder="Enter Your Password" name="password" />
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-12 text-center">
                                <button type="submit" data-style="zoom-in" class="btn btn-inline-block btn-primary btn-submit" >Add Host</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End add location Popup -->

<!-- Update User approval section Popup -->
<div id="cashier_approve" class="modal modal-info fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                    <div class="modal-header">
                         <h4 class="modal-title">Cashier Status</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" method="post" id="approveSection">
                        <div class="box-body autocomplete_box">
                        <input type="hidden" name="id" value="">

                            <div class="form-group">
                                <label class="control-label col-sm-12" for="location">Cashier status<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                  <select class="form-control select2"  name="user_status" required>



                                    <option value="1">Active</option>
                                    <option value="2">Block</option>
                                     </select>
                                </div>


                            </div>

                            <div class="form-group text-center">
                            <div class="col-sm-offset-2 col-sm-12">
                                <button type="submit" data-style="zoom-in" class="btn px-4 btn-primary btn-sm btn-submit w-25">Update</button>
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
  var l = false;
  var table_instance;
  table_instance = $('#cashierlist').DataTable({
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
        url: "{{route('admin.cashierlist')}}",
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
            {data: 'userinfo', name: 'username'},
            {data: 'amount_history',"searchable": false, "orderable": false},
            {data: 'account_status',"searchable": false, "orderable": false},
            {data: 'customer',"searchable": false, "orderable": false},
            {data: 'created_at', name: 'created_at'},
            {data: 'action',"searchable": false, "orderable": false},
        ],
        fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {

             if(aData['status']=='0' || aData['status']=='2'){
                $('td', nRow).css('background-color', 'rgb(217, 83, 79);').css('color','white');
            }

        }
  });



    $('#addcashier').on('submit',function(e){
            e.preventDefault();
    });

    $('#addcashier').validate({
        errorClass: 'is-invalid',
        submitHandler: function(form) {
                l = Ladda.create( document.querySelector('#addcashier .btn-submit') );
                l.start();
                $.ajax({
                    url: "{{route('admin.addcashier')}}",
                    method: "POST",
                    data: $("#addcashier").serialize(),
                    success: function (resultData) {
                        l.stop();
                        $('#addcashier .is-invalid').hide();
                         var msg = resultData.message;if(resultData.success)
                        {
                            toastr.success(msg);
                            table_instance.ajax.reload( null, false );
                            $('#addcashier').trigger("reset");

                            $('#addcode').modal('hide');

                        }
                        else if(!resultData.success)
                        {
                            if(resultData.type=="validation-error")
                            {    console.log(resultData.error);
                                $.each( resultData.error, function( key, value ) {
                                    if(key=="hidden_endtime"){
                                    $('#addcode input[name='+key+']').after('<label id="endtime-error" class="is-invalid" for="endtime">End time should be greater than start time.</label>');
                                    }else{
                                        $('#addcode input[name='+key+']').after('<label id="endtime-error" class="is-invalid" for="endtime">'+value+'</label>');
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

 $(document).on("click",".editcashier", function(){
    var id = $(this).attr('data-id');
    $.ajax({
        url: "getCashier",
        method: "get",
        data:{id:id},
        success: function (resultData) {

            var msg = resultData.message;
            if(resultData)
            {
                $('#cashier_approve input[name=id]').val(resultData.id);
                $('#cashier_approve select[name=user_status]').val(resultData.status);


                 $('#cashier_approve').modal('show');

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
                    url: "{{route('CashierApproved')}}",
                    method: "POST",
                    data: $("#approveSection").serialize(),
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
