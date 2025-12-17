@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Create New Propfirm</h3>
                    <a href="{{ route('admin.propfirms.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.propfirms.store') }}" method="POST">
                        @csrf

                        <h5 class="mb-3">User Account Details</h5>
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3">Propfirm Details</h5>
                        <div class="mb-3">
                            <label class="form-label">Propfirm Name</label>
                            <input type="text" name="propfirm_name" class="form-control @error('propfirm_name') is-invalid @enderror" value="{{ old('propfirm_name') }}" required>
                            @error('propfirm_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Propfirm Contact Email</label>
                            <input type="email" name="propfirm_email" class="form-control @error('propfirm_email') is-invalid @enderror" value="{{ old('propfirm_email') }}" required>
                            @error('propfirm_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Propfirm Mobile</label>
                            <input type="text" name="propfirm_mobile" class="form-control @error('propfirm_mobile') is-invalid @enderror" value="{{ old('propfirm_mobile') }}">
                            @error('propfirm_mobile')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Create Propfirm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
