<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>Reinalin Retail — Management System</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.31.0/dist/tabler-icons.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<link rel="stylesheet" href="assets/css/style.css"/>
</head>
<body>

<!-- ─── LOGIN ─── -->
<div class="auth-screen on" id="login-screen">
  <div class="auth-bg">
    <div class="auth-blob auth-blob-1"></div>
    <div class="auth-blob auth-blob-2"></div>
    <div class="auth-blob auth-blob-3"></div>
  </div>
  <button class="auth-theme-btn" onclick="toggleTheme()" title="Toggle theme">
    <i class="ti ti-moon" id="login-theme-icon"></i>
  </button>
  <div class="auth-card">
    <div class="auth-logo">
      <div class="logo-wrap logo-auth" id="logo-auth">
        <svg class="logo-svg-mark" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
          <rect x="6" y="18" width="28" height="16" rx="2" fill="white" fill-opacity="0.95"/>
          <path d="M4 20 L20 10 L36 20" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
          <rect x="16" y="24" width="8" height="10" rx="1.5" fill="#E8700A"/>
          <rect x="8" y="21" width="6" height="5" rx="1" fill="white" fill-opacity="0.4"/>
          <rect x="26" y="21" width="6" height="5" rx="1" fill="white" fill-opacity="0.4"/>
          <rect x="6" y="18" width="28" height="4" rx="1" fill="white" fill-opacity="0.25"/>
          <circle cx="23" cy="30" r="1" fill="white" fill-opacity="0.8"/>
        </svg>
      </div>
      <div>
        <div class="auth-brand" id="auth-brand-name">Reinalin Retail</div>
        <div class="auth-sub">Management System</div>
      </div>
    </div>
    <div class="auth-title">Welcome back 👋</div>
    <div class="auth-hint">Sign in to your account to continue</div>
    <div class="demo-creds">
      <p>Demo credentials</p>
      <div class="demo-cred-row"><i class="ti ti-shield"></i><span style="color:var(--text-secondary);font-size:11.5px">Admin:</span><code>admin / admin123</code></div>
      <div class="demo-cred-row"><i class="ti ti-receipt"></i><span style="color:var(--text-secondary);font-size:11.5px">Cashier:</span><code>cashier / cash123</code></div>
    </div>
    <div class="fg"><label class="fl">Username</label><input class="lfi" id="l-user" placeholder="Enter username" onkeydown="if(event.key==='Enter')doLogin()"></div>
    <div class="fg" style="margin-bottom:0">
      <label class="fl">Password</label>
      <div style="position:relative">
        <input class="lfi" type="password" id="l-pass" placeholder="Enter password" onkeydown="if(event.key==='Enter')doLogin()" style="padding-right:38px;margin-bottom:0">
        <button onclick="togglePassVis('l-pass','pass-eye')" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--text-tertiary);padding:2px">
          <i class="ti ti-eye" id="pass-eye"></i>
        </button>
      </div>
    </div>
    <div class="lerr" id="l-err" style="display:none"><i class="ti ti-alert-circle"></i><span id="l-err-msg"></span></div>
    <button class="lbtn" onclick="doLogin()" style="margin-top:16px"><i class="ti ti-login"></i>Sign in to dashboard</button>
    <div style="text-align:center;font-size:11px;color:var(--text-tertiary);margin-top:14px;font-weight:500">Contact your administrator to create an account</div>
  </div>
</div>

<!-- ─── TOAST ─── -->
<div class="toast-wrap" id="toast-wrap"></div>

<!-- ─── NOTIFICATION PANEL ─── -->
<div class="notif-panel" id="notif-panel">
  <div class="np-hd">Notifications <button class="btn bts" onclick="clearNotifs()" style="font-size:10px">Clear all</button></div>
  <div id="notif-items"></div>
</div>

<!-- ─── HIDDEN FILE INPUT for product images ─── -->
<input type="file" id="prod-img-input" accept="image/*" style="display:none" onchange="handleProdImageUpload(this)">

<!-- ─── APP SHELL ─── -->
<div class="shell" id="app" style="display:none;overflow:hidden">
</div>
  <div class="sb-overlay" id="sb-overlay" onclick="toggleMobileSidebar()"></div>
  <nav class="sb" id="main-sidebar">
  </nav>
    <div class="sb-logo">
      <div class="sb-brand">
        <div class="logo-wrap logo-sb" id="logo-sb">
          <svg class="logo-svg-mark" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="6" y="18" width="28" height="16" rx="2" fill="white" fill-opacity="0.95"/>
            <path d="M4 20 L20 10 L36 20" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
            <rect x="16" y="24" width="8" height="10" rx="1.5" fill="#E8700A"/>
            <rect x="8" y="21" width="6" height="5" rx="1" fill="white" fill-opacity="0.4"/>
            <rect x="26" y="21" width="6" height="5" rx="1" fill="white" fill-opacity="0.4"/>
            <rect x="6" y="18" width="28" height="4" rx="1" fill="white" fill-opacity="0.25"/>
            <circle cx="23" cy="30" r="1" fill="white" fill-opacity="0.8"/>
          </svg>
        </div>
        <div>
          <div class="sb-name">Reinalin Retail</div>
          <div class="sb-sub">Management System</div>
        </div>
      </div>
    </div>
    <div class="sb-user">
      <div class="sb-av" id="sb-av-init">JA</div>
      <div><div class="sb-uname" id="sb-uname">Aina Angeles</div><div class="sb-urole" id="sb-urole">Administrator</div></div>
    </div>
    <div class="nav-sec">
      <div class="nav-lbl">Main</div>
      <div class="ni active" onclick="nav('dashboard',this)"><i class="ti ti-layout-dashboard"></i>Dashboard</div>
      <div class="ni" onclick="nav('inventory',this)"><i class="ti ti-package"></i>Inventory<span class="badge-dot" id="inv-dot" style="display:none"></span></div>
      <div class="ni" onclick="nav('pos',this)"><i class="ti ti-receipt"></i>Point of Sale</div>
      <div class="ni admin-only" onclick="nav('staff',this)"><i class="ti ti-users"></i>Staff</div>
      <div class="ni admin-only" onclick="nav('attendance',this)"><i class="ti ti-clock"></i>Attendance</div>
      <div class="ni" onclick="nav('payments',this)"><i class="ti ti-credit-card"></i>Payments</div>
      <div class="ni admin-only" onclick="nav('expenses',this)"><i class="ti ti-receipt-2"></i>Expenses</div>
      <div class="ni" onclick="nav('customers',this)"><i class="ti ti-users-group"></i>Customers</div>
      <div class="ni admin-only" onclick="nav('reports',this)"><i class="ti ti-chart-bar"></i>Reports</div>
      <div class="ni" onclick="nav('transactions',this)"><i class="ti ti-list"></i>Transactions</div>
    </div>
    <div class="sb-bottom">
  <button class="btn bts admin-only" onclick="openBackupModal()" style="width:100%;justify-content:center;margin-bottom:8px;border-color:var(--border)">
    <i class="ti ti-database-export"></i>Backup DB
  </button>
  <button class="logout-btn" onclick="doLogout()"><i class="ti ti-logout"></i>Sign out</button>
