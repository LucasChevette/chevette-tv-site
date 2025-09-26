// Dados padrões (executado apenas se não existir nada salvo)
const DEFAULT_DATA = {
  plans: [
    { id: 'mensal', name: 'Mensal', price: 25.00, duration: '1 mês', desc: '2 telas simultâneas' },
    { id: 'trimestral', name: 'Trimestral', price: 45.00, duration: '3 meses', desc: 'Full HD & 4K' , highlight:true},
    { id: 'semestral', name: 'Semestral', price: 75.00, duration: '6 meses', desc: '2 telas simultâneas' },
    { id: 'anual', name: 'Anual', price: 150.00, duration: '12 meses', desc: 'Melhor custo-benefício' }
  ],
  testimonials: [
    { name: 'João M.', text: 'Nunca mais tive travamentos. Experiência top de linha.' },
    { name: 'Maria S.', text: 'Filmes e séries sempre atualizados. Vale cada centavo.' },
    { name: 'Lucas R.', text: 'Atendimento rápido no Whats. Recomendo!' }
  ],
  coupons: [
    // exemplo: {code:'LIPINHO',percent:10,uses:0,expires:'2025-12-31'}
  ],
  promotions: [
    // exemplo: {id:'promo1',planId:'trimestral',percent:15,endsAt:'2025-09-25T23:59'}
  ]
};

const STORAGE_KEY = 'chevette_data_v1';
function loadData(){
  const raw = localStorage.getItem(STORAGE_KEY);
  if (!raw) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(DEFAULT_DATA));
    return JSON.parse(JSON.stringify(DEFAULT_DATA));
  }
  try { return JSON.parse(raw); } catch { localStorage.setItem(STORAGE_KEY, JSON.stringify(DEFAULT_DATA)); return JSON.parse(JSON.stringify(DEFAULT_DATA));}
}
function saveData(data){ localStorage.setItem(STORAGE_KEY, JSON.stringify(data)); }

let data = loadData();
let activeCoupon = null;

// Utils
function money(v){ return 'R$ ' + Number(v).toFixed(2).replace('.',','); }
function findActivePromo(planId){
  const now = new Date();
  return data.promotions.find(p => p.planId === planId && new Date(p.endsAt) > now);
}

// Render plans
const plansContainer = document.getElementById('plans-container');
function renderPlans(){
  plansContainer.innerHTML = '';
  data.plans.forEach(plan => {
    const promo = findActivePromo(plan.id);
    const basePrice = plan.price;
    let finalPrice = basePrice;
    let promoLabel = '';
    if (promo){
      finalPrice = +(basePrice * (1 - promo.percent/100)).toFixed(2);
      promoLabel = `<div class="ribbon">Promo ${promo.percent}% OFF</div>`;
    }
    if (activeCoupon){
      // check coupon percentage
      finalPrice = +(finalPrice * (1 - activeCoupon.percent/100)).toFixed(2);
    }

    const card = document.createElement('article');
    card.className = 'card price-card' + (plan.highlight ? ' destaque' : '');
    card.innerHTML = `
      ${promoLabel}
      <h3>${plan.name}</h3>
      <div class="price"><span class="price-value">${money(finalPrice)}</span> <small>/ ${plan.duration}</small></div>
      <p class="muted">${plan.desc}</p>
      <ul class="features">
        <li>2 telas simultâneas</li>
        <li>${plan.duration}</li>
        <li>Suporte via WhatsApp</li>
      </ul>
      <div style="display:flex;gap:8px">
        <button class="btn" data-action="buy" data-id="${plan.id}">Assinar Agora</button>
        <button class="btn ghost" data-action="details" data-id="${plan.id}">Detalhes</button>
      </div>
    `;
    plansContainer.appendChild(card);
  });
}
renderPlans();

// Coupon handling
const couponInput = document.getElementById('coupon-input');
const applyBtn = document.getElementById('apply-coupon');
const clearBtn = document.getElementById('clear-coupon');

applyBtn.addEventListener('click', () => {
  const code = couponInput.value.trim().toUpperCase();
  if (!code) return alert('Digite um código de cupom');
  const coupon = data.coupons.find(c => c.code.toUpperCase() === code);
  if (!coupon) return alert('Cupom inválido');
  if (coupon.expires && new Date(coupon.expires) < new Date()) return alert('Cupom expirado');
  if (coupon.uses && coupon.uses > 0 && coupon.uses === coupon.used) return alert('Cupom atingiu o limite de uso');
  activeCoupon = coupon;
  clearBtn.style.display = 'inline-flex';
  applyBtn.style.display = 'none';
  couponInput.disabled = true;
  renderPlans();
  alert(`✅ Cupom aplicado: -${coupon.percent}%`);
});
clearBtn.addEventListener('click', () => {
  activeCoupon = null;
  couponInput.value = '';
  couponInput.disabled = false;
  clearBtn.style.display = 'none';
  applyBtn.style.display = 'inline-flex';
  renderPlans();
});

