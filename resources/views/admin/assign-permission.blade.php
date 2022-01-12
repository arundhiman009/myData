@extends('layouts.admin.admin-app')

@section('title')
    Permission - {{ Config::get('app.name') }}
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
            <h1>Assign Permission</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Add Permission</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

   
    
  <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
 <div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title"> Edit Role: {{$data['name']}} </h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
               <form class="form-horizontal" method="post" id="savelocation" action="{{route('assign-permission-role')}}">
                @csrf
                <div class="card-body">
                   
                  <div class="form-group">
                    
                    <label for="Role">Role Name</label>
                    <input type="email" class="form-control" id="Role" readonly="" value="{{$data['name']}}">
                  </div>
                   <label for="Role">Permission Name</label>
                   <div class="form-group row">
                    @if(count($permission)==0)
                    <p style="margin-left: 1rem">No Permission</p> 
                    @else
                     @foreach($permission as $permis)
                        <div class="form-check col-3">
                          <input class="form-check-input" value="{{$permis->id}}" type="checkbox" name="permission_id[]" {{$data->hasPermissionTo($permis->name)=='1'?'checked':''}} >
                          <label class="form-check-label">{{$permis->name}}</label>
                        </div>
                        @endforeach
                       @endif 
                      </div>
                  
                  <input type="hidden" class="form-control input-lg" value="{{$data['id']}}" id="name" name="role_id" placeholder="Name"  >
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-warning">Action</button>
                </div>
              </form>
            </div>
            <!-- /.card -->



          
          </div>

     

          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    

    
</div>

<!-- Add location Popup -->  

<!-- End add location Popup -->   
<!-- Update location Popup -->  
 


@endsection