</div>
  </nav>

  <div class="main">
    <div class="topbar">
  <div style="display:flex;align-items:center;gap:8px">
    <button class="hamburger" onclick="toggleMobileSidebar()" title="Menu">
      <i class="ti ti-menu-2"></i>
    </button>
    <span class="tb-title" id="pg-title">Dashboard</span>
  </div>
      <div class="tb-right">
        <i class="ti ti-calendar"></i><span id="tb-date"></span>
        <button class="notif-btn" onclick="toggleNotifPanel()" id="notif-trigger">
          <i class="ti ti-bell"></i>
          <span class="notif-badge" id="notif-badge" style="display:none"></span>
        </button>
        <button class="theme-toggle" onclick="toggleTheme()"><i class="ti ti-moon" id="theme-icon"></i></button>
      </div>
    </div>
    <div class="content">

      <!-- DASHBOARD -->
      <div class="pg on" id="pg-dashboard">
        <div id="low-stock-alert" style="display:none" class="alert-bar">
          <i class="ti ti-alert-triangle"></i><span id="low-stock-msg"></span>
          <button class="btn bts" style="margin-left:auto" onclick="nav('inventory',document.querySelectorAll('.ni')[1])">View inventory</button>
        </div>
        <!-- Period tabs -->
<div style="display:flex;gap:4px;background:var(--bg-secondary);border-radius:10px;padding:4px;margin-bottom:12px;width:fit-content">
  <button class="rt on" id="period-today" onclick="setDashPeriod('today',this)">📅 Today</button>
  <button class="rt" id="period-week" onclick="setDashPeriod('week',this)">📆 This Week</button>
  <button class="rt" id="period-month" onclick="setDashPeriod('month',this)">🗓️ This Month</button>
  <button class="rt" id="period-all" onclick="setDashPeriod('all',this)">📊 All Time</button>
</div>

<div class="sc">
  <div class="sk hl">
    <div class="sk-icon" style="background:var(--acc-l)"><i class="ti ti-currency-peso" style="font-size:17px;color:var(--acc)"></i></div>
    <div class="sk-lbl" id="d-sales-lbl">Today's Sales</div>
    <div class="sk-val" id="d-sales">₱0</div>
    <div class="sk-trend up" id="d-sales-sub"><i class="ti ti-receipt" style="font-size:11px"></i><span id="d-sales-txn">0 transactions</span></div>
  </div>
  <div class="sk">
    <div class="sk-icon" style="background:var(--success-bg)"><i class="ti ti-chart-line" style="font-size:17px;color:var(--success-text)"></i></div>
    <div class="sk-lbl">Net Profit</div>
    <div class="sk-val" id="d-profit">₱0</div>
    <div class="sk-trend up"><i class="ti ti-trending-up" style="font-size:11px"></i><span>All time</span></div>
  </div>
  <div class="sk">
    <div class="sk-icon" style="background:var(--info-bg)"><i class="ti ti-package" style="font-size:17px;color:var(--info-text)"></i></div>
    <div class="sk-lbl">Products</div>
    <div class="sk-val" id="d-prods">0</div>
    <div class="sk-sub" id="d-stock-sub">In inventory</div>
  </div>
  <div class="sk">
    <div class="sk-icon" style="background:#f0e8ff"><i class="ti ti-receipt" style="font-size:17px;color:#7F77DD"></i></div>
    <div class="sk-lbl">Transactions</div>
    <div class="sk-val" id="d-txns">0</div>
    <div class="sk-sub">All time</div>
  </div>
</div>

<!-- Payment method breakdown -->
<div class="sc" style="grid-template-columns:repeat(4,1fr);margin-bottom:14px">
  <div class="sk">
    <div class="sk-icon" style="background:var(--success-bg)"><i class="ti ti-cash" style="font-size:17px;color:var(--success-text)"></i></div>
    <div class="sk-lbl">Cash Sales</div>
    <div class="sk-val" id="d-cash">₱0</div>
  </div>
  <div class="sk">
    <div class="sk-icon" style="background:var(--info-bg)"><i class="ti ti-wallet" style="font-size:17px;color:var(--info-text)"></i></div>
    <div class="sk-lbl">GCash</div>
    <div class="sk-val" id="d-gcash">₱0</div>
  </div>
  <div class="sk">
    <div class="sk-icon" style="background:var(--info-bg)"><i class="ti ti-credit-card" style="font-size:17px;color:var(--info-text)"></i></div>
    <div class="sk-lbl">Maya</div>
    <div class="sk-val" id="d-maya">₱0</div>
  </div>
  <div class="sk">
    <div class="sk-icon" style="background:var(--warn-bg)"><i class="ti ti-user-dollar" style="font-size:17px;color:var(--warn-text)"></i></div>
    <div class="sk-lbl">Utang</div>
    <div class="sk-val" id="d-utang">₱0</div>
  </div>
</div>
<!-- Sales Targets -->
<div class="card" style="margin-bottom:14px">
  <div class="card-hd">
    🎯 Sales Targets
    <button class="btn bts" onclick="openTargetModal()"><i class="ti ti-settings"></i>Set targets</button>
  </div>
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
    <!-- Daily target -->
    <div>
      <div style="display:flex;justify-content:space-between;margin-bottom:6px">
        <span style="font-size:12px;font-weight:700;color:var(--text-primary)">📅 Daily Target</span>
        <span style="font-size:12px;font-weight:800;color:var(--acc)" id="daily-pct">0%</span>
      </div>
      <div style="height:10px;background:var(--border);border-radius:5px;overflow:hidden;margin-bottom:6px">
        <div id="daily-bar" style="height:100%;width:0%;border-radius:5px;transition:width .8s ease;background:linear-gradient(90deg,#E8700A,#B85508)"></div>
      </div>
      <div style="display:flex;justify-content:space-between;font-size:11px;color:var(--text-secondary)">
        <span id="daily-sales-amt">₱0 achieved</span>
        <span id="daily-target-amt">Goal: ₱0</span>
      </div>
    </div>
    <!-- Monthly target -->
    <div>
      <div style="display:flex;justify-content:space-between;margin-bottom:6px">
        <span style="font-size:12px;font-weight:700;color:var(--text-primary)">🗓️ Monthly Target</span>
        <span style="font-size:12px;font-weight:800;color:var(--acc)" id="monthly-pct">0%</span>
      </div>
      <div style="height:10px;background:var(--border);border-radius:5px;overflow:hidden;margin-bottom:6px">
        <div id="monthly-bar" style="height:100%;width:0%;border-radius:5px;transition:width .8s ease;background:linear-gradient(90deg,#1D9E75,#116b4e)"></div>
      </div>
      <div style="display:flex;justify-content:space-between;font-size:11px;color:var(--text-secondary)">
        <span id="monthly-sales-amt">₱0 achieved</span>
        <span id="monthly-target-amt">Goal: ₱0</span>
      </div>
    </div>
  </div>
</div>
        <div class="row2">
          <div class="card"><div class="card-hd">Weekly Sales <span class="bdg bo">7 days</span></div><div class="ch"><canvas id="ch-sales"></canvas></div></div>
          <div class="card"><div class="card-hd">Sales by Category</div><div class="ch"><canvas id="ch-cat"></canvas></div></div>
        </div>
        <div class="card">
          <div class="card-hd">Recent Transactions <button class="btn bta bts" onclick="nav('pos',document.querySelectorAll('.ni')[2])"><i class="ti ti-plus"></i>New sale</button></div>
          <div style="overflow-x:auto"><table class="tbl" id="d-txn-tbl"></table></div>
        </div>
      </div>

      <!-- INVENTORY -->
    <div class="pg" id="pg-inventory">
      <div class="pg-header">
        <div class="pg-header-left"><h2>Inventory Management</h2><p id="inv-stat">Loading...</p></div>
       <div style="display:flex;gap:6px">
        <button class="btn" onclick="exportInvCSV()"><i class="ti ti-download"></i>Export</button>
        <button class="btn bta" onclick="openModal('add-prod')"><i class="ti ti-plus"></i>Add product</button>
    </div>
  </div>
  <div style="display:flex;gap:8px;margin-bottom:12px">
    <input type="text" class="fi" style="flex:1" placeholder="🔍 Search products..." id="inv-srch" oninput="renderInv()">
    <select class="fi" style="width:160px" id="inv-cat" onchange="renderInv()"><option value="">All categories</option></select>
    <select class="fi" style="width:140px" id="inv-filter" onchange="renderInv()">
      <option value="">All status</option><option value="low">Low stock</option><option value="out">Out of stock</option>
    </select>
  </div>
  <div class="card" style="padding:0;overflow:hidden"><div style="overflow-x:auto"><table class="tbl" id="inv-tbl"></table></div></div>

  <!-- Restock History -->
  <div class="card" style="margin-top:14px">
    <div class="card-hd">
      📦 Restock History
      <button class="btn bts" onclick="loadRestockLogs()">
        <i class="ti ti-refresh"></i>Refresh
      </button>
    </div>
    <div style="overflow-x:auto">
      <table class="tbl" id="restock-log-tbl">
        <thead>
          <tr>
            <th>Product</th>
            <th>Added</th>
            <th>Before</th>
            <th>After</th>
            <th>Restocked by</th>
            <th>Date & Time</th>
          </tr>
        </thead>
        <tbody id="restock-log-body">
          <tr><td colspan="6">
            <div class="ec"><i class="ti ti-history"></i>No restock history yet</div>
          </td></tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

      <!-- POS -->
      <div class="pg" id="pg-pos">
        <div class="pos-wrap">
          <div>
            <div class="scan-wrap" id="scan-zone">
  <i class="ti ti-scan" id="scan-icon"></i>
  <div style="font-size:12px;font-weight:700;color:var(--text-secondary);margin-bottom:8px">Barcode Scanner</div>
  <div style="display:flex;gap:6px;justify-content:center">
    <input type="text" class="fi" id="barcode-input-field"
      placeholder="Type or scan barcode..."
      style="width:200px;font-family:'JetBrains Mono',monospace"
      onkeydown="if(event.key==='Enter')simulateScan()"
      onclick="event.stopPropagation()">
    <button class="btn bta" onclick="simulateScan()" style="white-space:nowrap">
      <i class="ti ti-scan"></i>Scan
    </button>
  </div>
  <div style="font-size:11px;color:var(--text-tertiary);margin-top:6px">
    Press Enter or click Scan after typing barcode
  </div>
</div>
            <div style="display:flex;gap:8px;margin-bottom:10px">
              <input type="text" class="fi" style="flex:1" placeholder="🔍 Search product..." id="pos-srch" oninput="renderPOS()">
              <select class="fi" style="width:150px" id="pos-cat" onchange="renderPOS()"><option value="">All categories</option></select>
            </div>
            <!-- tip banner -->
            <div style="display:flex;align-items:center;gap:6px;background:var(--info-bg);border:1px solid rgba(55,138,221,.2);border-radius:8px;padding:7px 12px;margin-bottom:10px;font-size:11px;color:var(--info-text);font-weight:600">
              <i class="ti ti-camera" style="font-size:14px"></i>
              Hover any product tile and click <strong>📷 Upload Photo</strong> to add a product image.
            </div>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px" id="pos-grid"></div>
          </div>
          <div class="cp">
            <div class="cp-hd">
              🛒 Cart
              <div style="display:flex;align-items:center;gap:6px">
                <span style="font-size:11px;color:var(--text-secondary);font-weight:500" id="ct-cnt">0 items</span>
                <button class="btn bts btd" onclick="clearCart()" style="padding:2px 6px;font-size:10px"><i class="ti ti-trash"></i></button>
              </div>
            </div>
            <div class="cp-body" id="ct-body"></div>
            <div class="cp-ft">
              <div class="tr"><span>Subtotal</span><span id="ct-sub">₱0.00</span></div>
              <div class="disc-row">
                <span style="font-size:12px;color:var(--text-secondary);white-space:nowrap;font-weight:600">Discount</span>
                <input type="number" class="fi" id="disc-val" placeholder="0" min="0" oninput="calcTotal()" style="width:62px;padding:4px 7px;font-size:11px">
                <div class="disc-type" style="display:flex;gap:4px">
                  <button class="dt on" id="dt-pct" onclick="setDiscType('pct')">%</button>
                  <button class="dt" id="dt-fix" onclick="setDiscType('fix')">₱</button>
                </div>
                <span style="font-size:12px;color:var(--danger);margin-left:auto;font-weight:600" id="disc-show">−₱0.00</span>
              </div>
              <div class="tr big"><span>Total</span><span id="ct-total">₱0.00</span></div>
              <div class="pay-row">
                <div class="pb on" onclick="setPay('cash',this)"><i class="ti ti-cash"></i>Cash</div>
                <div class="pb" onclick="setPay('gcash',this)"><i class="ti ti-brand-google-pay"></i>GCash</div>
                <div class="pb" onclick="setPay('maya',this)"><i class="ti ti-credit-card"></i>Maya</div>
                <div class="pb" onclick="setPay('utang',this)"><i class="ti ti-user-dollar"></i>Utang</div>
              </div>
              <div id="utang-name-wrap" style="display:none;margin-bottom:6px">
                <input type="text" class="fi" id="utang-name" placeholder="Customer name for credit...">
              </div>
              <div style="display:flex;gap:6px;margin-bottom:8px">
                <input type="number" class="fi" id="cash-in" placeholder="Cash tendered" oninput="calcChange()">
                <span style="font-size:12px;padding:8px 0;color:var(--text-secondary);white-space:nowrap;font-weight:600">Chg: <span id="chg" style="color:var(--text-primary);font-weight:800">₱0.00</span></span>
              </div>
              <button class="btn bta" style="width:100%;justify-content:center;padding:11px;font-size:13px" onclick="checkout()"><i class="ti ti-check"></i>Process payment</button>
            </div>
          </div>
        </div>
      </div>

      <!-- STAFF -->
      <div class="pg" id="pg-staff">
        <div class="pg-header">
          <div class="pg-header-left"><h2>Staff Management</h2><p id="staff-stat">Loading...</p></div>
          <button class="btn bta" onclick="openModal('add-staff')"><i class="ti ti-user-plus"></i>Add staff member</button>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:10px;margin-bottom:14px" id="staff-cards"></div>
        <div class="card"><div class="card-hd">Employee Activity Log</div><div style="overflow-x:auto"><table class="tbl" id="staff-log"></table></div></div>
      </div>

     <!-- PAYMENTS -->
