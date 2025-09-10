@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h3 mb-0 text-dark">Profile Settings</h1>
                <p class="text-muted">Manage your account settings and preferences</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-user-edit mr-2"></i>Account Information
                        </h6>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success border-0 rounded-3 mb-4">
                                <i class="fas fa-check-circle mr-2"></i>
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="/profile">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label for="username" class="form-label font-weight-bold">
                                            <i class="fas fa-user mr-2 text-primary"></i>Username
                                        </label>
                                        <input type="text" class="form-control form-control-lg" id="username"
                                            name="username" value="{{ old('username', $user->username) }}" required>
                                        <small class="text-muted">Choose a unique username for your account</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label for="password" class="form-label font-weight-bold">
                                            <i class="fas fa-lock mr-2 text-primary"></i>New Password
                                        </label>
                                        <input type="password" class="form-control form-control-lg" id="password"
                                            name="password">
                                        <small class="text-muted">Leave blank to keep current password</small>
                                    </div>
                                </div>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger border-0 rounded-3 mb-4">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    {{ $errors->first() }}
                                </div>
                            @endif

                            <div class="d-flex">
                                <button type="submit" class="btn btn-primary btn-lg px-4 mr-2">
                                    <i class="fas fa-save mr-2"></i>Save Changes
                                </button>
                                <a href="/dashboard" class="btn btn-secondary btn-lg px-4">
                                    <i class="fas fa-times mr-2"></i>Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-info-circle mr-2"></i>Account Info
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                style="width: 80px; height: 80px;">
                                <i class="fas fa-user text-white fa-2x"></i>
                            </div>
                            <h5 class="fw-bold">{{ $user->username }}</h5>
                            <p class="text-muted">Administrator</p>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <strong>Account ID:</strong>
                            <span class="text-muted">#{{ $user->id }}</span>
                        </div>

                        <div class="mb-3">
                            <strong>Member Since:</strong>
                            <span class="text-muted">{{ $user->created_at->format('M d, Y') }}</span>
                        </div>

                        <div class="mb-3">
                            <strong>Last Updated:</strong>
                            <span class="text-muted">{{ $user->updated_at->format('M d, Y') }}</span>
                        </div>

                        <div class="mb-3">
                            <strong>Status:</strong>
                            <span class="badge bg-success">Active</span>
                        </div>
                    </div>
                </div>

                <div class="card shadow mt-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-shield-alt mr-2"></i>Security Tips
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Use a strong, unique password
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Update your password regularly
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Keep your account information up to date
                            </li>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
