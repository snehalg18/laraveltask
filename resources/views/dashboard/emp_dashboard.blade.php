@extends('template.main')
@section('title', 'Profile')
@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">@yield('title')</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item active">@yield('title')</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <!-- Profile Section -->
                <div class="row">
                  
                    <div class="col-md-12">
                        <!-- User Information -->
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Profile Information</h3>
                            </div>
                            <div class="card-body">
                                <p><strong>Name: </strong>{{ auth()->user()->name }}</p>
                                <p><strong>Email: </strong>{{ auth()->user()->email }}</p>
                                <p><strong>Address: </strong>{{ auth()->user()->address }}</p>
                                <p><strong>Date of Birth: </strong>{{ \Carbon\Carbon::parse(auth()->user()->dob)->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->

            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>

@endsection