<div class="pg" id="pg-payments">
  <div class="pg-header"><div class="pg-header-left"><h2>Payment Management</h2><p>Track and manage all payment methods</p></div></div>
  <div class="sc">
    <div class="sk"><div class="sk-icon" style="background:var(--success-bg)"><i class="ti ti-cash" style="color:var(--success-text);font-size:17px"></i></div><div class="sk-lbl">Cash</div><div class="sk-val" id="p-cash">₱0</div></div>
    <div class="sk"><div class="sk-icon" style="background:var(--info-bg)"><i class="ti ti-wallet" style="color:var(--info-text);font-size:17px"></i></div><div class="sk-lbl">E-Wallet</div><div class="sk-val" id="p-ewallet">₱0</div></div>
    <div class="sk"><div class="sk-icon" style="background:var(--warn-bg)"><i class="ti ti-user-dollar" style="color:var(--warn-text);font-size:17px"></i></div><div class="sk-lbl">Credit (Utang)</div><div class="sk-val" id="p-utang">₱0</div></div>
    <div class="sk hl"><div class="sk-icon" style="background:var(--acc-l)"><i class="ti ti-coins" style="color:var(--acc);font-size:17px"></i></div><div class="sk-lbl">Total Collected</div><div class="sk-val" id="p-total">₱0</div></div>
  </div>
  <div class="card" style="margin-bottom:14px">
    <div class="card-hd">Credit Accounts (Utang) <button class="btn bts" onclick="markUtangPaid()" style="color:var(--warn-text);border-color:#e8c070">Mark selected as paid</button></div>
    <div id="utang-list"></div>
  </div>
  <div class="card"><div class="card-hd">Payment History</div><div style="overflow-x:auto"><table class="tbl" id="pay-tbl"></table></div></div>
</div>

<!-- ATTENDANCE -->
<div class="pg" id="pg-attendance">
  <div class="pg-header">
    <div class="pg-header-left"><h2>Attendance & Payroll</h2><p>Track staff attendance and compute payroll</p></div>
    <div style="display:flex;gap:6px">
      <input type="month" class="fi" style="width:160px" id="att-month" onchange="renderAttendance()">
      <button class="btn bta" onclick="generatePayroll()"><i class="ti ti-calculator"></i>Generate Payroll</button>
    </div>
  </div>

  <!-- Summary cards -->
  <div class="sc" style="grid-template-columns:repeat(4,1fr);margin-bottom:14px">
    <div class="sk">
      <div class="sk-icon" style="background:var(--info-bg)"><i class="ti ti-users" style="font-size:17px;color:var(--info-text)"></i></div>
      <div class="sk-lbl">Staff Present Today</div>
      <div class="sk-val" id="att-present">0</div>
    </div>
    <div class="sk">
      <div class="sk-icon" style="background:var(--success-bg)"><i class="ti ti-clock" style="font-size:17px;color:var(--success-text)"></i></div>
      <div class="sk-lbl">Total Hours Today</div>
      <div class="sk-val" id="att-hours">0</div>
    </div>
    <div class="sk">
      <div class="sk-icon" style="background:var(--warn-bg)"><i class="ti ti-calendar" style="font-size:17px;color:var(--warn-text)"></i></div>
      <div class="sk-lbl">Days This Month</div>
      <div class="sk-val" id="att-days">0</div>
    </div>
    <div class="sk hl">
      <div class="sk-icon" style="background:var(--acc-l)"><i class="ti ti-currency-peso" style="font-size:17px;color:var(--acc)"></i></div>
      <div class="sk-lbl">Monthly Payroll</div>
      <div class="sk-val" id="att-payroll">₱0</div>
    </div>
  </div>

  <!-- Time in/out buttons -->
  <div class="card" style="margin-bottom:14px">
    <div class="card-hd">⏰ Today's Attendance
      <span style="font-size:11px;color:var(--text-secondary);font-weight:500" id="att-today-date"></span>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:8px" id="att-staff-btns"></div>
  </div>

  <!-- Attendance log -->
  <div class="card" style="margin-bottom:14px">
    <div class="card-hd">📋 Attendance Log
      <input type="date" class="fi" style="width:160px" id="att-log-date" onchange="loadAttendanceLog()" value="">
    </div>
    <div style="overflow-x:auto"><table class="tbl" id="att-log-tbl"></table></div>
  </div>

  <!-- Payroll -->
  <div class="card">
    <div class="card-hd">💰 Payroll Summary
      <span style="font-size:11px;color:var(--text-secondary);font-weight:500">Click "Generate Payroll" to compute</span>
    </div>
    <div style="overflow-x:auto"><table class="tbl" id="payroll-tbl"></table></div>
  </div>
</div>

