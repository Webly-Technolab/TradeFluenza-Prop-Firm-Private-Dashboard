@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Propfirms Management</h3>
                    <div>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary me-2">
                            <i class="bi bi-arrow-left"></i> Back to Dashboard
                        </a>
                        <a href="{{ route('admin.propfirms.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i> Add New Propfirm
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Propfirm Name</th>
                                    <th>Propfirm Email</th>
                                    <th>Mobile</th>
                                    <th>API Token</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($propfirms as $propfirm)
                                <tr>
                                    <td>{{ $propfirm->name }}</td>
                                    <td>{{ $propfirm->email }}</td>
                                    <td>{{ $propfirm->propfirm_name }}</td>
                                    <td>{{ $propfirm->propfirm_email }}</td>
                                    <td>{{ $propfirm->propfirm_mobile ?? '-' }}</td>
                                    <td>
                                        <div class="input-group input-group-sm" style="width: 200px;">
                                            <input type="text" class="form-control" value="{{ $propfirm->api_token ?? 'Not Generated' }}" readonly>
                                            <form action="{{ route('admin.propfirms.regenerate-api-key', $propfirm) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-secondary" title="Regenerate API Key" onclick="return confirm('Are you sure? This will invalidate the old key.')">
                                                    <i class="bi bi-arrow-clockwise"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.propfirms.edit', $propfirm) }}" class="btn btn-sm btn-info">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.propfirms.destroy', $propfirm) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this propfirm?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <p class="text-muted mb-0">No propfirms found</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $propfirms->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
