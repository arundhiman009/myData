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
        .card-header.custom_popup a{padding: 6px 16px;}

    </style>

@endsection
@section('content')

    <div class="content-wrapper">
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
                            <div class="card-body" id="show-hide-card" style="display:none">
                                <div class="row pr-2">
                                    @if(Auth::user()->getRoleNames()->first() == 'Admin')
                                        <div class="col-sm-6 form-group mb-2 col-md-3">
                                            {!! Form::select('search_cashier_id', $cashier_list, '', ['id'=>'search_cashier_id','class' => 'form-control select2','title' => 'Select Cashier','placeholder'=>'Select Cashier']); !!}
                                        </div>
                                    @endif
                                    @if(Auth::user()->getRoleNames()->first() == 'Admin' || Auth::user()->getRoleNames()->first() == 'Cashier')
                                        <div class="col-sm-6 form-group mb-2 col-md-3">
                                            {!! Form::select('search_customer_id', $customer_list, '', ['id'=>'search_customer_id','class' => 'form-control select2','title' => 'Select Customer','placeholder'=>'Select Customer']); !!}
                                        </div>
                                    @endif
                                    <div class="col-sm-6 form-group mb-2 col-md-3">
                                        {!! Form::select('search_request_type', ['1' => 'Online', '2' => 'Pick Up'], '', ['id'=>'search_request_type','class' => 'form-control select2','title' => 'Select Request Type','placeholder'=>'Select Request Type']); !!}
                                    </div>
                                    <div class="col-sm-6 form-group mb-2 col-md-3">
                                        {!! Form::select('search_request_status', ['0' => 'Processing', '2' => 'Approved', '1' => 'Rejected'], '', ['id'=>'search_request_status','class' => 'form-control select2','title' => 'Select Request Status','placeholder'=>'Select Request Status']); !!}
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
                            @if(Auth::user()->getRoleNames()->first() == 'User')
                            <div class="card-header custom_popup">
                                <a href="javascript:;" id="cashout_request_model" class="btn px-4 btn-success btn-sm float-sm-right"><i class="mr-1 fas fa-plus" aria-hidden="true"></i>
                                    Submit Cashout Request
                                </a>
                            </div>
                            @endif
                            <div class="card-body">
                                <table id="user_request" class="table table-bordered table-striped dataTable dt-responsive nowrap w-100">
                                    <thead class="text-center shankar">
                                        <tr>
                                            <th class="text-center">Customer Info</th>
                                            <th class="text-center">Host Info</th>
                                            <th class="text-center">Amount</th>
                                            <th class="text-center">Payment Source</th>
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
    <div id="cashout_request" class="modal modal-info fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Cashout Request </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="cashoutrequest">
                        <div class="box-body">
                            <input type="hidden" name="id" id="form_id" value="">
                            <div class="form-group">
                                <label class="control-label col-sm-12" for="amount">Amount<span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text type_symbol">$</span>
                                        </div>
                                        <input type="text" class="form-control input-lg required" id="amount" placeholder="Amount" name="amount" step="0.01">
                                         <label id="amount-error" class="amount-error is-invalid" for="amount"
                                                    style="display:none"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-12" for="payment_method">Payment Source<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control payment_method select2 required" name="method">
                                        <option value="">Select</option>
                                        <option value="1">Online</option>
                                        <option value="2">Pick Up</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group online_payment" style="display:none;">
                                <label class="control-label col-sm-12" for="paypal_id">Payment Method<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control paypal_id select2 required" name="paypal_id">
                                        <option value="">Select</option>
                                        @if(count($payment_methods) > 0)
                                            @foreach($payment_methods as $payment_method)
                                            <option value="{{$payment_method}}">{{$payment_method}}</option>
                                            @endforeach
                                        @else
                                            <option value="Venmo">Venmo</option>
                                            <option value="Cash App">Cash App</option>
                                            <option value="Zelle">Zelle</option>
                                            <option value="Paypal">Paypal</option>

                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="offline_payment" style="display:none;">
                                <div class="form-group">
                                    <label class="control-label col-sm-12" for="offline_location">Choose Pick Up Location<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <select class="form-control offline_location select2 required" name="offline_location_id">
                                            <option value="">Select Pick Up Location</option>
                                            @foreach ($locations as $location)
                                                <option value="{{ $location->id }}">{{ $location->name }}
                                                @if(empty($location->cashoutSlot))
                                                @else
                                                    ({{ $location->cashoutSlot->start_time.' - '.$location->cashoutSlot->end_time }})
                                                @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="offline_payment_date" style="display:none">
                                <div class="form-group">
                                    <label class="control-label col-sm-12" for="offline_location_slot">Choose Pick Up Day<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <select class="form-control offline_location_slot select2 required" name="offline_location_slot">
                                            <option value="">Select Pick Up Day</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-12 text-center">
                                    <button type="submit" data-style="zoom-in" class="btn btn-primary btn-submit">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Modal Comes for admin and cashier Popup -->
    <div id="update_request_status" class="modal modal-info fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Cashout Money Request by <span class="" id="username">User</span></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" method="post" id="updateForm">
                        <div class="box-body">
                            <input type="hidden" name="id" id="forms_id" value="">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Amount</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text type_symbol">$</span>
                                            </div>
                                            <input type="number" id="txn" class="form-control" disabled />
                                           
                                        </div>
                                    </div>
                               </div>
                               <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Payment Source</label>
                                        <div class="input-group">
                                            <input type="text" id="promo" class="form-control" disabled />
                                        </div>
                                    </div>
                               </div>
                               <div class="col-md-6 payment_detail" >
                                    <div class="form-group">
                                        <label class="control-label">Payment Method</label>
                                        <div class="input-group">
                                            <input type="text" id="reference" class="form-control" disabled />
                                        </div>
                                    </div>
                               </div>
                               <div class="col-md-6 location" >
                                    <div class="form-group">
                                        <label class="control-label">Location</label>
                                        <div class="input-group">
                                            <input type="text" id="location" class="form-control" disabled />
                                        </div>
                                    </div>
                               </div>
                               <div class="col-md-6 slot_book">
                                <div class="form-group">
                                    <label class="control-label" for="bonus">Slot Booked for</label>
                                    <div class="input-group">
                                        <input type="text" id="bonus" class="form-control" disabled />
                                    </div>
                                </div>
                           </div>
                               <div class="col-md-6">
                                    <div class="form-group mx-0">
                                        <label class="control-label" for="status">Status<span class="text-danger">*</span></label>
                                        <select class="form-control select2 ap_status required" required style="width: 100%;" id="status" name="status"
                                            required>
                                            <option value="0" selected>Processing</option>
                                            <option value="1">Rejected</option>
                                            <option value="2">Approved</option>
                                        </select>
                                    </div>
                               </div>
                                <div class="col-sm-12">
                                    <div class="form-group mx-0">
                                        <label class="control-label" for="comments">Comments<span class="text-danger">*</span></label>
                                        <textarea required name="comment" id="comment" class="form-control required"
                                            data-target="#update_comment">
                                        </textarea>
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
    var defaultDate;
    $('.select2').select2();
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

        $("#cashout_request_model").on("click", function() {
            defaultDate = null;
            $("#form_id").val("");
            $("#amount").val("");
            $('select[name=method]').val("");

            $('select[name=method]').select2().trigger('change');
            $('.offline_payment_date').hide();
            $("#paypal_id").val("");
            $('select[name=offline_location_id]').val("");
            $('select[name=offline_location_id]').select2().trigger('change');
            $('select[name=offline_location_slot]').val("").trigger('change');
            $("#cashout_request").modal('show');
        });


        $('.payment_method').select2().on("change", function(e) {
            var txt = $(this).val();
            $('.online_payment').hide();
            $('.offline_payment').hide();

            if (txt == '1') {
                $('.online_payment').show();
                $('.offline_payment_date').hide();
            }
            if (txt == '2') {
                $('.offline_payment').show();
            }
        });

        $(".offline_location").on('change', function() {
            if($(this).val()) {
                $('.offline_payment_date').show();

                $.ajax({
                    url: "{{ route('cashout.slots') }}",
                    method: "POST",
                    data: {
                        "location": $(".offline_location").val()
                    },
                    success: function(resultData) {
                        let opt = "<option value=''>Select</option>";
                        if (resultData.data != undefined && resultData.data.length > 0) {
                            resultData.data.forEach(element => {
                                var option = element.split("-");
                                opt += "<option value='" + element + "'>" + option[1]+'/'+option[2]+'/'+option[0] + "</option>"
                            });
                        }
                        $(".offline_location_slot").html(opt);
                        if(defaultDate){
                            $('select[name=offline_location_slot]').val(defaultDate.toString()).trigger('change');
                        }
                        else {
                            $('select[name=offline_location_slot]').val("");
                            $('select[name=offline_location_slot]').select2().trigger('change');
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
            }
        });

        var amountlimit = "";
        $('#cashoutrequest').validate({
            rules: {
                amount: {
                    required: true,
                    number: true,
                }
            },
            errorClass: 'is-invalid',
            submitHandler: function(form) {
                l = Ladda.create(document.querySelector('#cashoutrequest .btn-submit'));
                l.start();
                $.ajax({
                    url: "{{ route('cashout.store') }}",
                    method: "POST",
                    data: $("#cashoutrequest").serialize(),
                    success: function(resultData) {
                        l.stop();
                        var msg = resultData.message;
                        if (resultData.success) {
                            toastr.success(msg);
                            table_instance.ajax.reload( null, false );
                            $('#cashoutrequest').trigger("reset");
                            $('#cashout_request').modal('hide');
                        } else if (!resultData.success) {
                            if (resultData.type == "validation-error") {
                                $.each(resultData.error, function(key, value) {
                                     
                                    $('#' + key + '-error').show().html(value);
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

            // Submit form
        $('#updateForm').validate({
            errorClass: 'is-invalid',
            submitHandler: function(form) {
                l = Ladda.create(document.querySelector('#updateForm .btn-submit'));
                l.start();
                $.ajax({
                    url: "{{ route('admin.cashout.status') }}",
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
                                toastr.error(resultData.error);
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

    $(document).on("click",".editRequest", function() {
        var id = $(this).attr('data-id');
        var amt = $(this).attr('data-amt');
        var mode = $(this).attr('data-mode');
        var pay_id = $(this).attr('data-pay_id');
        var off_loc = $(this).attr('data-off_loc');
        var off_loc_slot = $(this).attr('data-off_loc_slot');
        defaultDate = off_loc_slot;



        $("#form_id").val(id);
        $("#amount").val(amt);
        $('select[name=method]').val(mode);


        $('select[name=method]').select2().trigger('change');
        $('select[name=paypal_id]').val(pay_id);
        $('select[name=paypal_id]').select2().trigger('change');
        $('select[name=offline_location_id]').val(off_loc);
        $('select[name=offline_location_id]').select2().trigger('change');
        $('select[name=offline_location_slot]').val(defaultDate.toString()).trigger('change');

        $("#cashout_request").modal('show');
    });

    $(document).on("click", ".approveBox", function() {

        $("#update_request_status").modal('show');

        var id = $(this).attr('data-id');
        var user_id = $(this).attr('data-user_id');
        var username = $(this).attr('data-user-name');
        var amt = $(this).attr('data-amt');
        var mode = $(this).attr('data-mode');
        var location = $(this).attr('data-payment-location');
        var pay_id = $(this).attr('data-pay_id');
        var off_loc = $(this).attr('data-off_loc');
        var status = $(this).attr('data-status');
        var off_loc_slot = $(this).attr('data-off_loc_slot');
        var comments = $(this).attr('data-comments');

        $(".payment_detail").css('display','none');
        $(".slot_book").css('display','none');
        $(".location").css('display','none');


        $('#username').text(username);
        $(".ap_status").val(status).change();
        $("#comment").val(comments);
        $("#location").val(location);
        $("#forms_id").val(id);
        $("#user_id").val(user_id);
        $("#tx_number").val(amt);
        $("#txn").val(amt)
        let mode_text = mode == 2 ? 'Pick Up' : 'Online';
        $("#promo").val(mode_text)
        $("#reference").val(pay_id)
        $("#bonus").val(off_loc_slot)
        if(mode == '1'){
          $(".payment_detail").css('display','block');
        }
        if(mode == '2'){
            $(".location").css('display','block');
           $(".slot_book").css('display','block');
        }
    });

    var l = false;
    var search_cashier_id;
    var search_customer_id;
    var search_request_type;
    var search_request_status;
    var search_request_between;

    var table_instance;
    table_instance = $('#user_request').DataTable({
        searching: false,
        processing: true,
        serverSide: true,
        retrieve: true,
        paging: true,
        responsive: true,
        order: [
            [5, 'desc']
        ], //Initial no order.
        ajax: {
            url: "{{ route('cashout.list') }}",
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
            }
        },
        columnDefs:[{
            'targets': [0,1,2,3,6], // column index (start from 0)
            'orderable': false, // set orderable false for selected columns
        },
        {
            "responsivePriority": 1,
            "targets": -1
        },
        {
            "responsivePriority": 2,
            "targets": 1
        }],
        columns: [{
            data:'username', name:'user.username', className:"text-left"
        },
        {
            data:'cashierinfo', name:'to_user.username', className:"text-left"
        },
        {
            data: 'payment_info', name: 'payment_info', className: "text-left"
        },
        {
            data: 'payment_source', name: 'payment_source', className: "text-left"
        },
        {
            data: 'status',
            name: 'status',
            className: "text-center"
        },
        {
            data: 'created_at',
            name: 'cashout_requests.created_at',
            className: "text-center"
        },
        {
            data: 'action',
            name: 'Action',
            className: "text-center"
        }],
        fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            if(aData['status'].charAt(0)=='A'){
                $('td', nRow).css('background-color', 'rgb(92,184,92)').css('color','white');
            }
            else if(aData['status'].charAt(0)=='R'){
                $('td', nRow).css('background-color', '#d9534f').css('color','white');
            }
            else{
                $('td', nRow).css('background-color', 'rgb(255,171,53)').css('color','white');
            }
        }
    });

    function removeRequest(obj,id){
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to delete?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Delete it!'
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{route('cashout.delete')}}",
                    method: "POST",
                    data:{id:id},
                    success: function (resultData) {
                        if(resultData.success)
                        {
                            toastr.success( resultData.message);
                        }
                        else {
                            toastr.error( resultData.error);
                        }
                        table_instance.ajax.reload( null, true);
                    }
                });
            }
        })
    }

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