<!-- CUSTOMERS -->
<div class="pg" id="pg-customers">
  <div class="pg-header">
    <div class="pg-header-left"><h2>Customer Management</h2><p>Track customers, purchase history and credits</p></div>
    <button class="btn bta" onclick="openCustomerModal()"><i class="ti ti-user-plus"></i>Add customer</button>
  </div>
  <div class="sc" style="grid-template-columns:repeat(4,1fr);margin-bottom:14px">
    <div class="sk">
      <div class="sk-icon" style="background:var(--info-bg)"><i class="ti ti-users" style="font-size:17px;color:var(--info-text)"></i></div>
      <div class="sk-lbl">Total Customers</div>
      <div class="sk-val" id="cust-total">0</div>
    </div>
    <div class="sk">
      <div class="sk-icon" style="background:var(--success-bg)"><i class="ti ti-currency-peso" style="font-size:17px;color:var(--success-text)"></i></div>
      <div class="sk-lbl">Total Revenue</div>
      <div class="sk-val" id="cust-revenue">₱0</div>
    </div>
    <div class="sk">
      <div class="sk-icon" style="background:var(--warn-bg)"><i class="ti ti-user-dollar" style="font-size:17px;color:var(--warn-text)"></i></div>
      <div class="sk-lbl">Outstanding Credit</div>
      <div class="sk-val" id="cust-credit">₱0</div>
    </div>
    <div class="sk">
      <div class="sk-icon" style="background:#f0e8ff"><i class="ti ti-receipt" style="font-size:17px;color:#7F77DD"></i></div>
      <div class="sk-lbl">Total Transactions</div>
      <div class="sk-val" id="cust-txns">0</div>
    </div>
  </div>
  <div style="display:flex;gap:8px;margin-bottom:12px">
    <input type="text" class="fi" style="flex:1" placeholder="🔍 Search customers by name or phone..." id="cust-srch" oninput="renderCustomers()">
  </div>
  <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:10px" id="cust-cards"></div>
</div>

<!-- EXPENSES -->
<div class="pg" id="pg-expenses">
  <div class="pg-header">
    <div class="pg-header-left"><h2>Expense Tracking</h2><p>Record and monitor all business expenses</p></div>
    <button class="btn bta" onclick="openExpenseModal()"><i class="ti ti-plus"></i>Add expense</button>
  </div>
  <div class="sc" style="grid-template-columns:repeat(4,1fr);margin-bottom:14px">
    <div class="sk">
      <div class="sk-icon" style="background:var(--danger-bg)"><i class="ti ti-calendar-day" style="font-size:17px;color:var(--danger)"></i></div>
      <div class="sk-lbl">Today's Expenses</div>
      <div class="sk-val" id="exp-today">₱0</div>
    </div>
    <div class="sk">
      <div class="sk-icon" style="background:var(--warn-bg)"><i class="ti ti-calendar-month" style="font-size:17px;color:var(--warn-text)"></i></div>
      <div class="sk-lbl">This Month</div>
      <div class="sk-val" id="exp-month">₱0</div>
    </div>
    <div class="sk">
      <div class="sk-icon" style="background:var(--info-bg)"><i class="ti ti-report-money" style="font-size:17px;color:var(--info-text)"></i></div>
      <div class="sk-lbl">All Time</div>
      <div class="sk-val" id="exp-all">₱0</div>
    </div>
    <div class="sk hl">
      <div class="sk-icon" style="background:var(--success-bg)"><i class="ti ti-trending-up" style="font-size:17px;color:var(--success-text)"></i></div>
      <div class="sk-lbl">Net Profit (Month)</div>
      <div class="sk-val" id="exp-net">₱0</div>
    </div>
  </div>
  <div class="row2" style="margin-bottom:14px">
    <div class="card">
      <div class="card-hd">Expenses by Category</div>
      <div id="exp-cat-list"></div>
    </div>
    <div class="card">
      <div class="card-hd">Monthly Overview</div>
      <div class="ch"><canvas id="ch-expenses"></canvas></div>
    </div>
  </div>
  <div class="card" style="padding:0;overflow:hidden">
    <div class="card-hd" style="padding:12px 16px">
      All Expenses
      <div style="display:flex;gap:6px">
        <input type="text" class="fi" style="width:180px" id="exp-srch" placeholder="🔍 Search..." oninput="renderExpenses()">
        <select class="fi" style="width:140px" id="exp-cat-filter" onchange="renderExpenses()">
          <option value="">All categories</option>
          <option>Utilities</option>
          <option>Rent</option>
          <option>Supplies</option>
          <option>Salaries</option>
          <option>Maintenance</option>
          <option>Transportation</option>
          <option>Other</option>
        </select>
      </div>
    </div>
    <div style="overflow-x:auto"><table class="tbl" id="exp-tbl"></table></div>
  </div>
</div>

<!-- REPORTS -->
<div class="pg" id="pg-reports">
  <div class="pg-header"><div class="pg-header-left"><h2>Reports & Analytics</h2><p>Business insights and performance data</p></div></div>
  <div class="rpt-tabs">
    <button class="rt on" onclick="rptTab('sales',this)">📊 Sales</button>
    <button class="rt" onclick="rptTab('inventory',this)">📦 Inventory</button>
    <button class="rt" onclick="rptTab('employee',this)">👥 Employee</button>
  </div>
  <div id="rpt-sales">
    <div class="row2">
      <div class="card"><div class="card-hd">Monthly Revenue</div><div class="ch"><canvas id="ch-monthly"></canvas></div></div>
      <div class="card"><div class="card-hd">Top Products</div><div class="ch"><canvas id="ch-top"></canvas></div></div>
    </div>
    <div class="card"><div class="card-hd">Sales Summary <button class="btn bts" onclick="exportCSV()"><i class="ti ti-download"></i>Export CSV</button></div><div style="overflow-x:auto"><table class="tbl" id="rpt-tbl"></table></div></div>
  </div>
  <div id="rpt-inventory" style="display:none">
    <div class="card"><div class="card-hd">Inventory Report <button class="btn bts" onclick="exportInvCSV()"><i class="ti ti-download"></i>Export CSV</button></div><div style="overflow-x:auto"><table class="tbl" id="rpt-inv-tbl"></table></div></div>
  </div>
  <div id="rpt-employee" style="display:none">
    <div class="card"><div class="card-hd">Employee Report</div><div style="overflow-x:auto"><table class="tbl" id="rpt-emp-tbl"></table></div></div>
  </div>
</div>

