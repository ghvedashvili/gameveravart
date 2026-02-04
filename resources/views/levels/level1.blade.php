@extends('layouts.app')

@section('content')
@if($userLevel == $level)

<div class="container" style="max-width:600px; margin-top:5px;">
    <h2 class="text-center mb-4" style="color:#2c3e50;">Nickname for Veravart</h2>

    <div style="background:#fff; border-radius:15px; box-shadow:0 4px 20px rgba(0,0,0,0.1); padding:20px;">
        
        <!-- Nickname Input -->
        <div style="position:relative; margin-bottom:20px;">
            <textarea id="nicknameInput" placeholder="შეიყვანეთ Nickname" rows="3"
                style="width:100%; padding:15px; font-size:1.2rem; border:3px solid #3498db; border-radius:10px; outline:none; resize:vertical; min-height:80px;"></textarea>
           <div id="charCounter" style="position:absolute; bottom:10px; right:15px; font-size:0.9rem; color:#7f8c8d; background:rgba(255,255,255,0.8); padding:2px 8px; border-radius:10px; opacity:0; transition:all 0.3s; display:none;">0/35</div>
        </div>

        <!-- Rules container -->
        <div id="rulesContainer" style="min-height:60px; margin-bottom:20px;"></div>

        <!-- Copy Section -->
        <div id="copySection" style="display:none; flex-direction:row; align-items:center; gap:10px; margin-top:10px;">
            <button id="copyBtn" style="background:#3498db; color:#fff; border:none; padding:10px 20px; border-radius:8px; cursor:pointer; transition:0.3s; display:flex; align-items:center; gap:5px;">
                📋 კოპირება
            </button>
            <div id="copyFeedback" style="color:#27ae60; font-size:0.9rem; font-weight:bold; opacity:0; transition:0.3s;">დაკოპირებულია!</div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const nicknameInput = document.getElementById('nicknameInput');
const rulesContainer = document.getElementById('rulesContainer');
const charCounter = document.getElementById('charCounter');
const copySection = document.getElementById('copySection');
const copyBtn = document.getElementById('copyBtn');
const copyFeedback = document.getElementById('copyFeedback');

let allRules = [];
let activeRuleIds = [];
let completedRuleIds = new Set();
let gameWon = false;
let isSubmitting = false;

// BLOCK COPY/PASTE
['copy','paste','cut','contextmenu','drop'].forEach(evt=>{
    nicknameInput.addEventListener(evt,e=>e.preventDefault());
});
nicknameInput.addEventListener('keydown', e=>{
    if((e.ctrlKey||e.metaKey)&&['v','c','x'].includes(e.key.toLowerCase())) e.preventDefault();
});

// FETCH rules from backend
async function fetchRules() {
    try {
        const res = await fetch(`/level/{{ $level }}/nickname/live`, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute('content')
            },
            body: JSON.stringify({
                nickname: nicknameInput.value
            })
        });

        if (!res.ok) {
            console.warn('fetchRules failed:', res.status);
            return;
        }

        const data = await res.json();

        if (data.locked) return;

        allRules = data.rules || [];

        if (activeRuleIds.length === 0 && allRules.length > 0) {
            activeRuleIds.push(allRules[0].id);
        }

    } catch (err) {
        console.error('fetchRules error:', err);
    }
}


// CHECK rules
function checkRules(){
    allRules.forEach(r=>r.passed?completedRuleIds.add(r.id):completedRuleIds.delete(r.id));

    const allActivePassed = activeRuleIds.every(id=>{
        const r = allRules.find(x=>x.id===id);
        return r && r.passed;
    });

    if(allActivePassed){
        const next = allRules.find(r=>!activeRuleIds.includes(r.id) && !r.passed);
        if(next) activeRuleIds.push(next.id);
    }

    renderRules();

    const allPassed = allRules.length && allRules.every(r=>r.passed);
    if(allPassed && !gameWon && !isSubmitting && nicknameInput.value.trim()!==''){
        gameWon = true;
        submitNickname();
    }
}

// SUBMIT
async function submitNickname() {
    if (isSubmitting) return;
    isSubmitting = true;

    try {
        const res = await fetch(`/level/{{ $level }}/nickname/submit`, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute('content')
            },
            body: JSON.stringify({
                nickname: nicknameInput.value
            })
        });

        if (!res.ok) {
            throw new Error('Submit failed');
        }

        const data = await res.json();

        if (data.status === 'success') {
            Swal.fire({
                title: '🎉 Nickname accepted!',
                html: `<b>${data.nickname}</b>`,
                confirmButtonText: 'NEXT LEVEL',
                allowOutsideClick: false
            }).then(() => {
                window.location.href = `/levels/${data.newLevel}`;
            });
        } else {
            gameWon = false;
            allRules = data.rules || allRules;
            checkRules();
        }

    } catch (err) {
        console.error('submitNickname error:', err);
        gameWon = false;
    } finally {
        isSubmitting = false;
    }
}


