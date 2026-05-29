@if($userLevel == $level)

@push('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&family=Rajdhani:wght@400;600;700&display=swap" rel="stylesheet">
@endpush

<style>
  html, body {
    margin: 0;
    padding: 0;
    height: 100%;
    overflow: hidden;
    background: #0a0a0a;
  }

  .f1-machine-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 1.5rem;
    background: #0a0a0a;
    font-family: 'Rajdhani', sans-serif;
    position: fixed;
    inset: 0;
    overflow: hidden;
    box-sizing: border-box;
  }

  /* Carbon fiber background */
  .f1-machine-wrapper::before {
    content: '';
    position: fixed;
    inset: 0;
    background-image:
      repeating-linear-gradient(45deg, rgba(255,255,255,0.018) 0px, rgba(255,255,255,0.018) 1px, transparent 1px, transparent 8px),
      repeating-linear-gradient(-45deg, rgba(255,255,255,0.018) 0px, rgba(255,255,255,0.018) 1px, transparent 1px, transparent 8px);
    z-index: 0;
    pointer-events: none;
  }

  .f1-title {
    font-family: 'Orbitron', monospace;
    font-size: clamp(0.9rem, 3.5vmin, 1.8rem);
    font-weight: 900;
    color: #e10600;
    letter-spacing: 0.3em;
    text-transform: uppercase;
    margin-bottom: 0.2rem;
    position: relative;
    z-index: 1;
    text-shadow: 0 0 20px rgba(225, 6, 0, 0.5);
  }

  .f1-subtitle {
    font-size: clamp(0.55rem, 1.5vmin, 0.85rem);
    color: #555;
    letter-spacing: 0.25em;
    text-transform: uppercase;
    margin-bottom: 1.2rem;
    position: relative;
    z-index: 1;
  }

  /* ===== WHEELS CONTAINER ===== */
  .machine__numbers {
    display: flex;
    gap: 1.8rem;
    position: relative;
    z-index: 1;
  }

  /* ===== SINGLE WHEEL ===== */
  .wheel-wrap {
    position: relative;
    width: clamp(60px, 18vmin, 130px);
    height: clamp(60px, 18vmin, 130px);
  }

  /* The spinning tire disc — always dark metal, rubber ring uses team color */
  .tire {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    position: relative;

    /* Disc: always dark metal, never changes */
    background:
      radial-gradient(circle at 35% 30%, rgba(255,255,255,0.10) 0%, transparent 50%),
      radial-gradient(circle at 50% 50%, #1c1c1e 0%, #0c0c0c 100%);

    /* Rubber ring sidewall — the outer rings use team color via CSS variable */
    box-shadow:
      0 0 0 3px #0a0a0a,
      0 0 0 8px var(--rubber-color, #2a2a2a),
      0 0 0 11px var(--rubber-color-dark, #1a1a1a),
      0 0 0 14px #080808,
      0 0 0 17px var(--rubber-color, #2a2a2a),
      0 0 0 19px #050505,
      0 0 22px rgba(0,0,0,0.9),
      inset 0 0 0 1px rgba(255,255,255,0.06);

    animation: none;
    will-change: transform;
    transition: box-shadow 0.4s ease;
  }

  /* 5-spoke alloy pattern */
  .tire::before {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: 50%;
    background:
      repeating-conic-gradient(
        rgba(255,255,255,0.06) 0deg 8deg,
        rgba(0,0,0,0.55)      8deg 36deg,
        rgba(255,255,255,0.06) 36deg 44deg,
        rgba(0,0,0,0.55)      44deg 72deg
      );
  }

  /* Centre hub — polished cap */
  .tire::after {
    content: '';
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    width: 28%;
    height: 28%;
    border-radius: 50%;
    background:
      radial-gradient(circle at 40% 35%, #555 0%, #2a2a2a 45%, #111 100%);
    box-shadow:
      0 0 0 2px #333,
      0 0 0 4px #111,
      0 2px 8px rgba(0,0,0,0.9);
  }

  .tire-letter {
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    font-family: 'Orbitron', monospace;
    font-size: clamp(1.2rem, 6vmin, 3.5rem);
    font-weight: 900;
    color: #ffffff;
    text-shadow:
      0 0 16px rgba(255,255,255,0.5),
      -2px -2px 0 #000,
       2px -2px 0 #000,
      -2px  2px 0 #000,
       2px  2px 0 #000,
       0 2px 8px rgba(0,0,0,1);
    z-index: 10;
    pointer-events: none;
    will-change: transform;
    line-height: 1;
  }

  /* ===== SPIN KEYFRAMES ===== */
  @keyframes spinWheel {
    from { transform: rotate(var(--start-rot)); }
    to   { transform: rotate(var(--end-rot)); }
  }

  /* ===== CHECKERED GO BUTTON ===== */
  #goBtn {
    position: relative;
    margin-top: 1.2rem;
    padding: 0;
    font-family: 'Orbitron', monospace;
    font-size: clamp(2rem, 8vmin, 4rem);
    font-weight: 900;
    letter-spacing: 0.25em;
    text-transform: uppercase;
    cursor: pointer;
    border: none;
    background: transparent;
    z-index: 1;
    transition: transform 0.1s, filter 0.2s;
    /* Checkered pattern clipped into the text */
    background-image: repeating-conic-gradient(#ffffff 0% 25%, #111111 0% 50%);
    background-size: 18px 18px;
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    color: transparent;
    filter: drop-shadow(0 0 8px rgba(255,255,255,0.25));
  }

  #goBtn:hover {
    transform: translateY(-3px) scale(1.06);
    filter: drop-shadow(0 0 18px rgba(225,6,0,0.8));
  }
  #goBtn:active { transform: translateY(0) scale(0.97); }
  #goBtn:disabled { opacity: 0.35; cursor: not-allowed; transform: none; filter: none; }

  /* ===== WIN MESSAGE ===== */
  .win-message {
    font-family: 'Orbitron', monospace;
    font-size: clamp(0.7rem, 2vmin, 1rem);
    font-weight: 700;
    letter-spacing: 0.15em;
    color: #e10600;
    margin-top: 0.6rem;
    min-height: 1.5rem;
    text-shadow: 0 0 15px rgba(225,6,0,0.7);
    position: relative;
    z-index: 1;
  }

  /* ===== FORM ===== */
  .f1-form {
    margin-top: 0.8rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 300px;
  }

  .f1-form .form-control {
    width: 100%;
    background: #111;
    border: 1px solid #2a2a2a;
    border-bottom: 2px solid #e10600;
    color: #fff;
    font-family: 'Orbitron', monospace;
    font-size: 0.85rem;
    padding: 0.6rem 1rem;
    border-radius: 3px;
    outline: none;
    letter-spacing: 0.1em;
    transition: border-color 0.2s, box-shadow 0.2s;
  }
  .f1-form .form-control::placeholder { color: #444; }
  .f1-form .form-control:focus {
    border-color: #e10600;
    box-shadow: 0 0 12px rgba(225,6,0,0.25);
  }

  .f1-form .btn-primary {
    background: #e10600;
    color: #fff;
    font-family: 'Orbitron', monospace;
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    padding: 0.6rem 2.5rem;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    transition: background 0.2s, box-shadow 0.2s, transform 0.1s;
    box-shadow: 0 4px 15px rgba(225,6,0,0.3);
  }
  .f1-form .btn-primary:hover {
    background: #ff1a0e;
    box-shadow: 0 6px 25px rgba(225,6,0,0.5);
    transform: translateY(-1px);
  }
  .f1-form .btn-primary:active { transform: translateY(0); }

  /* Speed lines */
  .speed-lines {
    position: fixed;
    inset: 0;
    pointer-events: none;
    z-index: 0;
    overflow: hidden;
  }
  .speed-lines::before {
    content: '';
    position: absolute;
    top: -50%; left: -10%;
    width: 120%; height: 200%;
    background: repeating-linear-gradient(
      100deg,
      transparent 0px, transparent 40px,
      rgba(225,6,0,0.025) 40px, rgba(225,6,0,0.025) 41px
    );
    transform: skewY(-5deg);
  }

  .sr-only {
    position: absolute; width: 1px; height: 1px;
    padding: 0; margin: -1px; overflow: hidden;
    clip: rect(0,0,0,0); white-space: nowrap; border-width: 0;
  }
</style>

<div class="f1-machine-wrapper">
  <div class="speed-lines"></div>

  <div class="f1-title">Formula 1</div>
  <div class="f1-subtitle">Driver Code Identifier</div>

  <div aria-hidden="true" class="machine__numbers" id="wheelsContainer">
    <!-- wheels injected by JS -->
  </div>

  <p aria-live="polite" class="sr-only" id="srResult">AAA</p>

  <button id="goBtn">GO!</button>
  <div class="win-message" id="win-message">დააჭირეთ "GO!"</div>

  <form id="answerForm" class="f1-form">
    @csrf
    <input type="text" class="form-control mb-2" id="answer" placeholder="შეიყვანეთ პასუხი...">
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
</div>

<script>
(function () {
  // 2025 F1 driver → team rubber ring color (single color per team)
  const driverRubberColor = {
    // McLaren: papaya orange
    "PIA": "#FF8000",
    "NOR": "#FF8000",
    // Ferrari: red
    "LEC": "#E8002D",
    "HAM": "#E8002D",
    // Red Bull: dark blue
    "VER": "#3671C6",
    "LAW": "#3671C6",
    // Mercedes: teal
    "RUS": "#27F4D2",
    "ANT": "#27F4D2",
    // Aston Martin: green
    "ALO": "#229971",
    "STR": "#229971",
    // Alpine: pink
    "GAS": "#FF87BC",
    "DOO": "#FF87BC",
    // Haas: red
    "OCO": "#E8002D",
    "BEA": "#E8002D",
    // Racing Bulls: blue
    "TSU": "#6692FF",
    "HAD": "#6692FF",
    // Williams: blue
    "ALB": "#64C4FF",
    "SAI": "#64C4FF",
    // Sauber: lime green
    "HUL": "#52E252",
    "BOR": "#52E252",
  };

  function hexToRgba(hex, alpha) {
    const r = parseInt(hex.slice(1,3),16);
    const g = parseInt(hex.slice(3,5),16);
    const b = parseInt(hex.slice(5,7),16);
    return `rgba(${r},${g},${b},${alpha})`;
  }

  // Darken a hex color for the inner ring band
  function darken(hex, amount = 0.5) {
    const r = Math.round(parseInt(hex.slice(1,3),16) * amount);
    const g = Math.round(parseInt(hex.slice(3,5),16) * amount);
    const b = Math.round(parseInt(hex.slice(5,7),16) * amount);
    return `rgb(${r},${g},${b})`;
  }

  // Apply team color only to the rubber ring (box-shadow), disc stays dark metal
  function applyRubberColor(tireEl, color) {
    tireEl.style.setProperty('--rubber-color', color);
    tireEl.style.setProperty('--rubber-color-dark', darken(color, 0.55));
  }

  const f1Codes = [
    "PIA","NOR","LEC","HAM","VER","LAW","RUS","ANT","ALO","STR",
    "GAS","DOO","OCO","BEA","TSU","HAD","ALB","SAI","HUL","BOR"
  ];

  const container  = document.getElementById('wheelsContainer');
  const goBtn      = document.getElementById('goBtn');
  const srResult   = document.getElementById('srResult');
  const winMessage = document.getElementById('win-message');

  // Build 3 wheels
  const wheels = [];
  for (let i = 0; i < 3; i++) {
    const wrap = document.createElement('div');
    wrap.className = 'wheel-wrap';

    const tire = document.createElement('div');
    tire.className = 'tire';

    const letter = document.createElement('div');
    letter.className = 'tire-letter';
    letter.textContent = 'A';

    wrap.appendChild(tire);
    wrap.appendChild(letter);
    container.appendChild(wrap);

    wheels.push({ tire, letter, currentRot: 0 });
  }

  let codeIndex = 0;

  function spinTo(wheelObj, targetLetter, totalSpins, duration, onDone) {
    const degPerLetter = 360 / 26;
    const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const targetIdx = letters.indexOf(targetLetter);
    const targetDeg = targetIdx * degPerLetter;

    const currentMod = ((wheelObj.currentRot % 360) + 360) % 360;
    let diff = targetDeg - currentMod;
    if (diff < 0) diff += 360;

    const endRot = wheelObj.currentRot + totalSpins * 360 + diff;
    const startRot = wheelObj.currentRot;
    const startTime = performance.now();

    function easeOutBack(t) {
      const c1 = 1.70158, c3 = c1 + 1;
      return 1 + c3 * Math.pow(t - 1, 3) + c1 * Math.pow(t - 1, 2);
    }

    function frame(now) {
      const elapsed = now - startTime;
      let t = Math.min(elapsed / duration, 1);
      const rot = startRot + (endRot - startRot) * easeOutBack(t);
      wheelObj.currentRot = rot;
      wheelObj.tire.style.transform = `rotate(${rot}deg)`;
      wheelObj.letter.style.transform = `translate(-50%, -50%) rotate(${-rot}deg)`;
      if (t < 1) {
        requestAnimationFrame(frame);
      } else {
        wheelObj.currentRot = endRot;
        wheelObj.tire.style.transform = `rotate(${endRot}deg)`;
        wheelObj.letter.style.transform = `translate(-50%, -50%) rotate(${-endRot}deg)`;
        if (onDone) onDone();
      }
    }
    requestAnimationFrame(frame);
  }

  goBtn.addEventListener('click', () => {
    goBtn.disabled = true;
    winMessage.textContent = '';

    const code = f1Codes[codeIndex];
    codeIndex = (codeIndex + 1) % f1Codes.length;

    // Single team color for all 3 rubber rings
    const teamColor = driverRubberColor[code] || '#2a2a2a';

    let finished = 0;

    wheels.forEach((w, i) => {
      const ch = code[i];
      w.letter.textContent = ch;

      // Apply team color to rubber ring only — disc stays dark
      applyRubberColor(w.tire, teamColor);

      const spins = 8 + i * 2;
      const duration = 1000 + i * 150;

      spinTo(w, ch, spins, duration, () => {
        finished++;
        if (finished === 3) {
          srResult.textContent = code;
          goBtn.disabled = false;
        }
      });
    });
  });
})();
</script>

@else
  @include('levels.levelcomplete', ['level' => $level, 'userLevel' => auth()->user()->level])
@endif