<!-- TRANSACTIONS -->
<div class="pg" id="pg-transactions">
  <div class="pg-header">
    <div class="pg-header-left"><h2>Transaction Monitoring</h2><p>Full history of all sales transactions</p></div>
    <div style="display:flex;gap:8px;flex-wrap:wrap">
      <input type="text" class="fi" style="width:200px" id="txn-srch" placeholder="🔍 Search ID, product, staff..." oninput="renderTxns()">
      <input type="date" class="fi" style="width:160px" id="txn-date" onchange="renderTxns()">
      <select class="fi" style="width:140px" id="txn-pay" onchange="renderTxns()">
        <option value="">All methods</option><option>Cash</option><option>GCash</option><option>Maya</option><option>Utang</option>
      </select>
      <button class="btn" onclick="clearTxnFilters()"><i class="ti ti-x"></i>Clear</button>
    </div>
  </div>
  <div class="card" style="padding:0;overflow:hidden"><div style="overflow-x:auto"><table class="tbl" id="txn-tbl"></table></div></div>
</div>

<!-- ─── MODALS ─── -->

<!-- Product detail popup -->
<div class="modalbg" id="modal-prod-detail">
  <div class="modal prod-detail-modal">
    <div class="pdm-hero" id="pdm-hero">
      <span id="pdm-em" style="font-size:64px"></span>
      <div class="pdm-badge" id="pdm-badge"></div>
    </div>
    <div class="pdm-name" id="pdm-name"></div>
    <div class="pdm-cat" id="pdm-cat"></div>
    <div class="pdm-stats">
      <div class="pdm-stat"><div class="pdm-stat-val" id="pdm-price"></div><div class="pdm-stat-lbl">Selling price</div></div>
      <div class="pdm-stat"><div class="pdm-stat-val" id="pdm-stock"></div><div class="pdm-stat-lbl">In stock</div></div>
      <div class="pdm-stat"><div class="pdm-stat-val" id="pdm-mkp"></div><div class="pdm-stat-lbl">Markup</div></div>
    </div>
    <div style="font-size:11px;color:var(--text-secondary);margin-bottom:4px;font-weight:700;letter-spacing:.2px;text-transform:uppercase">Stock level</div>
    <div class="stock-bar"><div class="stock-bar-fill" id="pdm-stockbar"></div></div>
    <div style="display:flex;justify-content:space-between;font-size:10.5px;color:var(--text-tertiary);margin-top:3px;margin-bottom:16px"><span>0</span><span id="pdm-reorder-lbl"></span></div>
    <div style="display:flex;gap:8px">
      <button class="btn" style="flex:1;justify-content:center" onclick="closeModal('modal-prod-detail')">Close</button>
      <button class="btn bta" style="flex:1;justify-content:center" id="pdm-add-btn"><i class="ti ti-shopping-cart-plus"></i>Add to cart</button>
      <button class="btn" style="flex:1;justify-content:center" id="pdm-edit-btn"><i class="ti ti-edit"></i>Edit</button>
    </div>
  </div>
</div>

<!-- Add product -->
<div class="modalbg" id="modal-add-prod">
  <div class="modal">
    <h3><i class="ti ti-package"></i><span id="prod-mtitle">Add product</span></h3>
    <div class="fg"><label class="fl">Product name</label><input class="fi" id="pf-name" placeholder="e.g. Soft Drinks 1.5L"></div>
    <div class="fr">
      <div class="fg"><label class="fl">Category</label><select class="fi" id="pf-cat"><option>Beverages</option><option>Snacks</option><option>Noodles & Pasta</option><option>Canned Goods</option><option>Condiments</option><option>Personal Care</option><option>Rice & Grains</option><option>Others</option></select></div>
      <div class="fg"><label class="fl">Emoji icon</label><input class="fi" id="pf-em" placeholder="🥤" maxlength="4"></div>
    </div>
    <div class="fr">
      <div class="fg"><label class="fl">Cost price (₱)</label><input class="fi" type="number" id="pf-cost" placeholder="0.00" min="0" oninput="autoPrice()"></div>
      <div class="fg"><label class="fl">Markup %</label><input class="fi" type="number" id="pf-markup" placeholder="20" min="0" oninput="autoPrice()"></div>
    </div>
    <div class="fr">
      <div class="fg"><label class="fl">Selling price (₱)</label><input class="fi" type="number" id="pf-price" placeholder="0.00" min="0"></div>
      <div class="fg"><label class="fl">Stock quantity</label><input class="fi" type="number" id="pf-stock" placeholder="0" min="0"></div>
    </div>
    <div class="fg"><label class="fl">Re-order point</label><input class="fi" type="number" id="pf-reorder" placeholder="10" min="0"></div>
    <div class="fr">
  <div class="fg">
    <label class="fl">Expiry date (optional)</label>
    <input class="fi" type="date" id="pf-expiry">
  </div>
  <div class="fg">
    <label class="fl">Warn me before (days)</label>
    <input class="fi" type="number" id="pf-expiry-alert" placeholder="30" min="1" value="30">
  </div>
</div>
    <div class="fr">
  <div class="fg"><label class="fl">SKU (Stock Keeping Unit)</label><input class="fi" id="pf-sku" placeholder="e.g. BEV-001"></div>
  <div class="fg"><label class="fl">Barcode number</label><input class="fi" id="pf-barcode" placeholder="e.g. 4800029830023"></div>
</div>
    <!-- Product image upload -->
    <div class="fg">
      <label class="fl">Product image (JPG/PNG) — optional</label>
      <div class="img-upload-zone" id="prod-img-zone" onclick="document.getElementById('pf-img-input').click()" ondragover="event.preventDefault();this.classList.add('dragging')" ondragleave="this.classList.remove('dragging')" ondrop="handleProdImgDrop(event)">
        <i class="ti ti-photo"></i>
        <div style="font-size:12px;font-weight:700;color:var(--text-secondary)">Click or drag & drop to upload</div>
        <div style="font-size:11px;color:var(--text-tertiary)">JPG, PNG, WebP · Replaces emoji on POS tile</div>
      </div>
      <input type="file" id="pf-img-input" accept="image/*" style="display:none" onchange="handleProdFormImg(this)">
      <img id="pf-img-preview" class="img-preview-thumb" src="" alt="Preview">
    </div>
    <div class="ma"><button class="btn" onclick="closeModal('modal-add-prod')">Cancel</button><button class="btn bta" onclick="saveProd()"><i class="ti ti-check"></i>Save product</button></div>
  </div>
</div>