// Testimonial carousel
const testiContainer = document.getElementById('testimonials-container');
function renderTestimonials(){
  testiContainer.innerHTML = '';
  data.testimonials.forEach((t, i) => {
    const fig = document.createElement('figure');
    fig.className = 'card quote';
    fig.innerHTML = `<blockquote>“${t.text}”</blockquote><figcaption>— ${t.name}</figcaption>`;
    fig.style.opacity = (i===0?1:0);
    fig.style.transition = 'opacity .6s';
    testiContainer.appendChild(fig);
  });
}
renderTestimonials();
let testiIndex = 0;
setInterval(()=>{
  const nodes = Array.from(testiContainer.children);
  if (nodes.length < 2) return;
  nodes.forEach((n,i)=> n.style.opacity = 0);
  testiIndex = (testiIndex + 1) % nodes.length;
  nodes[testiIndex].style.opacity = 1;
},4000);

// Modal logic
const modal = document.getElementById('plan-modal');
const modalClose = document.getElementById('modal-close');
const modalCancel = document.getElementById('modal-cancel');
const modalTitle = document.getElementById('modal-plan-title');
const modalDesc = document.getElementById('modal-plan-desc');
const modalPrice = document.getElementById('modal-price');
const modalWhatsapp = document.getElementById('modal-whatsapp');

document.addEventListener('click', (e) => {
  const buy = e.target.closest('[data-action="buy"]');
  if (buy){
    const id = buy.dataset.id;
    showPlanModal(id);
  }
  const det = e.target.closest('[data-action="details"]');
  if (det){
    const id = det.dataset.id;
    const p = data.plans.find(x=>x.id===id);
    alert(`${p.name}\n\n${p.desc}\n\nPreço: ${money(p.price)}\nDuração: ${p.duration}`);
  }
});

function showPlanModal(planId){
  const plan = data.plans.find(p=>p.id===planId);
  if (!plan) return;
  modalTitle.textContent = `Assinar: ${plan.name}`;
  modalDesc.textContent = plan.desc;
  // compute price with promo + coupon
  const promo = findActivePromo(plan.id);
  let final = plan.price;
  if (promo) final = +(final * (1 - promo.percent/100)).toFixed(2);
  if (activeCoupon) final = +(final * (1 - activeCoupon.percent/100)).toFixed(2);
  modalPrice.textContent = money(final);
  // whatsapp link with prefilled message
  const text = encodeURIComponent(`Olá 👋 Quero assinar o plano *${plan.name}* por ${money(final)}. ${activeCoupon ? `Usei o cupom ${activeCoupon.code}.` : ''}`);
  modalWhatsapp.href = `https://wa.me/5575991078689?text=${text}`;
  modal.classList.add('show');
}
modalClose.addEventListener('click', ()=> modal.classList.remove('show'));
modalCancel.addEventListener('click', ()=> modal.classList.remove('show'));

// Trial timer (example, not functional tracking server-side)
const trialTimeEl = document.getElementById('trial-time');
if (trialTimeEl){
  let remaining = 4*60*60;
  trialTimeEl.textContent = new Date(remaining*1000).toISOString().substr(11,8);
  window.startTrialTimer = () => {
    if (window.trialId) return;
    window.trialId = setInterval(()=>{
      remaining = Math.max(0,remaining-1);
      trialTimeEl.textContent = new Date(remaining*1000).toISOString().substr(11,8);
      if (remaining===0) clearInterval(window.trialId);
    },1000);
  };
}

// WhatsApp CTA links
document.getElementById('contact-whatsapp').href = 'https://wa.me/5575991078689';
document.getElementById('trial-whatsapp').href = 'https://wa.me/5575991078689?text=Ol%C3%A1%20Quero%20um%20teste%20gr%C3%A1tis%20de%204h';

// Promotion countdown updater
function updatePromoBadges(){
  const now = new Date();
  data.promotions.forEach(p => {
    const ends = new Date(p.endsAt);
    if (ends <= now) {
      // expired -> nothing to do besides re-render plans
    }
  });
  renderPlans();
}
setInterval(updatePromoBadges, 10000);
