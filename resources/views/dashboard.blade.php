@extends('layouts.app')
@section('title','Dashboard')
@section('content')

<div class="container">
    <div class="row top-row bg-primary mb-2">
        <p class="text-center text-uppercase text-light fw-bold fs-1">Dashboard</p>
    </div>
    <div class="row bg-light pt-10 pb-10 pl-10 pr-10 d-flex justify-content-center dashcontainer">
        <div class="col-sm-3">

            @if (Route::has('claims.mileage'))
            <a href="{{ route('claims.mileage') }}" >
             <div class="card bg-warning text-dark grow rounded dash1" style="width: 18rem; height:14rem;">

                <div class="card-body">
                  <h2 class="card-title fw-bold fs-2 text-light text-center">Mileage Claims</h2>
                  <p class="card-text text-light fs-5 text-center">Create or update your mileage claims</p>
                </div>
              </div>
            </a>
            @endif
        </div>
        <div class="col-sm-3">
             @if (Route::has('claims.print'))
            <a href="{{ route('claims.print') }}" >
             <div class="card bg-info text-dark grow rounded dash2" style="width: 18rem; height:14rem;">
                <div class="card-body">
                  <h5 class="card-title fw-bold fs-2 text-light text-center">Print Claims</h5>
                  <p class="card-text text-light fs-5 text-center">Print your mileage claims</p>
                  <p class="display-6 text-light text-center mt-3" id="claimtotal"></p>
                </div>
              </div>
            </a>
            @endif
        </div>
        <div class="col-sm-3">
            @if (Route::has('service.tickets'))
            <a href="{{ route('service.tickets') }}" >
                <div class="card bg-danger text-dark grow rounded dash3" style="width: 18rem; height:14rem;">
                   <div class="card-body">
                    <h5 class="card-title fw-bold fs-2 text-light text-center">Service Tickets</h5>
                     <p class="card-text text-light fs-5 text-center">Update your pending service tickets </p>
                     <p class="display-6 text-light text-center mt-3" id="pendingtickets"></p>
                   </div>
                 </div>
               </a>
               @endif
        </div>

    </div>

@endsection
