@extends('layouts.admin.admin-app')

@section('title')
    Load Money Report - {{ Config::get('app.name') }}
@endsection
@section('page-css')
<style>
.custom_popup .btn.btn-block.btn-warning.btn-sm{max-width:200px;color:#fff}
.select2-container.select2-container--default.select2-container--open{width:100%;height:auto;min-height:100%}
.select2-container{width:100%!important}
.payment_detail{margin-top:20px}
.promo-listing{margin-left:-8px}
.popupBox{margin-left:15px;width:96%}
.outstandingSection{height:0;position:relative;left:92px;top:8px}
</style>

@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Load Money Report</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Load Money Report</li>
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
                            <div class="card-body" id="show-hide-card" style="display:none">
                                <div class="row pr-2">
                                    @if(Auth::user()->getRoleNames()->first() == 'Admin')
                                        <div class="col-sm-6 form-group mb-2 col-md-3">
                                            {!! Form::select('search_cashier_id', $cashier_list, '', ['id'=>'search_cashier_id','class' => 'form-control select2','title' => 'Select Cashier','placeholder'=>'Select Host']); !!}
                                        </div>
                                    @endif
                                    <div class="col-sm-6 form-group mb-2 col-md-3">
                                        {!! Form::select('search_request_status', ['0' => 'Outstanding', '1' => 'Request Sent', '2' => 'Approved'], '', ['id'=>'search_request_status','class' => 'form-control select2','title' => 'Select Request Status','placeholder'=>'Select Request Status']); !!}
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
                            <!-- <div class="card-header">
                                OutStanding:<h1 class="" id="outstanding"></h1>
                            </div> -->


                            <div class="card-body">
                                <table id="user_request" class="table table-bordered table-striped dataTable dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th class="all text-center"><input type="checkbox" name="checkAll" class="checkAll"/></th>
                                            <th class="text-center">Host Info</th>
                                            <th class="text-center">Payment Info </th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Comment</th>
                                            <th class="text-center">Creation Date  </th>
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
      <!-- Update Modal Comes for admin and cashier Popup -->
    <div id="update_request_status" class="modal modal-info fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Send Request by <span class="" id="username">{{Auth::user()->username}}</span></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" method="post" id="updateForm">
                        <div class="box-body">

                            <div class="form-row">

                               <input type="hidden" name="ids" class="submitIds" value="[]"/>
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

    $('.select2').select2();

    var i;
    var isAdmin = '{{ Auth::user()->getRoleNames()->first() }}';
    if(isAdmin == "Admin"){
      var button_name ="admin_approve";
      var buttonName = "Approve";
    }
    else if(isAdmin == "Cashier"){
        var button_name ="status_approve";
        var buttonName = "Send Request";
    }

    var l = false;
    var search_cashier_id;
    var search_request_status;
    var search_request_between;
    var table_instance;
    table_instance = $('#user_request').DataTable({
        drawCallback: function (oSettings) {

            $("#user_request_wrapper .row mb-5.col-sm-12.col-md-6:last").html('<div class="outstandingSection">Outstanding Amount: <span class="font-weight-bold" id="outstanding"></span> </div><button   class="btn btn-primary  btn-submit float-right px-3 ' + button_name + ' "  >'+ buttonName +'</button>');

            $('#outstanding').text(this.api().ajax.json().outstanding);

        },
        searching: false,
        processing: true,
        serverSide: true,
        retrieve: true,
        paging: true,
        bSortable:true,
        responsive: true,
        order: [
            [4, 'desc']
        ],
        ajax: {
            url: "{{ route('admin.loadmoney.report.list') }}",
            method: 'POST',
            data:function(d)
            {
               
                d.search_cashier_id = search_cashier_id;
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
            'targets': [0,1,2], // column index (start from 0)
            'orderable': false, // set orderable false for selected columns
        },
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
        }],
        columns: [{
            data: 'request_status',
            name: '#',
            className:"text-left",
            render: function(data, type, row)
            {

                if(isAdmin == "Admin")
                {

                    if(data == 'Request Sent'){

                        return '<input type="checkbox" value='+ row['id'] +' name="checkStatus[]" class=" check_status"/>';
                    }
                    else
                    {

                       return '';

                    }
                }
                else if(isAdmin == "Cashier")
                {
                    if(data == 'Outstanding')
                    {
                        return '<input type="checkbox" class="check_status" name="checkStatus[]" value='+ row['id'] +'>';
                    }
                    else
                    {
                       return '';

                    }

                }
            }
        },
        {
            data:'cashierinfo', name:'to_user.username', className:"text-left"
        },
        {
            data:'payment_info', name:'payment_info', className:"text-left", sort:'false'
        },
        {
            data:'request_status', name:'payment_infos.status', className:"text-center",
            render: function(data, type, row)
            {
                if(isAdmin == "Admin")
                {

                    if(data == 'Request Sent')
                    {

                        return 'Request Recieved';
                    }

                }

                return data;

            }
        },
        {
            data:'comment', name:'payment_infos.comments', className:"text-center"
        },
        {
            data:'created_at', name:'payment_infos.created_at', className:"text-center"
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

    });

    $(document).on("click", ".status_approve", function() {

        var ids = $("[name='checkStatus[]']:checked").map(function() {   return this.value;  }).get();

        if(ids.length > 0){
            $("#update_request_status").modal('show');
            $('.submitIds').val(ids); // here we using hidden filed to send the data
            $('#comment').val('');
        }
        else
        {
            toastr.error('No transaction select');

        }

    });


   $(document).on("click", ".admin_approve", function() {

   transactionIds = $("[name='checkStatus[]']:checked").map(function() {   return this.value;  }).get();
   if(transactionIds.length > 0){
         Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to approve the request?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Approve it!'
                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{route('admin.accept.request')}}",
                        method: "POST",
                        data:{ids:transactionIds },
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
    else
    {
        toastr.error('No transaction select');

    }

    });


  //submit form cashier send request

    $('#updateForm').validate({
            errorClass: 'is-invalid',
            submitHandler: function(form) {
                l = Ladda.create(document.querySelector('#updateForm .btn-submit'));
                l.start();
                $.ajax({
                    url: "{{ route('cashier.send.request') }}",
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
    function applyFilter()
    {
        l = Ladda.create( document.querySelector( '.search-button' ) );
        l.start();
        search_cashier_id = $('#search_cashier_id').val();
        search_request_status = $('#search_request_status').val();
        search_request_between = $('#search_request_between').val();
        table_instance.ajax.reload(null,true);
        return false;
    }

    $(".checkAll").change(function(){

        if ($('input:checkbox').prop('checked') ) {

            $('input:checkbox').prop('checked',true);
        }
        else
        {
            $('input:checkbox').prop('checked', false);
        }

   });





</script>
@endsection
