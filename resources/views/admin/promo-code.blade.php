@extends('layouts.admin.admin-app')

@section('title')
    Promo Code - {{ Config::get('app.name') }}
@endsection

@section('page-css')
<style>
.custom_popup .btn.btn-block.btn-warning.btn-sm{max-width:200px;color:#fff}
.select2-container.select2-container--default.select2-container--open{width:100%;height:auto;min-height:100%}
.select2-container{width:100%!important}
</style>
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Promo Code</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Promo Code</li>
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
                        <div class="card-header custom_popup">
                            <a href="javascript:;" class="btn px-4 btn-success btn-sm float-sm-right" data-toggle="modal" data-target="#addcode"><i class="mr-1 fa fa-plus" aria-hidden="true"></i> Add Promo code</a>
                        </div>
                        <div class="card-body">
                            <table id="promocodelist" class="table table-bordered table-striped dataTable dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th class="all">Name</th>
                                        <th>Amount</th>
                                        <th>Expiry Date</th>
                                        <th>Status</th>
                                        <th>Limit</th>
                                        <th>Discount Type</th>
                                        <th>Creation Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div id="addcode" class="modal modal-info fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Promo Code</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="post" id="savepromocode">
                    <div class="box-body">
                        <input type="hidden" name="id" value="">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-12" for="name">Name<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control input-lg" id="name" placeholder="Name" name="name" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-12" for="status">Status<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <select class="form-control select2" style="width: 100%;" name="status" required>
                                            <option value="1" selected>Active</option>
                                            <option value="0" >Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-12" for="limit">Limit<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control input-lg" id="limit" placeholder="Limit" name="limit" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-12" for="discount_type">Discount Type<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <select class="form-control select2" name="discount_type" required>
                                            <option value="">Select</option>
                                            <option value="0">Fixed</option>
                                            <option value="1">Percentage</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-12" for="amount">Amount<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text type_symbol">$</span>
                                            </div>
                                            <input type="text" class="form-control input-lg" id="amount"  min="0" placeholder="Amount" name="amount" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-12" for="expiry_date">Expiry Date<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <div class="input-group date" id="expiry_date" data-target-input="nearest">
                                            <input type="text" name="expiry_date" class="form-control datetimepicker-input" data-target="#expiry_date"/>
                                            <div class="input-group-append" data-target="#expiry_date" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 text-center">
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-12">
                                        <button type="submit" data-style="zoom-in" class="btn px-4 btn-primary btn-submit">Save</button>
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
<div id="update_promo" class="modal modal-info fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Coupon</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="post" id="updatepromo">
                    <div class="box-body">
                        <input type="hidden" name="id" value="">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-12" for="name">Name<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control input-lg" id="name" placeholder="Name" name="name" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-12" for="status">Status<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <select class="form-control select2" style="width: 100%;" name="status" required>
                                        <option value="1" selected>Active</option>
                                            <option value="0" >Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-12" for="limit">Limit<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control input-lg" id="limit" placeholder="Limit" name="limit" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-12" for="discount_type">Discount Type<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                    <select class="form-control select2" name="discount_type" required>
                                        <option value="" >Select</option>
                                        <option value="0" >Fixed</option>
                                        <option value="1" >Percentage</option>
                                    </select>
                                    </div>
                                </div>
                            </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label col-sm-12" for="amount">Amount<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text type_symbol">$</span>
                                        </div>
                                        <input type="text" class="form-control input-lg" id="amount" placeholder="Amount" name="amount" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label col-sm-12" for="expiry_date">Expiry Date<span class="text-danger">*</span></label>
                                <div class="input-group date" id="update_expiry_date" data-target-input="nearest">
                                    <input name="expiry_date" type="text" class="form-control datetimepicker-input" data-target="#update_expiry_date"/>
                                    <div class="input-group-append" data-target="#update_expiry_date" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 text-ceter">
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
    
    var l = false;
  var table_instance;
  table_instance = $('#promocodelist').DataTable({
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
        url: "{{route('admin.promocodelist')}}",
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
            {data: 'name', name: 'name', className : "text-center"},
            {data: 'amount', name: 'amount', className : "text-center"},
            {data: 'expiry_date', name: 'expiry_date', className : "text-center"},
            {data: 'status', name: 'status', className : "text-center"},
            {data: 'limit', name: 'limit', className : "text-center"},
            {data: 'discount_type', name: 'discount_type', className : "text-center"},
            {data: 'created_at', name: 'created_at', className : "text-center"},
            {data: 'action', name: 'action', className : "text-center", "searchable": false, "orderable": false}
        ]
  });

