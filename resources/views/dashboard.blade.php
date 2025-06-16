@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Dashboard
                        @if(Auth::user()->isAdmin())
                            - Administrador
                        @else
                            - Cliente
                        @endif
                    </h1>
                </div>
            </div>
        </div>

        @if(Auth::user()->isAdmin())
            @include('dashboard.admin')
        @else
            @include('dashboard.client')
        @endif
    </div>
@endsection