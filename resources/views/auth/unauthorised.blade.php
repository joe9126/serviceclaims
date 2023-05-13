@extends('layouts.app')
@section('title','Unauthorised Access')
@section('content')

<div class="container overflow-hidden">
    <div class="main-body">
        <div class="row gutters-sm justify-content-center">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="display-6 text-danger text-center">Unauthorised Access</h2>
                        <p class="text-center">The page you are trying to access is restricted. Please contact Admin for assistance.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection