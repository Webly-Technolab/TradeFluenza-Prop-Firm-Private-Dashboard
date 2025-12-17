@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Tradefluenza Payout Dashboard</h3>
                    <a href="{{ route('admin.propfirms.index') }}" class="btn btn-primary">
                        <i class="bi bi-building"></i> Manage Propfirms
                    </a>
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
                            <button class="nav-link active" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button" role="tab" aria-controls="active" aria-selected="true">Active Payouts</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab" aria-controls="history" aria-selected="false">Payout History</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="payoutTabsContent">
                        <!-- Active Payouts Tab -->
                        <div class="tab-pane fade show active" id="active" role="tabpanel" aria-labelledby="active-tab">
                            <div class="table-responsive">
                                <table class="table table-hover" id="activePayoutsTable">
                                    <thead>
                                        <tr>
                                            <th>Payout ID</th>
                                            <th>Propfirm</th>
                                            <th>Beneficiary</th>
                                            <th>Amount</th>
                                            <th>Payment Type</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($payouts as $payout)
                                        <tr>
                                            <td><strong>{{ $payout->payout_id }}</strong></td>
                                            <td>{{ $payout->propfirm->propfirm_name }}</td>
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
                                            </td>
                                            <td>
                                                @if($payout->status === 2)
                                                    <form action="{{ route('admin.payouts.release', $payout) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @php
                                                            $message = "Payout Release Request\n" .
                                                                "Payout ID: " . $payout->payout_id . "\n" .
                                                                "Propfirm: " . $payout->propfirm->propfirm_name . "\n" .
                                                                "Beneficiary: " . $payout->beneficiary_name . "\n" .
                                                                "Amount: " . $payout->formatted_amount . "\n" .
                                                                "Payment Type: " . ucfirst($payout->payment_type);
                                                            
                                                            if ($payout->payment_type === 'upi') {
                                                                $message .= "\nUPI ID: " . $payout->upi_id;
                                                            } else {
                                                                $message .= "\nBank: " . $payout->beneficiary_bank . "\n" .
                                                                            "Account No: " . $payout->beneficiary_account_number . "\n" .
                                                                            "IFSC: " . $payout->ifsc_code;
                                                            }

                                                            $waLink = "https://wa.me/917355953598?text=" . urlencode($message);
                                                        @endphp
                                                        <button type="submit" class="btn btn-sm btn-primary" onclick="window.open('{{ $waLink }}', '_blank')">
                                                             Release
                                                        </button>
                                                    </form>
                                                @elseif($payout->status === 3)
                                                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#uploadModal{{ $payout->id }}">
                                                        <i class="bi bi-upload"></i> Upload Proof
                                                    </button>
                                                @elseif($payout->status === 4)
                                                    <form action="{{ route('admin.payouts.final-payout', $payout) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success">
                                                            <i class="bi bi-check-circle"></i> Final Payout
                                                        </button>
                                                    </form>
                                                @elseif($payout->status === 5)
                                                    <form action="{{ route('admin.payouts.complete', $payout) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success">
                                                            <i class="bi bi-check-all"></i> Complete
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>

                                        <!-- Upload Modal -->
                                        @if($payout->status === 3)
                                        <div class="modal fade" id="uploadModal{{ $payout->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('admin.payouts.upload-proof', $payout) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Upload Payment Proofs</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">Payment Proof Images</label>
                                                                <input type="file" name="proofs[]" class="form-control" multiple accept="image/*" required>
                                                                <small class="text-muted">You can upload multiple images</small>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary">Upload</button>
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
                                    <select id="adminHistoryStatusFilter" class="form-select">
                                        <option value="">All Statuses</option>
                                        <option value="Rejected">Rejected</option>
                                        <option value="Payout Successful">Payout Successful</option>
                                    </select>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover" id="historyPayoutsTable">
                                    <thead>
                                        <tr>
                                            <th>Payout ID</th>
                                            <th>Propfirm</th>
                                            <th>Beneficiary</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($history_payouts as $payout)
                                        <tr>
                                            <td><strong>{{ $payout->payout_id }}</strong></td>
                                            <td>{{ $payout->propfirm->propfirm_name }}</td>
                                            <td>
                                                <div>{{ $payout->beneficiary_name }}</div>
                                                <small class="text-muted">{{ $payout->beneficiary_email }}</small>
                                            </td>
                                            <td><strong>{{ $payout->formatted_amount }}</strong></td>
                                            <td>{{ $payout->created_at->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $payout->status_color }}">
                                                    {{ $payout->status_label }}
                                                </span>
                                                @if($payout->status == 1 && $payout->rejection_reason)
                                                    <div class="small text-danger mt-1">{{ $payout->rejection_reason }}</div>
                                                @endif
                                            </td>
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
    $('#activePayoutsTable').DataTable({
        "order": [[ 5, "asc" ]], // Order by Status
        "pageLength": 25
    });
    
    var historyTable = $('#historyPayoutsTable').DataTable({
        "order": [[ 4, "desc" ]], // Order by Date
        "pageLength": 25
    });

    // Custom filtering function for Status
    $('#adminHistoryStatusFilter').on('change', function() {
        var status = $(this).val();
        historyTable.column(5).search(status).draw();
    });
});
</script>
@endpush
