@extends('layouts.admin.admin-app')

@section('title')
    Load Money - {{ Config::get('app.name') }}
@endsection

@section('page-css')

    <style>


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
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Load Money Offline</h1>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="card card-default">
                            <div class="card-header">
                                <h3 class="card-title"><b>Add money</b></h3>
                            </div>
                            <div class="card-body">
                                <form class="form payment" id="formPay">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Select Game<span class="text-danger">*</span> </label>
                                                <select class="form-control game required select2"  name="game"
                                                    style="width: 100%;">
                                                    <option value=''>Select Game</option>
                                                    <option value="Fire Kiren">Fire Kiren</option>
                                                    <option value="Tiger">Tiger</option>
                                                </select>
                                                <label id="game-error" class="game-error is-invalid" for="game"
                                                style="display:none"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Select Customer<span class="text-danger">*</span> </label>
                                                <select class="form-control game required select2"  name="customer"
                                                    style="width: 100%;">
                                                     <option value=''>Select Customer</option>
                                                    @foreach ($users as $key => $value) {
                                                     <option value="{{$value->id}}">{{$value->username}}</option>
                                                      }

                                                @endforeach
                                                </select>
                                                <label id="customer-error" class="is-invalid" for="customer"  style="display:none">This field is required.</label>
                                            </div>


                                        </div>
                                        <!-- /.form-group -->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                       <div class="form-group">
                                                <label>Amount<span class="text-danger">*</span></label>
                                                <input type="number" onchange="validateForm('init')" min="20"
                                                    placeholder="Enter amount" class="form-control required amount"
                                                    id="amount" name="amount" step="0.01"/>
                                            </div>
                                        </div>

                                         <div class="col-md-6">
                                       <div class="form-group"> 
                                                <label>Payment Mode<span class="text-danger">*</span></label>
                                                <select class="form-control game required select2"  name="payment_method"
                                                    style="width: 100%;">
                                                    <option value="">Select Payment Mode</option>
                                                    <option value="Paypal">Paypal</option>
                                                    <option value="Venmo">Venmo</option>
                                                    <option value="Bank deposit">Bank deposit</option>
                                                    <option value="By Cheque">By Cheque</option>

                                                </select>
                                                 <label id="payment_method-error" class="is-invalid" for="payment_method" style="display:none">This field is required.</label>
                                            </div>

                                        </div>
                                    </div>





                            <!-- /.card-body -->
                            <div >
                                <div class="row">
                                    <div class="table-responsive col-md-6" style="display: none;">
                                        <table class="table m-0">
                                            <tbody>
                                                <tr>
                                                    <td>Just pay</td>
                                                    <td><span id="show_amt">$ 0</span></td>
                                                </tr>
                                                <tr class="promo_row">

                                                </tr>

                                                <tr>
                                                    <td>You will get</td>
                                                    <td><span class="total_amt">$ 0</span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-12 text-center my-4">
                                            <button data-style="zoom-in" class="btn btn-primary btn-submit" type="submit"><span>Submit</span></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>


@endsection
@section('page-scripts')
    <script src="{{ asset('js/custom.js') }}"></script>


    <script>

$('.select2').select2();

function validateForm() {
    $('.promo_code').removeClass('required is-invalid');
    let a = $("form.payment").validate({
        errorClass: 'is-invalid'
        }).form();
    return a;
}

$(document).ready(function(){

    $('#formPay').on('submit',function(e){
        e.preventDefault();
    });
    var amountlimit = "";
    $('#formPay').validate({

        errorClass: 'is-invalid',
        submitHandler: function(form) {
                l = Ladda.create( document.querySelector('#formPay .btn-submit') );
                l.start();
                $.ajax({
                    url: "{{route('load.money.offline.submit')}}",
                    method: "POST",
                    data: $("#formPay").serialize(),
                    success: function (resultData) {
                        l.stop();
                         var msg = resultData.message;
                        if(resultData.success)
                        {
                            toastr.success(msg);
                            $('#formPay').trigger("reset");
                           window.location.href = "{{route('admin.loadmoney.request')}}";

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
