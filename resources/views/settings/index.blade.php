@extends('layouts.admin.admin-app')

@section('title')
    Settings - {{ Config::get('app.name') }}
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Settings</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
            <div class="col">
                <form class="form set_social_share_info">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="content">Social Share Content <span class="text-danger">*</span></label>
                                <div class="col-auto">
                                  <label class="sr-only" for="content">Content</label>
                                  <div class="input-group mb-2">
                                    <textarea id="content" name="content" class="form-control content required" rows="5">{{$admin->content ?? null}}</textarea>
                                  </div>
                                  <label id="content-error" class="content-error is-invalid" for="content" style="display:none"></label>
                                </div>
                                <small id="contentHelp" class="form-text text-muted">This content will appear at user section social Share page.</small>
                              </div>
                        </div>

                        <div class="colsm-6">
                            <div class="form-group">
                                <label for="tag">Social Share Tag Line <span class="text-danger">*</span></label>
                                <div class="col-auto">
                                  <label class="sr-only" for="tag">Tag Line</label>
                                  <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                      <div class="input-group-text"><i class="fas fa-tags"></i></div>
                                    </div>
                                    <input id="tag" name="tag" type="text" value="{{$admin->tag ?? null}}" required class="form-control tag required"  placeholder="Give $25 & Get $25" />
                                  </div>
                                  <label id="tag-error" class="tag-error is-invalid" for="tag" style="display:none"></label>
                                </div>
                                <small id="tagHelp" class="form-text text-muted">This is the tag line which appear at user section social share page</small>
                              </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="amount">Referral Bonus Amount <span class="text-danger">*</span></label>
                                <div class="col-auto">
                                  <label class="sr-only" for="amount">Amount</label>
                                  <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                      <div class="input-group-text"><i class="fas fa-dollar-sign"></i></div>
                                    </div>
                                    <input type="number" name="amount"  required id="amount" value="{{$admin->amount ?? null}}" min="0"  class="form-control amount required"  placeholder="Amount" />
                                  </div>
                                  <label id="amount-error" class="amount-error is-invalid" for="amount" style="display:none"></label>
                                </div>
                                <small id="amountHelp" class="form-text text-muted">This is the amount that will be given to referral and referee</small>
                              </div>
                        </div>
                    </div>
                    <div class="col-12 text-center my-4">
                        <button class="btn btn-inline-block btn-primary px-4" >Save</button>
                    </div>
                </form>
            </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection

@section('page-scripts')

    <script>
        var id = "{{$admin->id ?? null}}"

        $("form.set_social_share_info").validate({
        errorClass: 'is-invalid',
        submitHandler: function(form) {
            let content = $("textarea.content").val();
            let amount = $(".amount").val();
            let tag = $(".tag").val();
            SwalProgress();
            $.ajax({
                url: "{{route('admin.settings.info')}}",
                method: 'POST',
                data: { content: content, amount:amount, tag:tag, id:id }
            }).done(function(res) {
                SwalHideProgress();
                if(res.success){
                    id = res.data.id
                    SwalAlert({title : "Success!", text  : res.message, icon : "success"});
                }else{
                    SwalAlert({title : "Oops!", text  : res.message, icon : "error"});
                }
            }).fail(function(err,xhr){
                SwalHideProgress();
                let  response = JSON.parse(err.responseText);
                var errorString = '';
                $.each( response.errors, function( key, value) {
                    errorString =  value;
                });
                SwalAlert({title : "Oops!", text  : errorString, icon : "error"});
            });

            return false;
            }
        });


    </script>
    <script src="{{ asset('js/custom.js') }}"></script>

@endsection
