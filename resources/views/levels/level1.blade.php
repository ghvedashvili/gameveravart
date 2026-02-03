@extends('layouts.app')

@section('content')

@if($userLevel == $level)

<div class="container">

<style>
/* ---------- Card ---------- */
.nickname-box {
    max-width: 560px;
    margin: 60px auto;
    padding: 30px;
    border-radius: 18px;
    background: linear-gradient(135deg, #f8f9fa, #ffffff);
    box-shadow: 0 15px 40px rgba(0,0,0,.08);
}

/* ---------- Title ---------- */
.nickname-title {
    font-family: 'Courier New', monospace;
    letter-spacing: 2px;
}

/* ---------- TEXTAREA ---------- */
.nickname-textarea {
    font-family: 'Courier New', monospace;
    font-size: 1.15rem;
    letter-spacing: 3px;
    text-align: center;
    border-radius: 14px;
    border: 2px solid #dee2e6;
    padding: 14px;
    resize: none;
    min-height: 90px;
}

.nickname-textarea:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 .25rem rgba(13,110,253,.2);
}

/* ---------- RULES WRAPPER ---------- */
.rules-wrapper {
    margin-top: 25px;
    max-height: 260px;           /* ⬅️ არ გაცდება */
    overflow-y: auto;            /* ⬅️ scroll */
    padding-right: 6px;
}

/* Scrollbar (nice touch) */
.rules-wrapper::-webkit-scrollbar {
    width: 6px;
}
.rules-wrapper::-webkit-scrollbar-thumb {
    background: #adb5bd;
    border-radius: 10px;
}
.rules-wrapper::-webkit-scrollbar-track {
    background: transparent;
}

/* ---------- RULE ---------- */
.rule-alert {
    font-family: 'Courier New', monospace;
    letter-spacing: 2px;
    user-select: none;
}