// RENDER rules with left accent
function renderRules(){
    rulesContainer.innerHTML='';
    const pending = [];
    const passed = [];
    activeRuleIds.forEach(id=>{
        const r = allRules.find(x=>x.id===id);
        if(!r) return;
        r.passed ? passed.push(r) : pending.push(r);
    });

    pending.forEach(r=>{
        const div = document.createElement('div');
        div.style.background="#ffeaea";
        div.style.color="#e74c3c";
        div.style.padding="10px 15px";
        div.style.borderRadius="8px";
        div.style.margin="5px 0";
        div.style.fontWeight="bold";
        div.style.position="relative";
        div.style.borderLeft="6px solid #c0392b"; // მარცხენა ხაზის აქცენტი
        div.textContent=r.text;
        rulesContainer.appendChild(div);
    });

    passed.forEach(r=>{
        const div = document.createElement('div');
        div.style.background="#d4f8e8";
        div.style.color="#27ae60";
        div.style.padding="10px 15px";
        div.style.borderRadius="8px";
        div.style.margin="5px 0";
        div.style.fontWeight="bold";
        div.style.position="relative";
        div.style.borderLeft="6px solid #2ecc71"; // მარცხენა ხაზის აქცენტი
        div.textContent=r.text;
        rulesContainer.appendChild(div);
    });

    // if(allRules.every(r=>r.passed)){
    //     gameWon=true;
    //     nicknameInput.disabled=true;
    //     copySection.style.display='flex';
    //     const winMessage=document.createElement('div');
    //     winMessage.textContent="🎉 გილოცავთ! წარმატებით შექმენით Nickname!";
    //     winMessage.style.background="#d4f8e8";
    //     winMessage.style.color="#27ae60";
    //     winMessage.style.padding="15px";
    //     winMessage.style.borderRadius="8px";
    //     winMessage.style.textAlign="center";
    //     winMessage.style.marginTop="10px";
    //     rulesContainer.appendChild(winMessage);
    // }
}

// CHARACTER COUNTER
// INIT - დამალე თავიდანვე
charCounter.style.display = 'none';
charCounter.style.opacity = '0';

let counterActivated = false;

// CHARACTER COUNTER
charCounter.style.display = 'none';
charCounter.style.opacity = '0';

// CHARACTER COUNTER
// INIT - დამალე თავიდანვე
charCounter.style.display = 'none';
charCounter.style.opacity = '0';

nicknameInput.addEventListener('input', async ()=>{
    await fetchRules();
    checkRules();
    const len = nicknameInput.value.length;
    
    // იპოვე წესი 12 allRules-იდან
    const rule12 = allRules.find(r => r.id === 12);
    
    // შეამოწმე: წესი 12 უნდა არსებობდეს და იყოს აქტიურ ლისტში
    const hasRule12 = rule12 && activeRuleIds.includes(12);
    
    if (hasRule12) {
        charCounter.textContent = `${len}/35`;
        if (charCounter.style.display === 'none') {
            charCounter.style.display = 'block';
            setTimeout(() => { charCounter.style.opacity = '1'; }, 10);
        }
    } else if (charCounter.style.display !== 'none') {
        // თუ წესი 12 არ არის აქტიური, მაგრამ counter ჩანს, დამალე
        charCounter.style.opacity = '0';
        setTimeout(() => { charCounter.style.display = 'none'; }, 300); // დაელოდე transition-ს
    }
});
// COPY FUNCTION
copyBtn.addEventListener('click', ()=>{
    navigator.clipboard.writeText(nicknameInput.value).then(()=>{
        copyFeedback.style.opacity='1';
        setTimeout(()=>{ copyFeedback.style.opacity='0'; },2000);
    });
});
fetchRules().then(() => {
    // შევამოწმოთ წესები ცარიელი ველით
    checkRules();
});
// fetchRules();
</script>

@else
<div class="container mt-5">
    <div class="alert alert-success text-center">
        ✅ <b>{{ $nickname }}</b>
    </div>
</div>
@endif
@endsection
