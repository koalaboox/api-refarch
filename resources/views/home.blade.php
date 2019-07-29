@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if (Request::user()->api_token)
                            You are connected to the Koalaboox API.
                            <br>
                            <a href="{{ route('invoice.index') }}">View my invoices</a>
                        @else
                            <a href="{{ route('api.redirect') }}">Connect to Koalaboox API</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
