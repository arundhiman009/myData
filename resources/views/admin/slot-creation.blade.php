@extends('layouts.admin.admin-app')
@section('title')
    Slot Creation - {{ Config::get('app.name') }}
@endsection
@section('page-css')
<link href="{{ asset('assets/plugins/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" media="screen">
<style>
.custom_popup .btn.btn-block.btn-warning.btn-sm{max-width:200px;color:#fff}
.select2-container.select2-container--default.select2-container--open{width:100%;height:auto;min-height:100%}
.select2-container{width:100%!important}
.custom-checkbox>input+label::before,.custom-checkbox>input:checked+label::before{content:""}
.form-group .custom-checkbox{margin:0 15px}.form-group .custom-checkbox>input:checked+label::before{left:-24px;top:4px}
.day.highlight{background:#04c;color:#fff!important}.modal-dialog{max-width:900px}
.table-condensed{width:100%}
.datepicker-inline{width:100%!important}
.new.day,.old.day{visibility:hidden}
.table-condensed thead tr:first-child{background:#ffc107!important;color:#fff!important;pointer-events:none}
.datepicker td,.datepicker th{height:40px!important;width:40px!important;border-radius:unset!important}
.table-condensed thead tr:first-child{background:#ffc107!important;color:#fff!important}
.datepicker .datepicker-switch,.datepicker .next,.datepicker .prev,.datepicker tfoot tr th{background:unset!important}
.datetimepicker-hours thead th,.datetimepicker-minutes thead th{display:none}
.datetimepicker-hours,.datetimepicker-minutes{width:330px}
span.minute{border-radius:unset!important}
tr .next,tr .prev{font-size:0!important;pointer-events:none!important}
</style>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css" rel="stylesheet"/>
@endsection

@section('content')
<div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Time Slot Creation</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Slot Creation</li>
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
              <div class="card-header custom_popup">
                <a href="javascript:;" class="btn px-4 btn-success btn-sm float-sm-right" data-toggle="modal" data-target="#addslot"><i class="mr-1 fa fa-plus" aria-hidden="true"></i> Add Slot</a>
              </div>

              <div class="card-body">
			  <table id="slot" class="table table-bordered table-striped dataTable dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th class="all">Location Name</th>
                        <th>Start Time</th>
                        <th>End Time</th>
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

<div id="addslot" class="modal modal-info fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
                <h4 class="modal-title">Add Slot</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
        <div class="modal-body">
            <form class="form-horizontal" method="post" id="saveslot">
            <div class="box-body">
            <input type="hidden" name="id" value="">
			<div class="row ">
			<div class="col-sm-5">
				<div class="form-group">
                    <label class="control-label col-sm-12" for="startdate">Date<span  class="text-danger">*</span></label>
                    <div class="col-sm-12">
                     <input size="16"  id="startdate" type="hidden"  class="form-control  input-lg" value="" placeholder="Start Date" name="slot_date" required readonly>
                    <div id="calendar"></div>
                    </div>
                </div>
			</div>
			<div class="col-sm-7">
                <div class="form-group">
                    <label class="control-label col-sm-12" for="location">Location<span  class="text-danger">*</span></label>
                    <div class="col-sm-12">
                       <select class="form-control select2 notinlocationdata" name="location" required>
                        <option value="" >Select</option>
                            @if($getNotInSlot)
                            @foreach($getNotInSlot as $locationnot)
                            <option value="{{$locationnot->id}}" >{{$locationnot->name}}</option>
                            @endforeach
                            @endif
                       </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-12" for="startdate">Start Time<span  class="text-danger">*</span></label>
                    <div class="col-sm-12">
                    <div class="controls input-append date starttime"  data-link-field="dtp_input1">
                        <input size="16"  id="starttime" type="text"  class="form-control  input-lg" value="" placeholder="Start Time" name="start_time" required readonly>
                        <span class="add-on"><i class="icon-remove"></i></span>
                        <span class="add-on"><i class="icon-th"></i></span>
                    </div>
                    <input type="hidden" id="dtp_input1" name="hidden_starttime" value="" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-12" for="enddate">End Time<span  class="text-danger">*</span></label>
                    <div class="col-sm-12">
                    <div class="controls input-append date endtime"  data-link-field="dtp_input2" >
                        <input size="16" type="text"  class="form-control  input-lg" id="endtime"  value="" placeholder="End Time" name="end_time" required readonly>
                        <span class="add-on"><i class="icon-remove"></i></span>
                        <span class="add-on"><i class="icon-th"></i></span>
                    </div>
                    <input type="hidden" id="dtp_input2" name="hidden_endtime" value="" />

                    </div>
                </div>
                <div class="form-group">
                <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="allmonth"  name="allmonth" value="0">
                          <label for="allmonth" class="custom-control-label">Every Month</label>
                        </div>
                        </div>
                <div class="form-group">
                <div class="col-sm-offset-2 col-sm-12 text-center">
                    <button type="submit" data-style="zoom-in" class="btn px-4 btn-primary btn-submit">Save</button>
                </div>
                </div></div></div></div>
            </form>
        </div>
        </div>
    </div>
</div>
<!-- End add location Popup -->
<!-- Update location Popup -->
<div id="update_slot" class="modal modal-info fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
        <div class="modal-header">
                <h4 class="modal-title">Update Slot</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>

        </div>
        <div class="modal-body">
            <form class="form-horizontal" method="post" id="updateslot">
            <div class="box-body">
            <input type="hidden" name="id" value="">
			<div class="row ">
				<div class="col-sm-5">
				<div class="form-group">
                    <label class="control-label col-sm-12" for="startdate">Date<span  class="text-danger">*</span></label>
                    <div class="col-sm-12">
                    <input size="16"  id="update_startdate" type="hidden"  class="form-control  input-lg" value="" placeholder="Start Date" name="slot_date" required readonly>
                    <div id="update_calendar"></div></div>
                </div>
				</div>
				<div class="col-sm-7">
                <div class="form-group">
                    <label class="control-label col-sm-12" for="location">Location<span  class="text-danger">*</span></label>
                    <div class="col-sm-12">
                       <select class="form-control select2 alllocationdata" style="width:100%;" name="location" required>
                        <option value="" >Select</option>
                            @if($getAllLocation)
                            @foreach($getAllLocation as $location)
                            <option value="{{$location['id']}}" >{{$location['name']}}</option>
                            @endforeach
                            @endif
                       </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-12" for="startdate">Start Time<span  class="text-danger">*</span></label>
                    <div class="col-sm-12">
                    <div class="controls input-append date starttime" data-date="" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input5">
                        <input size="16"  id="starttime" type="text"  class="form-control  input-lg" value="" placeholder="Start Time" name="start_time" required readonly>
                        <span class="add-on"><i class="icon-remove"></i></span>
                        <span class="add-on"><i class="icon-th"></i></span>
                    </div>
                    <input type="hidden" id="dtp_input5" name="hidden_starttime" value="2021-04-22 23:25:14" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-12" for="enddate">End Time<span  class="text-danger">*</span></label>
                    <div class="col-sm-12">
                    <div class="controls input-append date endtime"  data-date="" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input4" >
                        <input size="16" type="text"  class="form-control  input-lg" id="endtime"  value="" placeholder="End Time" name="end_time" required readonly>
                        <span class="add-on"><i class="icon-remove"></i></span>
                        <span class="add-on"><i class="icon-th"></i></span>
                    </div>
                    <input type="hidden" id="dtp_input4" name="hidden_endtime" value="2021-04-22 01:50:14" />
                    </div>
                </div>
				<div class="form-group">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input" id="update_allmonth"  name="allmonth" value="0">
						<label for="update_allmonth" class="custom-control-label">Every Month</label>
					</div>
				</div>
                <div class="form-group text-center">
                <div class="col-sm-offset-2 col-sm-12">
                    <button type="submit" data-style="zoom-in" class="btn px-4 btn-primary btn-submit">Update</button>
                </div>
                </div></div></div></div>
            </form>
        </div>
        </div>
    </div>
    </div>
<!-- End update location Popup -->
@endsection

@section('page-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
<script src="{{ asset('assets/plugins/datetimepicker/js/bootstrap-datetimepicker.js') }}" charset="UTF-8"></script>
<script>
$(document).on('change','#addslot #endtime,#addslot #starttime',function(){
	var start_actual_time = $("#dtp_input1").val();
	var end_actual_time = $("#dtp_input2").val();
	start_actual_time = new Date(start_actual_time);
	end_actual_time = new Date(end_actual_time);
	var diff = end_actual_time - start_actual_time;
	if(start_actual_time>end_actual_time){
        $('.endtime #endtime-error').remove();
		$('#addslot #endtime').after('<label id="endtime-error" class="is-invalid" for="endtime">End time should be greater than start time.</label>');
        $('.btn-submit').prop('disabled', true);
    }else{
        $('.endtime #endtime-error').hide();
    $('.btn-submit').prop('disabled', false);
    }
});
$(document).on('change','#update_slot #endtime,#update_slot #starttime',function(){
	var start_actual_time = $("#dtp_input5").val();
	var end_actual_time = $("#dtp_input4").val();
	start_actual_time = new Date(start_actual_time);
	end_actual_time = new Date(end_actual_time);
	var diff = end_actual_time - start_actual_time;
	$('.endtime #endtime-error').hide();
    $('.btn-submit').prop('disabled', false);
	if(start_actual_time>end_actual_time){
		$('#update_slot #endtime').after('<label id="endtime-error" class="is-invalid" for="endtime">End time should be greater than start time.</label>');
        $('.btn-submit').prop('disabled', true);
    }
});
    $("#allmonth").on('change',function()
    {
    if(!$(this).is(':checked')){
            $(this).val(0);
    }else{
        $(this).val(1);
    }

    });
    $("#update_allmonth").on('change',function()
    {
    if(!$(this).is(':checked')){
            $(this).val(0);
    }else{
        $(this).val(1);
    }

    });
    $('#calendar').datepicker({
        format: 'dd-mm',
        inline: true,
        lang: 'en',
        step: 5,
        defaultDate: null,
        multidate: 31
    });
    $('#calendar').on('changeDate', function() {
        $('#startdate').val(
            $('#calendar').datepicker('getFormattedDate')
        );
    });
    $('#update_calendar').datepicker({
        format: 'dd-mm',
        inline: true,
        lang: 'en',
        step: 5,
        defaultDate: null,
        multidate: 31
    });
    $('#update_calendar').on('changeDate', function() {
        $('#update_startdate').val(
            $('#update_calendar').datepicker('getFormattedDate')
        );
    });
    $('.starttime').datetimepicker({
        format: 'HH:ii p',
        autoclose: true,
        // todayHighlight: true,
        showMeridian: true,
        startView: 1,
        maxView: 1 ,
        pickerPosition: "top-right"
    });
    $('.endtime').datetimepicker({
        format: 'HH:ii p',
        autoclose: true,
        // todayHighlight: true,
        showMeridian: true,
        startView: 1,
        maxView: 1 ,
        pickerPosition: "top-right"
    });
    var l = false;
    var table_instance;
    table_instance = $('#slot').DataTable({
    lengthChange: false,
    searching: true,
    processing: true,
    serverSide: true,
    retrieve: true,
    paging: true,
    responsive: true,
    pageLength: 10,
    order: [[ 4, 'desc' ]], //Initial no order.
    ajax: {
        url: "{{route('admin.slotlist')}}",
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
            {data: 'location', name: 'location', className : "text-center"},
            {data: 'start_time', name: 'start_time', className : "text-center"},
            {data: 'end_time', name: 'end_time', className : "text-center"},
            {data: 'created_at', name: 'created_at', className : "text-center"},
            {data: 'action', name: 'action', "searchable": false, "orderable": false, className : "text-center"},
        ]
  });

// Add record
	$(document).ready(function(){
		$('.select2').select2();
		$('#saveslot').on('submit',function(e){
			e.preventDefault();
		});

    $('#saveslot').validate({
        errorClass: 'is-invalid',
        submitHandler: function(form) {
                l = Ladda.create( document.querySelector('#saveslot .btn-submit') );
                l.start();
                $.ajax({
                    url: "{{route('admin.saveslot')}}",
                    method: "POST",
                    data: $("#saveslot").serialize(),
                    success: function (resultData) {
                        l.stop();
						$('#saveslot .is-invalid').hide();
                         var msg = resultData.message;if(resultData.success)
                        {
                            toastr.success(msg);
                            table_instance.ajax.reload( null, false );
                            $('#saveslot').trigger("reset");
							$('#calendar').val("").datepicker("update");
                            $('#addslot').modal('hide');
							$('.notinlocationdata').html(resultData.data.getNotInSlot);
							$('.notinlocationdata').html(resultData.data.getNotInSlot);
							$('.notinlocationdata').select2().trigger('change');
							$('.alllocationdata').html(resultData.data.getAllLocation);
							$('.alllocationdata').html(resultData.data.getAllLocation);
							$('.alllocationdata').select2().trigger('change');
							$('#update_slot').modal('hide');

                        }
                        else if(!resultData.success)
                        {
                            if(resultData.type=="validation-error")
                            {    console.log(resultData.error);
                                $.each( resultData.error, function( key, value ) {
									if(key=="hidden_endtime"){
									$('#saveslot input[name='+key+']').after('<label id="endtime-error" class="is-invalid" for="endtime">End time should be greater than start time.</label>');
									}else{
										$('#saveslot input[name='+key+']').after('<label id="endtime-error" class="is-invalid" for="endtime">'+value+'</label>');
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
    $('#updateslot').validate({
        errorClass: 'is-invalid',

        submitHandler: function(form) {
                l = Ladda.create( document.querySelector('#updateslot .btn-submit') );
                l.start();
                $.ajax({
                    url: "{{route('admin.updateslot')}}",
                    method: "POST",
                    data: $("#updateslot").serialize(),
                    success: function (resultData) {
						l.stop();
						$('#saveslot .is-invalid').hide();
                         var msg = resultData.message;
						if(resultData.success)
                        {
                            toastr.success(msg);
                            table_instance.ajax.reload( null, false );
							$('.notinlocationdata').html(resultData.data.getNotInSlot);
							$('.notinlocationdata').html(resultData.data.getNotInSlot);
							$('.notinlocationdata').select2().trigger('change');
							$('.alllocationdata').html(resultData.data.getAllLocation);
							$('.alllocationdata').html(resultData.data.getAllLocation);
							$('.alllocationdata').select2().trigger('change');
							$('#update_slot').modal('hide');
                        }
                        else if(!resultData.success)
                        {
                            if(resultData.type=="validation-error")
                            {
                                $.each( resultData.error, function( key, value ) {
									if(key=="hidden_endtime"){
									$('#updateslot input[name='+key+']').after('<label id="endtime-error" class="is-invalid" for="endtime">Start time should be greater than end time.</label>');
									}else{
										$('#updateslot input[name='+key+']').after('<label id="endtime-error" class="is-invalid" for="endtime">'+value+'</label>');
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
 });
 $(document).on("click",".editlocation", function(){
    var id = $(this).attr('data-id');
    $.ajax({
        url: "{{route('admin.getslot')}}",
        method: "POST",
        data:{id:id},
        success: function (resultData) {
            var msg = resultData.message;
			if(resultData.success)
            {
				//console.log(resultData.data.data.location_id);
                $('#updateslot input[name=id]').val(resultData.data.data.id);
                $('#updateslot select').val(resultData.data.data.location_id);
                $('#updateslot select').select2().trigger('change');
                $('#updateslot #startdate').val(resultData.data.data.slot_date);
                $('#updateslot #starttime').val(resultData.data.data.start_time);
                $('#updateslot #endtime').val(resultData.data.data.end_time);
                $('#update_calendar').val(resultData.data.data.end_time);
                //resultData.data.selected_date
                //new Date(['2021, 4, 4','2021, 5, 4'])
                if(resultData.data.data.is_reacting==1){
                    $('#updateslot input[name=allmonth]').prop('checked', true);
                }else{
                    $('#updateslot input[name=allmonth]').prop('checked', false);
                }
                // new Date(2021, 4, 4)
				$('#update_calendar').datepicker('remove');
                arr = "";
                var selecteddate = resultData.data.selected_date.split(',');

				var check = moment(new Date(), 'YYYY/MM/DD');
				var day   = check.format('D');
				var year  = check.format('YYYY');
				if(resultData.data.data.is_reacting==1){
					var month = check.format('M');
					var txt = "is_reacting";
				}else{
                    var month = check.format('M');
                    if(month==resultData.data.data.slot_month){
                        var month = resultData.data.data.slot_month;
                    }else{
                        var month = check.format('M');
                        selecteddate = "";
                    }


					var txt = "not_is_reacting";
				}
                var arrayval = [];
                $.each(selecteddate, function( index, value ) {
					arrayval.push('"'+value+'-'+month+'-'+year+''+'"');
                });

                $('#update_calendar').datepicker({
                    format: 'dd-mm-yyyy',
                    inline: true,
                    lang: 'en',
                    step: 5,
                    defaultDate: null,
                    multidate: 31
                });
                $('#update_calendar').datepicker().datepicker('setDate',arrayval );

                $('#update_slot').modal('show');
            }
        }
    });

    });
    function removelocation(obj,id) {
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
                url: "{{route('admin.deleteslot')}}",
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

						//console.log(resultData.data.getAllLocation);
						$('.notinlocationdata').html(resultData.data.getNotInSlot);
						$('.notinlocationdata').html(resultData.data.getNotInSlot);
						 $('.notinlocationdata').select2().trigger('change');
						 $('.alllocationdata').html(resultData.data.getNotInSlot);
						$('.alllocationdata').html(resultData.data.getNotInSlot);
						 $('.alllocationdata').select2().trigger('change');
                        table_instance.ajax.reload( null, false );

                    }
                }
            });
        }
        })
    }
</script>
@endsection