<!-- Add / Edit Staff -->
<div class="modalbg" id="modal-add-staff">
  <div class="modal" style="max-width:560px">
    <h3><i class="ti ti-user-plus"></i><span id="staff-mtitle">Add staff member</span></h3>
    <div class="section-divider">Personal Information</div>
    <div class="fr3">
      <div class="fg"><label class="fl">First name *</label><input class="fi" id="sf-first" placeholder="Maria"></div>
      <div class="fg"><label class="fl">Middle name*</label><input class="fi" id="sf-middle" placeholder="Dela"></div>
      <div class="fg"><label class="fl">Last name *</label><input class="fi" id="sf-last" placeholder="Santos"></div>
      <div class="fg"><label class="fl">Suffix *</label><input class="fi" id="sf-suffix" placeholder="Jr."></div>
    </div>
    <div class="fr">
      <div class="fg"><label class="fl">Date of birth</label><input class="fi" type="date" id="sf-dob"></div>
      <div class="fg"><label class="fl">Gender</label><select class="fi" id="sf-gender"><option value="">Select gender</option><option>Male</option><option>Female</option><option>Non-binary</option><option>Prefer not to say</option></select></div>
    </div>
    <div class="fg"><label class="fl">Address</label><input class="fi" id="sf-address" placeholder="Street, Barangay, City/Municipality"></div>
    <div class="fr">
      <div class="fg"><label class="fl">City / Municipality</label><input class="fi" id="sf-city" placeholder="Olongapo City"></div>
      <div class="fg"><label class="fl">Province</label><input class="fi" id="sf-province" placeholder="Zambales"></div>
    </div>
    <div class="section-divider">Contact Information</div>
    <div class="fr">
      <div class="fg"><label class="fl">Mobile number *</label><input class="fi" id="sf-phone" placeholder="09XXXXXXXXX"></div>
      <div class="fg"><label class="fl">Email address</label><input class="fi" type="email" id="sf-email" placeholder="maria@email.com"></div>
    </div>
    <div class="fg"><label class="fl">Emergency contact name</label><input class="fi" id="sf-ec-name" placeholder="Emergency contact person"></div>
    <div class="fr">
      <div class="fg"><label class="fl">Emergency contact number</label><input class="fi" id="sf-ec-phone" placeholder="09XXXXXXXXX"></div>
      <div class="fg"><label class="fl">Relationship</label><input class="fi" id="sf-ec-rel" placeholder="e.g. Spouse, Parent"></div>
    </div>
    <div class="section-divider">Employment Details</div>
    <div class="fr">
      <div class="fg"><label class="fl">Role / Position *</label>
        <select class="fi" id="sf-role"><option>Cashier</option><option>Inventory Staff</option><option>Delivery Driver</option><option>Stock Boy / Girl</option><option>Store Supervisor</option><option>Manager</option><option>Accountant</option><option>Security Guard</option><option>Janitor / Cleaner</option><option>Administrator</option></select>
      </div>
      <div class="fg"><label class="fl">Employment type</label>
        <select class="fi" id="sf-emp-type"><option>Full-time</option><option>Part-time</option><option>Contractual</option><option>Probationary</option></select>
      </div>
    </div>
    <div class="fr">
      <div class="fg"><label class="fl">Date hired</label><input class="fi" type="date" id="sf-hired"></div>
      <div class="fg"><label class="fl">Salary per day (₱) *</label><input class="fi" type="number" id="sf-sal" placeholder="500" min="0"></div>
    </div>
    <div class="section-divider">Government IDs (optional)</div>
    <div class="fr">
      <div class="fg"><label class="fl">SSS number</label><input class="fi" id="sf-sss" placeholder="XX-XXXXXXX-X"></div>
      <div class="fg"><label class="fl">PhilHealth number</label><input class="fi" id="sf-philhealth" placeholder="XX-XXXXXXXXX-X"></div>
    </div>
    <div class="fr">
      <div class="fg"><label class="fl">Pag-IBIG (HDMF) number</label><input class="fi" id="sf-pagibig" placeholder="XXXX-XXXX-XXXX"></div>
      <div class="fg"><label class="fl">TIN number</label><input class="fi" id="sf-tin" placeholder="XXX-XXX-XXX"></div>
    </div>
    <div class="section-divider">Login Credentials</div>
    <div class="fr">
      <div class="fg"><label class="fl">Account</label><input class="fi" id="sf-username" placeholder="Account" oninput="checkStaffUsername()"></div>
      <div class="fg"><label class="fl">Password</label><input class="fi" type="password" id="sf-password" placeholder="min. 6 characters"></div>
    </div>
    <div id="sf-user-msg" style="font-size:11px;margin-top:-8px;margin-bottom:8px;color:var(--text-tertiary)"></div>
    <div style="font-size:11px;color:var(--text-tertiary);background:var(--bg-secondary);padding:8px 10px;border-radius:8px;font-weight:500"><i class="ti ti-info-circle" style="font-size:12px"></i> If credentials are provided, this staff member can log in to the system.</div>
    <div class="ma"><button class="btn" onclick="closeModal('modal-add-staff')"><i class="ti ti-x"></i>Cancel</button><button class="btn bta" onclick="saveStaff()"><i class="ti ti-check"></i>Save staff member</button></div>
  </div>
</div>
<!-- Backup Database -->
<div class="modalbg" id="modal-backup">
  <div class="modal" style="max-width:420px">
    <h3><i class="ti ti-database-export"></i>Database Backup</h3>

    <!-- Stats -->
    <div style="background:var(--bg-secondary);border-radius:10px;padding:14px;margin-bottom:16px" id="backup-stats">
      <div style="text-align:center;color:var(--text-tertiary);font-size:12px">
        <i class="ti ti-loader" style="font-size:20px;display:block;margin-bottom:6px"></i>
        Loading database info...
      </div>
    </div>

    <div style="background:var(--info-bg);border-radius:10px;padding:12px;margin-bottom:16px;font-size:12px;color:var(--info-text)">
      <i class="ti ti-info-circle" style="font-size:14px"></i>
      This will download a <b>.sql file</b> containing all your data. Store it somewhere safe like Google Drive or USB.
    </div>

    <div style="font-size:11px;color:var(--text-secondary);margin-bottom:16px;font-weight:500">
      💡 <b>Tip:</b> Do this regularly — daily or weekly — to prevent data loss.
    </div>

    <div class="ma">
      <button class="btn" onclick="closeModal('modal-backup')">Cancel</button>
      <button class="btn bta" onclick="downloadBackup()" id="backup-btn">
        <i class="ti ti-download"></i>Download Backup
      </button>
    </div>
  </div>
