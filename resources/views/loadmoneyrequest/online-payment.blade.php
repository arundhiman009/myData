@extends('layouts.admin.admin-app')
@section('title')
    Load Money - {{ Config::get('app.name') }}
@endsection
@section('page-css')
<style>
    .display-table{display:table}
    .display-tr{display:table-row}
    .display-td{display:table-cell;vertical-align:middle;width:61%}
    .message{width:34%;float:right;top:10px;right:10px}
    .select2-container.select2-container--default.select2-container--open{width:100%;height:auto;min-height:100%}
    .select2-container{width:100%!important}
</style>
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Load Money</h1>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="card card-default">
                            <form id="payment_form" action="{{ route('load.money.submit') }}" method="post" class="form payment">
                                @csrf
                                <div class="card-header">
                                    <h3 class="card-title">Add money</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Choose Game <span class="text-danger">*</span> </label>
                                                <select class="form-control game required select2" id="game" name="game">
                                                    <option value=''>Select Game</option>
                                                    <option value="Fire Kiren">Fire Kiren</option>
                                                    <option value="Tiger">Tiger</option>
                                                </select>
                                                <label id="game-error" class="is-invalid" for="game" style="display:{{$errors->has('game') ? 'block' : 'none'}}">
                                                    @if($errors->has('game')) {{ $errors->first('game') }} @endif
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Amount <span class="text-danger">*</span></label>
                                                <input type="number" onchange="validateForm('init')" min="20" placeholder="Enter amount" class="form-control required amount" id="amount" name="amount" step="0.01" />
                                                <label id="amount-error" class="is-invalid" for="amount" style="display:{{$errors->has('amount') ? 'block' : 'none'}}">
                                                    @if($errors->has('amount')) {{ $errors->first('amount') }} @endif
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Promo Code</label>
                                                <div class="row">
                                                    <div class="col-9 col-sm-10">
                                                        <input type="text" placeholder="Enter promo code" class="form-control promo_code" name="promo_code" id="promo" onkeyup="stoppedTyping()" />
                                                    </div>
                                                    <div class="col-2">
                                                        <button type="button" id="promo_button" class="btn btn-warning text-white applyBtn" disabled="">
                                                            Apply
                                                        </button>
                                                    </div>
                                                </div>
                                                <label id="promo-error" class="game-error is-invalid" for="promo" style="display:none"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                        <div class="card-footer text-center mt-sm-1 bg-white px-0">
                                            <div class="row">
                                                <div class="table-responsive col-sm-6">
                                                    <table class="table border">
                                                        <tbody>
                                                            <tr>
                                                                <td>Just pay</td>
                                                                <td><span id="show_amt">$0</span></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                @if ($referralBonus)
                                                <div class="table-responsive col-sm-6">
                                                    <table class="table border">
                                                        <tbody>
                                                            <tr>
                                                                <td>Referral Bonus</td>
                                                                <td>${{number_format($referralBonus,2)}}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>  
                                                    <small class="text-danger top-size">On your first transaction only</small>
                                                </div>
                                                @endif
                                                <div class="table-responsive col-md-4 promo_code_atr" style="display: none;">
                                                    <table class="table border">
                                                        <tbody>                                                 
                                                            <tr class="promo_row">
                                                            </tr>                                                
                                                        </tbody>
                                                    </table>       
                                                </div>
                                                <div class="table-responsive col-sm-6">
                                                    <table class="table border">
                                                        <tbody>
                                                            <tr>   
                                                                <td>You will get</td>
                                                                <td><span class="total_amt">$0</span></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>                                    
                                    </div>
                                </div>
                                <div class="col-xs-6 pl-2 text-center">
                                    <button type="submit" class="btn btn-primary btn-submit" data-style="zoom-in">Pay Now</button>
                                </div>
                            </div>    
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('page-scripts')
<script src="{{ asset('js/custom.js') }}"></script>
<script type="text/javascript" src="https://js.stripe.com/v3/"></script> 
<script type="text/javascript">

    $('.select2').select2();  
    $( window ).on( "load", function() {
        $('.select2').select2(); 
    });

    function stoppedTyping(){
        if($('.promo_code').val() == ''){
            $('#promo_button').attr("disabled","true");           
        }
        else{
            $('#promo_button').removeAttr("disabled");
        }   
    }

    var l = false;
    $(document).ready(function(){
        $("#amount").on('keyup change', function() {
            let promoBalance = $('#promo_balance').html()
            if (promoBalance == undefined) {
                promoBalance = 0;
            }
            let temp_amt = {{ number_format($referralBonus,2) ?? 0 }}
            let val = $(this).val();

            if (val.length == 0) {
                val = 0
            }
            tAmt = parseFloat(val).toFixed(2);
            $('#show_amt').html('$' + tAmt.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            tamount = parseFloat(val != 0 ? parseFloat(val) + parseFloat(promoBalance) +
                parseFloat(temp_amt) : val).toFixed(2);
            $('.total_amt').html('$' + tamount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                
        });

        $(".applyBtn").click(function() {
            let pcode = $(".promo_code").val();
            let p_amount = document.getElementById("amount").value;
            if (pcode !== undefined &&
                p_amount !== undefined &&
                p_amount.length > 0 &&
                pcode.length > 0
            ) {
              
                $.ajax({
                    url: "{{ route('load.money.promo') }}",
                    method: 'POST',
                    data: {
                        promo_code: pcode,
                        amount: p_amount
                    }
                }).done(function(res) {
                    
                    if (res.success) {
                        $('.promo_code_atr').css('display','block');
                        $(".applyBtn").html(res.btn);
                        $(".promo_row").html(res.data)
                        $("#amount").trigger('change');


                        if (res.btn == "Remove") {
                            
                            $(".promo_code").attr('readonly', '');
                            $("#amount").attr('readonly', '');
                            $(".applyBtn").removeClass('btn-info');
                            $(".applyBtn").addClass('btn-danger');
                        } else {
                            $('.promo_code_atr').css('display','none');
                            $(".promo_code").removeAttr('readonly');
                            $("#amount").removeAttr('readonly', '');
                            $(".applyBtn").removeClass('btn-danger');
                            $(".applyBtn").addClass('btn-info');
                        }
                        toastr.success(res.message);
                      
                    } else {

                        toastr.error(res.message);
                        
                    }
                }).fail(function(err, xhr) {
                 
                    let response = JSON.parse(err.responseText);
                    var errorString = '';
                    $.each(response.errors, function(key, value) {
                        errorString = value;
                    });
                    toastr.error(errorString);
                   
                });
            } else {
                $('.promo_code').addClass('required');
                console.log($("form.payment"))
                $("form.payment").validate({
                    errorClass: 'is-invalid'
                }).form();
            }
        });  

        $('#payment_form').validate({
            errorClass: 'is-invalid',
        });
    });

    function validateForm() {
        $('.promo_code').removeClass('required is-invalid');
        let a = $("form.payment").validate({
            errorClass: 'is-invalid'
        }).form();
        return a;
    }
</script>

@endsection
