@extends('layouts.admin.admin-app')

@section('title')
    Cashout Request - {{ Config::get('app.name') }}
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
            <h1>Cashout Request</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Cashout Request</li>
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
              
              <!-- /.card-header -->
              <div class="card-body">
              <table id="user_request" class="table table-bordered table-striped dataTable dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th class="all">Name</th>
                        <th >Amount</th>
                        <th>Method</th>
                        <th>Location</th>
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


     {{-- Model for approval --}}
    <!-- Update location Popup -->
    <div id="update_request_status" class="modal modal-info fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Load Money Request by <span class="" id="username">User</span></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" method="post" id="updateForm">
                        <div class="box-body">
                            <input type="hidden" name="id" id="form_id" value="">
                            <input type="hidden" name="user_id" id="user_id" value="">
                           <div class="form-row">
                               <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-12" for="txn">Amount
                                            <div class="col-sm-12">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text type_symbol">$</span>
                                                    </div>
                                                    <input type="number" name="txn" id="txn" class="form-control" disabled />
                                                </div>
                                            </div>
                                    </div>
                               </div>
                               <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-12" for="promo">Method
                                        <div class="col-sm-12">
                                            <div class="input-group">
                                            <input type="text" name="promo" id="promo" class="form-control" disabled />
                                            </div>
                                        </div>
                                    </div>
                               </div>
                               <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-12" for="reference">Paypal Id
                                        <div class="col-sm-12">
                                            <div class="input-group">
                                                <input type="text" name="reference" id="reference" class="form-control"
                                                disabled />
                                            </div>
                                        </div>
                                    </div>
                               </div>
                               <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-12" for="bonus">Slot Booked for
                                        <div class="col-sm-12">
                                            <div class="input-group">
                                            <input type="text" name="bonus" id="bonus" class="form-control" disabled />
                                            </div>
                                        </div>
                                </div>
                           </div>
                               <div class="col-md-12">
                                    <div class="form-group mx-2">
                                        <label class="control-label col-sm-12 mb-0" for="status">Status<span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-12">
                                            <select class="form-control select2 ap_status required" required style="width: 100%;" id="status" name="status"
                                                required>
                                                <option value="0" selected>Pending</option>
                                                <option value="1">Reject</option>
                                                <option value="2">Approve</option>
                                            </select>
                                        </div>
                                    </div>
                               </div>
                                <div class="col-sm-12">
                                        <div class="form-group mx-2">
                                            <label class="control-label col-sm-12" for="comments">Comments<span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-12" id="update_comment">
                                            <textarea required name="comment" id="comment" class="form-control required"
                                                data-target="#update_comment">
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 d-flex justify-content-center">
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-12">
                                            <button type="submit" data-style="zoom-in" 
                                                class="btn btn-primary btn-lg btn-submit px-3">Update</button>
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
    <!-- End update location Popup -->

@endsection

@section('page-scripts')
<script>
         $('.select2').select2();
        var l = false;
        var table_instance;
        table_instance = $('#user_request').DataTable({
        lengthChange: false,
        searching: true,
        processing: true,
        serverSide: true,
        retrieve: true,
        paging: true,
        responsive: true,
        pageLength: 10,
        order: [[ 6, 'desc' ]], //Initial no order.
        ajax: {
            url: "{{route('admin.cashout.list')}}",
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
                {data: 'amount', name: 'amount', className : "text-center"},
                {data: 'method', name: 'method', className : "text-center"},
                {data: 'location', name: 'location', className : "text-center"},
                {data: 'status', name: 'status', className : "text-center"},
                {data: 'created_at', name: 'created_at', className : "text-center"},
                {data: 'action', name: 'action', className : "text-center"}
            ],
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                     if(aData['status']=='Accept'){
                $('td', nRow).css('background-color', 'rgb(92,184,92)').css('color','white');
                  }
                   else if(aData['status']=='Pending'){
                $('td', nRow).css('background-color', 'grey').css('color','white');
                  }
                   else if(aData['status']=='Reject'){
                $('td', nRow).css('background-color', '#d9534f').css('color','white');
                  }
                  else{
                     $('td', nRow).css('background-color', 'rgb(255,171,53)').css('color','white');
                  }


                  
                      } 
    });
    $(function() {
            $(document).on("click", ".approveBox", function() {

                $("#update_request_status").modal('show');

                var id = $(this).attr('data-id');
                var user_id = $(this).attr('data-user_id');
                var amt = $(this).attr('data-amt');
                var mode = $(this).attr('data-mode');
                var pay_id = $(this).attr('data-pay_id');
                var off_loc = $(this).attr('data-off_loc');
                var status = $(this).attr('data-status');
                var off_loc_slot = $(this).attr('data-off_loc_slot');
                var comments = $(this).attr('data-comments');

                $('#username').text($(this).closest('tr').find('td:nth-child(1)').text());
                $(".ap_status").val(status).change();
                $("#comment").val(comments);
                $("#form_id").val(id);
                $("#user_id").val(user_id);
                $("#tx_number").val(amt);
                $("#txn").val(amt)
                let mode_text = mode == 2 ? 'Offline' : 'Online';
                $("#promo").val(mode_text)
                $("#reference").val(pay_id)
                $("#bonus").val(off_loc_slot)

            });

            // Submit form
            $('#updateForm').validate({
                errorClass: 'is-invalid',
                submitHandler: function(form) {
                    l = Ladda.create(document.querySelector('#updateForm .btn-submit'));
                    l.start();
                    $.ajax({
                        url: "{{ route('admin.cashrequest.status') }}",
                        method: "POST",
                        data: $("#updateForm").serialize(),
                        success: function(resultData) {
                            l.stop();
                            var msg = resultData.message;
                            if (resultData.success) {
                                toastr.success(msg);
                                table_instance.ajax.reload(null, false);
                                $('#update_request_status').modal('hide');
                            } else if (!resultData.success) {
                                if (resultData.type == "validation-error") {
                                    $.each(resultData.error, function(key, value) {
                                        $('label.is-invalid').remove();
                                        $('#' + key).after('<label id="' + key +
                                            '-error" class="is-invalid" for="' +
                                            key + '">' + value + '</label>');
                                        $('#' + key).addClass('is-invalid');
                                    });
                                } else {
                                    toastr.error(msg);
                                }
                            }
                        },
                        error: function(jqXHR, exception) {
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
            })

        });
</script>
@endsection
