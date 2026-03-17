<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Thank You — Defense Completed!</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=Cormorant+Garamond:wght@300;400;600&display=swap"
        rel="stylesheet" />
    <style>
        :root {
            --gold: #ffd700;
            --deep-gold: #b8860b;
            --navy: #0a0e2a;
            --midnight: #060916;
            --white: #fffdf5;
            --cream: #f5edd6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: var(--midnight);
            min-height: 100vh;
            overflow-x: hidden;
            font-family: "Cormorant Garamond", serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        /* ── Starfield ── */
        #stars {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
        }

        .star {
            position: absolute;
            border-radius: 50%;
            background: white;
            animation: twinkle var(--d, 3s) ease-in-out infinite var(--delay, 0s);
        }

        @keyframes twinkle {

            0%,
            100% {
                opacity: 0.1;
                transform: scale(1);
            }

            50% {
                opacity: 1;
                transform: scale(1.4);
            }
        }

        /* ── Canvas layers ── */
        #confettiCanvas,
        #fireworksCanvas {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 10;
        }

        /* ── Grad Hats ── */
        .hat {
            position: fixed;
            font-size: clamp(1.5rem, 4vw, 3rem);
            pointer-events: none;
            z-index: 9;
            animation: floatHat var(--duration) ease-in-out infinite var(--delay);
            filter: drop-shadow(0 0 8px rgba(255, 215, 0, 0.6));
        }

        @keyframes floatHat {
            0% {
                transform: translateY(110vh) rotate(var(--rot-start));
                opacity: 0;
            }

            10% {
                opacity: 1;
            }

            90% {
                opacity: 1;
            }

            100% {
                transform: translateY(-15vh) rotate(var(--rot-end));
                opacity: 0;
            }
        }

        /* ── Hero card ── */
        .hero {
            position: relative;
            z-index: 20;
            text-align: center;
            padding: 3.5rem 3rem;
            max-width: 700px;
            width: 90%;
            background: linear-gradient(135deg,
                    rgba(10, 14, 42, 0.85) 0%,
                    rgba(15, 20, 55, 0.75) 100%);
            border: 1px solid rgba(255, 215, 0, 0.25);
            border-radius: 4px;
            backdrop-filter: blur(12px);
            box-shadow:
                0 0 60px rgba(255, 215, 0, 0.08),
                0 0 120px rgba(255, 215, 0, 0.04),
                inset 0 1px 0 rgba(255, 215, 0, 0.15);
            animation: fadeUp 1.2s cubic-bezier(0.22, 1, 0.36, 1) both 0.5s;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(60px) scale(0.96);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* corner ornaments */
        .hero::before,
        .hero::after {
            content: "";
            position: absolute;
            width: 40px;
            height: 40px;
            border-color: var(--gold);
            border-style: solid;
            opacity: 0.5;
        }

        .hero::before {
            top: 12px;
            left: 12px;
            border-width: 2px 0 0 2px;
        }

        .hero::after {
            bottom: 12px;
            right: 12px;
            border-width: 0 2px 2px 0;
        }

        .cap-emoji {
            font-size: clamp(3rem, 8vw, 5.5rem);
            display: block;
            animation: capBounce 1s cubic-bezier(0.34, 1.56, 0.64, 1) both 1.2s;
            filter: drop-shadow(0 0 20px rgba(255, 215, 0, 0.8));
            margin-bottom: 0.5rem;
        }

        @keyframes capBounce {
            from {
                opacity: 0;
                transform: scale(0) rotate(-20deg);
            }

            to {
                opacity: 1;
                transform: scale(1) rotate(0deg);
            }
        }

        .label {
            font-family: "Cormorant Garamond", serif;
            font-weight: 300;
            font-size: clamp(0.75rem, 2vw, 0.95rem);
            letter-spacing: 0.4em;
            text-transform: uppercase;
            color: var(--gold);
            opacity: 0.75;
            margin-bottom: 0.75rem;
            animation: fadeIn 1s both 1.6s;
        }

        h1 {
            font-family: "Playfair Display", serif;
            font-size: clamp(2.4rem, 6vw, 4.2rem);
            font-weight: 700;
            color: var(--white);
            line-height: 1.1;
            margin-bottom: 0.4rem;
            animation: fadeIn 1s both 1.8s;
        }

        h1 span {
            font-style: italic;
            font-weight: 400;
            color: var(--gold);
            display: block;
        }

        .divider {
            width: 80px;
            height: 1px;
            background: linear-gradient(90deg,
                    transparent,
                    var(--gold),
                    transparent);
            margin: 1.5rem auto;
            animation: expand 1s both 2s;
        }

        @keyframes expand {
            from {
                width: 0;
                opacity: 0;
            }

            to {
                width: 80px;
                opacity: 1;
            }
        }

        .message {
            font-size: clamp(1rem, 2.5vw, 1.2rem);
            color: var(--cream);
            line-height: 1.8;
            font-weight: 300;
            opacity: 0.88;
            animation: fadeIn 1s both 2.2s;
        }

        .names {
            margin-top: 2rem;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.6rem;
            animation: fadeIn 1s both 2.5s;
        }

        .name-chip {
            background: rgba(255, 215, 0, 0.1);
            border: 1px solid rgba(255, 215, 0, 0.3);
            color: var(--gold);
            padding: 0.35rem 1rem;
            border-radius: 2px;
            font-size: 0.9rem;
            letter-spacing: 0.05em;
            font-family: "Cormorant Garamond", serif;
            font-weight: 600;
        }

        .year-badge {
            margin-top: 2rem;
            font-size: clamp(3rem, 10vw, 6rem);
            font-family: "Playfair Display", serif;
            font-weight: 700;
            color: transparent;
            -webkit-text-stroke: 1px rgba(255, 215, 0, 0.3);
            letter-spacing: 0.05em;
            line-height: 1;
            animation: fadeIn 1s both 2.8s;
        }

        /* music toggle */
        #musicBtn {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 50;
            background: rgba(255, 215, 0, 0.12);
            border: 1px solid rgba(255, 215, 0, 0.4);
            color: var(--gold);
            width: 52px;
            height: 52px;
            border-radius: 50%;
            font-size: 1.4rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition:
                background 0.3s,
                transform 0.2s;
            backdrop-filter: blur(8px);
        }

        #musicBtn:hover {
            background: rgba(255, 215, 0, 0.25);
            transform: scale(1.1);
        }

        /* music visualizer bars */
        #visualizer {
            position: fixed;
            bottom: 5.5rem;
            right: 2rem;
            z-index: 50;
            display: flex;
            align-items: flex-end;
            gap: 3px;
            height: 30px;
        }

        .bar {
            width: 4px;
            background: var(--gold);
            border-radius: 2px;
            opacity: 0.7;
            animation: none;
        }

        .bar.playing {
            animation: bounce-bar var(--spd) ease-in-out infinite alternate var(--bar-delay);
        }

        @keyframes bounce-bar {
            from {
                height: 4px;
            }

            to {
                height: 28px;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* glow pulse on the whole card */
        @keyframes glowPulse {

            0%,
            100% {
                box-shadow:
                    0 0 60px rgba(255, 215, 0, 0.08),
                    0 0 120px rgba(255, 215, 0, 0.04),
                    inset 0 1px 0 rgba(255, 215, 0, 0.15);
            }

            50% {
                box-shadow:
                    0 0 80px rgba(255, 215, 0, 0.18),
                    0 0 160px rgba(255, 215, 0, 0.1),
                    inset 0 1px 0 rgba(255, 215, 0, 0.3);
            }
        }

        .hero {
            animation:
                fadeUp 1.2s cubic-bezier(0.22, 1, 0.36, 1) both 0.5s,
                glowPulse 4s ease-in-out infinite 2s;
        }
    </style>
</head>

<body>
    <!-- Stars -->
    <div id="stars"></div>

    <!-- Canvas layers -->
    <canvas id="fireworksCanvas"></canvas>
    <canvas id="confettiCanvas"></canvas>

    <!-- Floating grad hats (injected by JS) -->

    <!-- Hero -->
    <div class="hero">
        <span class="cap-emoji">🎓</span>
        <h1><span> Thank You</span></h1>
        <div class="divider"></div>

        <div class="names"></div>
    </div>

    <!-- Graduation music -->
    <audio id="gradMusic" src="grad.mp3" loop></audio>

    <!-- Music button -->
    <button id="musicBtn" title="Toggle music">🎵</button>
    <div id="visualizer">
        <div class="bar" style="--spd: 0.5s; --bar-delay: 0s; height: 8px"></div>
        <div
            class="bar"
            style="--spd: 0.7s; --bar-delay: 0.1s; height: 14px"></div>
        <div
            class="bar"
            style="--spd: 0.4s; --bar-delay: 0.2s; height: 6px"></div>
        <div
            class="bar"
            style="--spd: 0.6s; --bar-delay: 0.05s; height: 18px"></div>
        <div
            class="bar"
            style="--spd: 0.5s; --bar-delay: 0.15s; height: 10px"></div>
    </div>

    <!-- Graduation music via Web Audio (Pomp & Circumstance melody synthesized) -->
    <script>
        // ─────────────────────────────────────────────
        // 1. STARS
        // ─────────────────────────────────────────────
        const starsEl = document.getElementById("stars");
        for (let i = 0; i < 180; i++) {
            const s = document.createElement("div");
            s.className = "star";
            const size = Math.random() * 2.5 + 0.5;
            s.style.cssText = `
        width:${size}px; height:${size}px;
        left:${Math.random() * 100}%;
        top:${Math.random() * 100}%;
        --d:${2 + Math.random() * 4}s;
        --delay:-${Math.random() * 5}s;
    `;
            starsEl.appendChild(s);
        }

        // ─────────────────────────────────────────────
        // 2. FLOATING HATS
        // ─────────────────────────────────────────────
        const hatEmojis = ["🎓", "🎓", "🎓", "🎓", "⭐", "✨", "🌟"];
        for (let i = 0; i < 14; i++) {
            const h = document.createElement("div");
            h.className = "hat";
            const dur = 6 + Math.random() * 8;
            const delay = Math.random() * 10;
            const rotS = Math.random() * 40 - 20 + "deg";
            const rotE = Math.random() * 40 - 20 + "deg";
            h.textContent = hatEmojis[Math.floor(Math.random() * hatEmojis.length)];
            h.style.cssText = `
        left:${Math.random() * 95}%;
        --duration:${dur}s;
        --delay:-${delay}s;
        --rot-start:${rotS};
        --rot-end:${rotE};
    `;
            document.body.appendChild(h);
        }

        // ─────────────────────────────────────────────
        // 3. CONFETTI
        // ─────────────────────────────────────────────
        const cCanvas = document.getElementById("confettiCanvas");
        const cCtx = cCanvas.getContext("2d");
        cCanvas.width = window.innerWidth;
        cCanvas.height = window.innerHeight;
        window.addEventListener("resize", () => {
            cCanvas.width = window.innerWidth;
            cCanvas.height = window.innerHeight;
        });

        const COLORS = [
            "#FFD700",
            "#FFF8DC",
            "#B8860B",
            "#FF6B6B",
            "#4FC3F7",
            "#81C784",
            "#CE93D8",
            "#FFAB91",
        ];
        const pieces = [];
        for (let i = 0; i < 160; i++) {
            pieces.push({
                x: Math.random() * window.innerWidth,
                y: Math.random() * window.innerHeight - window.innerHeight,
                w: 6 + Math.random() * 8,
                h: 3 + Math.random() * 5,
                color: COLORS[Math.floor(Math.random() * COLORS.length)],
                speed: 1.2 + Math.random() * 2.5,
                angle: Math.random() * Math.PI * 2,
                spin: (Math.random() - 0.5) * 0.12,
                drift: (Math.random() - 0.5) * 1.2,
                opacity: 0.75 + Math.random() * 0.25,
            });
        }

        function drawConfetti() {
            cCtx.clearRect(0, 0, cCanvas.width, cCanvas.height);
            pieces.forEach((p) => {
                p.y += p.speed;
                p.x += p.drift;
                p.angle += p.spin;
                if (p.y > cCanvas.height + 20) {
                    p.y = -20;
                    p.x = Math.random() * cCanvas.width;
                }
                cCtx.save();
                cCtx.globalAlpha = p.opacity;
                cCtx.translate(p.x, p.y);
                cCtx.rotate(p.angle);
                cCtx.fillStyle = p.color;
                cCtx.fillRect(-p.w / 2, -p.h / 2, p.w, p.h);
                cCtx.restore();
            });
            requestAnimationFrame(drawConfetti);
        }
        drawConfetti();

        // ─────────────────────────────────────────────
        // 4. FIREWORKS
        // ─────────────────────────────────────────────
        const fCanvas = document.getElementById("fireworksCanvas");
        const fCtx = fCanvas.getContext("2d");
        fCanvas.width = window.innerWidth;
        fCanvas.height = window.innerHeight;
        window.addEventListener("resize", () => {
            fCanvas.width = window.innerWidth;
            fCanvas.height = window.innerHeight;
        });

        const fireworks = [];
        const fParticles = [];

        const FW_COLORS = [
            ["#FFD700", "#FFF176", "#FFCA28"],
            ["#FF6B6B", "#FF8A80", "#FF1744"],
            ["#4FC3F7", "#81D4FA", "#0288D1"],
            ["#CE93D8", "#E040FB", "#AB47BC"],
            ["#A5D6A7", "#69F0AE", "#388E3C"],
            ["#FFAB91", "#FF7043", "#FF8A65"],
            ["#FFFFFF", "#FFFDE7", "#F5F5F5"],
        ];

        function spawnFirework() {
            const x = 0.15 * fCanvas.width + Math.random() * fCanvas.width * 0.7;
            const y = 0.1 * fCanvas.height + Math.random() * fCanvas.height * 0.5;
            const palette = FW_COLORS[Math.floor(Math.random() * FW_COLORS.length)];
            const count = 55 + Math.floor(Math.random() * 40);
            for (let i = 0; i < count; i++) {
                const angle = ((Math.PI * 2) / count) * i + Math.random() * 0.3;
                const speed = 2.5 + Math.random() * 5;
                fParticles.push({
                    x,
                    y,
                    vx: Math.cos(angle) * speed,
                    vy: Math.sin(angle) * speed,
                    life: 1,
                    decay: 0.012 + Math.random() * 0.014,
                    color: palette[Math.floor(Math.random() * palette.length)],
                    radius: 1.5 + Math.random() * 2.5,
                    trail: [],
                });
            }
            // starburst center flash
            fParticles.push({
                x,
                y,
                vx: 0,
                vy: 0,
                life: 1,
                decay: 0.06,
                color: "#FFFFFF",
                radius: 10,
                trail: [],
            });
        }

        function drawFireworks() {
            fCtx.fillStyle = "rgba(6,9,22,0.18)";
            fCtx.fillRect(0, 0, fCanvas.width, fCanvas.height);

            for (let i = fParticles.length - 1; i >= 0; i--) {
                const p = fParticles[i];
                p.trail.push({
                    x: p.x,
                    y: p.y,
                });
                if (p.trail.length > 5) p.trail.shift();

                p.x += p.vx;
                p.y += p.vy;
                p.vy += 0.06; // gravity
                p.vx *= 0.98;
                p.vy *= 0.98;
                p.life -= p.decay;

                // draw trail
                if (p.trail.length > 1) {
                    fCtx.beginPath();
                    fCtx.moveTo(p.trail[0].x, p.trail[0].y);
                    for (let t = 1; t < p.trail.length; t++) {
                        fCtx.lineTo(p.trail[t].x, p.trail[t].y);
                    }
                    fCtx.strokeStyle = p.color;
                    fCtx.globalAlpha = p.life * 0.3;
                    fCtx.lineWidth = p.radius * 0.5;
                    fCtx.stroke();
                }

                fCtx.beginPath();
                fCtx.arc(p.x, p.y, p.radius * p.life, 0, Math.PI * 2);
                fCtx.fillStyle = p.color;
                fCtx.globalAlpha = p.life;
                fCtx.fill();

                if (p.life <= 0) fParticles.splice(i, 1);
            }
            fCtx.globalAlpha = 1;
            requestAnimationFrame(drawFireworks);
        }
        drawFireworks();

        // Launch fireworks at intervals
        function launchBurst() {
            spawnFirework();
        }
        setTimeout(() => {
            launchBurst();
            launchBurst();
        }, 800);
        setTimeout(() => {
            launchBurst();
        }, 1800);
        setTimeout(() => {
            launchBurst();
            launchBurst();
            launchBurst();
        }, 3200);
        setInterval(() => {
            const count = 1 + Math.floor(Math.random() * 3);
            for (let i = 0; i < count; i++) setTimeout(launchBurst, i * 300);
        }, 3500);

        // ─────────────────────────────────────────────
        // 5. GRADUATION MUSIC — grad.mp3
        // ─────────────────────────────────────────────
        const music = document.getElementById("gradMusic");
        music.volume = 0.7;

        function updateMusicBtn() {
            const btn = document.getElementById("musicBtn");
            btn.textContent = music.paused ? "🎵" : "🔇";
            document.querySelectorAll(".bar").forEach((b) => {
                music.paused ?
                    b.classList.remove("playing") :
                    b.classList.add("playing");
            });
        }

        document.getElementById("musicBtn").addEventListener("click", () => {
            if (music.paused) {
                music.play();
            } else {
                music.pause();
            }
            updateMusicBtn();
        });

        // Auto-play on load (browsers may block until user interaction)
        setTimeout(() => {
            music
                .play()
                .then(() => updateMusicBtn())
                .catch(() => {
                    // blocked by browser — user can click 🎵 button to start
                });
        }, 1000);
    </script>
    <script>
        const checker = setInterval(() => {
            fetch("../../app/config/dbConnection.php?check=1")
                .then((res) => res.text())
                .then((data) => {
                    if (data.includes("Connected")) {
                        clearInterval(checker);
                        history.back();
                    }
                })
                .catch(() => {
                    // Still down, do nothing
                });
        }, 1000);
    </script>
</body>

</html>