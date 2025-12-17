<!DOCTYPE html>
<html lang="en" class="light-style" dir="ltr" data-theme="theme-default">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tradefluenza Payout System</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --bs-primary: #696cff;
            --bs-success: #71dd37;
            --bs-info: #03c3ec;
            --bs-warning: #ffab00;
        }
        
        body {
            font-family: 'Public Sans', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 2rem 0;
        }
        
        .dashboard-card {
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            box-shadow: 0 0.125rem 0.625rem rgba(0, 0, 0, 0.1);
        }
        
        .dashboard-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.2);
        }
        
        .icon-wrapper {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        
        .icon-wrapper i {
            font-size: 2.5rem;
        }
        
        .bg-label-primary { background-color: rgba(105, 108, 255, 0.12); color: #696cff; }
        .bg-label-success { background-color: rgba(113, 221, 55, 0.12); color: #71dd37; }
        .bg-label-warning { background-color: rgba(255, 171, 0, 0.12); color: #ffab00; }
    </style>
</head>
<body>
    <div class="container">
        <div class="text-center text-white mb-5">
            <h1 class="display-3 fw-bold mb-3">Tradefluenza</h1>
            <h2 class="h4 mb-4 fw-normal">Payout Management System</h2>
            <p class="lead opacity-75">Select your dashboard to continue</p>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card dashboard-card h-100" onclick="window.location.href='/propfirm/dashboard'">
                    <div class="card-body text-center p-4">
                        <div class="icon-wrapper bg-label-primary">
                            <i class="bi bi-building"></i>
                        </div>
                        <h4 class="mb-3">Propfirm Dashboard</h4>
                        <p class="text-muted mb-4">
                            View and manage payout requests. Confirm or reject payouts for your firm.
                        </p>
                        <div class="d-flex gap-2 justify-content-center flex-wrap">
                            <span class="badge bg-primary">Confirm</span>
                            <span class="badge bg-danger">Reject</span>
                            <span class="badge bg-info">View Proofs</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card dashboard-card h-100" onclick="window.location.href='/admin/tradefluenza/dashboard'">
                    <div class="card-body text-center p-4">
                        <div class="icon-wrapper bg-label-success">
                            <i class="bi bi-speedometer2"></i>
                        </div>
                        <h4 class="mb-3">Tradefluenza Dashboard</h4>
                        <p class="text-muted mb-4">
                            Manage confirmed payouts. Release to vendors and approve final payments.
                        </p>
                        <div class="d-flex gap-2 justify-content-center flex-wrap">
                            <span class="badge bg-info">Release</span>
                            <span class="badge bg-warning">Review</span>
                            <span class="badge bg-success">Finalize</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card dashboard-card h-100" onclick="window.location.href='/vendor/dashboard'">
                    <div class="card-body text-center p-4">
                        <div class="icon-wrapper bg-label-warning">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <h4 class="mb-3">Vendor Dashboard</h4>
                        <p class="text-muted mb-4">
                            View assigned payouts and upload payment proof screenshots.
                        </p>
                        <div class="d-flex gap-2 justify-content-center flex-wrap">
                            <span class="badge bg-warning text-dark">Upload Proof</span>
                            <span class="badge bg-secondary">Track Status</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card bg-white bg-opacity-10 text-white border-0">
                    <div class="card-body">
                        <div class="row text-center g-3">
                            <div class="col-md-3">
                                <i class="bi bi-diagram-3 fs-4 d-block mb-2"></i>
                                <h6 class="mb-1">Status Flow</h6>
                                <small class="opacity-75">6-step automated workflow</small>
                            </div>
                            <div class="col-md-3">
                                <i class="bi bi-envelope fs-4 d-block mb-2"></i>
                                <h6 class="mb-1">Email Notifications</h6>
                                <small class="opacity-75">4 automated notification points</small>
                            </div>
                            <div class="col-md-3">
                                <i class="bi bi-chat-dots fs-4 d-block mb-2"></i>
                                <h6 class="mb-1">Vendor Integration</h6>
                                <small class="opacity-75">WhatsApp & Telegram support</small>
                            </div>
                            <div class="col-md-3">
                                <i class="bi bi-shield-check fs-4 d-block mb-2"></i>
                                <h6 class="mb-1">Laravel 12</h6>
                                <small class="opacity-75">Modern & Secure</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
