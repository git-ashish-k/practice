@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                        <div class="panel-body row">
                            You are logged in! as <strong>{{ strtoupper(Auth::user()->rolename) }}</strong><br />
                            <div class="col-md-3">
                                <a class="btn btn-primary mb-2" href="{{ route('users.index') }}">See User Listing</a>
                            </div>
                            <div class="col-md-3">
                                <a class="btn btn-primary mb-2" href="{{ route('products.index') }}">See Product Listing</a><br />
                            </div>
                            <div class="col-md-3">
                                <a class="btn btn-primary mb-2" href="{{ route('brands.index') }}">See Brand Listing</a><br />
                            </div>
                            <div class="col-md-3">
                                <a class="btn btn-primary mb-2" href="{{ route('summary') }}">See Summary Listing</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection