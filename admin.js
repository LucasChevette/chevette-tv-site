const STORAGE_KEY = 'chevette_data_v1';
function loadData(){ return JSON.parse(localStorage.getItem(STORAGE_KEY) || '{}'); }
function saveData(d){ localStorage.setItem(STORAGE_KEY, JSON.stringify(d)); }

let data = loadData();
if (!data || !data.plans) {
  // fallback - se não existir, inicializa com padrão (mesmo do script)
  data = {
    plans: [
      { id: 'mensal', name: 'Mensal', price: 25.00, duration: '1 mês', desc: '' },
      { id: 'trimestral', name: 'Trimestral', price: 45.00, duration: '3 meses', desc: 'Full HD & 4K', highlight:true },
      { id: 'semestral', name: 'Semestral', price: 75.00, duration: '6 meses', desc: '' },
      { id: 'anual', name: 'Anual', price: 150.00, duration: '12 meses', desc: 'Melhor custo-benefício' }
    ],
    testimonials: [],
    coupons: [],
    promotions: []
  };
  saveData(data);
}

// -------- login ----------
const loginPanel = document.getElementById('login-panel');
const dashboard = document.getElementById('dashboard');
document.getElementById('admin-login').addEventListener('click', () => {
  const pass = document.getElementById('admin-password').value;
  // senha simples — troque depois; para produção usar autenticação real
  if (pass === 'chevette123') {
    loginPanel.style.display = 'none';
    dashboard.style.display = 'block';
    refreshAll();
  } else alert('Senha incorreta');
});

// ------- Plans CRUD -------
const plansList = document.getElementById('plans-list');
const pid = document.getElementById('plan-id');
const pname = document.getElementById('plan-name');
const pprice = document.getElementById('plan-price');
const pduration = document.getElementById('plan-duration');
const pdesc = document.getElementById('plan-desc');
document.getElementById('save-plan').addEventListener('click', () => {
  const id = pid.value.trim() || pname.value.trim().toLowerCase().replace(/\s+/g,'-');
  const existing = data.plans.find(p=>p.id===id);
  const planObj = { id, name: pname.value.trim(), price: parseFloat(pprice.value)||0, duration: pduration.value.trim(), desc: pdesc.value.trim() };
  if (existing){
    Object.assign(existing, planObj);
  } else data.plans.push(planObj);
  saveData(data);
  refreshAll();
  pid.value = ''; pname.value=''; pprice.value=''; pduration.value=''; pdesc.value='';
});
document.getElementById('clear-plan').addEventListener('click', () => {
  pid.value='';pname.value='';pprice.value='';pduration.value='';pdesc.value='';
});

function renderPlansList(){
  plansList.innerHTML = '';
  data.plans.forEach(p=>{
    const div = document.createElement('div');
    div.className='list-item';
    div.innerHTML = `<div><strong>${p.name}</strong> — ${p.duration} — ${p.price.toFixed(2)}</div>
      <div>
        <button class="small-btn" onclick='editPlan("${p.id}")'>Editar</button>
        <button class="small-btn" onclick='deletePlan("${p.id}")'>Excluir</button>
      </div>`;
    plansList.appendChild(div);
  });
}
window.editPlan = function(id){
  const p = data.plans.find(x=>x.id===id);
  if (!p) return;
  pid.value = p.id; pname.value = p.name; pprice.value = p.price; pduration.value = p.duration; pdesc.value = p.desc;
}
window.deletePlan = function(id){
  if (!confirm('Excluir plano?')) return;
  data.plans = data.plans.filter(x=>x.id!==id);
  saveData(data);
  refreshAll();
}

// ------- Testimonials -------
const testimonialsList = document.getElementById('testimonials-list');
const tname = document.getElementById('testi-name');
const ttext = document.getElementById('testi-text');
document.getElementById('add-testi').addEventListener('click', () => {
  if (!tname.value.trim() || !ttext.value.trim()) return alert('Preencha nome e texto');
  data.testimonials.push({ name: tname.value.trim(), text: ttext.value.trim() });
  saveData(data);
  tname.value=''; ttext.value='';
  refreshAll();
});
function renderTestimonialsList(){
  testimonialsList.innerHTML='';
  (data.testimonials||[]).forEach((t,i)=>{
    const d = document.createElement('div');
    d.className='list-item';
    d.innerHTML = `<div><strong>${t.name}</strong> — ${t.text}</div>
      <div>
        <button class="small-btn" onclick='delTesti(${i})'>Excluir</button>
      </div>`;
    testimonialsList.appendChild(d);
  });
}
window.delTesti = function(i){
  if (!confirm('Excluir depoimento?')) return;
  data.testimonials.splice(i,1);
  saveData(data); refreshAll();
}

