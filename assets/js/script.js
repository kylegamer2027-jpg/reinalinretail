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
    hero.innerHTML=`<span style="font-size:64px">${p.em}</span><div class="pdm-badge" id="pdm-badge"></div>`;
  }
  document.getElementById('pdm-name').textContent=p.name;
  document.getElementById('pdm-cat').textContent=p.cat;
  document.getElementById('pdm-price').textContent=fmt(p.price);
  document.getElementById('pdm-stock').textContent=p.stock;
  const mkp=p.cost>0?Math.round((p.price-p.cost)/p.cost*100)+'%':'—';
  document.getElementById('pdm-mkp').textContent=mkp;
  const status=p.stock===0?`<span class="bdg br">Out of stock</span>`:p.stock<p.reorder?`<span class="bdg bo">Low stock</span>`:`<span class="bdg bg">In stock</span>`;
  document.getElementById('pdm-badge').innerHTML=status;
  const bar=document.getElementById('pdm-stockbar');
  const pct=Math.min(100,(p.stock/Math.max(p.reorder*2,p.stock))*100);
  const barColor=p.stock===0?'var(--danger)':p.stock<p.reorder?'var(--warn-text)':'var(--success)';
  bar.style.background=barColor;bar.style.width='0%';
  setTimeout(()=>bar.style.width=pct+'%',50);
  document.getElementById('pdm-reorder-lbl').textContent=`Reorder at ${p.reorder}`;

  // Show current qty in cart if any
  const inCart=cart[id]||0;
  const addBtn=document.getElementById('pdm-add-btn');
  addBtn.innerHTML=inCart>0
    ?`<i class="ti ti-shopping-cart-plus"></i>Add more (${inCart} in cart)`
    :`<i class="ti ti-shopping-cart-plus"></i>Add to cart`;
  addBtn.onclick=()=>{addCart(id);closeModal('modal-prod-detail');};
  addBtn.disabled=p.stock===0||inCart>=p.stock;
  addBtn.style.opacity=(p.stock===0||inCart>=p.stock)?'.5':'1';

  // Hide edit button when in POS context
  const editBtn=document.getElementById('pdm-edit-btn');
  editBtn.style.display='none';

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
   return`<div class="ci-row">${thumb}
  <div class="ci-inf">
    <div class="ci-nm">${p.name}</div>
    <div class="ci-pr">${fmt(p.price)} × ${cart[k]} = <strong>${fmt(ln)}</strong></div>
  </div>
  <div class="qc" style="display:flex;align-items:center;gap:4px;flex-shrink:0;">
    <button class="qb" onclick="chgQty('${k}',-1)" title="Decrease">−</button>
    <span style="font-size:12px;min-width:22px;text-align:center;color:var(--text-primary);font-weight:700">${cart[k]}</span>
    <button class="qb" onclick="chgQty('${k}',1)" title="Increase">+</button>
    <button class="qb" onclick="removeFromCart('${k}')" title="Remove item" style="color:var(--danger);border-color:var(--danger);background:var(--danger-bg);">
        <i class="ti ti-trash" style="font-size:11px"></i>
    </button>
  </div>
</div>`;
  }).join('');
  document.getElementById('ct-sub').textContent=fmt(sub);
  calcTotal();
}
function chgQty(id,d){const p=prods.find(x=>x.id===id);const nq=(cart[id]||0)+d;if(nq<=0){delete cart[id];}else if(nq<=p.stock){cart[id]=nq;}renderCart();}
function removeFromCart(id){
  delete cart[id];
  renderPOS();
  toast('Item removed from cart','info');
}
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
    const monthLabels=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    const monthTotals=Array(12).fill(0);
    txns.forEach(t=>{
      const m=new Date(t.date).getMonth();
      if(!isNaN(m)) monthTotals[m]+=t.total;
    });
    c1._ch=new Chart(c1,{type:'bar',data:{labels:monthLabels,datasets:[{data:monthTotals,backgroundColor:'rgba(232,112,10,0.8)',borderRadius:8,hoverBackgroundColor:'#E8700A'}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{x:{grid:{display:false},ticks:{color:tc,font:{size:11,family:'Plus Jakarta Sans'}}},y:{grid:{color:gc},ticks:{color:tc,font:{size:11,family:'Plus Jakarta Sans'},callback:v=>'₱'+v}}}}});
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
