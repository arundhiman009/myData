@extends('layouts.admin.admin-app')

@section('title')
  Dashboard - {{ Config::get('app.name') }}
@endsection

@section('content')
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-4 col-sm-6">
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{amountFormat($lifetimeEarning,2)}}</h3>
                <p>Total Earning</p>
              </div>
              <div class="icon">
                <i><img src="{{ asset('assets/images/dashboard-img/save-money.png') }}" alt="dashboard-money" class="img-fluid"></i>
              </div>
              <a href="{{route('admin.users')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-sm-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>{{amountFormat($spentOnReferrences,2)}}</h3>

                <p>Paid on referrences</p>
              </div>
              <div class="icon">
                <i><img src="{{ asset('assets/images/dashboard-img/dashboard-money.png') }}" alt="dashboard-money" class="img-fluid"></i>
              </div>
              <a href="{{route('admin.users')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-sm-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>{{amountFormat($spendOnPromo,2)}}</h3>

                <p>Paid on promo</p>
              </div>
              <div class="icon">
                <i><img src="{{ asset('assets/images/dashboard-img/dashboard-money.png') }}" alt="dashboard-money" class="img-fluid"></i>
              </div>
              <a href="{{route('admin.users')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-4 col-sm-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3 class="text-white">{{$pendingTransactions ?? 0}}</h3>
                <p class="text-white">Pending Load Money Request</p>
              </div>
              <div class="icon">
                <i><img src="{{ asset('assets/images/dashboard-img/payment.png') }}" alt="Payment" class=" img-fluid"></i>
              </div>
              <a href="{{route('admin.loadmoney.request')}}" class="small-box-footer text-white">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-sm-6">
            <!-- small box -->
            <div class="small-box bg-primary">
              <div class="inner">
                <h3>{{amountFormat($totalWithdrawals,2)}} </h3>

                <p>Total Withdrawals</p>
              </div>
              <div class="icon">
                <i><img src="{{ asset('assets/images/dashboard-img/save-money.png') }}" alt="Save Money" class="img-fluid"></i>
              </div>
              <a href="{{route('admin.users')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->

          <!-- ./col -->
          <!-- ./col -->
          <div class="col-lg-4 col-sm-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3 class="text-white">{{$pendingCashouts ?? 0}}</h3>
                <p class="text-white">Pending Cashout Request</p>
              </div>
              <div class="icon">
                <i><img src="{{ asset('assets/images/dashboard-img/payment.png') }}" alt="Payment" class=" img-fluid"></i>
              </div>
              <a href="{{route('cashout.index')}}" class="small-box-footer text-white">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

           <div class="col-lg-4 col-sm-6">
            <!-- small box -->
            <div class="small-box bg-dark member">
              <div class="inner">
                <h4 class="text-white">

                  {!! casinoDate(auth::user()->created_at) !!}


                 </h4>
                <p class="text-white">Member Since</p>
              </div>
              <div class="icon">
                <i><img src="{{ asset('assets/images/dashboard-img/payment.png') }}" alt="member_since" class=" img-fluid"></i>
              </div>
              <!-- <a href="#" class="small-box-footer member text-white" style="visibility: hidden;"> <i class="fas fa-arrow-circle-right"></i></a> -->
            </div>
          </div>
          <!-- ./col -->
        </div>

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection
