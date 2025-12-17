@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3>Propfirm Payout Dashboard</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <ul class="nav nav-tabs mb-4" id="payoutTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab" aria-controls="pending" aria-selected="true">Pending Actions</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab" aria-controls="history" aria-selected="false">Payout History</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="payoutTabsContent">
                        <!-- Pending Actions Tab -->
                        <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                            <div class="table-responsive">
                                <table class="table table-hover" id="pendingPayoutsTable">
                                    <thead>
                                        <tr>
                                            <th>Payout ID</th>
                                            <th>Beneficiary</th>
                                            <th>Amount</th>
                                            <th>Payment Details</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($payouts as $payout)
                                        <tr>
                                            <td><strong>{{ $payout->payout_id }}</strong></td>
                                            <td>
                                                <div>{{ $payout->beneficiary_name }}</div>
                                                <small class="text-muted">{{ $payout->beneficiary_email }}</small>
                                            </td>
                                            <td><strong>{{ $payout->formatted_amount }}</strong></td>
                                            <td>
                                                @if($payout->payment_type === 'upi')
                                                    <span class="badge bg-info">UPI</span>
                                                    <div><small>{{ $payout->upi_id }}</small></div>
                                                @else
                                                    <span class="badge bg-primary">Bank</span>
                                                    <div><small>{{ $payout->beneficiary_bank }}</small></div>
                                                    <div><small>{{ $payout->beneficiary_account_number }}</small></div>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $payout->status_color }}">
                                                    {{ $payout->status_label }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($payout->status === 0)
                                                    <form action="{{ route('propfirm.payouts.confirm', $payout) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success">
                                                            <i class="bi bi-check-circle"></i> Confirm
                                                        </button>
                                                    </form>
                                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $payout->id }}">
                                                        <i class="bi bi-x-circle"></i> Reject
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>

                                        <!-- Reject Modal -->
                                        @if($payout->status === 0)
                                        <div class="modal fade" id="rejectModal{{ $payout->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('propfirm.payouts.reject', $payout) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Reject Payout</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">Rejection Reason</label>
                                                                <textarea name="rejection_reason" class="form-control" rows="3" required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger">Reject Payout</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Payout History Tab -->
                        <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                            <div class="d-flex justify-content-end mb-3">
                                <div class="col-md-3">
                                    <select id="propfirmHistoryStatusFilter" class="form-select">
                                        <option value="">All Statuses</option>
                                        <option value="Rejected">Rejected</option>
                                        <!-- <option value="Confirmed">Confirmed</option> -->
                                        <option value="Released for Payment">Released for Payment</option>
                                        <!-- <option value="Proof Uploaded">Proof Uploaded</option> -->
                                        <!-- <option value="Final Payout">Final Payout</option> -->
                                        <option value="Payout Successful">Payout Successful</option>
                                    </select>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover" id="historyPayoutsTable">
                                    <thead>
                                        <tr>
                                            <th>Payout ID</th>
                                            <th>Beneficiary</th>
                                            <th>Amount</th>
                                            <th>Payment Details</th>
                                            <th>Status</th>
                                            <th>Last Updated</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($history_payouts as $payout)
                                        <tr>
                                            <td><strong>{{ $payout->payout_id }}</strong></td>
                                            <td>
                                                <div>{{ $payout->beneficiary_name }}</div>
                                                <small class="text-muted">{{ $payout->beneficiary_email }}</small>
                                            </td>
                                            <td><strong>{{ $payout->formatted_amount }}</strong></td>
                                            <td>
                                                @if($payout->payment_type === 'upi')
                                                    <span class="badge bg-info">UPI</span>
                                                    <div><small>{{ $payout->upi_id }}</small></div>
                                                @else
                                                    <span class="badge bg-primary">Bank</span>
                                                    <div><small>{{ $payout->beneficiary_bank }}</small></div>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $payout->status_color }}">
                                                    {{ $payout->status_label }}
                                                </span>
                                                @if($payout->status == 1 && $payout->rejection_reason)
                                                    <div class="small text-danger mt-1">{{ $payout->rejection_reason }}</div>
                                                @endif
                                            </td>
                                            <td>{{ $payout->updated_at->format('Y-m-d H:i') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#pendingPayoutsTable').DataTable({
        "order": [[ 0, "desc" ]], // Order by Payout ID
        "pageLength": 25
    });
    
    var historyTable = $('#historyPayoutsTable').DataTable({
        "order": [[ 5, "desc" ]], // Order by Last Updated
        "pageLength": 25
    });

    // Custom filtering function for Status
    $('#propfirmHistoryStatusFilter').on('change', function() {
        var status = $(this).val();
        historyTable.column(4).search(status).draw();
    });
});
</script>
@endpush