// ------- Coupons -------
const couponsList = document.getElementById('coupons-list');
const couponCode = document.getElementById('coupon-code');
const couponPercent = document.getElementById('coupon-percent');
const couponUses = document.getElementById('coupon-uses');
const couponExpire = document.getElementById('coupon-expire');
document.getElementById('add-coupon').addEventListener('click', () => {
  if (!couponCode.value.trim() || !couponPercent.value.trim()) return alert('Código e % são obrigatórios');
  data.coupons = data.coupons || [];
  data.coupons.push({ code: couponCode.value.trim().toUpperCase(), percent: parseFloat(couponPercent.value)||0, uses: parseInt(couponUses.value)||0, used:0, expires: couponExpire.value || null });
  saveData(data); couponCode.value=''; couponPercent.value=''; couponUses.value=''; couponExpire.value=''; refreshAll();
});
function renderCoupons(){
  couponsList.innerHTML='';
  (data.coupons||[]).forEach((c,i)=>{
    const d = document.createElement('div'); d.className='list-item';
    d.innerHTML = `<div><strong>${c.code}</strong> — ${c.percent}% — usos:${c.uses||'∞'} ${c.expires? ' — expira: '+c.expires : ''}</div>
      <div>
        <button class="small-btn" onclick='delCoupon(${i})'>Excluir</button>
      </div>`;
    couponsList.appendChild(d);
  });
}
window.delCoupon = function(i){ if(!confirm('Excluir cupom?')) return; data.coupons.splice(i,1); saveData(data); refreshAll(); }

// ------- Promotions -------
const promosList = document.getElementById('promos-list');
const promoPlanSelect = document.getElementById('promo-plan-select');
const promoPercent = document.getElementById('promo-percent');
const promoEnd = document.getElementById('promo-end');
document.getElementById('add-promo').addEventListener('click', () => {
  if (!promoPlanSelect.value || !promoPercent.value || !promoEnd.value) return alert('Escolha plano, % e data de término');
  data.promotions = data.promotions || [];
  data.promotions.push({ id: 'promo_'+Date.now(), planId: promoPlanSelect.value, percent: parseFloat(promoPercent.value)||0, endsAt: promoEnd.value });
  saveData(data); promoPercent.value=''; promoEnd.value=''; refreshAll();
});
function renderPromos(){
  promosList.innerHTML='';
  (data.promotions||[]).forEach((p,i)=>{
    const d = document.createElement('div'); d.className='list-item';
    d.innerHTML = `<div><strong>${p.planId}</strong> — ${p.percent}% — termina: ${p.endsAt}</div>
      <div>
        <button class="small-btn" onclick='delPromo(${i})'>Excluir</button>
      </div>`;
    promosList.appendChild(d);
  });
}
window.delPromo = function(i){ if(!confirm('Excluir promoção?')) return; data.promotions.splice(i,1); saveData(data); refreshAll(); }

// ------- Export / Import / Reset -------
document.getElementById('export-data').addEventListener('click', () => {
  const raw = JSON.stringify(data, null, 2);
  prompt('Copie o JSON abaixo (CTRL+C):', raw);
});
document.getElementById('import-data').addEventListener('click', () => {
  const raw = prompt('Cole o JSON exportado aqui:');
  if (!raw) return;
  try {
    const parsed = JSON.parse(raw);
    data = parsed; saveData(data); alert('Importado com sucesso'); refreshAll();
  } catch(e){ alert('JSON inválido'); }
});
document.getElementById('reset-data').addEventListener('click', () => {
  if (!confirm('Resetar todos os dados para padrão?')) return;
  localStorage.removeItem(STORAGE_KEY);
  location.reload();
});

// ------- Helpers -------
function refreshAll(){
  data = JSON.parse(localStorage.getItem(STORAGE_KEY));
  renderPlansList();
  renderTestimonialsList();
  renderCoupons();
  renderPromos();
  // preencher select de planos
  promoPlanSelect.innerHTML = '<option value="">Escolha um plano</option>';
  (data.plans||[]).forEach(p => {
    const opt = document.createElement('option'); opt.value=p.id; opt.textContent = p.name;
    promoPlanSelect.appendChild(opt);
  });
}
refreshAll();
