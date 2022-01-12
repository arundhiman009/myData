@extends('layouts.admin.admin-app')

@section('title')
    Profile - {{ Config::get('app.name') }}
@endsection
@section('page-css')
<style type="text/css">
    .form-check {
        position: relative;
        display: block;
        padding-right: 5.25rem;
    }
    .buttonUpdate {
      position: absolute;
      bottom: 0;
      width: 100%;
    }
</style>
@endsection

@section('content')
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Profile</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">User Profile</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content propfile-section">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-3">
            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                    <div class="avatar-md">
                        <span class="avatar-title bg-soft-secondary font-12 rounded-circle receiever-name">
                            {!! getFirstLetterString(Auth::user()->username) !!}
                        </span>
                    </div>
                 <!--  <img src="{{ asset('assets/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image"> -->
                </div>
                <h3 class="profile-username text-center">{{ ucfirst(Auth::user()->username)}}</h3>
                <p class="text-muted text-center">{{ucfirst(Auth::user()->email)}}</p>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <!-- /.card -->
          </div>
          <!-- /.col -->

          <div class="col-md-9">
            <div class="card">
             @if(Auth::user()->getRoleNames()->first() == "Admin" || Auth::user()->getRoleNames()->first() == "Cashier" )
              <div class="card-header p-2">
                Payment Accept Methods
              </div>

              <!-- /.card-header -->
              <div class="card-body">
                <form class="form-horizontal" method="post" id="profileUpdate">
                    <input type="hidden" name="user_id" value="{{auth::user()->id}}"/>
                    <div class="d-flex">
                        <div class="form-check">
                            <input type="checkbox"  name="payment[venmo]" <?php
                            if(array_key_exists("venmo", $payment_methods)){
                                    echo "Checked";
                                } ?>  value="Venmo" class="form-check-input" id="exampleCheck1">
                            <label class="form-check-label"  for="exampleCheck1">Venmo</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="payment[cash_app]"
                             <?php
                            if(array_key_exists("cash_app", $payment_methods)){
                                    echo "Checked";
                                } ?>
                                value="Cash App" class="form-check-input" id="exampleCheck2">
                            <label class="form-check-label" for="exampleCheck2">Cash App</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="payment[zelle]" value="Zelle"
                               <?php
                            if(array_key_exists("zelle", $payment_methods)){
                                    echo "Checked";
                                } ?>
                             class="form-check-input" id="exampleCheck3">
                            <label class="form-check-label" for="exampleCheck3">Zelle</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="payment[paypal]"
                                <?php
                            if(array_key_exists("paypal", $payment_methods)){
                                    echo "Checked";
                                } ?>
                             value="Paypal" class="form-check-input" id="exampleCheck4">
                            <label class="form-check-label" for="exampleCheck4">Paypal</label>
                        </div>
                    </div>
                    <div class="form-group row buttonUpdate">
                    <div class=" col-sm-10">
                        <button type="submit"  data-style="zoom-in" class="btn btn-inline-block btn-primary btn-submit" >Update</button>
                    </div>
                    </div>
                </form>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
             @endif
            </div>
            <!-- /.card -->
          </div>

          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

@endsection
@section('page-scripts')
<script src="js/app.js"></script>
<script>
$('#profileUpdate').on('submit',function(e){
        e.preventDefault();
    });
  $('#profileUpdate').validate({

        errorClass: 'is-invalid',
        submitHandler: function(form) {
                l = Ladda.create( document.querySelector('#profileUpdate .btn-submit') );
                l.start();
                $.ajax({
                    url: "{{route('update-profile')}}",
                    method: "POST",
                    data: $("#profileUpdate").serialize(),
                    success: function (resultData) {
                        l.stop();
                         var msg = resultData.message;
                        if(resultData.success)
                        {
                            toastr.success(msg);

                        }
                        else if(!resultData.success)
                        {
                            if(resultData.type=="validation-error")
                            {
                                $.each( resultData.error, function( key, value ) {
                                    toastr.error(value);
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