// Add record
$(document).ready(function(){
    
    var date = new Date();
    date.setDate(date.getDate()+1);
    $('#expiry_date').datetimepicker({
        format: 'MM/DD/YYYY',
        minDate: date
    });
    $('#update_expiry_date').datetimepicker({
        format: 'MM/DD/YYYY',
        minDate: date
    });

    $('.select2').select2().on("change", function (e) {
        var txt = $(this).val();
        if(txt=='0'){
            $('.type_symbol').html('$');
            $("#amount").removeClass('percentage');
            $("#amount").val('');
        }
        if(txt=='1'){
            $('.type_symbol').html('%');
            $("#amount").addClass('percentage');
            $("#amount").val('');
        }
    });
    $('#savepromocode').on('submit',function(e){
        e.preventDefault();
    });
    var amountlimit = "";
    $('#savepromocode').validate({
        rules: {
            limit: {
            required: true,
            number: true
            },
            amount: {
            required: true,
            number: true,
            max: function(value,element) {
                var txt =  $('select[name=discount_type]').val();
                if(txt=="1"){
                    return 100;
                }
            }
            },
        },

        errorClass: 'is-invalid',
        submitHandler: function(form) {
                l = Ladda.create( document.querySelector('#savepromocode .btn-submit') );
                l.start();
                $.ajax({
                    url: "{{route('admin.savepromocode')}}",
                    method: "POST",
                    data: $("#savepromocode").serialize(),
                    success: function (resultData) {
                        l.stop();
                         var msg = resultData.message;
                        if(resultData.success)
                        {
                            toastr.success(msg);
                            table_instance.ajax.reload( null, false );
                            $('#savepromocode').trigger("reset");
                            $('#updatepromo select[name=status]').val(1);
                            $('#updatepromo select[name=status]').select2().trigger('change');
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
   $('#updatepromo').validate({
        errorClass: 'is-invalid',
        rules: {
            limit: {
            required: true,
            number: true
            },
            amount: {
            required: true,
            number: true,
            max: function(value,element) {
                var txt =  $('select[name=discount_type]').val();
                if(txt=="percentage"){
                    return 100;
                }
            }
            },
        },
        submitHandler: function(form) {
                l = Ladda.create( document.querySelector('#updatepromo .btn-submit') );
                l.start();
                $.ajax({
                    url: "{{route('admin.updatepromo')}}",
                    method: "POST",
                    data: $("#updatepromo").serialize(),
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
 });

$(document).on("click",".editpromo", function(){
    var id = $(this).attr('data-id');
    $.ajax({
        url: "{{route('admin.getpromo')}}",
        method: "POST",
        data:{id:id},
        success: function (resultData) {
            var msg = resultData.message;
            if(resultData.success)
            {
                if(resultData.data.discount_type=='0'){
                $('.type_symbol').html('$');
                }
                if(resultData.data.discount_type=='1'){
                    $('.type_symbol').html('%');
                }
                $('#updatepromo input[name=id]').val(resultData.data.id);
                $('#updatepromo input[name=name]').val(resultData.data.name);
                $('#updatepromo select[name=status]').val(resultData.data.status);
                $('#updatepromo select[name=status]').select2().trigger('change');
                $('#updatepromo input[name=limit]').val(resultData.data.limit);
                $('#updatepromo select[name=discount_type]').val(resultData.data.discount_type);
                $('#updatepromo select[name=discount_type]').select2().trigger('change');
                $('#updatepromo input[name=amount]').val(resultData.data.amount);
                $('#updatepromo input[name=expiry_date]').val(casioDate(resultData.data.expiry_date));
                $('#update_promo').modal('show');
            }
        }
    });

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
                url: "{{route('admin.deletepromocode')}}",
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
    function casioDate(date){
        return moment(date).format('MM/DD/YYYY');
    }
</script>
@endsection
