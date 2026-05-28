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
<style>
*{box-sizing:border-box;margin:0;padding:0}

:root{
  --acc:#E8700A;--acc-l:#FEF0E6;--acc-d:#B85508;--acc-rgb:232,112,10;
  --sw:186px;
  --bg-primary:#ffffff;--bg-secondary:#f7f6f2;--bg-tertiary:#eeecea;
  --text-primary:#1a1a18;--text-secondary:#6b6a64;--text-tertiary:#9a9892;
  --border:#e5e3dc;--border-md:#d0cec7;
  --danger:#e24b4a;--danger-bg:#fcebeb;--danger-text:#a32d2d;
  --success:#1D9E75;--success-bg:#eaf5f0;--success-text:#116b4e;
  --info-bg:#e6f1fb;--info-text:#185fa5;
  --warn-bg:#fef0e6;--warn-text:#b85508;
  --shadow:0 1px 3px rgba(0,0,0,0.07),0 1px 2px rgba(0,0,0,0.04);
  --shadow-lg:0 4px 16px rgba(0,0,0,0.1),0 2px 6px rgba(0,0,0,0.06);
  --shadow-xl:0 8px 32px rgba(0,0,0,0.14),0 2px 8px rgba(0,0,0,0.08);
  --radius:8px;--radius-lg:12px;--radius-xl:16px;
}
[data-theme="dark"]{
  --bg-primary:#1c1c1a;--bg-secondary:#252522;--bg-tertiary:#111110;
  --text-primary:#eeecea;--text-secondary:#908e86;--text-tertiary:#5e5d58;
  --border:#2e2d29;--border-md:#3a3936;
  --danger:#e24b4a;--danger-bg:#2a1616;--danger-text:#f09595;
  --success-bg:#0d1f08;--success-text:#97c459;
  --info-bg:#061525;--info-text:#85b7eb;
  --warn-bg:#1f1104;--warn-text:#ef9f27;
  --acc-l:#1f1208;
  --shadow:0 1px 4px rgba(0,0,0,0.4);
  --shadow-lg:0 4px 20px rgba(0,0,0,0.5);
  --shadow-xl:0 8px 32px rgba(0,0,0,0.7);
}

body{font-family:'Plus Jakarta Sans',system-ui,sans-serif;background:var(--bg-tertiary);color:var(--text-primary);font-size:13px;transition:background .2s,color .2s;height:100vh;overflow:hidden}

