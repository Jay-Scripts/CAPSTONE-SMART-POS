<?php
include "../../app/config/dbConnection.php";

if (isset($_GET['check_trans'])) {
    $reg_transaction_id = trim($_GET['check_trans']);
    $reg_transaction_id = htmlspecialchars($reg_transaction_id);

    if (!preg_match("/^[0-9]+$/", $reg_transaction_id)) {
        echo json_encode(['valid' => false, 'message' => 'Transaction ID must be numeric']);
        exit;
    }

    $stmt = $conn->prepare("
        SELECT COUNT(*) FROM REG_TRANSACTION 
        WHERE REG_TRANSACTION_ID = ? 
        AND REG_TRANSACTION_ID NOT IN (SELECT reg_transaction_id FROM customer_feedback)
    ");
    $stmt->execute([$reg_transaction_id]);
    $validTrans = $stmt->fetchColumn();

    if ($validTrans) {
        echo json_encode(['valid' => true]);
    } else {
        echo json_encode(['valid' => false, 'message' => 'Invalid or already submitted Transaction ID']);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    header('Content-Type: application/json');

    if (!$data) {
        echo json_encode(['success' => false, 'message' => 'No data received']);
        exit;
    }

    function sanitizeInput($value)
    {
        return htmlspecialchars(trim($value));
    }

    $reg_transaction_id = sanitizeInput($data['reg_transaction_id'] ?? '');
    if (empty($reg_transaction_id)) {
        echo json_encode(['success' => false, 'message' => 'Transaction ID is required']);
        exit;
    }

    $stmt = $conn->prepare("
        SELECT COUNT(*) FROM REG_TRANSACTION
        WHERE REG_TRANSACTION_ID = ? 
        AND REG_TRANSACTION_ID NOT IN (SELECT reg_transaction_id FROM customer_feedback)
    ");
    $stmt->execute([$reg_transaction_id]);
    if (!$stmt->fetchColumn()) {
        echo json_encode(['success' => false, 'message' => 'Invalid or already submitted Transaction ID']);
        exit;
    }

    $questions = ['q1', 'q2', 'q3', 'q4', 'q5'];
    foreach ($questions as $q) {
        if (!isset($data[$q]) || !is_numeric($data[$q]) || $data[$q] < 1 || $data[$q] > 5) {
            echo json_encode(['success' => false, 'message' => "Invalid rating for $q"]);
            exit;
        }
    }

    $feedback_text = sanitizeInput($data['feedback_text'] ?? '');
    $feedback_text = preg_replace("/[^a-zA-Z0-9 .,!?'-]/", '', $feedback_text);

    try {
        $stmt = $conn->prepare("
            INSERT INTO customer_feedback 
            (reg_transaction_id, staff_attitude, product_accuracy, cleanliness, speed_of_service, overall_satisfaction, feedback_text)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $reg_transaction_id,
            $data['q1'],
            $data['q2'],
            $data['q3'],
            $data['q4'],
            $data['q5'],
            $feedback_text
        ]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Big Brew — Feedback</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: system-ui, -apple-system, sans-serif;
        }

        .star-btn {
            font-size: 32px;
            color: #e5e7eb;
            cursor: pointer;
            transition: color .15s, transform .1s;
            line-height: 1;
            background: none;
            border: none;
            padding: 0 2px;
        }

        .star-btn:active {
            transform: scale(.88);
        }

        .star-btn.on {
            color: #f59e0b;
        }

        .q-card {
            transition: opacity .2s, border-color .2s;
        }

        .q-card.done {
            opacity: .55;
        }

        .q-card.active {
            border-color: #f59e0b !important;
            opacity: 1;
        }

        .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #d1d5db;
            transition: all .2s;
        }

        .dot.active {
            width: 18px;
            border-radius: 99px;
            background: #f59e0b;
        }

        .dot.done {
            background: #1f2937;
        }

        textarea:focus {
            outline: none;
            border-color: #f59e0b;
        }
    </style>
</head>

<body class="bg-stone-100 min-h-screen flex flex-col">

    <!-- Header -->
    <header class="bg-[#1a1a1a] px-5 py-4 flex items-center gap-3 sticky top-0 z-10">
        <div class="w-9 h-9 rounded-xl bg-amber-400 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                <path d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a2 2 0 01-2-2v-1" />
                <path d="M15 3H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l4-4h4a2 2 0 002-2V5a2 2 0 00-2-2z" />
            </svg>
        </div>
        <div>
            <p class="text-white font-semibold text-sm leading-tight">Rate your visit</p>
            <p class="text-stone-400 text-xs" id="headerSub">Big Brew Feedback</p>
        </div>
        <div class="ml-auto flex items-center gap-1.5" id="dotWrap"></div>
    </header>

    <!-- Body -->
    <main class="flex-1 px-4 py-5 max-w-lg mx-auto w-full">

        <!-- Progress -->
        <div class="flex justify-between items-center mb-2">
            <span class="text-xs font-semibold text-stone-500 uppercase tracking-wide" id="stepLabel">Question 1 of 5</span>
            <span class="text-xs font-semibold text-amber-500" id="pctLabel">0%</span>
        </div>
        <div class="h-1.5 bg-stone-200 rounded-full mb-5 overflow-hidden">
            <div id="progressFill" class="h-full bg-amber-400 rounded-full transition-all duration-500" style="width:0%"></div>
        </div>

        <!-- Question cards -->
        <div class="flex flex-col gap-3" id="cardContainer"></div>

        <!-- Optional feedback -->
        <div class="mt-4 mb-4">
            <label class="block text-xs font-semibold text-stone-400 uppercase tracking-wide mb-2">Optional feedback</label>
            <textarea id="feedbackText"
                placeholder="Anything else you'd like to share?"
                class="w-full border border-stone-200 rounded-2xl px-4 py-3 text-sm text-stone-800 resize-none h-24 bg-white"></textarea>
        </div>

        <!-- Submit -->
        <button id="submitBtn"
            class="w-full bg-[#1a1a1a] text-white font-semibold text-sm py-4 rounded-2xl transition active:scale-95">
            Submit feedback
        </button>

    </main>

    <!-- Footer -->
    <footer class="text-center py-4 text-xs text-stone-400">
        &copy; 2025 Big Brew · Thank you for your time
    </footer>

    <script>
        let regTransactionId = null;

        const questions = [{
                key: 'q1',
                title: 'Staff attitude',
                desc: 'Friendly, polite and helpful during your visit.'
            },
            {
                key: 'q2',
                title: 'Order accuracy',
                desc: 'Product served matched your order exactly.'
            },
            {
                key: 'q3',
                title: 'Cleanliness',
                desc: 'Store and tables were clean and tidy.'
            },
            {
                key: 'q4',
                title: 'Speed of service',
                desc: 'Order was prepared in a reasonable time.'
            },
            {
                key: 'q5',
                title: 'Overall satisfaction',
                desc: 'Your overall experience today.'
            },
        ];

        const ratings = {};
        const container = document.getElementById('cardContainer');
        const dotWrap = document.getElementById('dotWrap');

        // Build cards + dots
        questions.forEach((q, i) => {
            // Card
            const card = document.createElement('div');
            card.className = 'q-card bg-white border border-stone-200 rounded-2xl p-4' + (i === 0 ? ' active' : '');
            card.id = 'card-' + i;
            card.innerHTML = `
                <div class="flex items-center gap-2.5 mb-1">
                    <span class="w-5 h-5 rounded-full bg-amber-400 text-white text-[10px] font-bold flex items-center justify-center shrink-0">${i+1}</span>
                    <span class="text-sm font-semibold text-stone-800">${q.title}</span>
                </div>
                <p class="text-xs text-stone-400 mb-3 pl-7 leading-relaxed">${q.desc}</p>
                <div class="flex gap-1 pl-6" id="stars-${i}">
                    ${[1,2,3,4,5].map(n => `<button class="star-btn" data-qi="${i}" data-v="${n}">★</button>`).join('')}
                </div>`;
            container.appendChild(card);

            // Dot
            const dot = document.createElement('div');
            dot.className = 'dot' + (i === 0 ? ' active' : '');
            dot.id = 'dot-' + i;
            dotWrap.appendChild(dot);
        });

        // Star click
        container.addEventListener('click', e => {
            const btn = e.target.closest('.star-btn');
            if (!btn) return;

            const qi = parseInt(btn.dataset.qi);
            const v = parseInt(btn.dataset.v);
            ratings[questions[qi].key] = v;

            // Highlight stars
            document.querySelectorAll(`#stars-${qi} .star-btn`).forEach((s, i) => {
                s.classList.toggle('on', i < v);
            });

            // Mark card done
            const card = document.getElementById('card-' + qi);
            card.classList.remove('active');
            card.classList.add('done');

            // Mark dot done
            const dot = document.getElementById('dot-' + qi);
            dot.classList.remove('active');
            dot.classList.add('done');

            // Update progress
            const rated = Object.keys(ratings).length;
            const pct = Math.round((rated / 5) * 100);
            document.getElementById('progressFill').style.width = pct + '%';
            document.getElementById('pctLabel').textContent = pct + '%';

            // Activate next
            const next = qi + 1;
            if (next < questions.length) {
                const nextCard = document.getElementById('card-' + next);
                const nextDot = document.getElementById('dot-' + next);
                nextCard.classList.add('active');
                nextCard.classList.remove('done');
                nextDot.classList.add('active');
                document.getElementById('stepLabel').textContent = `Question ${next + 1} of 5`;
                nextCard.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
            } else {
                document.getElementById('stepLabel').textContent = 'All questions answered';
            }
        });

        // Submit
        document.getElementById('submitBtn').addEventListener('click', () => {
            const unanswered = questions.filter(q => !ratings[q.key]);
            if (unanswered.length > 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Almost there',
                    text: `Please rate: ${unanswered.map(q => q.title).join(', ')}`,
                    confirmButtonColor: '#f59e0b'
                });
                return;
            }
            if (!regTransactionId) {
                Swal.fire({
                    icon: 'error',
                    title: 'No transaction ID',
                    confirmButtonColor: '#f59e0b'
                });
                return;
            }
            submitFeedback();
        });

        function submitFeedback() {
            fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        ...ratings,
                        feedback_text: document.getElementById('feedbackText').value.trim(),
                        reg_transaction_id: regTransactionId
                    })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thank you!',
                            text: 'Your feedback has been submitted.',
                            confirmButtonColor: '#1a1a1a',
                            confirmButtonText: 'Done'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message,
                            confirmButtonColor: '#f59e0b'
                        });
                    }
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Server error',
                        text: 'Please try again.',
                        confirmButtonColor: '#f59e0b'
                    });
                });
        }

        // Transaction ID prompt on load
        Swal.fire({
            title: 'Enter your transaction number',
            input: 'text',
            inputPlaceholder: 'e.g. 1042',
            confirmButtonColor: '#1a1a1a',
            confirmButtonText: 'Continue',
            allowOutsideClick: false,
            allowEscapeKey: false,
            inputValidator: async (value) => {
                if (!value) return 'Transaction ID is required';
                try {
                    const res = await fetch('?check_trans=' + encodeURIComponent(value));
                    const data = await res.json();
                    if (!data.valid) return data.message;
                    regTransactionId = value;
                    document.getElementById('headerSub').textContent = 'Transaction #' + value;
                } catch {
                    return 'Error checking Transaction ID';
                }
            }
        });
    </script>
</body>

</html>