</div>
<!-- Set Sales Target -->
<div class="modalbg" id="modal-target">
  <div class="modal" style="max-width:380px">
    <h3><i class="ti ti-target"></i>Set Sales Targets</h3>
    <div class="fg">
      <label class="fl">Daily Sales Target (₱)</label>
      <input class="fi" type="number" id="tgt-daily" placeholder="e.g. 2000" min="0">
      <div style="font-size:11px;color:var(--text-secondary);margin-top:4px">How much you want to sell per day</div>
    </div>
    <div class="fg">
      <label class="fl">Monthly Sales Target (₱)</label>
      <input class="fi" type="number" id="tgt-monthly" placeholder="e.g. 50000" min="0">
      <div style="font-size:11px;color:var(--text-secondary);margin-top:4px">How much you want to sell this month</div>
    </div>
    <div class="ma">
      <button class="btn" onclick="closeModal('modal-target')">Cancel</button>
      <button class="btn bta" onclick="saveTargets()"><i class="ti ti-check"></i>Save targets</button>
    </div>
  </div>
</div>
<!-- Add/Edit Customer -->
<div class="modalbg" id="modal-customer">
  <div class="modal" style="max-width:420px">
    <h3><i class="ti ti-user-plus"></i><span id="cust-mtitle">Add Customer</span></h3>
    <div class="fg"><label class="fl">Full name *</label><input class="fi" id="cf-name" placeholder="e.g. Juan dela Cruz"></div>
    <div class="fr">
      <div class="fg"><label class="fl">Phone number</label><input class="fi" id="cf-phone" placeholder="09XXXXXXXXX"></div>
      <div class="fg"><label class="fl">Email</label><input class="fi" type="email" id="cf-email" placeholder="juan@email.com"></div>
    </div>
    <div class="fg"><label class="fl">Address</label><input class="fi" id="cf-address" placeholder="Street, Barangay, City"></div>
    <div class="fg"><label class="fl">Notes</label><textarea class="fi" id="cf-notes" placeholder="Any notes about this customer..." rows="2" style="resize:vertical"></textarea></div>
    <div class="ma">
      <button class="btn" onclick="closeModal('modal-customer')">Cancel</button>
      <button class="btn bta" onclick="saveCustomer()"><i class="ti ti-check"></i>Save customer</button>
    </div>
  </div>
</div>

<!-- Customer History -->
<div class="modalbg" id="modal-cust-history">
  <div class="modal" style="max-width:500px">
    <h3><i class="ti ti-history"></i><span id="ch-cust-name">Customer History</span></h3>
    <div id="ch-cust-stats" style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:14px"></div>
    <div class="card-hd" style="margin-bottom:8px">Purchase History</div>
    <div style="overflow-x:auto"><table class="tbl" id="ch-cust-tbl"></table></div>
    <div class="ma"><button class="btn" onclick="closeModal('modal-cust-history')">Close</button></div>
  </div>
</div>
<!-- Add/Edit Expense -->
<div class="modalbg" id="modal-expense">
  <div class="modal" style="max-width:420px">
    <h3><i class="ti ti-receipt-2"></i><span id="exp-mtitle">Add Expense</span></h3>
    <div class="fg"><label class="fl">Title / Description</label><input class="fi" id="ef-title" placeholder="e.g. Electricity bill May"></div>
    <div class="fr">
      <div class="fg">
        <label class="fl">Category</label>
        <select class="fi" id="ef-cat">
          <option>Utilities</option>
          <option>Rent</option>
          <option>Supplies</option>
          <option>Salaries</option>
          <option>Maintenance</option>
          <option>Transportation</option>
          <option>Other</option>
        </select>
      </div>
      <div class="fg"><label class="fl">Amount (₱)</label><input class="fi" type="number" id="ef-amount" placeholder="0.00" min="0"></div>
    </div>
    <div class="fg"><label class="fl">Date</label><input class="fi" type="date" id="ef-date"></div>
    <div class="fg"><label class="fl">Notes (optional)</label><textarea class="fi" id="ef-notes" placeholder="Additional details..." rows="2" style="resize:vertical"></textarea></div>
    <div class="ma">
      <button class="btn" onclick="closeModal('modal-expense')">Cancel</button>
      <button class="btn bta" onclick="saveExpense()"><i class="ti ti-check"></i>Save expense</button>
    </div>
  </div>
</div>
<!-- Confirm delete -->
<div class="modalbg" id="modal-confirm">
  <div class="modal" style="max-width:340px;text-align:center">
    <div style="width:56px;height:56px;background:var(--danger-bg);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 14px">
      <i class="ti ti-alert-triangle" style="font-size:26px;color:var(--danger)"></i>
    </div>
    <h3 id="confirm-title" style="margin-bottom:8px;justify-content:center">Confirm action</h3>
    <p id="confirm-msg" style="font-size:12px;color:var(--text-secondary);margin-bottom:16px"></p>
    <div style="display:flex;gap:8px;justify-content:center">
      <button class="btn" onclick="closeModal('modal-confirm')">Cancel</button>
      <button class="btn btd" id="confirm-ok"><i class="ti ti-trash"></i>Yes, delete</button>
    </div>
  </div>
</div>

<!-- Restock modal -->
<div class="modalbg" id="modal-restock">
  <div class="modal" style="max-width:320px">
    <h3><i class="ti ti-package"></i>Restock product</h3>
    <div style="font-size:13px;font-weight:700;margin-bottom:12px" id="restock-pname"></div>
    <div class="fg"><label class="fl">Current stock</label><input class="fi" id="restock-cur" disabled></div>
    <div class="fg"><label class="fl">Add quantity</label><input class="fi" type="number" id="restock-qty" placeholder="e.g. 50" min="1"></div>
    <div class="ma"><button class="btn" onclick="closeModal('modal-restock')">Cancel</button><button class="btn bta" onclick="confirmRestock()"><i class="ti ti-check"></i>Restock</button></div>
  </div>
</div>

<!-- Receipt with QR -->
<div class="modalbg" id="modal-receipt">
  <div class="modal receipt-modal">
    <div class="receipt-icon"><i class="ti ti-check"></i></div>
    <h3 style="margin-bottom:4px;justify-content:center">Payment Complete!</h3>
    <p style="font-size:12px;color:var(--text-secondary)" id="rec-msg"></p>
    <div class="receipt-detail" id="rec-detail"></div>
    <div class="qr-wrap" id="qr-wrap">
      <div id="qr-code-container"></div>
      <div class="qr-label" id="qr-label">Scan to verify transaction</div>
    </div>
    <div style="display:flex;gap:8px">
      <button class="btn" style="flex:1;justify-content:center" onclick="printReceipt()"><i class="ti ti-printer"></i>Print</button>
      <button class="btn bta" style="flex:1;justify-content:center" onclick="closeModal('modal-receipt')">Done</button>
    </div>
  </div>
</div>

<script src="assets/js/script.js"></script>
<!-- ─── PRINT AREA ─── -->
<div id="print-area" style="display:none"></div>
</body>
</html>
}