:root{
  --logo-bg: linear-gradient(135deg,#E8700A,#B85508);
  --logo-radius: 12px;
  --logo-shadow: 0 4px 16px rgba(232,112,10,.4);
}

.logo-wrap{display:flex;align-items:center;justify-content:center;background:var(--logo-bg);border-radius:var(--logo-radius);box-shadow:var(--logo-shadow);overflow:hidden;flex-shrink:0;}
.logo-wrap img{width:100%;height:100%;object-fit:contain;padding:14%;display:block}
.logo-wrap.logo-auth{width:48px;height:48px;border-radius:14px}
.logo-wrap.logo-sb{width:34px;height:34px;border-radius:9px;box-shadow:0 2px 8px rgba(232,112,10,.35)}
.logo-svg-mark{width:60%;height:60%}

/* ─── AUTH ─── */
.auth-screen{display:none;align-items:center;justify-content:center;height:100vh;width:100%;position:fixed;inset:0;z-index:999;background:var(--bg-tertiary);}
.auth-screen.on{display:flex}
.auth-bg{position:absolute;inset:0;overflow:hidden;pointer-events:none}
.auth-blob{position:absolute;border-radius:50%;filter:blur(80px);opacity:.12}
.auth-blob-1{width:500px;height:500px;background:#E8700A;top:-100px;left:-100px}
.auth-blob-2{width:400px;height:400px;background:#378ADD;bottom:-80px;right:-80px}
.auth-blob-3{width:300px;height:300px;background:#1D9E75;top:50%;right:20%}
.auth-card{width:420px;background:var(--bg-primary);border:0.5px solid var(--border);border-radius:20px;box-shadow:var(--shadow-xl);padding:36px;position:relative;z-index:1;}
.auth-logo{display:flex;align-items:center;gap:12px;margin-bottom:28px}
.auth-brand{font-size:16px;font-weight:800;color:var(--text-primary);letter-spacing:-.3px}
.auth-sub{font-size:11px;color:var(--text-secondary);margin-top:1px;font-weight:500}
.auth-title{font-size:24px;font-weight:800;margin-bottom:4px;letter-spacing:-.5px}
.auth-hint{font-size:12px;color:var(--text-secondary);margin-bottom:24px;font-weight:500}
.demo-creds{background:var(--bg-secondary);border:0.5px solid var(--border);border-radius:10px;padding:12px 14px;margin-bottom:20px;font-size:11.5px}
.demo-creds p{color:var(--text-secondary);margin-bottom:6px;font-weight:600;font-size:11px;text-transform:uppercase;letter-spacing:.5px}
.demo-cred-row{display:flex;align-items:center;gap:6px;padding:4px 0}
.demo-cred-row i{font-size:13px;color:var(--acc)}
.demo-cred-row code{color:var(--acc);font-family:'JetBrains Mono',monospace;font-size:11px;background:var(--acc-l);padding:2px 6px;border-radius:4px}
.lfi{width:100%;border:1.5px solid var(--border);border-radius:10px;padding:10px 12px;font-size:13px;background:var(--bg-primary);color:var(--text-primary);outline:none;transition:border .15s,box-shadow .15s;margin-bottom:10px;font-family:inherit;font-weight:500}
.lfi:focus{border-color:var(--acc);box-shadow:0 0 0 3px rgba(232,112,10,.1)}
.lfi::placeholder{color:var(--text-tertiary);font-weight:400}
.lbtn{width:100%;padding:12px;background:linear-gradient(135deg,#E8700A,#B85508);color:#fff;border:none;border-radius:10px;font-size:13px;font-weight:700;cursor:pointer;transition:all .2s;margin-top:6px;display:flex;align-items:center;justify-content:center;gap:7px;font-family:inherit;letter-spacing:.02em;box-shadow:0 4px 16px rgba(232,112,10,.35)}
.lbtn:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(232,112,10,.45)}
.lbtn:active{transform:scale(.98)}
.lerr{color:var(--danger);font-size:12px;margin-top:8px;display:none;align-items:center;gap:5px;background:var(--danger-bg);padding:8px 10px;border-radius:8px;font-weight:500}
.auth-theme-btn{position:fixed;top:16px;right:16px;width:38px;height:38px;border:1.5px solid var(--border);border-radius:10px;background:var(--bg-primary);cursor:pointer;display:flex;align-items:center;justify-content:center;color:var(--text-secondary);transition:all .15s;z-index:1001}
.auth-theme-btn:hover{background:var(--bg-secondary)}

/* ─── APP SHELL ─── */
.shell{display:flex;height:100vh;overflow:hidden}
.sb{width:var(--sw);background:var(--bg-primary);border-right:0.5px solid var(--border);flex-shrink:0;display:flex;flex-direction:column;overflow-y:auto;transition:background .2s,border .2s}
[data-theme="dark"] .sb{background:#161614}
.sb-logo{padding:14px 12px 10px;border-bottom:0.5px solid var(--border)}
.sb-brand{display:flex;align-items:center;gap:9px}
.sb-name{font-size:12px;font-weight:800;color:var(--text-primary);letter-spacing:-.2px}
.sb-sub{font-size:10px;color:var(--text-tertiary);margin-top:1px;font-weight:500}
.sb-user{display:flex;align-items:center;gap:8px;padding:10px 12px;border-bottom:0.5px solid var(--border);background:var(--bg-secondary)}
.sb-av{width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,var(--acc),var(--acc-d));display:flex;align-items:center;justify-content:center;font-size:10px;color:#fff;font-weight:800;flex-shrink:0;box-shadow:0 2px 6px rgba(232,112,10,.3)}
.sb-uname{font-size:11.5px;color:var(--text-primary);font-weight:700}
.sb-urole{font-size:10px;color:var(--text-tertiary);font-weight:500}
.nav-sec{padding:8px 0;flex:1}
.nav-lbl{font-size:9px;color:var(--text-tertiary);padding:6px 14px 3px;letter-spacing:.8px;text-transform:uppercase;font-weight:700}
.ni{display:flex;align-items:center;gap:8px;padding:8px 14px;font-size:12px;color:var(--text-secondary);cursor:pointer;transition:all .15s;border-left:2.5px solid transparent;user-select:none;margin-right:8px;border-radius:0 8px 8px 0;font-weight:500}
.ni:hover{background:var(--bg-secondary);color:var(--text-primary)}
.ni.active{background:var(--acc-l);color:var(--acc);border-left-color:var(--acc);font-weight:700}
.ni i{font-size:15px;flex-shrink:0}
.ni .badge-dot{width:6px;height:6px;border-radius:50%;background:var(--danger);margin-left:auto}
.sb-bottom{border-top:0.5px solid var(--border);padding:10px 12px}
.logout-btn{display:flex;align-items:center;gap:7px;font-size:12px;color:var(--text-secondary);cursor:pointer;padding:6px 0;transition:color .15s;width:100%;background:none;border:none;font-family:inherit;font-weight:500}
.logout-btn:hover{color:var(--danger)}
.main{flex:1;display:flex;flex-direction:column;min-width:0;overflow:hidden}
.topbar{background:var(--bg-primary);border-bottom:0.5px solid var(--border);padding:10px 20px;display:flex;align-items:center;justify-content:space-between;transition:background .2s}
.tb-title{font-size:15px;font-weight:800;color:var(--text-primary);letter-spacing:-.3px}
.tb-right{display:flex;align-items:center;gap:10px;font-size:12px;color:var(--text-secondary)}
.theme-toggle{width:34px;height:34px;border:1px solid var(--border);border-radius:9px;background:transparent;cursor:pointer;display:flex;align-items:center;justify-content:center;color:var(--text-secondary);transition:all .15s}
.theme-toggle:hover{background:var(--bg-secondary)}
.notif-btn{width:34px;height:34px;border:1px solid var(--border);border-radius:9px;background:transparent;cursor:pointer;display:flex;align-items:center;justify-content:center;color:var(--text-secondary);transition:all .15s;position:relative}
.notif-btn:hover{background:var(--bg-secondary)}
.notif-badge{position:absolute;top:4px;right:4px;width:8px;height:8px;border-radius:50%;background:var(--danger);border:1.5px solid var(--bg-primary)}
.content{flex:1;padding:16px 20px;overflow-y:auto;background:var(--bg-tertiary);transition:background .2s}
.pg{display:none}.pg.on{display:block}

/* ─── STATS ─── */
.sc{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:14px}
.sk{background:var(--bg-primary);border:0.5px solid var(--border);border-radius:12px;padding:14px 16px;transition:all .2s;position:relative;overflow:hidden}
.sk::after{content:'';position:absolute;bottom:0;left:0;right:0;height:3px;background:transparent;transition:background .2s}
.sk.hl::after{background:linear-gradient(90deg,#E8700A,#B85508)}
.sk:hover{box-shadow:var(--shadow)}
.sk-icon{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;margin-bottom:10px;font-size:17px}
.sk-lbl{font-size:11px;color:var(--text-secondary);margin-bottom:3px;font-weight:600;letter-spacing:.2px}
.sk-val{font-size:21px;font-weight:800;color:var(--text-primary);letter-spacing:-.5px}
.sk-sub{font-size:10.5px;color:var(--text-tertiary);margin-top:2px;font-weight:500}
.sk-trend{font-size:10.5px;margin-top:4px;display:flex;align-items:center;gap:3px;font-weight:600}
.sk-trend.up{color:var(--success-text)}.sk-trend.dn{color:var(--danger-text)}
.row2{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px}
.card{background:var(--bg-primary);border:0.5px solid var(--border);border-radius:12px;padding:16px;transition:background .2s,border .2s}
.card-hd{font-size:13px;font-weight:700;margin-bottom:12px;display:flex;justify-content:space-between;align-items:center;color:var(--text-primary);letter-spacing:-.1px}
.ch{height:130px;position:relative}

/* ─── TABLE ─── */
.tbl{width:100%;border-collapse:collapse;font-size:12px}
.tbl th{text-align:left;padding:9px 12px;color:var(--text-secondary);font-weight:700;border-bottom:1px solid var(--border);white-space:nowrap;font-size:11px;letter-spacing:.3px;text-transform:uppercase}
.tbl td{padding:9px 12px;border-bottom:0.5px solid var(--border);vertical-align:middle;color:var(--text-primary)}
.tbl tr:last-child td{border-bottom:none}
.tbl tr:hover td{background:var(--bg-secondary)}

/* ─── BADGES ─── */
.bdg{display:inline-block;padding:3px 9px;border-radius:999px;font-size:10.5px;font-weight:700;letter-spacing:.02em}
.bg{background:var(--success-bg);color:var(--success-text)}
.br{background:var(--danger-bg);color:var(--danger-text)}
.bo{background:var(--warn-bg);color:var(--warn-text)}
.bb{background:var(--info-bg);color:var(--info-text)}

/* ─── BUTTONS ─── */
.btn{border:1px solid var(--border-md);border-radius:8px;padding:7px 13px;font-size:12px;cursor:pointer;background:transparent;color:var(--text-primary);display:inline-flex;align-items:center;gap:5px;transition:all .15s;font-family:inherit;font-weight:600}
.btn:hover{background:var(--bg-secondary)}
.bta{background:linear-gradient(135deg,#E8700A,#C06008);color:#fff !important;border-color:transparent;box-shadow:0 2px 8px rgba(232,112,10,.3)}
.bta:hover{background:linear-gradient(135deg,#D46008,#A85006) !important;box-shadow:0 4px 14px rgba(232,112,10,.4);transform:translateY(-1px)}
.btd{border-color:#e2a0a0;color:var(--danger)}
.btd:hover{background:var(--danger-bg)}
.btg{border-color:#9cc477;color:var(--success-text)}
.btg:hover{background:var(--success-bg)}
.bts{padding:4px 9px;font-size:11px}

/* ─── FORM ─── */
.fi{border:1.5px solid var(--border);border-radius:8px;padding:8px 11px;font-size:12px;background:var(--bg-primary);color:var(--text-primary);width:100%;outline:none;transition:border .15s,box-shadow .15s;font-family:inherit;font-weight:500}
.fi:focus{border-color:var(--acc);box-shadow:0 0 0 3px rgba(232,112,10,.1)}
.fi::placeholder{color:var(--text-tertiary);font-weight:400}
.fl{font-size:11px;color:var(--text-secondary);margin-bottom:4px;font-weight:700;letter-spacing:.2px}
.fg{display:flex;flex-direction:column;gap:4px;margin-bottom:10px}
.fr{display:grid;grid-template-columns:1fr 1fr;gap:10px}
.fr3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px}

/* ─── POS ─── */
.pos-wrap{display:grid;grid-template-columns:1fr 310px;gap:12px}

/* ── POS PRODUCT TILE ── */
.pg-tile{
  background:var(--bg-primary);border:1.5px solid var(--border);
  border-radius:12px;padding:0;cursor:pointer;text-align:center;
  transition:all .18s;position:relative;overflow:hidden;
  display:flex;flex-direction:column;
}
.pg-tile:hover{border-color:var(--acc);box-shadow:0 4px 16px rgba(232,112,10,.15);transform:translateY(-2px)}
.pg-tile:active{transform:scale(.97)}
.pg-tile.oos{opacity:.42;cursor:not-allowed}
.pg-tile.oos:hover{transform:none;border-color:var(--border);box-shadow:none}

/* ── Image area — always a fixed-height block ── */
.pt-img-wrap{
  width:100%;
  height:180px;
  display:flex;
  align-items:center;
  justify-content:center;
  overflow:hidden;
  flex-shrink:0;
  position:relative;
  border-radius:10px 10px 0 0;
  background:transparent; /* removed white background */
  padding:0;
}

.pt-img-wrap img{
  width:100%;
  height:100%;
  object-fit:contain; /* shows whole image */
  object-position:center;
  display:block;
  transition:transform .25s ease;
  background:transparent; /* important */
}

.pg-tile:hover .pt-img-wrap img{
  transform:scale(1.05);
}

/* ── Placeholder shown when no real image ── */
.pt-placeholder{
  display:flex;flex-direction:column;align-items:center;justify-content:center;
  gap:3px;width:100%;height:100%;
  background:linear-gradient(160deg, var(--bg-secondary) 0%, var(--bg-tertiary) 100%);
  position:relative;
}
.pt-placeholder::before{
  content:'';position:absolute;inset:0;
  background:repeating-linear-gradient(
    45deg,
    transparent,
    transparent 8px,
    rgba(0,0,0,0.018) 8px,
    rgba(0,0,0,0.018) 9px
  );
  border-radius:10px 10px 0 0;
}
[data-theme="dark"] .pt-placeholder::before{
  background:repeating-linear-gradient(
    45deg,
    transparent,
    transparent 8px,
    rgba(255,255,255,0.025) 8px,
    rgba(255,255,255,0.025) 9px
  );
}
.pt-ph-emoji{
  font-size:30px;line-height:1;display:block;
  position:relative;z-index:1;
  filter:drop-shadow(0 2px 4px rgba(0,0,0,0.10));
}
.pt-ph-camera{
  position:absolute;bottom:6px;right:8px;
  width:20px;height:20px;border-radius:50%;
  background:var(--bg-primary);border:1.5px solid var(--border-md);
  display:flex;align-items:center;justify-content:center;
  z-index:2;
  box-shadow:0 1px 4px rgba(0,0,0,0.10);
  transition:all .15s;
}
.pt-ph-camera i{font-size:10px;color:var(--text-tertiary)}
.pg-tile:hover .pt-ph-camera{
  background:var(--acc);border-color:var(--acc);
}
.pg-tile:hover .pt-ph-camera i{color:#fff}

/* Bottom info area */
.pt-info{padding:7px 8px 9px;flex:1;display:flex;flex-direction:column;gap:1px}
.pt-nm{font-size:10.5px;font-weight:700;color:var(--text-primary);line-height:1.3;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.pt-pr{font-size:13px;color:var(--acc);font-weight:800;margin-top:2px}
.pt-st{font-size:9.5px;color:var(--text-tertiary);font-weight:500}
.pt-badge{position:absolute;top:6px;right:6px;background:var(--acc);color:#fff;font-size:10px;font-weight:700;width:20px;height:20px;border-radius:50%;display:flex;align-items:center;justify-content:center;z-index:3}

/* OOS overlay */
.pt-oos-overlay{
  position:absolute;inset:0;background:rgba(0,0,0,0.04);
  display:flex;align-items:center;justify-content:center;
  pointer-events:none;border-radius:10px;z-index:1;
}
.pg-tile.oos .pt-oos-overlay::after{
  content:'Out of Stock';font-size:9px;font-weight:800;
  background:var(--danger);color:#fff;
  padding:2px 7px;border-radius:99px;letter-spacing:.3px;
}

/* Upload button on tile hover */
.pt-upload-btn{
  position:absolute;bottom:0;left:0;right:0;
  background:rgba(232,112,10,0.92);color:#fff;border:none;
  font-size:9.5px;font-weight:700;padding:5px 8px;cursor:pointer;
  display:none;white-space:nowrap;z-index:3;gap:4px;
  align-items:center;justify-content:center;
  font-family:inherit;letter-spacing:.2px;
  backdrop-filter:blur(2px);
}
.pg-tile:hover .pt-upload-btn{display:flex}
.pg-tile.has-img:hover .pt-upload-btn{
  /* On tiles with real images, show the button inside the image area */
  bottom:auto;top:50%;transform:translateY(-50%);
  left:8px;right:8px;border-radius:6px;
  background:rgba(0,0,0,0.6);
}

/* ─── CART ─── */
.cp{background:var(--bg-primary);border:0.5px solid var(--border);border-radius:12px;display:flex;flex-direction:column;height:calc(100vh - 128px);position:sticky;top:0}
.cp-hd{padding:12px 14px;border-bottom:0.5px solid var(--border);font-weight:800;font-size:13px;display:flex;justify-content:space-between;align-items:center;letter-spacing:-.2px}
.cp-body{flex:1;padding:4px 0;overflow-y:auto}
.ci-row{display:flex;align-items:center;gap:8px;padding:6px 12px;transition:background .1s;border-radius:8px;margin:2px 4px}
.ci-row:hover{background:var(--bg-secondary)}
.ci-em{font-size:18px;flex-shrink:0}
.ci-inf{flex:1;min-width:0}
.ci-nm{font-size:12px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:var(--text-primary)}
.ci-pr{font-size:11px;color:var(--text-secondary)}
.qc{display:flex;align-items:center;gap:4px}
.qb{width:24px;height:24px;border:1.5px solid var(--border-md);border-radius:6px;background:transparent;cursor:pointer;font-size:14px;display:flex;align-items:center;justify-content:center;color:var(--text-primary);transition:all .15s;font-weight:700}
.qb:hover{background:var(--acc);color:#fff;border-color:var(--acc)}
.cp-ft{padding:12px 14px;border-top:0.5px solid var(--border)}
.tr{display:flex;justify-content:space-between;font-size:12px;padding:3px 0;color:var(--text-secondary)}
.tr.big{font-size:15px;font-weight:800;color:var(--text-primary);border-top:1px solid var(--border);margin-top:6px;padding-top:9px;letter-spacing:-.3px}
.pay-row{display:flex;gap:5px;margin:8px 0}
.pb{flex:1;padding:7px 3px;border:1.5px solid var(--border-md);border-radius:8px;font-size:10.5px;cursor:pointer;background:transparent;color:var(--text-secondary);text-align:center;transition:all .15s;display:flex;flex-direction:column;align-items:center;gap:2px;font-family:inherit;font-weight:600}
.pb:hover{border-color:var(--acc);color:var(--acc)}
.pb.on{border-color:var(--acc);color:var(--acc);background:var(--acc-l);font-weight:700}
.pb i{font-size:15px}

/* ─── STAFF ─── */
.staff-card{background:var(--bg-primary);border:1px solid var(--border);border-radius:12px;padding:16px;transition:all .2s}
.staff-card:hover{box-shadow:var(--shadow-lg);transform:translateY(-2px)}
.staff-avatar{width:52px;height:52px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:16px;color:#fff;font-weight:800;flex-shrink:0}
.staff-role-badge{display:inline-flex;align-items:center;gap:4px;padding:3px 8px;border-radius:6px;font-size:10px;font-weight:700}

/* ─── REPORTS TABS ─── */
.rpt-tabs{display:flex;gap:4px;background:var(--bg-secondary);border-radius:10px;padding:4px;margin-bottom:14px}
.rt{flex:1;padding:7px;font-size:12px;border:none;background:transparent;border-radius:8px;cursor:pointer;color:var(--text-secondary);transition:all .15s;font-family:inherit;font-weight:600}
.rt.on{background:var(--bg-primary);color:var(--text-primary);font-weight:700;box-shadow:var(--shadow)}

/* ─── CREDIT ─── */
.credit-row{background:var(--warn-bg);border:1px solid #e8c070;border-radius:10px;padding:12px 14px;margin-bottom:8px;display:flex;justify-content:space-between;align-items:center;transition:all .15s}
[data-theme="dark"] .credit-row{border-color:#5a3d0a}
.credit-row:hover{box-shadow:var(--shadow)}
.cr-name{font-size:13px;font-weight:700;color:var(--warn-text)}
.cr-amt{font-size:12px;color:var(--warn-text);font-weight:500}

/* ─── EMPTY ─── */
.ec{text-align:center;padding:28px 16px;color:var(--text-tertiary)}
.ec i{font-size:32px;display:block;margin-bottom:8px}

/* ─── MODALS ─── */
.modalbg{display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:100;align-items:center;justify-content:center;padding:20px;backdrop-filter:blur(4px)}
.modalbg.on{display:flex}
.modal{background:var(--bg-primary);border-radius:18px;padding:26px;width:100%;max-width:460px;border:0.5px solid var(--border);box-shadow:var(--shadow-xl);animation:modalIn .2s ease;max-height:90vh;overflow-y:auto}
@keyframes modalIn{from{opacity:0;transform:scale(.96) translateY(-10px)}to{opacity:1;transform:none}}
.modal h3{font-size:17px;font-weight:800;margin-bottom:18px;color:var(--text-primary);letter-spacing:-.3px;display:flex;align-items:center;gap:8px}
.modal h3 i{color:var(--acc)}
.ma{display:flex;justify-content:flex-end;gap:8px;margin-top:18px;padding-top:14px;border-top:0.5px solid var(--border)}
.section-divider{font-size:10px;font-weight:800;letter-spacing:.8px;text-transform:uppercase;color:var(--text-tertiary);margin:14px 0 10px;padding:0 0 6px;border-bottom:0.5px solid var(--border)}

/* ─── PRODUCT DETAIL MODAL ─── */
.prod-detail-modal{max-width:480px}
.pdm-hero{background:var(--bg-secondary);border-radius:14px;height:120px;display:flex;align-items:center;justify-content:center;font-size:64px;margin-bottom:16px;position:relative;overflow:hidden}
.pdm-hero img{width:100%;height:100%;object-fit:cover;border-radius:14px}
.pdm-badge{position:absolute;top:10px;right:10px}
.pdm-name{font-size:18px;font-weight:800;margin-bottom:4px;letter-spacing:-.3px}
.pdm-cat{font-size:12px;color:var(--text-secondary);margin-bottom:12px;font-weight:500}
.pdm-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:14px}
.pdm-stat{background:var(--bg-secondary);border-radius:10px;padding:10px;text-align:center}
.pdm-stat-val{font-size:16px;font-weight:800;color:var(--text-primary);letter-spacing:-.3px}
.pdm-stat-lbl{font-size:10px;color:var(--text-secondary);margin-top:2px;font-weight:600}
.stock-bar{height:6px;background:var(--border);border-radius:3px;overflow:hidden;margin-top:4px}
.stock-bar-fill{height:100%;border-radius:3px;transition:width .6s ease}

/* ─── NOTIFICATION PANEL ─── */
.notif-panel{position:fixed;top:54px;right:20px;width:310px;background:var(--bg-primary);border:0.5px solid var(--border);border-radius:16px;box-shadow:var(--shadow-xl);z-index:200;display:none;animation:modalIn .15s ease}
.notif-panel.on{display:block}
.np-hd{padding:12px 16px;border-bottom:0.5px solid var(--border);font-weight:800;font-size:13px;display:flex;justify-content:space-between;align-items:center;letter-spacing:-.2px}
.notif-item{padding:10px 16px;border-bottom:0.5px solid var(--border);transition:background .15s;cursor:pointer}
.notif-item:last-child{border-bottom:none}
.notif-item:hover{background:var(--bg-secondary)}
.notif-item.unread{border-left:3px solid var(--acc)}
.ni-title{font-size:12px;font-weight:700;color:var(--text-primary);margin-bottom:2px}
.ni-body{font-size:11px;color:var(--text-secondary)}
.ni-time{font-size:10px;color:var(--text-tertiary);margin-top:2px}

/* ─── BARCODE SCANNER ─── */
.scan-wrap{background:var(--bg-secondary);border:2px dashed var(--border-md);border-radius:12px;padding:14px;text-align:center;cursor:pointer;transition:all .15s;margin-bottom:10px}
.scan-wrap:hover{border-color:var(--acc);background:var(--acc-l)}
.scan-wrap i{font-size:26px;color:var(--text-tertiary);display:block;margin-bottom:5px;transition:color .15s}
.scan-wrap:hover i{color:var(--acc)}
.scanning{animation:scanPulse .8s ease infinite}
@keyframes scanPulse{0%,100%{opacity:1}50%{opacity:.3}}

/* ─── QR CODE ─── */
.qr-wrap{display:flex;flex-direction:column;align-items:center;gap:6px;padding:12px;background:var(--bg-secondary);border-radius:12px;margin:10px 0}
.qr-wrap canvas, .qr-wrap img{border-radius:6px;border:3px solid white}
.qr-label{font-size:10px;color:var(--text-secondary);font-weight:600;text-align:center}

/* ─── QUICK SALE FAB ─── */
.quick-sale-fab{position:fixed;bottom:24px;right:24px;width:54px;height:54px;border-radius:50%;background:linear-gradient(135deg,#E8700A,#B85508);border:none;cursor:pointer;display:none;align-items:center;justify-content:center;color:#fff;font-size:22px;box-shadow:0 4px 18px rgba(232,112,10,.5);transition:all .2s;z-index:50}
.quick-sale-fab:hover{transform:scale(1.1);box-shadow:0 6px 24px rgba(232,112,10,.6)}
.quick-sale-fab.visible{display:flex}

/* ─── TOAST ─── */
.toast-wrap{position:fixed;bottom:20px;right:20px;z-index:300;display:flex;flex-direction:column;gap:8px;pointer-events:none}
.toast{background:var(--bg-primary);border:0.5px solid var(--border);border-radius:10px;padding:10px 14px;font-size:12px;box-shadow:var(--shadow-xl);display:flex;align-items:center;gap:8px;pointer-events:auto;animation:toastIn .25s ease;color:var(--text-primary);max-width:300px;font-weight:600}
.toast.success{border-left:3px solid var(--success)}
.toast.error{border-left:3px solid var(--danger)}
.toast.info{border-left:3px solid #378ADD}
@keyframes toastIn{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:none}}

/* ─── RECEIPT ─── */
.receipt-modal{max-width:360px;text-align:center}
.receipt-icon{width:56px;height:56px;background:var(--success-bg);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 12px}
.receipt-icon i{font-size:26px;color:var(--success-text)}
.receipt-detail{background:var(--bg-secondary);border-radius:10px;padding:14px;text-align:left;font-size:12px;margin:10px 0;color:var(--text-primary);font-family:'JetBrains Mono',monospace}
.receipt-row{display:flex;justify-content:space-between;padding:2px 0}

/* ─── LOW STOCK ─── */
.alert-bar{background:var(--warn-bg);border:1px solid #e8c070;border-radius:10px;padding:10px 16px;margin-bottom:14px;display:flex;align-items:center;gap:8px;font-size:12px;color:var(--warn-text);font-weight:600}
[data-theme="dark"] .alert-bar{border-color:#5a3d0a}

/* ─── DISCOUNT ─── */
.disc-row{display:flex;align-items:center;gap:6px;margin-bottom:6px}
.dt{padding:3px 8px;border:1.5px solid var(--border-md);border-radius:5px;font-size:11px;cursor:pointer;background:transparent;color:var(--text-secondary);transition:all .15s;font-family:inherit;font-weight:700}
.dt.on{background:var(--acc);color:#fff;border-color:var(--acc)}

/* ─── SEARCH HIGHLIGHT ─── */
mark{background:rgba(232,112,10,.2);color:inherit;border-radius:2px;padding:0 2px}

/* ─── SCROLLBAR ─── */
::-webkit-scrollbar{width:5px;height:5px}
::-webkit-scrollbar-track{background:transparent}
::-webkit-scrollbar-thumb{background:var(--border-md);border-radius:99px}
::-webkit-scrollbar-thumb:hover{background:var(--text-tertiary)}

/* ─── PAGE HEADER ─── */
.pg-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:14px}
.pg-header-left h2{font-size:17px;font-weight:800;letter-spacing:-.4px}
.pg-header-left p{font-size:11.5px;color:var(--text-secondary);margin-top:2px;font-weight:500}

/* ─── IMAGE UPLOAD MODAL ─── */
.img-upload-zone{border:2px dashed var(--border-md);border-radius:12px;padding:20px;text-align:center;cursor:pointer;transition:all .15s;background:var(--bg-secondary)}
.img-upload-zone:hover{border-color:var(--acc);background:var(--acc-l)}
.img-upload-zone i{font-size:28px;color:var(--text-tertiary);display:block;margin-bottom:6px}
.img-upload-zone.dragging{border-color:var(--acc);background:var(--acc-l)}
.img-preview-thumb{width:100%;height:120px;object-fit:contain;border-radius:10px;border:1.5px solid var(--border);margin-top:10px;display:none}

/* ─── PRINT STYLES ─── */
@media print {
  * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
  
  body > *:not(#print-area) { display: none !important; }
  
  #print-area {
    display: block !important;
    width: 80mm;
    margin: 0 auto;
    font-family: 'JetBrains Mono', monospace;
    font-size: 11px;
    color: #000;
    background: #fff;
  }

  #print-area .pr-title {
    text-align: center;
    font-size: 14px;
    font-weight: 800;
    letter-spacing: 2px;
    margin-bottom: 2px;
  }

  #print-area .pr-sub {
    text-align: center;
    font-size: 10px;
    margin-bottom: 6px;
    color: #555;
  }

  #print-area .pr-divider {
    border-top: 1px dashed #000;
    margin: 6px 0;
  }

  #print-area .pr-row {
    display: flex;
    justify-content: space-between;
    padding: 2px 0;
    font-size: 11px;
  }

  #print-area .pr-row.big {
    font-weight: 800;
    font-size: 13px;
    border-top: 1px dashed #000;
    margin-top: 4px;
    padding-top: 4px;
  }

  #print-area .pr-footer {
    text-align: center;
    font-size: 10px;
    color: #555;
    margin-top: 8px;
  }

  #print-area .pr-barcode {
    text-align: center;
    font-size: 9px;
    letter-spacing: 3px;
    margin-top: 6px;
    font-weight: 800;
  }
}
/* ─── MOBILE RESPONSIVE ─── */
.hamburger { display: none; }
.sb-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 499; backdrop-filter: blur(2px); }
.sb-overlay.on { display: block; }

@media (max-width: 768px) {
  :root { --sw: 220px; }

  /* Shell */
  .shell { overflow: hidden; }

  /* Sidebar hidden off screen */
  .sb {
    position: fixed !important;
    left: 0;
    top: 0;
    bottom: 0;
    z-index: 500;
    transform: translateX(-100%);
    transition: transform .25s ease;
    box-shadow: var(--shadow-xl);
  }
  .sb.mobile-open { transform: translateX(0) !important; }

  /* Main takes full width */
  .main { width: 100% !important; flex: 1; }

  /* Topbar */
  .topbar { padding: 10px 14px; }
  .tb-title { font-size: 13px; }

  /* Hamburger visible */
  .hamburger {
    display: flex !important;
    width: 34px;
    height: 34px;
    border: 1px solid var(--border);
    border-radius: 9px;
    background: transparent;
    cursor: pointer;
    align-items: center;
    justify-content: center;
    color: var(--text-secondary);
    flex-shrink: 0;
  }

  /* Content */
  .content { padding: 12px; }

  /* Stats — 2 columns */
  .sc { grid-template-columns: repeat(2,1fr) !important; gap: 8px !important; }

  /* Charts — single column */
  .row2 { grid-template-columns: 1fr !important; }

  /* POS layout */
  .pos-wrap { grid-template-columns: 1fr !important; }
  #pos-grid { grid-template-columns: repeat(2,1fr) !important; gap: 6px !important; }

  /* Cart on mobile */
  .cp {
    position: fixed !important;
    bottom: 0;
    left: 0;
    right: 0;
    height: auto !important;
    max-height: 65vh;
    border-radius: 16px 16px 0 0 !important;
    z-index: 400;
    transform: translateY(calc(100% - 52px));
    transition: transform .3s ease;
    box-shadow: 0 -4px 24px rgba(0,0,0,0.2);
  }
  .cp.cart-open { transform: translateY(0) !important; }
  .cp-hd { cursor: pointer; }

  /* Cards */
  #staff-cards { grid-template-columns: 1fr !important; }
  #cust-cards { grid-template-columns: 1fr !important; }
  #att-staff-btns { grid-template-columns: repeat(2,1fr) !important; }

  /* Forms */
  .fr { grid-template-columns: 1fr !important; }
  .fr3 { grid-template-columns: 1fr 1fr !important; }

  /* Modals slide up from bottom */
  .modalbg { align-items: flex-end !important; padding: 0 !important; }
  .modal {
    width: 100% !important;
    max-width: 100% !important;
    border-radius: 16px 16px 0 0 !important;
    max-height: 90vh;
    overflow-y: auto;
  }

  /* Tables */
  .tbl { font-size: 11px; }
  .tbl th, .tbl td { padding: 6px 8px; white-space: nowrap; }

  /* Page header */
  .pg-header { flex-direction: column; gap: 8px; }
  .pg-header > div:last-child { width: 100%; flex-wrap: wrap; }

  /* Reports */
  .rpt-tabs { flex-wrap: wrap; }
  .rt { font-size: 11px; padding: 6px; }

  /* Dashboard tabs */
  #period-today, #period-week, #period-month, #period-all {
    font-size: 10px;
    padding: 5px 4px;
  }

  /* FAB */
  .quick-sale-fab { bottom: 70px !important; right: 16px !important; }

  /* Stat values */
  .sk-val { font-size: 17px !important; }
  .sk { padding: 12px !important; }
}

@media (max-width: 480px) {
  .sc { grid-template-columns: 1fr 1fr !important; }
  .fr3 { grid-template-columns: 1fr !important; }
  #pos-grid { grid-template-columns: repeat(2,1fr) !important; }
}
</style>
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

<script>
// ─── DATA ───
const EM={'Beverages':'🥤','Snacks':'🍪','Noodles & Pasta':'🍜','Canned Goods':'🥫','Condiments':'🧂','Personal Care':'🧴','Rice & Grains':'🌾','Others':'📦'};
const fmt=n=>'₱'+Number(n).toLocaleString('en-PH',{minimumFractionDigits:2,maximumFractionDigits:2});
const fmts=n=>'₱'+Number(n).toLocaleString('en-PH',{minimumFractionDigits:0,maximumFractionDigits:0});


let currentUser=null;
let prods   = [];
let staff   = [];
let txns    = [];
let credits = [];
let cart={}, payMethod='Cash', editProdId=null, editStaffId=null, discType='pct';
let selectedUtang=new Set();
let restockId=null;
let notifications=[];
let confirmCallback=null;
let pendingProdImg='';
let uploadTargetProdId=null;

// ─── PROD IMAGE HANDLING ───
function triggerTileImageUpload(prodId, event){
  event.stopPropagation();
  uploadTargetProdId=prodId;
  document.getElementById('prod-img-input').click();
}

function handleProdImageUpload(input){
  const file=input.files[0];if(!file)return;
  const reader=new FileReader();
  reader.onload=e=>{
    const p=prods.find(x=>x.id===uploadTargetProdId);
    if(p){
      p.img=e.target.result;
      toast(p.em+' Photo added for '+p.name+'!','success');
      renderPOS();
    }
  };
  reader.readAsDataURL(file);
  input.value='';
}

function handleProdFormImg(input){
  const file=input.files[0];if(!file)return;
  const reader=new FileReader();
  reader.onload=e=>{
    pendingProdImg=e.target.result;
    const preview=document.getElementById('pf-img-preview');
    preview.src=e.target.result;
    preview.style.display='block';
    document.getElementById('prod-img-zone').style.borderColor='var(--success)';
  };
  reader.readAsDataURL(file);
}

function handleProdImgDrop(event){
  event.preventDefault();
  document.getElementById('prod-img-zone').classList.remove('dragging');
  const file=event.dataTransfer.files[0];
  if(!file||!file.type.startsWith('image/'))return;
  const input=document.getElementById('pf-img-input');
  const dt=new DataTransfer();dt.items.add(file);
  input.files=dt.files;
  handleProdFormImg(input);
}

// ─── THEME ───
let isDark = localStorage.getItem('theme') === 'dark';

function applyTheme(){
  document.documentElement.setAttribute('data-theme', isDark ? 'dark' : 'light');
  const ic = isDark ? 'ti-sun' : 'ti-moon';
  ['theme-icon','login-theme-icon'].forEach(id => {
    const el = document.getElementById(id);
    if(el) el.className = 'ti ' + ic;
  });
}

function toggleTheme(){
  isDark = !isDark;
  localStorage.setItem('theme', isDark ? 'dark' : 'light');
  applyTheme();
  if(document.getElementById('pg-dashboard').classList.contains('on')) renderDash();
  if(document.getElementById('pg-reports').classList.contains('on')) renderReports(true);
}

// Apply saved theme immediately on page load
applyTheme();

// ─── TOAST ───
function toast(msg,type='success'){
  const w=document.getElementById('toast-wrap');
  const t=document.createElement('div');
  t.className='toast '+type;
  const icon={success:'ti-check-circle',error:'ti-alert-circle',info:'ti-info-circle'}[type]||'ti-info-circle';
  const color={success:'var(--success-text)',error:'var(--danger)',info:'var(--info-text)'}[type];
  t.innerHTML=`<i class="ti ${icon}" style="color:${color};flex-shrink:0;font-size:16px"></i><span>${msg}</span>`;
  w.appendChild(t);
  setTimeout(()=>{t.style.transition='opacity .3s,transform .3s';t.style.opacity='0';t.style.transform='translateY(8px)';},2800);
  setTimeout(()=>t.remove(),3200);
}

// ─── NOTIFICATIONS ───
function addNotif(title,body){
  notifications.unshift({id:Date.now(),title,body,time:new Date().toLocaleTimeString('en-PH',{hour:'2-digit',minute:'2-digit'}),unread:true});
  renderNotifs();
}
function renderNotifs(){
  const unread=notifications.filter(n=>n.unread).length;
  const badge=document.getElementById('notif-badge');
  if(badge)badge.style.display=unread>0?'block':'none';
  const dot=document.getElementById('inv-dot');
  if(dot)dot.style.display=prods.some(p=>p.stock<=p.reorder)?'block':'none';
  const container=document.getElementById('notif-items');
  if(!container)return;
  if(!notifications.length){container.innerHTML='<div class="ec" style="padding:16px"><i class="ti ti-bell-off"></i>No notifications</div>';return;}
  container.innerHTML=notifications.slice(0,8).map(n=>`<div class="notif-item ${n.unread?'unread':''}" onclick="markRead(${n.id})">
    <div class="ni-title">${n.title}</div>
    <div class="ni-body">${n.body}</div>
    <div class="ni-time">${n.time}</div>
  </div>`).join('');
}
function markRead(id){const n=notifications.find(x=>x.id===id);if(n)n.unread=false;renderNotifs();}
function clearNotifs(){notifications=[];renderNotifs();toggleNotifPanel();}
function toggleNotifPanel(){
  const p=document.getElementById('notif-panel');
  const on=p.classList.toggle('on');
  if(on){notifications.forEach(n=>n.unread=false);renderNotifs();}
}
document.addEventListener('click',e=>{
  const p=document.getElementById('notif-panel');
  const btn=document.getElementById('notif-trigger');
  if(p&&btn&&!p.contains(e.target)&&!btn.contains(e.target))p.classList.remove('on');
});

// ─── AUTH ───
async function checkStaffUsername(){
  const u=document.getElementById('sf-username').value.trim();
  const msg=document.getElementById('sf-user-msg');
  if(!u){
    msg.textContent='No username = no system login access.';
    msg.style.color='var(--text-tertiary)';
    return;
  }

  // Check against database
  const res = await fetch('api/auth.php?action=check_username&username=' + encodeURIComponent(u));
  const data = await res.json();

  msg.textContent = data.taken ? '⚠️ Username already taken' : '✅ Username available';
  msg.style.color = data.taken ? 'var(--danger)' : 'var(--success-text)';
}
async function loadProducts(){
  const res  = await fetch('api/products.php?action=list');
  const rows = await res.json();
  prods = rows.map(p => ({
    id:          'p' + p.id,
    _dbId:       parseInt(p.id),
    name:        p.name,
    cat:         p.category,
    em:          p.emoji,
    cost:        parseFloat(p.cost),
    price:       parseFloat(p.price),
    stock:       parseInt(p.stock),
    reorder:     parseInt(p.reorder_point),
    img:         p.img || '',
    sku:         p.sku || '',
    barcode:     p.barcode || '',
    expiry:      p.expiry_date || '',
    expiryAlert: parseInt(p.expiry_alert_days) || 30
  }));
}

async function loadTransactions() {
  const res = await fetch('api/transactions.php?action=list');
  const rows = await res.json();
  txns = rows.map(t => ({
    id:       t.txn_code,
    items:    (t.items || []).map(i => ({
                name:  i.product_name,
                qty:   parseInt(i.quantity),
                price: parseFloat(i.unit_price),
                em:    ''
              })),
    total:    parseFloat(t.total),
    pay:      t.payment_method,
    customer: t.customer_name || null,
    staff:    t.staff_name,
    date:     t.txn_date,
    time:     t.txn_time
  }));
}
async function doLogin(){
  const u = document.getElementById('l-user').value.trim();
  const p = document.getElementById('l-pass').value;
  const errEl = document.getElementById('l-err');

  const res = await fetch('api/auth.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({action: 'login', username: u, password: p})
  });
  const data = await res.json();

  if (!data.success) {
    errEl.style.display = 'flex';
    document.getElementById('l-err-msg').textContent = data.message;
    document.getElementById('l-user').style.borderColor = 'var(--danger)';
    document.getElementById('l-pass').style.borderColor = 'var(--danger)';
    return;
  }

  errEl.style.display = 'none';
  ['l-user','l-pass'].forEach(id => document.getElementById(id).style.borderColor = '');
  currentUser = data.user;
  document.getElementById('sb-av-init').textContent  = data.user.initials;
  document.getElementById('sb-uname').textContent    = data.user.name;
  document.getElementById('sb-urole').textContent    = data.user.role;
  document.getElementById('login-screen').classList.remove('on');

  setTimeout(async () => {
    document.getElementById('app').style.display = 'flex';
    document.getElementById('tb-date').textContent = new Date().toLocaleDateString('en-PH',{weekday:'short',year:'numeric',month:'short',day:'numeric'});
    await loadProducts();   // ← fetch from database
    await loadTransactions();
    renderDash();
    const lowStock = prods.filter(p => p.stock > 0 && p.stock < p.reorder);
    const outStock = prods.filter(p => p.stock === 0);
    if (outStock.length) addNotif('Out of stock alert', `${outStock.length} product(s) need restocking immediately`);
    if (lowStock.length) addNotif('Low stock warning', `${lowStock.length} product(s) running low`);
    renderNotifs();
    if(data.user.role !== 'Administrator'){
      document.querySelectorAll('.admin-only').forEach(el => el.style.display = 'none');
    } else {
      document.querySelectorAll('.admin-only').forEach(el => el.style.display = '');
    }       
    toast('Welcome back, ' + data.user.name.split(' ')[0] + '! 👋');
  }, 100);
}

async function doLogout(){
  await fetch('api/auth.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({action: 'logout'})
  });

  currentUser = null;
  cart = {};
  ['l-user','l-pass'].forEach(id => {
    document.getElementById(id).value = '';
    document.getElementById(id).style.borderColor = '';
  });
  document.getElementById('app').style.display = 'none';
  document.getElementById('login-screen').classList.add('on');
  notifications = [];
  toast('Signed out successfully.', 'info');
}

function togglePassVis(inputId,iconId){
  const f=document.getElementById(inputId);
  const on=f.type==='password';
  f.type=on?'text':'password';
  document.getElementById(iconId).className='ti '+(on?'ti-eye-off':'ti-eye');
}

// ─── NAV ───
function nav(name,el){
  document.querySelectorAll('.pg').forEach(p=>p.classList.remove('on'));
  document.querySelectorAll('.ni').forEach(n=>n.classList.remove('active'));
  document.getElementById('pg-'+name).classList.add('on');
  if(el)el.classList.add('active');
  const T={dashboard:'Dashboard',inventory:'Inventory Management',pos:'Point of Sale',staff:'Staff Management',attendance:'Attendance & Payroll',payments:'Payment Management',expenses:'Expense Tracking',customers:'Customer Management',reports:'Reports & Analytics',transactions:'Transaction Monitoring'};
  document.getElementById('pg-title').textContent=T[name]||name;
  const renders={dashboard:renderDash,inventory:renderInv,pos:renderPOS,staff:renderStaff,attendance:renderAttendance,payments:renderPayments,expenses:renderExpenses,customers:renderCustomers,reports:()=>renderReports(false),transactions:renderTxns};
if(renders[name])(async()=>await renders[name]())();
  const fab=document.getElementById('quick-sale-fab');
  if(fab)fab.classList.toggle('visible',name!=='pos');
}

// ─── DASHBOARD ───
let dashPeriod = 'today';
let dashSummary = null;

function setDashPeriod(period, el){
  dashPeriod = period;
  document.querySelectorAll('#period-today,#period-week,#period-month,#period-all')
    .forEach(b => b.classList.remove('on'));
  el.classList.add('on');
  updateDashStats();
}

function updateDashStats(){
  if(!dashSummary) return;
  const s = dashSummary;
  const periodMap = {today:'Today\'s Sales', week:'This Week\'s Sales', month:'This Month\'s Sales', all:'Total Sales'};
  const data = s[dashPeriod];

  document.getElementById('d-sales-lbl').textContent = periodMap[dashPeriod];
  document.getElementById('d-sales').textContent     = fmts(data.total);
  document.getElementById('d-sales-txn').textContent = data.count + ' transaction' + (data.count != 1 ? 's' : '');
  document.getElementById('d-txns').textContent      = s.all.count;

  const profit = s.all.total - s.cost.total_cost;
  document.getElementById('d-profit').textContent = fmts(profit);
  document.getElementById('d-prods').textContent  = prods.length;

  const stockMsg = s.out_stock > 0
    ? `⚠️ ${s.out_stock} out of stock`
    : s.low_stock > 0
    ? `⚠️ ${s.low_stock} low stock`
    : 'All stocked up ✅';
  document.getElementById('d-stock-sub').textContent = stockMsg;

  // Payment breakdown
  const payMap = {};
  (s.payments || []).forEach(p => payMap[p.payment_method] = parseFloat(p.total));
  document.getElementById('d-cash').textContent   = fmts(payMap['Cash']  || 0);
  document.getElementById('d-gcash').textContent  = fmts(payMap['GCash'] || 0);
  document.getElementById('d-maya').textContent   = fmts(payMap['Maya']  || 0);
  document.getElementById('d-utang').textContent  = fmts(payMap['Utang'] || 0);

  // Low stock alert bar
  const alertEl = document.getElementById('low-stock-alert');
  if(s.low_stock || s.out_stock){
    alertEl.style.display = 'flex';
    document.getElementById('low-stock-msg').textContent =
      `${s.out_stock} out of stock · ${s.low_stock} low stock. Attention needed!`;
  } else {
    alertEl.style.display = 'none';
  }
}
function drawCharts(){
  const gc = isDark?'rgba(255,255,255,0.06)':'rgba(0,0,0,0.05)';
  const tc = isDark?'#777':'#aaa';

  // Build last 7 days labels
  const last7 = [];
  for(let i = 6; i >= 0; i--){
    const d = new Date();
    d.setDate(d.getDate() - i);
    last7.push(d.toISOString().split('T')[0]);
  }
  const dayLabels = last7.map(d => new Date(d).toLocaleDateString('en-PH',{weekday:'short'}));

  // Map chart data from API
  const chartMap = {};
  if(dashSummary && dashSummary.chart){
    dashSummary.chart.forEach(r => chartMap[r.txn_date] = parseFloat(r.total));
  }
  const chartVals = last7.map(d => chartMap[d] || 0);

  const c1 = document.getElementById('ch-sales');
  if(c1._ch) c1._ch.destroy();
  c1._ch = new Chart(c1,{type:'line',data:{labels:dayLabels,datasets:[{data:chartVals,borderColor:'#E8700A',backgroundColor:'rgba(232,112,10,0.08)',tension:.4,pointRadius:4,pointBackgroundColor:'#E8700A',borderWidth:2.5,fill:true}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{x:{grid:{color:gc},ticks:{color:tc,font:{size:11,family:'Plus Jakarta Sans'}}},y:{grid:{color:gc},ticks:{color:tc,font:{size:11,family:'Plus Jakarta Sans'},callback:v=>'₱'+v}}}}});

  // Category chart
  const cats = [...new Set(prods.map(p => p.cat))];
  const catv = cats.map(c =>
    txns.reduce((a,t) => a + t.items
      .filter(it => prods.find(p => p.name===it.name && p.cat===c))
      .reduce((b,it) => b + it.price * it.qty, 0), 0) || 0
  );

  const c2 = document.getElementById('ch-cat');
  if(c2._ch) c2._ch.destroy();
  c2._ch = new Chart(c2,{type:'doughnut',data:{labels:cats,datasets:[{data:catv,backgroundColor:['#E8700A','#378ADD','#1D9E75','#7F77DD','#D4537E','#639922','#EF9F27','#888780'],borderWidth:0,hoverOffset:4}]},options:{responsive:true,maintainAspectRatio:false,cutout:'60%',plugins:{legend:{position:'right',labels:{font:{size:11,family:'Plus Jakarta Sans'},color:tc,boxWidth:10,padding:6}}}}});
}

async function renderDash(){
  // Fetch summary from API
  const res = await fetch('api/dashboard.php?action=summary');
  dashSummary = await res.json();

  updateDashStats();
  await loadTransactions();
  await loadTargets();

  // Recent transactions table
  document.getElementById('d-txn-tbl').innerHTML=`<thead><tr><th>ID</th><th>Items</th><th>Total</th><th>Method</th><th>Staff</th><th>Date</th><th>Time</th></tr></thead><tbody>${txns.slice(0,5).map(t=>`<tr style="cursor:pointer" onclick="showTxnDetail('${t.id}')"><td style="color:var(--acc);font-weight:700;font-family:'JetBrains Mono',monospace">${t.id}</td><td style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">${t.items.map(i=>i.qty+'× '+i.name).join(', ')}</td><td style="font-weight:700">${fmt(t.total)}</td><td><span class="bdg ${t.pay==='Cash'?'bg':t.pay==='Utang'?'bo':'bb'}">${t.pay}</span></td><td>${t.staff}</td><td>${t.date}</td><td>${t.time}</td></tr>`).join('')}</tbody>`;

  drawCharts();
}
// ─── INVENTORY ───
async function loadRestockLogs(){
  const res = await fetch('api/products.php?action=restock_logs');
  const logs = await res.json();
  const tbody = document.getElementById('restock-log-body');
  if(!tbody) return;

  if(!logs.length){
    tbody.innerHTML = `<tr><td colspan="6">
      <div class="ec"><i class="ti ti-history"></i>No restock history yet</div>
    </td></tr>`;
    return;
  }

  tbody.innerHTML = logs.map(l => `
    <tr>
      <td style="font-weight:600">${l.product_name}</td>
      <td style="color:var(--success-text);font-weight:800">+${l.quantity_added}</td>
      <td style="color:var(--text-secondary)">${l.previous_stock}</td>
      <td style="font-weight:700">${l.new_stock}</td>
      <td>${l.restocked_by}</td>
      <td style="color:var(--text-secondary);font-size:11px">${new Date(l.created_at).toLocaleString('en-PH')}</td>
    </tr>
  `).join('');
}
async function checkExpiryWarnings(){
  const res1 = await fetch('api/products.php?action=expiring');
  const res2 = await fetch('api/products.php?action=expired');
  const expiring = await res1.json();
  const expired  = await res2.json();

  // Remove old expiry alert if exists
  const old = document.getElementById('expiry-alert-bar');
  if(old) old.remove();

  if(!expiring.length && !expired.length) return;

  const bar = document.createElement('div');
  bar.id = 'expiry-alert-bar';

  if(expired.length){
    bar.className = 'alert-bar';
    bar.style.background = 'var(--danger-bg)';
    bar.style.borderColor = '#e2a0a0';
    bar.style.color = 'var(--danger)';
    bar.innerHTML = `<i class="ti ti-alert-circle"></i>
      <span>⚠️ ${expired.length} product(s) have <b>expired</b>:
      ${expired.slice(0,3).map(p => p.name).join(', ')}
      </span>`;
  } else {
    bar.className = 'alert-bar';
    bar.innerHTML = `<i class="ti ti-clock"></i>
      <span>🕐 ${expiring.length} product(s) expiring soon:
      ${expiring.slice(0,3).map(p => p.name + ' (' + p.expiry_date + ')').join(', ')}
      </span>`;
  }

  const pgHeader = document.querySelector('#pg-inventory .pg-header');
  pgHeader.insertAdjacentElement('afterend', bar);

  // Also add notification
  if(expired.length) addNotif('Expired products!', `${expired.length} product(s) have expired`);
  else if(expiring.length) addNotif('Expiry warning', `${expiring.length} product(s) expiring soon`);
}
async function renderInv(){
  await loadRestockLogs();
  await checkExpiryWarnings();
  const q=(document.getElementById('inv-srch').value||'').toLowerCase();
  const cat=document.getElementById('inv-cat');
  const flt=document.getElementById('inv-filter').value;
  const cats=[...new Set(prods.map(p=>p.cat))];
  if(cat.options.length<=1)cats.forEach(c=>{const o=document.createElement('option');o.value=c;o.textContent=c;cat.appendChild(o);});
  const cf=cat.value;
  const low=prods.filter(p=>p.stock>0&&p.stock<p.reorder).length;
  const out=prods.filter(p=>p.stock===0).length;
  document.getElementById('inv-stat').textContent=`${prods.length} products · ${low} low stock · ${out} out of stock`;
  document.getElementById('inv-dot').style.display=(low||out)?'block':'none';
  const fp=prods.filter(p=>{
    const mq=!q||p.name.toLowerCase().includes(q)||p.cat.toLowerCase().includes(q);
    const mc=!cf||p.cat===cf;
    const mf=!flt||(flt==='low'&&p.stock>0&&p.stock<p.reorder)||(flt==='out'&&p.stock===0);
    return mq&&mc&&mf;
  });
 const hl=(text,q)=>q?text.replace(new RegExp('('+q.replace(/[.*+?^${}()|[\]\\]/g,'\\$&')+')','gi'),'<mark>$1</mark>'):text;
const today=new Date().toISOString().split('T')[0];
document.getElementById('inv-tbl').innerHTML=`<thead><tr><th>#</th><th>Product</th><th>Category</th><th>Cost</th><th>Price</th><th>Markup</th><th>Stock</th><th>Re-order</th><th>Expiry</th><th>Status</th><th>Actions</th></tr></thead><tbody>${fp.length?fp.map((p,i)=>{
    const isExpired=p.expiry&&p.expiry<today;
    const isExpiring=p.expiry&&!isExpired&&new Date(p.expiry)<=new Date(Date.now()+p.expiryAlert*86400000);
    const st=p.stock===0?'<span class="bdg br">Out of stock</span>':p.stock<p.reorder?'<span class="bdg bo">Low stock</span>':'<span class="bdg bg">In stock</span>';
    const expiryBadge=isExpired?'<span class="bdg br" style="margin-left:4px">Expired</span>':isExpiring?'<span class="bdg bo" style="margin-left:4px">Expiring soon</span>':'';
    const mkp=p.cost>0?Math.round((p.price-p.cost)/p.cost*100)+'%':'—';
    const bg=p.stock===0?'background:var(--danger-bg)':p.stock<p.reorder?'background:var(--warn-bg)':'';
    const thumb=p.img?`<img src="${p.img}" style="width:26px;height:26px;object-fit:cover;border-radius:5px;margin-right:5px;vertical-align:middle;border:1px solid var(--border)">`:p.em+' ';
    const expColor=isExpired?'var(--danger)':isExpiring?'var(--warn-text)':'var(--text-secondary)';
    return`<tr style="${bg}cursor:pointer" onclick="showProdDetail('${p.id}')"><td style="color:var(--text-tertiary);font-size:11px;font-family:'JetBrains Mono',monospace">${i+1}</td><td><span style="display:inline-flex;align-items:center">${thumb}<span style="font-weight:600">${hl(p.name,q)}</span></span></td><td>${p.cat}</td><td>${fmt(p.cost)}</td><td style="font-weight:700">${fmt(p.price)}</td><td>${mkp}</td><td style="font-weight:700;color:${p.stock===0?'var(--danger)':p.stock<p.reorder?'var(--warn-text)':'var(--text-primary)'}">${p.stock}</td><td>${p.reorder}</td><td style="font-size:11px;color:${expColor}">${p.expiry||'—'}</td><td>${st}${expiryBadge}</td><td onclick="event.stopPropagation()"><div style="display:flex;gap:4px"><button class="btn bts btg" onclick="openRestock('${p.id}')" title="Restock"><i class="ti ti-plus"></i></button><button class="btn bts" onclick="editProd('${p.id}')" title="Edit"><i class="ti ti-edit"></i></button><button class="btn bts btd" onclick="confirmDel('${p.id}')" title="Delete"><i class="ti ti-trash"></i></button></div></td></tr>`;
  }).join(''):`<tr><td colspan="11"><div class="ec"><i class="ti ti-search"></i>No products match your filters</div></td></tr>`}</tbody>`;
}
// ─── PRODUCT DETAIL POPUP ───
function showProdDetail(id){
  const p=prods.find(x=>x.id===id);if(!p)return;
  const hero=document.getElementById('pdm-hero');
  if(p.img){
    hero.innerHTML=`<img src="${p.img}" style="width:100%;height:100%;object-fit:cover;border-radius:14px"><div class="pdm-badge" id="pdm-badge"></div>`;
  }else{
    hero.innerHTML=`<span id="pdm-em" style="font-size:64px">${p.em}</span><div class="pdm-badge" id="pdm-badge"></div>`;
  }
  document.getElementById('pdm-name').textContent=p.name;
  document.getElementById('pdm-cat').textContent=p.cat;
  document.getElementById('pdm-price').textContent=fmt(p.price);
  document.getElementById('pdm-stock').textContent=p.stock;
  const mkp=p.cost>0?Math.round((p.price-p.cost)/p.cost*100)+'%':'—';
  document.getElementById('pdm-mkp').textContent=mkp;
  const status=p.stock===0?`<span class="bdg br">Out of stock</span>`:p.stock<p.reorder?`<span class="bdg bo">Low stock</span>`:`<span class="bdg bg">In stock</span>`;
  document.getElementById('pdm-badge').innerHTML=status;
  const maxDisplay=Math.max(p.reorder*2,p.stock);
  const pct=Math.min(100,(p.stock/maxDisplay)*100);
  const barColor=p.stock===0?'var(--danger)':p.stock<p.reorder?'var(--warn-text)':'var(--success)';
  const bar=document.getElementById('pdm-stockbar');
  bar.style.background=barColor;bar.style.width='0%';
  setTimeout(()=>bar.style.width=pct+'%',50);
  document.getElementById('pdm-reorder-lbl').textContent=`Reorder at ${p.reorder}`;
  document.getElementById('pdm-add-btn').onclick=()=>{addToCartDirect(id);closeModal('modal-prod-detail');};
  document.getElementById('pdm-add-btn').disabled=p.stock===0;
  document.getElementById('pdm-add-btn').style.opacity=p.stock===0?'.5':'1';
  document.getElementById('pdm-edit-btn').onclick=()=>{closeModal('modal-prod-detail');editProd(id);};
  document.getElementById('pdm-edit-btn').style.display='';
  openModal('prod-detail');
}
function addToCartDirect(id){
  const p=prods.find(x=>x.id===id);if(!p||p.stock===0){toast('Out of stock!','error');return;}
  if((cart[id]||0)>=p.stock){toast('Not enough stock!','error');return;}
  cart[id]=(cart[id]||0)+1;
  toast(p.em+' '+p.name+' added to cart','success');
}

// ─── RESTOCK ───
function openRestock(id){
  restockId=id;
  const p=prods.find(x=>x.id===id);
  document.getElementById('restock-pname').textContent=p.em+' '+p.name;
  document.getElementById('restock-cur').value=p.stock+' units';
  document.getElementById('restock-qty').value='';
  openModal('restock');
}
async function confirmRestock(){
  const qty = parseInt(document.getElementById('restock-qty').value) || 0;
  if(qty <= 0){ toast('Enter a valid quantity.','error'); return; }

  const p = prods.find(x => x.id === restockId);
  await fetch('api/products.php?action=restock', {
    method: 'POST',
    headers: {'Content-Type':'application/json'},
    body: JSON.stringify({
      id: p._dbId,
      qty,
      restocked_by: currentUser ? currentUser.name : 'Unknown'
    })
  });

  toast(p.name + ' restocked by ' + qty + ' units ✅');
  addNotif('Restock completed', `${p.name}: +${qty} units`);
  closeModal('modal-restock');
  await loadProducts();
  renderInv();
}

// ─── CONFIRM DELETE ───
function confirmDel(id){
  confirmCallback = async () => {
    const p = prods.find(x => x.id === id);
    await fetch('api/products.php?action=delete', {
      method: 'POST',
      headers: {'Content-Type':'application/json'},
      body: JSON.stringify({ id: p._dbId })
    });
    delete cart[id];
    await loadProducts();
    renderInv();
    toast('Product deleted.','info');
    closeModal('modal-confirm');
  };
  const p = prods.find(x => x.id === id);
  document.getElementById('confirm-title').textContent = 'Delete product?';
  document.getElementById('confirm-msg').textContent = `Are you sure you want to delete "${p.name}"? This action cannot be undone.`;
  document.getElementById('confirm-ok').onclick = confirmCallback;
  openModal('confirm');
}

// ─── POS ───
function renderPOS(){
  const q=(document.getElementById('pos-srch').value||'').toLowerCase();
  const csel=document.getElementById('pos-cat');
  const cats=[...new Set(prods.map(p=>p.cat))];
  if(csel.options.length<=1)cats.forEach(c=>{const o=document.createElement('option');o.value=c;o.textContent=c;csel.appendChild(o);});
  const cf=csel.value;
  const fp=prods.filter(p=>(!q||p.name.toLowerCase().includes(q))&&(!cf||p.cat===cf));

  document.getElementById('pos-grid').innerHTML=fp.map(p=>{
    const ic=cart[p.id]||0;
    const oos=p.stock===0;
    const hasImg=!!p.img;

    // Image area: real uploaded photo OR styled placeholder with emoji
    const imgContent = hasImg
      ? `<img src="${p.img}" alt="${p.name}">`
      : `<div class="pt-placeholder">
           <span class="pt-ph-emoji">${p.em}</span>
           <div class="pt-ph-camera"><i class="ti ti-camera"></i></div>
         </div>`;

    return`<div class="pg-tile${oos?' oos':''}${hasImg?' has-img':''}" onclick="${oos?'toast(\'Out of stock!\',\'error\')':'showPosDetail(\''+p.id+'\')'}">
      ${ic?`<div class="pt-badge">${ic}</div>`:''}
      <div class="pt-img-wrap">
        ${imgContent}
        ${oos?'<div class="pt-oos-overlay"></div>':''}
      </div>
      <button class="pt-upload-btn" onclick="triggerTileImageUpload('${p.id}',event)">
        <i class="ti ti-camera" style="font-size:10px"></i>${hasImg?'Change Photo':'Upload Photo'}
      </button>
      <div class="pt-info">
        <div class="pt-nm">${p.name}</div>
        <div class="pt-pr">${fmt(p.price)}</div>
        <div class="pt-st">${p.stock} left</div>
      </div>
    </div>`;
  }).join('');
  renderCart();
}

function showPosDetail(id){
  const p=prods.find(x=>x.id===id);if(!p)return;
  const hero=document.getElementById('pdm-hero');
  if(p.img){
    hero.innerHTML=`<img src="${p.img}" style="width:100%;height:100%;object-fit:cover;border-radius:14px"><div class="pdm-badge" id="pdm-badge"></div>`;
  }else{
    hero.innerHTML=`<span id="pdm-em" style="font-size:64px">${p.em}</span><div class="pdm-badge" id="pdm-badge"></div>`;
  }
  document.getElementById('pdm-name').textContent=p.name;
  document.getElementById('pdm-cat').textContent=p.cat;
  document.getElementById('pdm-price').textContent=fmt(p.price);
  document.getElementById('pdm-stock').textContent=p.stock;
  const mkp=p.cost>0?Math.round((p.price-p.cost)/p.cost*100)+'%':'—';
  document.getElementById('pdm-mkp').textContent=mkp;
  const status=p.stock===0?`<span class="bdg br">Out</span>`:p.stock<p.reorder?`<span class="bdg bo">Low</span>`:`<span class="bdg bg">In stock</span>`;
  document.getElementById('pdm-badge').innerHTML=status;
  const bar=document.getElementById('pdm-stockbar');
  const pct=Math.min(100,(p.stock/Math.max(p.reorder*2,p.stock))*100);
  const barColor=p.stock===0?'var(--danger)':p.stock<p.reorder?'var(--warn-text)':'var(--success)';
  bar.style.background=barColor;bar.style.width='0%';
  setTimeout(()=>bar.style.width=pct+'%',50);
  document.getElementById('pdm-reorder-lbl').textContent=`Reorder at ${p.reorder}`;
  document.getElementById('pdm-add-btn').innerHTML='<i class="ti ti-shopping-cart-plus"></i>Add to cart';
  document.getElementById('pdm-add-btn').onclick=()=>{addCart(id);closeModal('modal-prod-detail');};
  document.getElementById('pdm-add-btn').disabled=p.stock===0;
  document.getElementById('pdm-add-btn').style.opacity=p.stock===0?'.5':'1';
  document.getElementById('pdm-edit-btn').style.display='none';
  openModal('prod-detail');
}
function addCart(id){
  const p=prods.find(x=>x.id===id);if(!p||p.stock===0)return;
  if((cart[id]||0)>=p.stock){toast('Not enough stock!','error');return;}
  cart[id]=(cart[id]||0)+1;renderPOS();
  toast(p.em+' Added!','success');
}
function clearCart(){if(!Object.keys(cart).filter(k=>cart[k]>0).length){toast('Cart is already empty','info');return;}cart={};renderPOS();toast('Cart cleared','info');}

function renderCart(){
  const keys=Object.keys(cart).filter(k=>cart[k]>0);
  document.getElementById('ct-cnt').textContent=keys.length+' item'+(keys.length!==1?'s':'');
  if(!keys.length){
    document.getElementById('ct-body').innerHTML=`<div class="ec"><i class="ti ti-shopping-cart"></i>Cart is empty<br><span style="font-size:11px">Click a product to add</span></div>`;
    ['ct-sub','ct-total'].forEach(id=>document.getElementById(id).textContent='₱0.00');
    document.getElementById('disc-show').textContent='−₱0.00';return;
  }
  let sub=0;
  document.getElementById('ct-body').innerHTML=keys.map(k=>{const p=prods.find(x=>x.id===k);const ln=p.price*cart[k];sub+=ln;
    const thumb=p.img
      ?`<img src="${p.img}" style="width:32px;height:32px;object-fit:cover;border-radius:7px;flex-shrink:0;border:1px solid var(--border)">`
      :`<div class="ci-em">${p.em}</div>`;
    return`<div class="ci-row">${thumb}<div class="ci-inf"><div class="ci-nm">${p.name}</div><div class="ci-pr">${fmt(p.price)} × ${cart[k]} = <strong>${fmt(ln)}</strong></div></div><div class="qc"><button class="qb" onclick="chgQty('${k}',-1)">−</button><span style="font-size:12px;min-width:20px;text-align:center;color:var(--text-primary);font-weight:700">${cart[k]}</span><button class="qb" onclick="chgQty('${k}',1)">+</button></div></div>`;
  }).join('');
  document.getElementById('ct-sub').textContent=fmt(sub);
  calcTotal();
}
function chgQty(id,d){const p=prods.find(x=>x.id===id);const nq=(cart[id]||0)+d;if(nq<=0){delete cart[id];}else if(nq<=p.stock){cart[id]=nq;}renderCart();}
function setDiscType(t){discType=t;document.getElementById('dt-pct').classList.toggle('on',t==='pct');document.getElementById('dt-fix').classList.toggle('on',t==='fix');calcTotal();}
function calcTotal(){
  const keys=Object.keys(cart).filter(k=>cart[k]>0);
  const sub=keys.reduce((a,k)=>{const p=prods.find(x=>x.id===k);return a+p.price*cart[k];},0);
  const dv=parseFloat(document.getElementById('disc-val').value)||0;
  const disc=discType==='pct'?sub*(dv/100):Math.min(dv,sub);
  document.getElementById('disc-show').textContent='−'+fmt(disc);
  document.getElementById('ct-total').textContent=fmt(Math.max(0,sub-disc));
  calcChange();
}
function calcChange(){
  const total=parseFloat((document.getElementById('ct-total').textContent||'₱0').replace(/[₱,]/g,''))||0;
  const c=parseFloat(document.getElementById('cash-in').value)||0;
  document.getElementById('chg').textContent=fmt(Math.max(0,c-total));
}
function setPay(m,el){
  payMethod=m.charAt(0).toUpperCase()+m.slice(1);
  document.querySelectorAll('.pb').forEach(b=>b.classList.remove('on'));el.classList.add('on');
  document.getElementById('utang-name-wrap').style.display=m==='utang'?'block':'none';
}

// ─── CHECKOUT + QR ───
async function checkout(){
  const keys = Object.keys(cart).filter(k => cart[k] > 0);
  if(!keys.length){ toast('Cart is empty!','error'); return; }

  const total = parseFloat((document.getElementById('ct-total').textContent||'₱0').replace(/[₱,]/g,'')) || 0;
  const sub   = keys.reduce((a,k) => { const p=prods.find(x=>x.id===k); return a+p.price*cart[k]; }, 0);
  const cash  = parseFloat(document.getElementById('cash-in').value) || 0;

  if(payMethod==='Cash' && cash<total){ toast('Cash tendered is less than total!','error'); return; }
  const uname = document.getElementById('utang-name').value.trim();
  if(payMethod==='Utang' && !uname){ toast('Enter customer name for credit.','error'); return; }

  const change = Math.max(0, cash - total);
  const disc   = sub - total;
  const items  = keys.map(k => { const p=prods.find(x=>x.id===k); return{name:p.name,em:p.em,qty:cart[k],price:p.price}; });

  const now    = new Date();
  const txnCode = 'TXN-' + String(txns.length + 1).padStart(3,'0');

  const res = await fetch('api/transactions.php?action=add', {
    method: 'POST',
    headers: {'Content-Type':'application/json'},
    body: JSON.stringify({
      txn_code:       txnCode,
      total:          total,
      payment_method: payMethod,
      customer_name:  uname || null,
      staff_name:     currentUser ? currentUser.name : 'Staff',
      txn_date:       now.toISOString().split('T')[0],
      txn_time:       now.toLocaleTimeString('en-PH',{hour:'2-digit',minute:'2-digit'}),
      items
    })
  });
  const result = await res.json();
  if(!result.success){ toast('Transaction failed: ' + result.error,'error'); return; }

  const txn = {
    id: txnCode, items, total, pay: payMethod,
    staff: currentUser ? currentUser.name : 'Staff',
    customer: uname || null,
    date: now.toISOString().split('T')[0],
    time: now.toLocaleTimeString('en-PH',{hour:'2-digit',minute:'2-digit'})
  };
  txns.unshift(txn);

  document.getElementById('rec-msg').textContent = payMethod + (payMethod==='Cash' ? ' · Change: '+fmt(change) : '') + (disc>0 ? ' · Discount: '+fmt(disc) : '');
  document.getElementById('rec-detail').innerHTML =
    `<div style="text-align:center;font-weight:800;margin-bottom:4px;letter-spacing:1.5px;font-size:13px">REINALIN RETAIL</div>`+
    `<div style="text-align:center;font-size:10px;color:var(--text-secondary);margin-bottom:8px;font-family:'JetBrains Mono',monospace">${now.toLocaleString('en-PH')}</div>`+
    `<div style="border-top:1px dashed var(--border-md);margin:6px 0"></div>`+
    `<div class="receipt-row" style="font-size:10px;color:var(--text-tertiary);font-family:'JetBrains Mono',monospace"><span>Txn ID</span><span>${txnCode}</span></div>`+
    `<div style="border-top:1px dashed var(--border-md);margin:6px 0"></div>`+
    items.map(i=>`<div class="receipt-row"><span>${i.em} ${i.name} ×${i.qty}</span><span>${fmt(i.price*i.qty)}</span></div>`).join('')+
    `<div style="border-top:1px dashed var(--border-md);margin:6px 0"></div>`+
    `<div class="receipt-row"><span>Subtotal</span><span>${fmt(sub)}</span></div>`+
    (disc>0?`<div class="receipt-row"><span>Discount</span><span style="color:var(--danger)">−${fmt(disc)}</span></div>`:'')+
    `<div class="receipt-row" style="font-weight:800;font-size:13px"><span>TOTAL</span><span>${fmt(total)}</span></div>`+
    `<div class="receipt-row"><span>Payment</span><span>${payMethod}</span></div>`+
    (payMethod==='Cash'?`<div class="receipt-row"><span>Cash</span><span>${fmt(cash)}</span></div><div class="receipt-row"><span>Change</span><span style="color:var(--success-text);font-weight:700">${fmt(change)}</span></div>`:'')+
    (payMethod==='Utang'?`<div class="receipt-row"><span>Customer</span><span>${uname}</span></div>`:'')+
    `<div style="text-align:center;font-size:10px;color:var(--text-tertiary);margin-top:8px">Cashier: ${txn.staff}</div>`+
    `<div style="text-align:center;font-size:10px;color:var(--text-tertiary);margin-top:2px">Thank you for shopping! 🙏</div>`;

  generateQR(txnCode, total, payMethod);
  cart = {};
  document.getElementById('cash-in').value = '';
  document.getElementById('disc-val').value = '';
  document.getElementById('utang-name').value = '';
  openModal('receipt');
  addNotif('New transaction', `${txnCode} — ${payMethod} ${fmt(total)}`);
  await loadProducts();
  renderPOS();
}
function generateQR(txnId,total,method){
  const container=document.getElementById('qr-code-container');
  container.innerHTML='';
  const qrData=JSON.stringify({txn:txnId,store:'Reinalin Retail',total:total,method:method,date:new Date().toISOString()});
  try{
    new QRCode(container,{text:qrData,width:120,height:120,colorDark:isDark?'#ffffff':'#1a1a18',colorLight:isDark?'#252522':'#ffffff',correctLevel:QRCode.CorrectLevel.M});
    document.getElementById('qr-label').textContent=txnId+' · Scan to verify';
  }catch(e){container.innerHTML='<div style="font-size:11px;color:var(--text-tertiary);padding:10px">QR unavailable</div>';}
}
function printReceipt(){
  const now = new Date();
  const txnId = document.getElementById('rec-detail')
    .querySelector?.('span:last-child')?.textContent || 'TXN-???';

  // Get receipt data from the modal
  const items = [];
  document.querySelectorAll('#rec-detail .receipt-row').forEach(row => {
    const spans = row.querySelectorAll('span');
    if(spans.length === 2){
      items.push({ label: spans[0].textContent, value: spans[1].textContent });
    }
  });

  // Get total and payment info
  const total    = document.getElementById('ct-total')?.textContent || '₱0.00';
  const recMsg   = document.getElementById('rec-msg').textContent;
  const recDetail = document.getElementById('rec-detail').innerHTML;

  // Build print content
  const printContent = `
    <div class="pr-title">REINALIN RETAIL</div>
    <div class="pr-sub">Management System</div>
    <div class="pr-sub">${now.toLocaleString('en-PH')}</div>
    <div class="pr-divider"></div>
    ${recDetail}
    <div class="pr-divider"></div>
    <div class="pr-footer">Thank you for shopping! 🙏</div>
    <div class="pr-footer">Please come again!</div>
    <div class="pr-barcode">|||  ${recMsg.split('·')[0].trim()}  |||</div>
    <div class="pr-footer" style="margin-top:16px">.</div>
  `;

  document.getElementById('print-area').innerHTML = printContent;

  // Small delay then print
  setTimeout(() => {
    window.print();
    setTimeout(() => {
      document.getElementById('print-area').innerHTML = '';
    }, 500);
  }, 200);

  toast('Printing receipt...', 'info');
}

// ─── BARCODE SCANNER ───
async function simulateScan(){
  const icon = document.getElementById('scan-icon');
  const zone = document.getElementById('scan-zone');
  
  // Check if there's a real barcode input
  const barcodeInput = document.getElementById('barcode-input-field');
  const barcodeVal = barcodeInput ? barcodeInput.value.trim() : '';

  icon.classList.add('scanning');
  zone.style.borderColor = 'var(--acc)';

  if(barcodeVal){
    // Real barcode lookup
    const res = await fetch('api/products.php?action=find_barcode&barcode=' + encodeURIComponent(barcodeVal));
    const data = await res.json();
    icon.classList.remove('scanning');

    if(data.found){
      const p = prods.find(x => x.barcode === barcodeVal || x.sku === barcodeVal);
      if(p){
        addCart(p.id);
        zone.style.borderColor = 'var(--success)';
        toast('📷 Scanned: ' + p.em + ' ' + p.name, 'success');
        if(barcodeInput) barcodeInput.value = '';
      }
    } else {
      zone.style.borderColor = 'var(--danger)';
      toast('❌ Barcode not found: ' + barcodeVal, 'error');
    }
    setTimeout(() => zone.style.borderColor = '', 1500);
    return;
  }

  // Simulate random scan if no barcode entered
  setTimeout(() => {
    const available = prods.filter(p => p.stock > 0);
    if(!available.length){
      toast('No products in stock!', 'error');
      icon.classList.remove('scanning');
      zone.style.borderColor = '';
      return;
    }
    const p = available[Math.floor(Math.random() * available.length)];
    addCart(p.id);
    icon.classList.remove('scanning');
    zone.style.borderColor = 'var(--success)';
    setTimeout(() => zone.style.borderColor = '', 1000);
    toast('📷 Scanned: ' + p.em + ' ' + p.name, 'success');
  }, 900);
}
// ─── MOBILE ───
function toggleMobileSidebar(){
  const sb      = document.getElementById('main-sidebar');
  const overlay = document.getElementById('sb-overlay');
  const isOpen  = sb.classList.toggle('mobile-open');
  overlay.classList.toggle('on', isOpen);

  if(isOpen){
    sb.style.transform = 'translateX(0)';
  } else {
    sb.style.transform = 'translateX(-100%)';
  }
}

// Close sidebar on mobile when nav item clicked
document.querySelectorAll('.ni').forEach(item => {
  item.addEventListener('click', () => {
    if(window.innerWidth <= 768){
      document.getElementById('main-sidebar').classList.remove('mobile-open');
      document.getElementById('sb-overlay').classList.remove('on');
    }
  });
});

// Toggle cart on mobile
function toggleMobileCart(){
  if(window.innerWidth <= 768){
    document.querySelector('.cp')?.classList.toggle('cart-open');
  }
}

// Make cart header toggle cart on mobile
document.addEventListener('DOMContentLoaded', () => {
  const cpHd = document.querySelector('.cp-hd');
  if(cpHd) cpHd.addEventListener('click', toggleMobileCart);
});
// ─── BACKUP ───
async function openBackupModal(){
  document.getElementById('modal-backup').classList.add('on');

  // Load stats
  try {
    const res   = await fetch('api/backup.php?action=stats');
    const stats = await res.json();

    document.getElementById('backup-stats').innerHTML = `
      <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:10px">
        <div style="text-align:center;background:var(--bg-primary);border-radius:8px;padding:10px">
          <div style="font-size:18px;font-weight:800;color:var(--acc)">${stats.tables}</div>
          <div style="font-size:10px;color:var(--text-secondary);font-weight:600">Tables</div>
        </div>
        <div style="text-align:center;background:var(--bg-primary);border-radius:8px;padding:10px">
          <div style="font-size:18px;font-weight:800;color:var(--success-text)">${stats.total_rows}</div>
          <div style="font-size:10px;color:var(--text-secondary);font-weight:600">Total Records</div>
        </div>
        <div style="text-align:center;background:var(--bg-primary);border-radius:8px;padding:10px">
          <div style="font-size:14px;font-weight:800;color:var(--text-primary)">${new Date().toLocaleDateString('en-PH')}</div>
          <div style="font-size:10px;color:var(--text-secondary);font-weight:600">Today</div>
        </div>
      </div>
      <div style="font-size:11px;color:var(--text-secondary)">
        ${stats.stats.map(s =>
          `<span style="display:inline-flex;align-items:center;gap:4px;margin:2px 4px 2px 0;background:var(--bg-primary);padding:2px 8px;border-radius:99px;font-weight:600">
            ${s.table} <b style="color:var(--acc)">${s.rows}</b>
          </span>`
        ).join('')}
      </div>`;
  } catch(err){
    document.getElementById('backup-stats').innerHTML = `
      <div style="color:var(--danger);font-size:12px;text-align:center">
        <i class="ti ti-alert-circle"></i> Could not load database stats
      </div>`;
  }
}

async function downloadBackup(){
  const btn = document.getElementById('backup-btn');
  btn.innerHTML = '<i class="ti ti-loader"></i>Preparing...';
  btn.disabled  = true;

  try {
    const res  = await fetch('api/backup.php?action=download');
    const blob = await res.blob();
    const url  = URL.createObjectURL(blob);
    const a    = document.createElement('a');
    a.href     = url;
    a.download = 'reinalin_backup_' + new Date().toISOString().slice(0,10) + '.sql';
    a.click();
    URL.revokeObjectURL(url);

    toast('Backup downloaded successfully! 💾', 'success');
    closeModal('modal-backup');
  } catch(err){
    toast('Backup failed: ' + err.message, 'error');
  }

  btn.innerHTML = '<i class="ti ti-download"></i>Download Backup';
  btn.disabled  = false;
}
// ─── SALES TARGETS ───
async function loadTargets(){
  try {
    const res  = await fetch('api/targets.php?action=get');
    const data = await res.json();

    // Daily progress
    const dailyPct = data.daily_pct || 0;
    document.getElementById('daily-pct').textContent        = dailyPct + '%';
    document.getElementById('daily-bar').style.width        = dailyPct + '%';
    document.getElementById('daily-bar').style.background   = dailyPct >= 100
      ? 'linear-gradient(90deg,#1D9E75,#116b4e)'
      : 'linear-gradient(90deg,#E8700A,#B85508)';
    document.getElementById('daily-sales-amt').textContent  = fmt(data.daily_sales) + ' achieved';
    document.getElementById('daily-target-amt').textContent = 'Goal: ' + fmt(data.daily_target);

    // Monthly progress
    const monthlyPct = data.monthly_pct || 0;
    document.getElementById('monthly-pct').textContent        = monthlyPct + '%';
    document.getElementById('monthly-bar').style.width        = monthlyPct + '%';
    document.getElementById('monthly-bar').style.background   = monthlyPct >= 100
      ? 'linear-gradient(90deg,#E8700A,#B85508)'
      : 'linear-gradient(90deg,#1D9E75,#116b4e)';
    document.getElementById('monthly-sales-amt').textContent  = fmt(data.monthly_sales) + ' achieved';
    document.getElementById('monthly-target-amt').textContent = 'Goal: ' + fmt(data.monthly_target);

    // Show congrats if target hit
    if(dailyPct >= 100) toast('🎉 Daily sales target reached!', 'success');
    if(monthlyPct >= 100) toast('🎉 Monthly sales target reached!', 'success');

  } catch(err){
    console.error('loadTargets error:', err);
  }
}

function openTargetModal(){
  document.getElementById('modal-target').classList.add('on');
}

async function saveTargets(){
  const daily   = parseFloat(document.getElementById('tgt-daily').value);
  const monthly = parseFloat(document.getElementById('tgt-monthly').value);

  if(!daily || daily <= 0)  { toast('Enter a valid daily target.','error'); return; }
  if(!monthly || monthly <= 0){ toast('Enter a valid monthly target.','error'); return; }

  await fetch('api/targets.php?action=update', {
    method: 'POST',
    headers: {'Content-Type':'application/json'},
    body: JSON.stringify({ type:'daily', amount: daily, created_by: currentUser?.name })
  });

  await fetch('api/targets.php?action=update', {
    method: 'POST',
    headers: {'Content-Type':'application/json'},
    body: JSON.stringify({ type:'monthly', amount: monthly, created_by: currentUser?.name })
  });

  toast('Targets updated ✅');
  closeModal('modal-target');
  await loadTargets();
}
// ─── ATTENDANCE ───
async function renderAttendance(){
  const today = new Date().toISOString().split('T')[0];
  document.getElementById('att-today-date').textContent = new Date().toLocaleDateString('en-PH',{weekday:'long',year:'numeric',month:'long',day:'numeric'});

  // Set default month if not set
  const monthInput = document.getElementById('att-month');
  if(!monthInput.value) monthInput.value = new Date().toISOString().slice(0,7);
  const month = monthInput.value;

  // Set default log date
  const logDate = document.getElementById('att-log-date');
  if(!logDate.value) logDate.value = today;

  // Load staff for time in/out buttons
  await loadStaff();
  const btnsContainer = document.getElementById('att-staff-btns');
  if(!staff.length){
    btnsContainer.innerHTML = '<div class="ec"><i class="ti ti-users"></i>No staff found</div>';
  } else {
    // Check who is timed in today
    const res = await fetch('api/attendance.php?action=list&date=' + today);
    const todayLogs = await res.json();
    const timedIn  = todayLogs.filter(l => l.time_in && !l.time_out).map(l => parseInt(l.staff_id));
    const timedOut = todayLogs.filter(l => l.time_out).map(l => parseInt(l.staff_id));

    document.getElementById('att-present').textContent = todayLogs.length;
    const totalHours = todayLogs.reduce((a,l) => a + parseFloat(l.hours_worked||0), 0);
    document.getElementById('att-hours').textContent   = totalHours.toFixed(1) + 'h';

    btnsContainer.innerHTML = staff.map(s => {
      const isIn  = timedIn.includes(s._dbId);
      const isDone = timedOut.includes(s._dbId);
      const col   = ROLE_COLORS[s.role] || '#888';
      return `<div style="background:var(--bg-secondary);border:1px solid var(--border);border-radius:10px;padding:12px;text-align:center">
        <div style="font-weight:800;font-size:12px;margin-bottom:4px;color:var(--text-primary)">${s.first} ${s.last}</div>
        <div style="font-size:10px;color:${col};font-weight:700;margin-bottom:8px">${s.role}</div>
        ${isDone
          ? `<span class="bdg bg">✅ Done for today</span>`
          : isIn
          ? `<button class="btn btd bts" style="width:100%" onclick="timeOut(${s._dbId},'${s.first} ${s.last}')"><i class="ti ti-clock-off"></i>Time Out</button>`
          : `<button class="btn btg bts" style="width:100%" onclick="timeIn(${s._dbId},'${s.first} ${s.last}')"><i class="ti ti-clock"></i>Time In</button>`
        }
      </div>`;
    }).join('');
  }

  await loadAttendanceLog();
  await loadPayrollSummary(month);
}

async function timeIn(staffId, staffName){
  const res = await fetch('api/attendance.php?action=time_in', {
    method: 'POST',
    headers: {'Content-Type':'application/json'},
    body: JSON.stringify({ staff_id: staffId, staff_name: staffName })
  });
  const data = await res.json();
  if(data.success){
    toast(staffName + ' timed in ✅');
    await renderAttendance();
  } else {
    toast(data.message || 'Error', 'error');
  }
}

async function timeOut(staffId, staffName){
  const res = await fetch('api/attendance.php?action=time_out', {
    method: 'POST',
    headers: {'Content-Type':'application/json'},
    body: JSON.stringify({ staff_id: staffId })
  });
  const data = await res.json();
  if(data.success){
    toast(staffName + ' timed out — ' + data.hours + ' hours worked ✅');
    await renderAttendance();
  } else {
    toast(data.message || 'Error', 'error');
  }
}

async function loadAttendanceLog(){
  const date = document.getElementById('att-log-date').value;
  if(!date) return;
  const res  = await fetch('api/attendance.php?action=list&date=' + date);
  const logs = await res.json();

  document.getElementById('att-log-tbl').innerHTML = `
    <thead>
      <tr>
        <th>Staff</th><th>Time In</th><th>Time Out</th>
        <th>Hours</th><th>Status</th>
      </tr>
    </thead>
    <tbody>
      ${logs.length
        ? logs.map(l => `
          <tr>
            <td style="font-weight:700">${l.staff_name}</td>
            <td style="color:var(--success-text);font-weight:600">
              ${l.time_in ? new Date(l.time_in).toLocaleTimeString('en-PH',{hour:'2-digit',minute:'2-digit'}) : '—'}
            </td>
            <td style="color:var(--danger);font-weight:600">
              ${l.time_out ? new Date(l.time_out).toLocaleTimeString('en-PH',{hour:'2-digit',minute:'2-digit'}) : '—'}
            </td>
            <td style="font-weight:700">${l.hours_worked ? parseFloat(l.hours_worked).toFixed(1)+'h' : '—'}</td>
            <td><span class="bdg ${l.time_out ? 'bg' : 'bo'}">${l.time_out ? 'Complete' : 'On duty'}</span></td>
          </tr>`)
          .join('')
        : `<tr><td colspan="5"><div class="ec"><i class="ti ti-calendar-off"></i>No attendance records for this date</div></td></tr>`
      }
    </tbody>`;
}

async function loadPayrollSummary(month){
  const res     = await fetch('api/attendance.php?action=monthly&month=' + month);
  const summary = await res.json();

  const totalPayroll = summary.reduce((a,s) => a + (s.days_present * s.salary_per_day), 0);
  document.getElementById('att-days').textContent    = summary.reduce((a,s) => a + parseInt(s.days_present||0), 0);
  document.getElementById('att-payroll').textContent = fmts(totalPayroll);

  // Load generated payroll
  const pres    = await fetch('api/attendance.php?action=payroll_list&month=' + month);
  const payroll = await pres.json();

  document.getElementById('payroll-tbl').innerHTML = `
    <thead>
      <tr>
        <th>Staff</th><th>Days Worked</th><th>Daily Rate</th>
        <th>Gross Pay</th><th>Deductions</th><th>Net Pay</th>
        <th>Status</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      ${payroll.length
        ? payroll.map(p => `
          <tr>
            <td style="font-weight:700">${p.staff_name}</td>
            <td style="text-align:center;font-weight:700">${p.days_worked}</td>
            <td>${fmt(p.daily_rate)}</td>
            <td style="font-weight:700">${fmt(p.gross_pay)}</td>
            <td style="color:var(--danger)">${fmt(p.deductions)}</td>
            <td style="font-weight:800;color:var(--success-text)">${fmt(p.net_pay)}</td>
            <td><span class="bdg ${p.status==='Paid'?'bg':'bo'}">${p.status}</span></td>
            <td>
              ${p.status !== 'Paid'
                ? `<button class="btn bts btg" onclick="markPayrollPaid(${p.id})"><i class="ti ti-check"></i>Mark Paid</button>`
                : '—'
              }
            </td>
          </tr>`)
          .join('')
        : `<tr><td colspan="8"><div class="ec"><i class="ti ti-calculator"></i>No payroll generated yet. Click "Generate Payroll" above.</div></td></tr>`
      }
    </tbody>`;
}

async function generatePayroll(){
  const month = document.getElementById('att-month').value;
  if(!month){ toast('Please select a month first.','error'); return; }

  const res  = await fetch('api/attendance.php?action=generate_payroll', {
    method: 'POST',
    headers: {'Content-Type':'application/json'},
    body: JSON.stringify({ month })
  });
  const data = await res.json();
  if(data.success){
    toast('Payroll generated for ' + month + ' ✅');
    await loadPayrollSummary(month);
  }
}

async function markPayrollPaid(id){
  await fetch('api/attendance.php?action=mark_paid', {
    method: 'POST',
    headers: {'Content-Type':'application/json'},
    body: JSON.stringify({ id })
  });
  toast('Payroll marked as paid ✅');
  const month = document.getElementById('att-month').value;
  await loadPayrollSummary(month);
}

// ─── CUSTOMERS ───
let customers = [];
let editCustomerId = null;

async function loadCustomers(){
  const res = await fetch('api/customers.php?action=list');
  customers = await res.json();
}

async function renderCustomers(){
  await loadCustomers();

  // Summary
  const totalRevenue  = customers.reduce((a,c) => a + parseFloat(c.total_spent||0), 0);
  const totalCredit   = customers.reduce((a,c) => a + parseFloat(c.outstanding_credit||0), 0);
  const totalTxns     = customers.reduce((a,c) => a + parseInt(c.txn_count||0), 0);
  document.getElementById('cust-total').textContent   = customers.length;
  document.getElementById('cust-revenue').textContent = fmts(totalRevenue);
  document.getElementById('cust-credit').textContent  = fmts(totalCredit);
  document.getElementById('cust-txns').textContent    = totalTxns;

  // Filter
  const q = (document.getElementById('cust-srch')?.value || '').toLowerCase();
  const filtered = customers.filter(c =>
    !q || c.name.toLowerCase().includes(q) || (c.phone||'').includes(q)
  );

  const container = document.getElementById('cust-cards');
  if(!filtered.length){
    container.innerHTML = `<div class="ec" style="grid-column:1/-1"><i class="ti ti-users"></i>No customers found</div>`;
    return;
  }

  container.innerHTML = filtered.map(c => {
    const hasCredit = parseFloat(c.outstanding_credit||0) > 0;
    return `<div class="staff-card">
      <div style="display:flex;align-items:flex-start;gap:10px;margin-bottom:10px">
        <div class="staff-avatar" style="background:var(--acc-l);color:var(--acc);border:2px solid rgba(232,112,10,0.3)">
          ${c.name.split(' ').map(w=>w[0]).join('').slice(0,2).toUpperCase()}
        </div>
        <div style="flex:1;min-width:0">
          <div style="font-size:13px;font-weight:800;color:var(--text-primary)">${c.name}</div>
          <div style="font-size:11px;color:var(--text-secondary);margin-top:2px">
            ${c.phone ? '📞 '+c.phone : ''}
            ${c.email ? ' · ✉️ '+c.email : ''}
          </div>
          ${hasCredit ? `<span class="bdg bo" style="margin-top:4px;display:inline-block">⚠️ Utang: ${fmt(c.outstanding_credit)}</span>` : ''}
        </div>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:4px;margin-bottom:10px">
        <div style="font-size:10.5px;color:var(--text-secondary)"><i class="ti ti-receipt" style="color:var(--acc);font-size:11px"></i> ${c.txn_count||0} transactions</div>
        <div style="font-size:10.5px;color:var(--acc);font-weight:800"><i class="ti ti-currency-peso" style="font-size:11px"></i> ${fmts(c.total_spent||0)} spent</div>
        ${c.address ? `<div style="font-size:10.5px;color:var(--text-secondary);grid-column:1/-1"><i class="ti ti-map-pin" style="color:var(--acc);font-size:11px"></i> ${c.address}</div>` : ''}
      </div>
      <div style="display:flex;gap:6px">
        <button class="btn bts" style="flex:1;justify-content:center" onclick="viewCustomerHistory('${c.name}',${c.id})"><i class="ti ti-history"></i>History</button>
        <button class="btn bts" style="flex:1;justify-content:center" onclick="editCustomer(${c.id})"><i class="ti ti-edit"></i>Edit</button>
        <button class="btn bts btd" onclick="deleteCustomer(${c.id})"><i class="ti ti-trash"></i></button>
      </div>
    </div>`;
  }).join('');
}

function openCustomerModal(){
  editCustomerId = null;
  document.getElementById('cust-mtitle').textContent = 'Add Customer';
  ['cf-name','cf-phone','cf-email','cf-address','cf-notes'].forEach(id => document.getElementById(id).value = '');
  document.getElementById('modal-customer').classList.add('on');
}

function editCustomer(id){
  const c = customers.find(x => x.id == id);
  if(!c) return;
  editCustomerId = id;
  document.getElementById('cust-mtitle').textContent = 'Edit Customer';
  document.getElementById('cf-name').value    = c.name;
  document.getElementById('cf-phone').value   = c.phone || '';
  document.getElementById('cf-email').value   = c.email || '';
  document.getElementById('cf-address').value = c.address || '';
  document.getElementById('cf-notes').value   = c.notes || '';
  document.getElementById('modal-customer').classList.add('on');
}

async function saveCustomer(){
  const name = document.getElementById('cf-name').value.trim();
  if(!name){ toast('Customer name is required.','error'); return; }

  const obj = {
    name,
    phone:   document.getElementById('cf-phone').value.trim(),
    email:   document.getElementById('cf-email').value.trim(),
    address: document.getElementById('cf-address').value.trim(),
    notes:   document.getElementById('cf-notes').value.trim()
  };

  const action = editCustomerId ? 'edit' : 'add';
  if(editCustomerId) obj.id = editCustomerId;

  await fetch('api/customers.php?action=' + action, {
    method: 'POST',
    headers: {'Content-Type':'application/json'},
    body: JSON.stringify(obj)
  });

  toast(editCustomerId ? 'Customer updated ✅' : 'Customer added ✅');
  closeModal('modal-customer');
  await renderCustomers();
}

async function deleteCustomer(id){
  const c = customers.find(x => x.id == id);
  if(!c) return;
  confirmCallback = async () => {
    await fetch('api/customers.php?action=delete', {
      method: 'POST',
      headers: {'Content-Type':'application/json'},
      body: JSON.stringify({ id })
    });
    toast('Customer deleted.','info');
    closeModal('modal-confirm');
    await renderCustomers();
  };
  document.getElementById('confirm-title').textContent = 'Delete customer?';
  document.getElementById('confirm-msg').textContent   = `Delete "${c.name}"? This cannot be undone.`;
  document.getElementById('confirm-ok').onclick        = confirmCallback;
  openModal('confirm');
}

async function viewCustomerHistory(name, id){
  document.getElementById('ch-cust-name').textContent = name + '\'s History';
  const c = customers.find(x => x.id == id);

  // Stats
  document.getElementById('ch-cust-stats').innerHTML = `
    <div class="pdm-stat"><div class="pdm-stat-val">${c.txn_count||0}</div><div class="pdm-stat-lbl">Transactions</div></div>
    <div class="pdm-stat"><div class="pdm-stat-val">${fmts(c.total_spent||0)}</div><div class="pdm-stat-lbl">Total Spent</div></div>
    <div class="pdm-stat" style="background:${parseFloat(c.outstanding_credit||0)>0?'var(--warn-bg)':'var(--success-bg)'}">
      <div class="pdm-stat-val" style="color:${parseFloat(c.outstanding_credit||0)>0?'var(--warn-text)':'var(--success-text)'}">
        ${fmts(c.outstanding_credit||0)}
      </div>
      <div class="pdm-stat-lbl">Outstanding</div>
    </div>`;

  // History
  const res = await fetch('api/customers.php?action=history&name=' + encodeURIComponent(name));
  const history = await res.json();

  document.getElementById('ch-cust-tbl').innerHTML = `
    <thead><tr><th>TXN ID</th><th>Items</th><th>Total</th><th>Method</th><th>Date</th></tr></thead>
    <tbody>
      ${history.length
        ? history.map(t => `
          <tr>
            <td style="color:var(--acc);font-weight:700;font-family:'JetBrains Mono',monospace">${t.txn_code}</td>
            <td style="font-size:11px;color:var(--text-secondary)">${t.items_summary||'—'}</td>
            <td style="font-weight:700">${fmt(t.total)}</td>
            <td><span class="bdg ${t.payment_method==='Cash'?'bg':t.payment_method==='Utang'?'bo':'bb'}">${t.payment_method}</span></td>
            <td>${t.txn_date}</td>
          </tr>`).join('')
        : `<tr><td colspan="5"><div class="ec"><i class="ti ti-receipt-off"></i>No transactions yet</div></td></tr>`
      }
    </tbody>`;

  document.getElementById('modal-cust-history').classList.add('on');
}
// ─── EXPENSES ───
let expenses = [];
let editExpenseId = null;

async function loadExpenses(){
  const res = await fetch('api/expenses.php?action=list');
  expenses = await res.json();
}

async function renderExpenses(){
  await loadExpenses();

  // Load summary
  const sres = await fetch('api/expenses.php?action=summary');
  const summary = await sres.json();

  document.getElementById('exp-today').textContent = fmts(summary.today);
  document.getElementById('exp-month').textContent = fmts(summary.month);
  document.getElementById('exp-all').textContent   = fmts(summary.all);

  // Net profit = month sales - month expenses
  const salesRes = await fetch('api/dashboard.php?action=summary');
  const salesData = await salesRes.json();
  const netProfit = salesData.month.total - summary.month;
  document.getElementById('exp-net').textContent = fmts(netProfit);
  document.getElementById('exp-net').style.color = netProfit >= 0 ? 'var(--success-text)' : 'var(--danger)';

  // Category breakdown
  const catColors = {
    'Utilities':'#378ADD','Rent':'#E8700A','Supplies':'#1D9E75',
    'Salaries':'#7F77DD','Maintenance':'#D4537E','Transportation':'#EF9F27','Other':'#888780'
  };
  const catList = document.getElementById('exp-cat-list');
  if(summary.by_category.length){
    const maxAmt = Math.max(...summary.by_category.map(c => parseFloat(c.total)));
    catList.innerHTML = summary.by_category.map(c => {
      const pct = maxAmt > 0 ? (parseFloat(c.total) / maxAmt * 100) : 0;
      const col = catColors[c.category] || '#888';
      return `<div style="margin-bottom:10px">
        <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px">
          <span style="font-weight:600;color:var(--text-primary)">${c.category}</span>
          <span style="font-weight:700;color:${col}">${fmt(c.total)}</span>
        </div>
        <div style="height:6px;background:var(--border);border-radius:3px;overflow:hidden">
          <div style="height:100%;width:${pct}%;background:${col};border-radius:3px;transition:width .6s"></div>
        </div>
      </div>`;
    }).join('');
  } else {
    catList.innerHTML = '<div class="ec"><i class="ti ti-chart-pie"></i>No expenses yet</div>';
  }

  // Draw expense chart
  const gc = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.05)';
  const tc = isDark ? '#777' : '#aaa';
  const c1 = document.getElementById('ch-expenses');
  if(c1){
    if(c1._ch) c1._ch.destroy();
    const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    const monthData = Array(12).fill(0);
    expenses.forEach(e => {
      const m = new Date(e.expense_date).getMonth();
      monthData[m] += parseFloat(e.amount);
    });
    c1._ch = new Chart(c1, {
      type: 'bar',
      data: {
        labels: months,
        datasets: [{
          data: monthData,
          backgroundColor: 'rgba(226,75,74,0.7)',
          borderRadius: 6,
          hoverBackgroundColor: '#e24b4a'
        }]
      },
      options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          x: { grid: { display: false }, ticks: { color: tc, font: { size: 11 } } },
          y: { grid: { color: gc }, ticks: { color: tc, font: { size: 11 }, callback: v => '₱'+v } }
        }
      }
    });
  }

  // Filter and render table
  const q   = (document.getElementById('exp-srch')?.value || '').toLowerCase();
  const cf  = document.getElementById('exp-cat-filter')?.value || '';
  const filtered = expenses.filter(e =>
    (!q  || e.title.toLowerCase().includes(q) || e.category.toLowerCase().includes(q) || (e.notes||'').toLowerCase().includes(q)) &&
    (!cf || e.category === cf)
  );

  document.getElementById('exp-tbl').innerHTML = `
    <thead>
      <tr>
        <th>Title</th><th>Category</th><th>Amount</th>
        <th>Date</th><th>Added by</th><th>Notes</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      ${filtered.length
        ? filtered.map(e => `
          <tr>
            <td style="font-weight:600">${e.title}</td>
            <td><span class="bdg bb">${e.category}</span></td>
            <td style="font-weight:700;color:var(--danger)">${fmt(e.amount)}</td>
            <td>${e.expense_date}</td>
            <td>${e.added_by || '—'}</td>
            <td style="color:var(--text-secondary);font-size:11px">${e.notes || '—'}</td>
            <td>
              <div style="display:flex;gap:4px">
                <button class="btn bts" onclick="editExpense(${e.id})"><i class="ti ti-edit"></i></button>
                <button class="btn bts btd" onclick="deleteExpense(${e.id})"><i class="ti ti-trash"></i></button>
              </div>
            </td>
          </tr>`)
          .join('')
        : `<tr><td colspan="7"><div class="ec"><i class="ti ti-receipt-off"></i>No expenses found</div></td></tr>`
      }
    </tbody>`;
}

function openExpenseModal(){
  editExpenseId = null;
  document.getElementById('exp-mtitle').textContent = 'Add Expense';
  ['ef-title','ef-notes'].forEach(id => document.getElementById(id).value = '');
  document.getElementById('ef-amount').value = '';
  document.getElementById('ef-date').value = new Date().toISOString().split('T')[0];
  document.getElementById('ef-cat').value = 'Utilities';
  document.getElementById('modal-expense').classList.add('on');
}

function editExpense(id){
  const e = expenses.find(x => x.id == id);
  if(!e) return;
  editExpenseId = id;
  document.getElementById('exp-mtitle').textContent  = 'Edit Expense';
  document.getElementById('ef-title').value          = e.title;
  document.getElementById('ef-cat').value            = e.category;
  document.getElementById('ef-amount').value         = e.amount;
  document.getElementById('ef-date').value           = e.expense_date;
  document.getElementById('ef-notes').value          = e.notes || '';
  document.getElementById('modal-expense').classList.add('on');
}

async function saveExpense(){
  const title  = document.getElementById('ef-title').value.trim();
  const amount = parseFloat(document.getElementById('ef-amount').value);
  const date   = document.getElementById('ef-date').value;

  if(!title)  { toast('Please enter a title.','error'); return; }
  if(!amount || amount <= 0) { toast('Please enter a valid amount.','error'); return; }
  if(!date)   { toast('Please select a date.','error'); return; }

  const obj = {
    title, amount, expense_date: date,
    category:  document.getElementById('ef-cat').value,
    notes:     document.getElementById('ef-notes').value.trim(),
    added_by:  currentUser ? currentUser.name : 'Unknown'
  };

  const action = editExpenseId ? 'edit' : 'add';
  if(editExpenseId) obj.id = editExpenseId;

  await fetch('api/expenses.php?action=' + action, {
    method: 'POST',
    headers: {'Content-Type':'application/json'},
    body: JSON.stringify(obj)
  });

  toast(editExpenseId ? 'Expense updated ✅' : 'Expense added ✅');
  closeModal('modal-expense');
  await renderExpenses();
}

async function deleteExpense(id){
  const e = expenses.find(x => x.id == id);
  if(!e) return;
  confirmCallback = async () => {
    await fetch('api/expenses.php?action=delete', {
      method: 'POST',
      headers: {'Content-Type':'application/json'},
      body: JSON.stringify({ id })
    });
    toast('Expense deleted.','info');
    closeModal('modal-confirm');
    await renderExpenses();
  };
  document.getElementById('confirm-title').textContent = 'Delete expense?';
  document.getElementById('confirm-msg').textContent   = `Delete "${e.title}" — ${fmt(e.amount)}? This cannot be undone.`;
  document.getElementById('confirm-ok').onclick        = confirmCallback;
  openModal('confirm');
}
// ─── STAFF ───
const ROLE_COLORS={'Cashier':'#E8700A','Inventory Staff':'#378ADD','Delivery Driver':'#1D9E75','Stock Boy / Girl':'#7F77DD','Store Supervisor':'#D4537E','Manager':'#639922','Accountant':'#EF9F27','Security Guard':'#888780','Janitor / Cleaner':'#aaa','Administrator':'#E8700A'};
const ROLE_ICONS={'Cashier':'ti-receipt','Inventory Staff':'ti-package','Delivery Driver':'ti-truck','Stock Boy / Girl':'ti-box','Store Supervisor':'ti-star','Manager':'ti-briefcase','Accountant':'ti-calculator','Security Guard':'ti-shield','Janitor / Cleaner':'ti-tools','Administrator':'ti-crown'};

async function renderStaff(){
  await loadStaff();
  document.getElementById('staff-stat').textContent=staff.length+' staff members on record';
  document.getElementById('staff-cards').innerHTML=staff.map(s=>{
    const col=ROLE_COLORS[s.role]||'#888';
    const icon=ROLE_ICONS[s.role]||'ti-user';
    const fullName=`${s.first}${s.middle?' '+s.middle:''} ${s.last}`;
    const initials=(s.first[0]+(s.last[0])).toUpperCase();
    const hasCreds=s.username?`<span class="bdg bb" style="font-size:9px"><i class="ti ti-lock" style="font-size:9px"></i> System access</span>`:`<span class="bdg" style="background:var(--bg-secondary);color:var(--text-tertiary);font-size:9px">No login</span>`;
    return`<div class="staff-card">
      <div style="display:flex;align-items:flex-start;gap:10px;margin-bottom:10px">
        <div class="staff-avatar" style="background:${col}22;color:${col};border:2px solid ${col}44">${initials}</div>
        <div style="flex:1;min-width:0">
          <div style="font-size:13px;font-weight:800;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${fullName}</div>
          <div style="display:flex;align-items:center;gap:4px;margin-top:3px">
            <div class="staff-role-badge" style="background:${col}18;color:${col}"><i class="ti ${icon}" style="font-size:11px"></i>${s.role}</div>
          </div>
          <div style="margin-top:4px">${hasCreds}</div>
        </div>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:4px;margin-bottom:10px">
        <div style="font-size:10.5px;color:var(--text-secondary);display:flex;align-items:center;gap:4px"><i class="ti ti-phone" style="font-size:11px;color:var(--acc)"></i>${s.phone||'—'}</div>
        <div style="font-size:10.5px;color:var(--text-secondary);display:flex;align-items:center;gap:4px"><i class="ti ti-calendar" style="font-size:11px;color:var(--acc)"></i>${s.hired||'—'}</div>
        <div style="font-size:10.5px;color:var(--text-secondary);display:flex;align-items:center;gap:4px"><i class="ti ti-map-pin" style="font-size:11px;color:var(--acc)"></i>${s.city||'—'}</div>
        <div style="font-size:10.5px;color:var(--acc);font-weight:800;display:flex;align-items:center;gap:4px"><i class="ti ti-currency-peso" style="font-size:11px"></i>${fmt(s.sal)}/day</div>
      </div>
      <div style="display:flex;gap:6px">
        <button class="btn bts" style="flex:1;justify-content:center" onclick="editStaff('${s.id}')"><i class="ti ti-edit"></i>Edit</button>
        <button class="btn bts" style="flex:1;justify-content:center" onclick="viewStaffDetail('${s.id}')"><i class="ti ti-eye"></i>View</button>
      </div>
    </div>`;
  }).join('');
  const actv=txns.map(t=>({staff:t.staff,action:'Processed '+t.pay+' sale',total:fmt(t.total),date:t.date,time:t.time}));
  document.getElementById('staff-log').innerHTML=`<thead><tr><th>Staff member</th><th>Action</th><th>Amount</th><th>Date</th><th>Time</th></tr></thead><tbody>${actv.slice(0,10).map(a=>`<tr><td style="font-weight:600">${a.staff}</td><td>${a.action}</td><td style="font-weight:700;color:var(--success-text)">${a.total}</td><td>${a.date}</td><td>${a.time}</td></tr>`).join('')}</tbody>`;
}
function viewStaffDetail(id){
  const s=staff.find(x=>x.id===id);if(!s)return;
  const fullName=`${s.first}${s.middle?' '+s.middle:''} ${s.last}`;
  const txnCount=txns.filter(t=>t.staff===fullName).length;
  const revenue=txns.filter(t=>t.staff===fullName).reduce((a,t)=>a+t.total,0);
  const col=ROLE_COLORS[s.role]||'#888';
  const icon=ROLE_ICONS[s.role]||'ti-user';
  document.getElementById('confirm-title').textContent=fullName;
  document.getElementById('confirm-msg').innerHTML=`
    <div style="text-align:left;font-size:12px;line-height:1.8">
      <b style="color:${col}"><i class="ti ${icon}" style="font-size:12px"></i> ${s.role}</b> · ${s.empType||'Full-time'}<br>
      📞 ${s.phone||'—'} &nbsp; ✉️ ${s.email||'—'}<br>
      📍 ${s.address||''} ${s.city||''}, ${s.province||''}<br>
      🎂 ${s.dob||'—'} &nbsp; 🚹 ${s.gender||'—'}<br>
      📅 Hired: ${s.hired||'—'}<br>💰 ₱${s.sal}/day<br>
      <br><b>Gov't IDs:</b><br>SSS: ${s.sss||'—'} &nbsp; PhilHealth: ${s.philhealth||'—'}<br>
      Pag-IBIG: ${s.pagibig||'—'} &nbsp; TIN: ${s.tin||'—'}<br>
      <br><b>Performance:</b><br>Transactions: <b>${txnCount}</b> &nbsp; Revenue: <b>${fmt(revenue)}</b>
    </div>`;
  document.getElementById('confirm-ok').textContent='Edit';
  document.getElementById('confirm-ok').className='btn bta';
  document.getElementById('confirm-ok').onclick=()=>{closeModal('modal-confirm');editStaff(id);};
  openModal('confirm');
}

// ─── PAYMENTS ───
async function renderPayments(){
  await loadTransactions();
  const byPay = m => txns.filter(t => t.pay === m).reduce((a,t) => a+t.total, 0);
  const cash = byPay('Cash'), gcash = byPay('GCash'), maya = byPay('Maya'), utang = byPay('Utang');
  document.getElementById('p-cash').textContent   = fmts(cash);
  document.getElementById('p-ewallet').textContent = fmts(gcash + maya);
  document.getElementById('p-utang').textContent  = fmts(utang);
  document.getElementById('p-total').textContent  = fmts(cash + gcash + maya + utang);

  // Load credits from database
  const res = await fetch('api/credits.php?action=list');
  const rows = await res.json();
  credits = rows.map(c => ({
    id:   'c' + c.id,
    _dbId: parseInt(c.id),
    name: c.customer_name,
    total: parseFloat(c.total),
    date: c.txn_date,
    paid: c.paid == 1
  }));

  const ul = document.getElementById('utang-list');
  if(!credits.length){
    ul.innerHTML=`<div class="ec" style="padding:16px"><i class="ti ti-check-circle"></i>No credit accounts</div>`;
  } else {
    ul.innerHTML = credits.map(c=>`<div class="credit-row">
      <div style="display:flex;align-items:center;gap:8px">
        <input type="checkbox" id="ck-${c.id}" onchange="toggleUtangSel('${c.id}',this.checked)" style="width:14px;height:14px;accent-color:var(--acc)" ${selectedUtang.has(c.id)?'checked':''}>
        <div><div class="cr-name">${c.name}</div><div class="cr-amt">${fmt(c.total)} · ${c.date}</div></div>
      </div>
      <span class="bdg ${c.paid?'bg':'bo'}">${c.paid?'Paid':'Unpaid'}</span>
    </div>`).join('');
  }

  document.getElementById('pay-tbl').innerHTML=`<thead><tr><th>TXN ID</th><th>Amount</th><th>Method</th><th>Customer / Staff</th><th>Date</th><th>Time</th></tr></thead><tbody>${txns.map(t=>`<tr><td style="color:var(--acc);font-weight:700;font-family:'JetBrains Mono',monospace">${t.id}</td><td style="font-weight:700">${fmt(t.total)}</td><td><span class="bdg ${t.pay==='Cash'?'bg':t.pay==='Utang'?'bo':'bb'}">${t.pay}</span></td><td>${t.customer||t.staff}</td><td>${t.date}</td><td>${t.time}</td></tr>`).join('')}</tbody>`;
}
function toggleUtangSel(id,checked){if(checked)selectedUtang.add(id);else selectedUtang.delete(id);}
async function markUtangPaid(){
  if(!selectedUtang.size){ toast('No credit accounts selected.','error'); return; }
  const ids = [...selectedUtang].map(id => {
    const c = credits.find(x => x.id === id);
    return c ? c._dbId : null;
  }).filter(Boolean);

  await fetch('api/credits.php?action=mark_paid', {
    method: 'POST',
    headers: {'Content-Type':'application/json'},
    body: JSON.stringify({ ids })
  });

  selectedUtang.clear();
  toast('Selected accounts marked as paid ✅');
  await renderPayments();
}

// ─── REPORTS ───
let rptChartsDrawn=false;
async function renderReports(force=false){
  await loadTransactions();
  if(rptChartsDrawn&&!force)return;rptChartsDrawn=true;
  setTimeout(()=>{
    const gc=isDark?'rgba(255,255,255,0.06)':'rgba(0,0,0,0.05)';
    const tc=isDark?'#777':'#aaa';
    const c1=document.getElementById('ch-monthly');
    if(c1._ch)c1._ch.destroy();
    c1._ch=new Chart(c1,{type:'bar',data:{labels:['Jan','Feb','Mar','Apr','May','Jun'],datasets:[{data:[8500,9200,7800,11000,12400,0],backgroundColor:'rgba(232,112,10,0.8)',borderRadius:8,hoverBackgroundColor:'#E8700A'}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{x:{grid:{display:false},ticks:{color:tc,font:{size:11,family:'Plus Jakarta Sans'}}},y:{grid:{color:gc},ticks:{color:tc,font:{size:11,family:'Plus Jakarta Sans'},callback:v=>'₱'+v}}}}});
    const topP=prods.map(p=>({name:p.name.length>14?p.name.slice(0,14)+'…':p.name,sales:txns.reduce((a,t)=>a+t.items.filter(i=>i.name===p.name).reduce((b,i)=>b+i.price*i.qty,0),0)})).sort((a,b)=>b.sales-a.sales).slice(0,5);
    const c2=document.getElementById('ch-top');
    if(c2._ch)c2._ch.destroy();
    c2._ch=new Chart(c2,{type:'bar',data:{labels:topP.map(p=>p.name),datasets:[{data:topP.map(p=>p.sales),backgroundColor:'rgba(29,158,117,0.8)',borderRadius:8,hoverBackgroundColor:'#1D9E75'}]},options:{indexAxis:'y',responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{x:{grid:{color:gc},ticks:{color:tc,font:{size:11,family:'Plus Jakarta Sans'},callback:v=>'₱'+v}},y:{grid:{display:false},ticks:{color:tc,font:{size:11,family:'Plus Jakarta Sans'}}}}}});
  },80);
  document.getElementById('rpt-tbl').innerHTML=`<thead><tr><th>Product</th><th>Category</th><th>Units Sold</th><th>Revenue</th><th>Cost</th><th>Profit</th></tr></thead><tbody>${prods.map(p=>{const sold=txns.reduce((a,t)=>a+t.items.filter(i=>i.name===p.name).reduce((b,i)=>b+i.qty,0),0);const rev=sold*p.price;const cost=sold*p.cost;return`<tr><td>${p.em} <span style="font-weight:600">${p.name}</span></td><td>${p.cat}</td><td style="font-weight:700">${sold}</td><td>${fmt(rev)}</td><td>${fmt(cost)}</td><td style="font-weight:700;color:${rev-cost>0?'var(--success-text)':'var(--danger)'}">${fmt(rev-cost)}</td></tr>`;}).join('')}</tbody>`;
  document.getElementById('rpt-inv-tbl').innerHTML=`<thead><tr><th>Product</th><th>Category</th><th>In Stock</th><th>Re-order Pt.</th><th>Unit Cost</th><th>Total Value</th><th>Status</th></tr></thead><tbody>${prods.map(p=>`<tr><td>${p.em} <span style="font-weight:600">${p.name}</span></td><td>${p.cat}</td><td style="font-weight:700">${p.stock}</td><td>${p.reorder}</td><td>${fmt(p.cost)}</td><td style="font-weight:700">${fmt(p.stock*p.cost)}</td><td><span class="bdg ${p.stock===0?'br':p.stock<p.reorder?'bo':'bg'}">${p.stock===0?'Out of stock':p.stock<p.reorder?'Low stock':'In stock'}</span></td></tr>`).join('')}</tbody>`;
  document.getElementById('rpt-emp-tbl').innerHTML=`<thead><tr><th>Staff member</th><th>Role</th><th>Transactions</th><th>Revenue Handled</th><th>Salary/day</th><th>Employment</th></tr></thead><tbody>${staff.map(s=>{const nm=s.first+' '+s.last;const st=txns.filter(t=>t.staff===nm||t.staff===s.first+' '+(s.middle?s.middle+' ':'')+s.last);return`<tr><td style="font-weight:700">${s.first[0]}${s.last[0]} ${s.first} ${s.last}</td><td><span style="font-size:11px;color:${ROLE_COLORS[s.role]||'#888'};font-weight:700">${s.role}</span></td><td style="color:var(--acc);font-weight:700">${st.length}</td><td style="font-weight:700">${fmt(st.reduce((a,t)=>a+t.total,0))}</td><td>${fmt(s.sal)}</td><td><span class="bdg ${s.empType==='Full-time'?'bg':s.empType==='Contractual'?'bo':'bb'}">${s.empType||'Full-time'}</span></td></tr>`;}).join('')}</tbody>`;
}
function rptTab(name,el){
  document.querySelectorAll('.rt').forEach(r=>r.classList.remove('on'));el.classList.add('on');
  ['sales','inventory','employee'].forEach(n=>{document.getElementById('rpt-'+n).style.display=n===name?'block':'none';});
}

// ─── TRANSACTIONS ───
async function renderTxns(){
  await loadTransactions();
  const q   = (document.getElementById('txn-srch')?.value || '').toLowerCase();
  const df  = document.getElementById('txn-date').value;
  const pf  = document.getElementById('txn-pay').value;

  const ft = txns.filter(t => {
    const matchDate = !df || t.date === df;
    const matchPay  = !pf || t.pay === pf;
    const matchQ    = !q
      || t.id.toLowerCase().includes(q)
      || (t.staff||'').toLowerCase().includes(q)
      || (t.customer||'').toLowerCase().includes(q)
      || t.items.some(i => i.name.toLowerCase().includes(q));
    return matchDate && matchPay && matchQ;
  });

  // Highlight matching text
  const hl = (text, q) => q
    ? text.replace(new RegExp('('+q.replace(/[.*+?^${}()|[\]\\]/g,'\\$&')+')','gi'),'<mark>$1</mark>')
    : text;

  // Summary bar
  const totalAmt = ft.reduce((a,t) => a + t.total, 0);
  const summaryHtml = ft.length
    ? `<div style="background:var(--bg-secondary);border:0.5px solid var(--border);border-radius:10px;padding:10px 14px;margin-bottom:10px;display:flex;gap:20px;font-size:12px;font-weight:600;color:var(--text-secondary)">
        <span>📋 <b style="color:var(--text-primary)">${ft.length}</b> transaction${ft.length!==1?'s':''}</span>
        <span>💰 Total: <b style="color:var(--acc)">${fmt(totalAmt)}</b></span>
        <span>💵 Cash: <b>${fmt(ft.filter(t=>t.pay==='Cash').reduce((a,t)=>a+t.total,0))}</b></span>
        <span>📱 E-Wallet: <b>${fmt(ft.filter(t=>t.pay==='GCash'||t.pay==='Maya').reduce((a,t)=>a+t.total,0))}</b></span>
        <span>📒 Utang: <b>${fmt(ft.filter(t=>t.pay==='Utang').reduce((a,t)=>a+t.total,0))}</b></span>
       </div>`
    : '';

  const tableHtml = `
    <thead>
      <tr>
        <th>ID</th><th>Items</th><th>Total</th><th>Method</th>
        <th>Customer / Staff</th><th>Date</th><th>Time</th>
      </tr>
    </thead>
    <tbody>
      ${ft.length
        ? ft.map(t => `
          <tr style="cursor:pointer" onclick="showTxnDetail('${t.id}')">
            <td style="color:var(--acc);font-weight:700;font-family:'JetBrains Mono',monospace">
              ${hl(t.id, q)}
            </td>
            <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
              ${hl(t.items.map(i => i.qty+'× '+i.name).join(', '), q)}
            </td>
            <td style="font-weight:700">${fmt(t.total)}</td>
            <td>
              <span class="bdg ${t.pay==='Cash'?'bg':t.pay==='Utang'?'bo':'bb'}">
                ${t.pay}
              </span>
            </td>
            <td>${hl(t.customer||t.staff, q)}</td>
            <td>${t.date}</td>
            <td>${t.time}</td>
          </tr>`)
          .join('')
        : `<tr><td colspan="7">
            <div class="ec">
              <i class="ti ti-search"></i>
              No transactions found
              ${q ? `<br><span style="font-size:11px">No results for "<b>${q}</b>"</span>` : ''}
            </div>
           </td></tr>`
      }
    </tbody>`;

  // Insert summary + table
  const wrapper = document.getElementById('txn-tbl').parentElement;
  let summary = document.getElementById('txn-summary');
  if(!summary){
    summary = document.createElement('div');
    summary.id = 'txn-summary';
    wrapper.parentElement.insertBefore(summary, wrapper);
  }
  summary.innerHTML = summaryHtml;
  document.getElementById('txn-tbl').innerHTML = tableHtml;
}
function clearTxnFilters(){
  document.getElementById('txn-srch').value = '';
  document.getElementById('txn-date').value = '';
  document.getElementById('txn-pay').value  = '';
  renderTxns();
  toast('Filters cleared', 'info');
}
function showTxnDetail(id){
  const t=txns.find(x=>x.id===id);if(!t)return;
  document.getElementById('rec-msg').textContent=t.id+' · '+t.date+' '+t.time+' · '+t.pay;
  document.getElementById('rec-detail').innerHTML=
    t.items.map(i=>`<div class="receipt-row"><span>${i.qty}× ${i.name}</span><span>${fmt(i.price*i.qty)}</span></div>`).join('')+
    `<div style="border-top:1px dashed var(--border-md);margin:6px 0"></div>`+
    `<div class="receipt-row" style="font-weight:800"><span>Total</span><span>${fmt(t.total)}</span></div>`+
    `<div class="receipt-row"><span>Handled by</span><span>${t.customer||t.staff}</span></div>`;
  generateQR(t.id,t.total,t.pay);
  openModal('receipt');
}

// ─── PRODUCT CRUD ───
function openModal(n){
  if(n==='add-prod'){
    editProdId=null;pendingProdImg='';
    ['pf-name','pf-cost','pf-markup','pf-price','pf-stock','pf-reorder','pf-em','pf-sku','pf-barcode','pf-expiry'].forEach(id=>document.getElementById(id).value='');
    document.getElementById('pf-expiry-alert').value = 30;
    document.getElementById('prod-mtitle').textContent='Add product';
    document.getElementById('pf-img-preview').style.display='none';
    document.getElementById('pf-img-preview').src='';
    document.getElementById('prod-img-zone').style.borderColor='';
    document.getElementById('pf-img-input').value='';
  }
  if(n==='add-staff'){
    editStaffId=null;
    ['sf-first','sf-middle','sf-last','sf-phone','sf-email','sf-sal','sf-address','sf-city','sf-province','sf-sss','sf-philhealth','sf-pagibig','sf-tin','sf-ec-name','sf-ec-phone','sf-ec-rel','sf-username','sf-password'].forEach(id=>{const el=document.getElementById(id);if(el)el.value='';});
    document.getElementById('sf-gender').value='';
    document.getElementById('sf-emp-type').value='Full-time';
    document.getElementById('sf-role').value='Cashier';
    document.getElementById('sf-user-msg').textContent='';
    document.getElementById('staff-mtitle').textContent='Add staff member';
  }
  const el=document.getElementById('modal-'+n);
  if(el)el.classList.add('on');
}
function closeModal(id){
  const el=document.getElementById(id);if(el)el.classList.remove('on');
  const editBtn=document.getElementById('pdm-edit-btn');if(editBtn)editBtn.style.display='';
  if(id==='modal-confirm'){
    document.getElementById('confirm-ok').className='btn btd';
    document.getElementById('confirm-ok').innerHTML='<i class="ti ti-trash"></i>Yes, delete';
  }
}
document.addEventListener('click',e=>{
  document.querySelectorAll('.modalbg.on').forEach(mb=>{if(e.target===mb)mb.classList.remove('on');});
});

function autoPrice(){
  const c=parseFloat(document.getElementById('pf-cost').value)||0;
  const m=parseFloat(document.getElementById('pf-markup').value)||0;
  if(c&&m)document.getElementById('pf-price').value=(c*(1+m/100)).toFixed(2);
}

async function saveProd(){
  const name = document.getElementById('pf-name').value.trim();
  if(!name){ toast('Product name is required.','error'); return; }
  const price = parseFloat(document.getElementById('pf-price').value);
  if(!price||price<=0){ toast('Please set a valid selling price.','error'); return; }

  const cat = document.getElementById('pf-cat').value;
  const obj = {
    name,
    category: cat,
    emoji: document.getElementById('pf-em').value.trim() || EM[cat] || '📦',
    cost: parseFloat(document.getElementById('pf-cost').value) || 0,
    price,
    stock: parseInt(document.getElementById('pf-stock').value) || 0,
    reorder_point: parseInt(document.getElementById('pf-reorder').value) || 10,
    img: pendingProdImg || '',
    sku: document.getElementById('pf-sku').value.trim() || '',
    barcode: document.getElementById('pf-barcode').value.trim() || '',
    expiry_date: document.getElementById('pf-expiry').value || null,
    expiry_alert_days: parseInt(document.getElementById('pf-expiry-alert').value) || 30
};

  if(editProdId){
    obj.id = prods.find(p => p.id === editProdId)._dbId;
    if(!pendingProdImg){
      const existing = prods.find(p => p.id === editProdId);
      if(existing) obj.img = existing.img || '';
    }
    await fetch('api/products.php?action=edit', {
      method: 'POST',
      headers: {'Content-Type':'application/json'},
      body: JSON.stringify(obj)
    });
    toast('Product updated ✅');
  } else {
    await fetch('api/products.php?action=add', {
      method: 'POST',
      headers: {'Content-Type':'application/json'},
      body: JSON.stringify(obj)
    });
    toast('Product added ✅');
  }

  closeModal('modal-add-prod');
  await loadProducts();
  renderInv();
}

function editProd(id){
  const p=prods.find(x=>x.id===id);if(!p)return;editProdId=id;pendingProdImg='';
  document.getElementById('pf-name').value=p.name;document.getElementById('pf-cat').value=p.cat;
  document.getElementById('pf-em').value=p.em;document.getElementById('pf-cost').value=p.cost;
  document.getElementById('pf-price').value=p.price;document.getElementById('pf-stock').value=p.stock;
  document.getElementById('pf-reorder').value=p.reorder;
  document.getElementById('pf-markup').value=p.cost>0?Math.round((p.price-p.cost)/p.cost*100):0;
  const preview=document.getElementById('pf-img-preview');
  if(p.img){preview.src=p.img;preview.style.display='block';document.getElementById('prod-img-zone').style.borderColor='var(--success)';}
  else{preview.style.display='none';preview.src='';document.getElementById('prod-img-zone').style.borderColor='';}
  document.getElementById('pf-sku').value     = p.sku || '';
  document.getElementById('pf-barcode').value = p.barcode || '';
  document.getElementById('pf-expiry').value       = p.expiry || '';
  document.getElementById('pf-expiry-alert').value = p.expiryAlert || 30;
  document.getElementById('prod-mtitle').textContent='Edit product';
  document.getElementById('modal-add-prod').classList.add('on');
}

// ─── STAFF CRUD ───
async function loadStaff(){
  try {
    const res = await fetch('api/staff.php?action=list');
    const text = await res.text();
    const rows = JSON.parse(text);
    staff = rows.map(s => ({
      id:       's' + s.id,
      _dbId:    parseInt(s.id),
      first:    s.first_name,
      middle:   s.middle_name || '',
      last:     s.last_name,
      role:     s.role,
      empType:  s.emp_type,
      sal:      parseFloat(s.salary_per_day),
      phone:    s.phone,
      email:    s.email || '',
      address:  s.address || '',
      city:     s.city || '',
      province: s.province || '',
      dob:      s.dob || '',
      gender:   s.gender || '',
      hired:    s.date_hired || '',
      sss:      s.sss || '',
      philhealth: s.philhealth || '',
      pagibig:  s.pagibig || '',
      tin:      s.tin || '',
      ecName:   s.ec_name || '',
      ecPhone:  s.ec_phone || '',
      ecRel:    s.ec_relation || '',
      username: s.username || ''
    }));
  } catch(err) {
    console.error('loadStaff error:', err);
  }
}
async function saveStaff(){
  const first = document.getElementById('sf-first').value.trim();
  const last  = document.getElementById('sf-last').value.trim();
  if(!first||!last){ toast('First and last name are required.','error'); return; }
  const phone = document.getElementById('sf-phone').value.trim();
  if(!phone){ toast('Mobile number is required.','error'); return; }
  const sal = parseFloat(document.getElementById('sf-sal').value);
  if(!sal||sal<=0){ toast('Please enter a valid salary.','error'); return; }

  const username = document.getElementById('sf-username').value.trim();
  const password = document.getElementById('sf-password').value;
  if(username && password && password.length < 6){ toast('Password must be at least 6 characters.','error'); return; }

  const obj = {
    first, last,
    middle:      document.getElementById('sf-middle').value.trim(),
    role:        document.getElementById('sf-role').value,
    empType:     document.getElementById('sf-emp-type').value,
    sal, phone,
    email:       document.getElementById('sf-email').value.trim(),
    address:     document.getElementById('sf-address').value.trim(),
    city:        document.getElementById('sf-city').value.trim(),
    province:    document.getElementById('sf-province').value.trim(),
    dob:         document.getElementById('sf-dob').value,
    gender:      document.getElementById('sf-gender').value,
    hired:       document.getElementById('sf-hired').value,
    sss:         document.getElementById('sf-sss').value.trim(),
    philhealth:  document.getElementById('sf-philhealth').value.trim(),
    pagibig:     document.getElementById('sf-pagibig').value.trim(),
    tin:         document.getElementById('sf-tin').value.trim(),
    ecName:      document.getElementById('sf-ec-name').value.trim(),
    ecPhone:     document.getElementById('sf-ec-phone').value.trim(),
    ecRel:       document.getElementById('sf-ec-rel').value.trim(),
    username,
    password
  };

  const action = editStaffId ? 'edit' : 'add';
  if(editStaffId) obj.id = staff.find(s => s.id === editStaffId)?._dbId || editStaffId;

  await fetch('api/staff.php?action=' + action, {
    method: 'POST',
    headers: {'Content-Type':'application/json'},
    body: JSON.stringify(obj)
  });

  toast(first + ' ' + last + (editStaffId ? ' updated ✅' : ' added ✅'));
  addNotif(editStaffId ? 'Staff updated' : 'New staff member', `${first} ${last} — ${obj.role}`);
  closeModal('modal-add-staff');
  await loadStaff();
  renderStaff();
}
function editStaff(id){
  const s=staff.find(x=>x.id===id);if(!s)return;editStaffId=id;
  const fields={'sf-first':s.first,'sf-middle':s.middle||'','sf-last':s.last,'sf-phone':s.phone,'sf-email':s.email||'','sf-sal':s.sal,'sf-address':s.address||'','sf-city':s.city||'','sf-province':s.province||'','sf-dob':s.dob||'','sf-hired':s.hired||'','sf-sss':s.sss||'','sf-philhealth':s.philhealth||'','sf-pagibig':s.pagibig||'','sf-tin':s.tin||'','sf-ec-name':s.ecName||'','sf-ec-phone':s.ecPhone||'','sf-ec-rel':s.ecRel||'','sf-username':s.username||''};
  Object.entries(fields).forEach(([k,v])=>{const el=document.getElementById(k);if(el)el.value=v;});
  if(s.gender)document.getElementById('sf-gender').value=s.gender;
  if(s.role)document.getElementById('sf-role').value=s.role;
  if(s.empType)document.getElementById('sf-emp-type').value=s.empType;
  document.getElementById('sf-password').value='';
  document.getElementById('sf-user-msg').textContent='';
  document.getElementById('staff-mtitle').textContent='Edit staff member';
  document.getElementById('modal-add-staff').classList.add('on');
}

// ─── CSV EXPORT ───
function exportCSV(){
  const rows=[['Product','Category','Units Sold','Revenue','Cost','Profit']];
  prods.forEach(p=>{const sold=txns.reduce((a,t)=>a+t.items.filter(i=>i.name===p.name).reduce((b,i)=>b+i.qty,0),0);const rev=sold*p.price;const cost=sold*p.cost;rows.push([p.name,p.cat,sold,rev.toFixed(2),cost.toFixed(2),(rev-cost).toFixed(2)]);});
  downloadCSV('reinalin-sales-report.csv',rows);
}
function exportInvCSV(){
  const rows=[['Product','Category','Stock','Reorder Point','Cost','Total Value','Status']];
  prods.forEach(p=>{const status=p.stock===0?'Out of stock':p.stock<p.reorder?'Low stock':'In stock';rows.push([p.name,p.cat,p.stock,p.reorder,p.cost.toFixed(2),(p.stock*p.cost).toFixed(2),status]);});
  downloadCSV('reinalin-inventory.csv',rows);
}
function downloadCSV(name,rows){
  const csv=rows.map(r=>r.map(v=>'"'+String(v).replace(/"/g,'""')+'"').join(',')).join('\n');
  const a=document.createElement('a');a.href='data:text/csv;charset=utf-8,'+encodeURIComponent(csv);a.download=name;a.click();
  toast('CSV exported 📥','info');
}

// ─── FAB ───
document.body.insertAdjacentHTML('beforeend','<button class="quick-sale-fab" id="quick-sale-fab" onclick="nav(\'pos\',document.querySelectorAll(\'.ni\')[2])" title="Quick sale"><i class="ti ti-receipt"></i></button>');
// ─── MOBILE INIT ───
function initMobile(){
  if(window.innerWidth <= 768){
    // Force sidebar off screen
    const sb = document.getElementById('main-sidebar');
    if(sb){
      sb.style.position = 'fixed';
      sb.style.transform = 'translateX(-100%)';
      sb.style.zIndex = '500';
      sb.style.transition = 'transform .25s ease';
    }
    // Force main to full width
    const main = document.querySelector('.main');
    if(main) main.style.width = '100%';
  }
}
// Run on load and resize
window.addEventListener('resize', initMobile);
window.addEventListener('load', initMobile);
document.addEventListener('DOMContentLoaded', initMobile);
initMobile();
</script>
<!-- ─── PRINT AREA ─── -->
<div id="print-area" style="display:none"></div>
</body>
</html>
}