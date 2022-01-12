@extends('layouts.admin.admin-app')

@section('title')
  Dashboard - {{ Config::get('app.name') }}
@endsection

@section('content')

    <!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <div class="content-header">

      <div class="container-fluid">

        <div class="row mb-2">

          <div class="col-sm-6">

            <h1 class="m-0">Dashboard</h1>

          </div><!-- /.col -->

        </div><!-- /.row -->

      </div><!-- /.container-fluid -->

    </div>

    <!-- /.content-header -->



    <!-- Main content -->

    <section class="content">

      <div class="container-fluid">

        <!-- Small boxes (Stat box) -->

        <div class="row">

          <div class="col-lg-4 col-sm-6">

            <!-- small box -->

            <div class="small-box bg-info">

              <div class="inner">

              <h3>{{amountFormat($lifetimeEarning,2)}}</h3>



                <p>Loaded Money</p>

              </div>

              <div class="icon">

                <i><img src="{{ asset('assets/images/dashboard-img/dashboard-money.png') }}" alt="dashboard-money" class="img-fluid"></i>

              </div>

              <a href="{{route('admin.loadmoney.request')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>

            </div>

          </div>

          <div class="col-lg-4 col-sm-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>{{amountFormat($spentOnReferrences,2)}}</h3>

                <p>Earned from Referrences</p>
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

                <p>Earned from Promo</p>
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

            <div class="small-box bg-success">

              <div class="inner">

                <h3>{{amountFormat(Auth::user()->cashouts()->where('status','2')->sum('amount') ?? 0)}} </h3>



                <p>Lifetime Winnings</p>

              </div>

              <div class="icon">

                <i><img src="{{ asset('assets/images/dashboard-img/save-money.png') }}" alt="Save Money" class="img-fluid"></i>

              </div>

              <a href="{{route('cashout.index')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>

            </div>

          </div>

          <!-- ./col -->

          <div class="col-lg-4 col-sm-6">

            <!-- small box -->

            <div class="small-box bg-warning">

              <div class="inner">

                <h3 class="text-white">{{Auth::user()->cashouts()->where('status','0')->count()}}</h3>

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
              <!-- <a href="#" class="small-box-footer member text-white" style="visibility: hidden;">More info <i class="fas fa-arrow-circle-right"></i></a> -->
            </div>
          </div>
          <!-- ./col -->


        </div>

        <!-- /.row -->

        <!-- Main row -->

          <div class="dashboard-banner mb-4">

            <div class="row">

              <div class="col-sm-6 pl-5">

                  <div class="banner-content">

                      <h1>${{$bonus}}</h1>

                      <h2 class="text-white"> Referral Bonus </h2>

                      <p>Share with your friends and you both earn ${{$bonus}}. </p>

                      <a href="https://www.facebook.com/sharer/sharer.php?u={{$referal_link}}" class="btn social-button cusn-bg-grident">Share Now</a>

                  </div>

              </div>

          </div>

          </div>

        <!-- /.row (main row) -->

      </div><!-- /.container-fluid -->

    </section>

    <!-- /.content -->

  </div>

  <!-- /.content-wrapper -->

@endsection



@section('page-scripts')

    <script src="{{ asset('js/share.js') }}"></script>

@endsection
