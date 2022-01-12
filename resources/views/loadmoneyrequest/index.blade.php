@extends('layouts.admin.admin-app')

@section('title')
    Load Money Request - {{ Config::get('app.name') }}
@endsection
@section('page-css')
<style>
    .custom_popup .btn.btn-block.btn-warning.btn-sm{max-width:200px;color:#fff}
    .select2-container.select2-container--default.select2-container--open{width:100%;height:auto;min-height:100%}
    .select2-container{width:100%!important}
    .payment_detail{margin-top:20px}
    .promo-listing{margin-left:-8px}
    .popupBox{margin-left:15px;width:96%}
</style>
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>
                        @if(Auth::user()->getRoleNames()->first() != 'User')
                            Load Money Request
                        @else
                            Wallet
                        @endif
                       </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">

                            @if(Auth::user()->getRoleNames()->first() != 'User')
                                Load Money Request
                            @else
                                Wallet
                            @endif
                        </li>
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
                            <div class="card-header" id="toggle_filter" style="cursor:pointer">
                                <h3 class="card-title"><b>Advance Filters</b>
                                    <i class="fa fa-chevron-circle-down" id="icon_chev" ></i>
                                </h3>
                            </div>
                            <div class="card-body" id="show-hide-card" style="display:none" >
                                <div class="row pr-2">
                                    @if(Auth::user()->getRoleNames()->first() == 'Admin')
                                        <div class="col-sm-6 form-group mb-2 col-md-3">
                                            {!! Form::select('search_cashier_id', $cashier_list, '', ['id'=>'search_cashier_id','class' => 'form-control select2','title' => 'Select Cashier','placeholder'=>'Select Host']); !!}
                                        </div>
                                    @endif
                                    @if(Auth::user()->getRoleNames()->first() == 'Admin' || Auth::user()->getRoleNames()->first() == 'Cashier')
                                        <div class="col-sm-6 form-group mb-2 col-md-3">
                                            {!! Form::select('search_customer_id', $customer_list, '', ['id'=>'search_customer_id','class' => 'form-control select2','title' => 'Select Customer','placeholder'=>'Select Customer']); !!}
                                        </div>
                                    @endif
                                    <div class="col-sm-6 form-group mb-2 col-md-3">
                                        {!! Form::select('search_request_type', ['Online' => 'Online', 'Offline' => 'Offline'], '', ['id'=>'search_request_type','class' => 'form-control select2','title' => 'Select Request Type','placeholder'=>'Select Request Type']); !!}
                                    </div>
                                    <div class="col-sm-6 form-group mb-2 col-md-3">
                                        {!! Form::select('search_request_status', ['1' => 'Processing', '2' => 'Approved', '3' => 'Hold', '4' => 'Rejected'], '', ['id'=>'search_request_status','class' => 'form-control select2','title' => 'Select Request Status','placeholder'=>'Select Request Status']); !!}
                                    </div>
                                    <div class="col-sm-6 form-group mb-2 col-md-3">
                                        <input type="text" class="form-control" id="search_request_between" name="search_request_between" placeholder="Select date" >
                                        <i class="mdi mdi-calendar-range"></i>
                                    </div>
                                    <div class="col-sm-6 form-group mb-2 col-md-3">
                                        <button class="btn btn-primary search-button" data-style="zoom-in" onclick="return applyFilter()">Search</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            @if(Auth::user()->getRoleNames()->first() != 'User')
                            <div class="card-header custom_popup text-center">
                              <a href="{{route('load.money',['type'=>'offline'])}}" class="btn px-4 btn-success btn-sm float-sm-right"><i class="mr-1 fa fa-plus" aria-hidden="true"></i>Add Manual Request</a>
                            </div>
                            @endif

                            <input type="hidden" value="{{Auth::user()->getRoleNames()->first()}}" id="checkUser"/>
                            <div class="card-body">
                                <table id="user_request"
                                    class="table table-bordered table-striped dataTable dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th class="all text-center">#</th>
                                            <th class="text-center">Customer Info</th>
                                            <th class="text-center">Host Info</th>
                                            <th class="text-center">Payment Info </th>
                                            <th class="text-center">Transaction Info</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Creation Date</th>
                                            <th class="text-center">Action</th>
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
    {{-- Model for approval --}}
    <div id="update_request_status" class="modal modal-info fade payment-card" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Load Money Request by <span class="" id="username"></span></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" method="post" id="updateForm" >
                        <div class="box-body">
                            <input type="hidden" name="id" id="form_id" value="">
                            <input type="hidden" name="tx_number" id="tx_number" value="">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="alert alert-info alert-dismissible popupBox">
                                        <h5><i class="icon fas fa-credit-card"></i>Payment <b id="method_type"></b>
                                        </h5>
                                        <div class="payment_detail">
                                            <p>Promo code has been applied successfully.</p>
                                            <ul class="promo-listing">
                                                <li>Promo code: <b id="promo_name"></b> </li>
                                                <li>Type: <b id="promo_sign"></b> </li>
                                                <li>Promo Discount: <b id="promo_amount"></b></li>
                                                <li>Promo Amount: <b id="promo_transaction"></b></li>
                                            </ul>
                                        </div>
                                        <div class="referrie_code" style="display:none">

                                        </div>
                                        <div class="referral_code" style="display:none">

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group mx-2">
                                        <label class="control-label col-sm-12 mb-0" for="status">
                                            Status<span class="text-danger">*</span>
                                        </label>
                                        <div class="col-sm-12">
                                            <select class="form-control select2 ap_status required" id="status" name="status">
                                                <option value="1" selected>Pending</option>
                                                <option value="2">Approve</option>
                                                <option value="3">Hold</option>
                                                <option value="4">Reject</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group mx-2">
                                        <label class="control-label col-sm-12" for="comments">
                                            Comments<span class="text-danger">*</span>
                                        </label>
                                        <div class="col-sm-12" id="update_comments">
                                            <textarea required name="comments" id="comments" class="form-control required" data-target="#update_comments">
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 d-flex justify-content-center">
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-12">
                                            <button type="submit" data-style="zoom-in" class="btn btn-primary btn-lg btn-submit px-3">Update</button>
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
@endsection
@section('page-scripts')
<script>
    $('.select2').select2();
    var i;
    var isAdmin = $('#checkUser').val();
    if(isAdmin == "User") {
        i = -1;
    }
    var l = false;
    var search_cashier_id;
    var search_customer_id;
    var search_request_type;
    var search_request_status;
    var search_request_between;
    var table_instance;
    table_instance = $('#user_request').DataTable({
        // lengthChange: false,
        searching: false,
        processing: true,
        serverSide: true,
        autoWidth: false,
        responsive: true,
        retrieve: true,
        paging: true,
        bSortable:true,
        responsive: true,
        // pageLength: 10,
        order: [
            [6, 'desc']
        ], //Initial no order.
        ajax: {
            url: "{{ route('admin.loadmoney.list') }}",
            method: 'POST',
            data:function(d)
            {
                d.search_cashier_id = search_cashier_id;
                d.search_customer_id = search_customer_id;
                d.search_request_type = search_request_type;
                d.search_request_status = search_request_status;
                d.search_request_between = search_request_between;

            },
            complete: function(res) {
                if (l) {
                    l.stop();
                }
            },
        },
        columnDefs: [{
            'targets': [0,1,2,3,4,7], // column index (start from 0)
            'orderable': false, // set orderable false for selected columns
        },
        {
            "responsivePriority": 1,
            "targets": -1
        },
        {
            "responsivePriority": 2,
            "targets": 5
        },
        {
            "targets": [ i ],
            "visible": false
        }],
        columns: [{
            data: 'DT_RowIndex',
            name: '#',
        },
        {
            data:'username', name:'user.username', className:"text-left"
        },
        {
            data:'cashierinfo', name:'to_user.username', className:"text-left"
        },
        {
            data:'payment_info', name:'payment_info', className:"text-left", sort:'false'
        },
        {
            data:'transaction_history', name:'transaction_history', className:"text-left"

        },
        {
            data:'request_status', name:'payment_infos.status', className:"text-left"
        },
        {
            data:'created_at', name:'payment_infos.created_at', className:"text-center"
        },
        {
            data:'action', name:'action', className:"text-center",
        }],
        fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {

            if(aData['request_status'].charAt(0)=='A'){
                $('td', nRow).css('background-color', 'rgb(92,184,92)').css('color','white');
            }
            else if(aData['request_status'].charAt(0)=='H'){
                $('td', nRow).css('background-color', 'grey').css('color','white');
            }
            else if(aData['request_status'].charAt(0)=='R'){
                $('td', nRow).css('background-color', '#d9534f').css('color','white');
            }
            else{
                $('td', nRow).css('background-color', 'rgb(255,171,53)').css('color','white');
            }
        }
    });

    $(document).on("click", ".approveTransaction", function() {
        $("#update_request_status").modal('show');
        var id = $(this).attr('data-id');
        var method_type = $(this).attr('data-transaction-type');
        var username = $(this).attr('data-name');
        var tx_number = $(this).attr('data-tx_number');
        var tx = $(this).attr('data-tx');
        var promo_amount_data = $(this).attr('data-promo-amount');
        var promo = $(this).attr('data-promo');
        var promo_type = $(this).attr('data-promo-type');
        var promo_amount = $(this).attr('data-promo-discount');
        var refer = $(this).attr('data-refer');
        var referral_by = $(this).attr('data-refrerral-by');
        var referre = $(this).attr('data-referre');
        var method = $(this).attr('data-method');
        var balance = $(this).attr('data-total');
        var comments = $(this).attr('data-comments');
        var status = $(this).attr('data-status');
        $('#username').text(username);
        $(".ap_status").val(status).change();
        $("#comments").val(comments);

        $("#form_id").val(id);
        $("#tx_number").val(tx_number);
        $("#txn").val(0)
        $("#promo").val(0)
        $("#reference").val(0)
        $("#bonus").val(0)
        $('#method_type').html(method_type);
        $(".payment_detail").css('display','none');
        $(".referrie_code").css('display','none');
        $(".referral_code").css('display','none')
        if (tx != undefined && tx != 0) {
            $("#txn").val(tx)
        }
        if (promo != undefined && promo != 0) {
            $(".payment_detail").css('display','block');
            $('#promo_name').html(promo);
            $('#promo_amount').html(promo_amount);
            $('#promo_transaction').html('$'.promo_amount_data);
            if(promo_type == 1){
                var sign = 'Percentage (%)';
                $('#promo_sign').html(sign);
            }
            else{
                var sign = 'Fixed';
                $('#promo_sign').html(sign);
            }
        }
        if (method == 'referre' ) {

            var final_text = '<p><b>'+username+'</b> has been successfully registered and made first payment. He is referred by <b>'+referral_by+'</b>.</p><ul class="promo-listing"><li><b>'+username+'</b> has got referrie bonus of $'+promo_amount_data+'</li></ul>';

            $(".referrie_code").css('display','block')
            $(".referrie_code").html(final_text)
        }
        if (method == 'referral' ) {
            var final_text = '<p><b>'+referral_by+'</b> has been successfully done first payment and he is referred by <b>'+username+'</b>.</p><ul class="promo-listing"><li><b>'+username+'</b> has got referral bonus $'+promo_amount_data+'</li></ul>';

            $(".referral_code").css('display','block')
            $(".referral_code").html(final_text)
        }
        if (referre != undefined && referre != 0) {
            $("#bonus").val(referre)
        }
        if (balance != undefined && balance != 0) {
            $("#total").val(balance)
        }
    });

    // Submit form
    $(document).ready(function() {
        $('#search_request_between').daterangepicker({
            autoUpdateInput: false,
            locale: { cancelLabel: 'Clear' },
        });
        $('#search_request_between').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        });
        $('#search_request_between').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });

        $('#updateForm').validate({
            errorClass: 'is-invalid',
            submitHandler: function(form) {
                l = Ladda.create(document.querySelector('#updateForm .btn-submit'));
                l.start();
                $.ajax({
                    url: "{{ route('admin.loadmoney.status') }}",
                    method: "POST",
                    data: $("#updateForm").serialize(),
                    success: function(resultData) {
                        l.stop();
                        var msg = resultData.message;
                        if (resultData.success) {
                            toastr.success(msg);
                            table_instance.ajax.reload(null, true);
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
        });
    });

    function applyFilter()
    {
        l = Ladda.create( document.querySelector( '.search-button' ) );
        l.start();
        search_cashier_id = $('#search_cashier_id').val();
        search_customer_id = $('#search_customer_id').val();
        search_request_type = $('#search_request_type').val();
        search_request_status = $('#search_request_status').val();
        search_request_between = $('#search_request_between').val();
        table_instance.ajax.reload(null,true);
        return false;
    }
</script>
@endsection