/* ---------- CAPTCHA STYLE ---------- */
/* ---------- CAPTCHA RULE ---------- */
.rule-captcha {
    position: relative;
    overflow: hidden;

    background:
        linear-gradient(120deg, #f5f5f5, #e9ecef);
    border: 1px dashed #adb5bd;

    font-family: 'Courier New', monospace;
    font-weight: 700;
    letter-spacing: 3px;

    color: #212529;
    text-shadow:
        1px 1px 0 rgba(0,0,0,.25),
       -1px -1px 0 rgba(255,255,255,.6);

    transform: skewX(-6deg);
}

/* ტექსტის დამატებითი დამახინჯება */
.rule-captcha span.captcha-text {
    display: inline-block;
    transform: rotate(-2deg) skewY(-4deg);
}

/* Noise ხაზები */
.rule-captcha::before,
.rule-captcha::after {
    content: '';
    position: absolute;
    inset: 0;
    pointer-events: none;
}

.rule-captcha::before {
    background: repeating-linear-gradient(
        45deg,
        rgba(0,0,0,0.08) 0px,
        rgba(0,0,0,0.08) 1px,
        transparent 1px,
        transparent 4px
    );
    opacity: .7;
}

.rule-captcha::after {
    background: repeating-linear-gradient(
        -30deg,
        rgba(0,0,0,0.05) 0px,
        rgba(0,0,0,0.05) 2px,
        transparent 2px,
        transparent 6px
    );
    opacity: .6;
}


/* ---------- ICON ---------- */
.rule-icon {
    font-size: 1.2rem;
    margin-right: 10px;
}
</style>

<div class="nickname-box">

    <h4 class="text-center mb-4 nickname-title text-muted">
        🔐 ENTER YOUR NICKNAME
    </h4>

    <!-- TEXTAREA -->
    <textarea
        id="nickname"
        name="nickname-{{ uniqid() }}"
        class="form-control nickname-textarea mb-4"
        placeholder="TYPE YOUR NICKNAME HERE"
        autocomplete="new-password"
        autocorrect="off"
        autocapitalize="off"
        spellcheck="false"
    ></textarea>

    <!-- RULES -->
    <div id="rules" class="rules-wrapper"></div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const input = document.getElementById('nickname');
const rulesEl = document.getElementById('rules');

let allRules = [];
let activeRuleIds = [];
let completedRuleIds = new Set();
let gameWon = false;
let isSubmitting = false;

/* ---- BLOCK COPY / PASTE ---- */
['copy','paste','cut','drop','contextmenu'].forEach(evt=>{
    input.addEventListener(evt,e=>e.preventDefault());
});
input.addEventListener('keydown',e=>{
    if((e.ctrlKey||e.metaKey)&&['v','c','x'].includes(e.key.toLowerCase())){
        e.preventDefault();
    }
});

/* ---- FETCH RULES ---- */
async function fetchRules(){
    try{
        const res = await fetch("{{ url('/level/'.$level.'/nickname/live') }}",{
            method:'POST',
            headers:{
                'Content-Type':'application/json',
                'Accept':'application/json',
                'X-CSRF-TOKEN':"{{ csrf_token() }}"
            },
            body:JSON.stringify({nickname:input.value})
        });
        if(!res.ok) return;
        const data = await res.json();
        if(data.locked) return;

        allRules = data.rules;

        if(activeRuleIds.length===0 && allRules.length>0){
            activeRuleIds.push(allRules[0].id);
        }
    }catch(e){
        console.error(e);
    }
}

function isRulePassed(rule){ return !!rule.passed; }

/* ---- CHECK RULES ---- */
function checkRules(){
    allRules.forEach(rule=>{
        rule.passed ? completedRuleIds.add(rule.id) : completedRuleIds.delete(rule.id);
    });

    const allActivePassed = activeRuleIds.every(id=>{
        const rule = allRules.find(r=>r.id===id);
        return rule && rule.passed;
    });

    if(allActivePassed){
        const next = allRules.find(r=>!activeRuleIds.includes(r.id)&&!r.passed);
        if(next) activeRuleIds.push(next.id);
    }

    renderRules();

    const allPassed = allRules.length && allRules.every(r=>r.passed);
    if(allPassed && !gameWon && !isSubmitting && input.value.trim()!==''){
        gameWon = true;
        submitNickname();
    }
}

/* ---- SUBMIT ---- */
async function submitNickname(){
    if(isSubmitting) return;
    isSubmitting = true;

    try{
        const res = await fetch("{{ url('/level/'.$level.'/nickname/submit') }}",{
            method:'POST',
            headers:{
                'Content-Type':'application/json',
                'Accept':'application/json',
                'X-CSRF-TOKEN':"{{ csrf_token() }}"
            },
            body:JSON.stringify({nickname:input.value})
        });
        const data = await res.json();

        if(data.status==='success'){
            Swal.fire({
                title:'🎉 Nickname accepted!',
                html:`<b>${data.nickname}</b>`,
                confirmButtonText:'NEXT LEVEL',
                allowOutsideClick:false
            }).then(()=>window.location.href=`/levels/${data.newLevel}`);
        }else{
            gameWon = false;
            allRules = data.rules || allRules;
            checkRules();
        }
    }catch(e){
        gameWon = false;
    }finally{
        isSubmitting = false;
    }
}

/* ---- RENDER RULES ---- */
function renderRules(){
    rulesEl.innerHTML = '';

    const pending = [];
    const passed = [];

    activeRuleIds.forEach(id=>{
        const rule = allRules.find(r=>r.id===id);
        if(!rule) return;
        rule.passed ? passed.push(rule) : pending.push(rule);
    });

    pending.forEach(rule=>{
        const div = document.createElement('div');
        div.className = 'alert alert-danger rule-alert d-flex align-items-center';
        if(rule.id===999) div.classList.add('rule-captcha');
       div.innerHTML = `
    <span class="rule-icon">❌</span>
    <span class="captcha-text">${rule.text}</span>
`;

        rulesEl.appendChild(div);
    });

    passed.forEach(rule=>{
        const div = document.createElement('div');
        div.className = 'alert alert-success rule-alert d-flex align-items-center';
        if(rule.id===999) div.classList.add('rule-captcha');
       div.innerHTML = `
    <span class="rule-icon">✅</span>
    <span class="captcha-text">${rule.text}</span>
`;
        rulesEl.appendChild(div);
    });
}

/* ---- EVENTS ---- */
input.addEventListener('input',async()=>{
    await fetchRules();
    checkRules();
});

fetchRules();
</script>

@else
<div class="container mt-5">
    <div class="alert alert-success text-center">
        ✅ <b>{{ $nickname }}</b>
    </div>
</div>
@endif

@endsection
