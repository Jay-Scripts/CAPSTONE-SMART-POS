<?php
include "../../app/config/dbConnection.php";

// Handle AJAX validation of transaction ID
if (isset($_GET['check_trans'])) {
    $reg_transaction_id = trim($_GET['check_trans']);
    $reg_transaction_id = htmlspecialchars($reg_transaction_id);

    if (!preg_match("/^[0-9]+$/", $reg_transaction_id)) {
        echo json_encode(['valid' => false, 'message' => 'Transaction ID must be numeric']);
        exit;
    }

    // Corrected table and column
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

// Handle survey submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    header('Content-Type: application/json');

    if (!$data) {
        echo json_encode(['success' => false, 'message' => 'No data received']);
        exit;
    }

    function sanitizeInput($value)
    {
        $value = trim($value);
        $value = htmlspecialchars($value);
        return $value;
    }

    $reg_transaction_id = sanitizeInput($data['reg_transaction_id'] ?? '');
    if (empty($reg_transaction_id)) {
        echo json_encode(['success' => false, 'message' => 'Transaction ID is required']);
        exit;
    }

    // Server-side validation against DB
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

    // Validate ratings
    $questions = ['q1', 'q2', 'q3', 'q4', 'q5'];
    foreach ($questions as $q) {
        if (!isset($data[$q]) || !is_numeric($data[$q]) || $data[$q] < 1 || $data[$q] > 5) {
            echo json_encode(['success' => false, 'message' => "Invalid rating for $q"]);
            exit;
        }
    }

    // Sanitize optional feedback
    $feedback_text = sanitizeInput($data['feedback_text'] ?? '');
    $feedback_text = preg_replace("/[^a-zA-Z0-9 .,!?'-]/", '', $feedback_text);

    // Insert feedback
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
    <title>Big Brew Survey</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .star {
            font-size: 2.5rem;
            cursor: pointer;
            transition: transform 0.2s, color 0.2s;
        }

        .star:hover {
            transform: scale(1.4);
            color: #f59e0b;
        }

        .star.selected {
            color: #f59e0b;
        }
    </style>
</head>

<body class="bg-amber-50 min-h-screen flex flex-col">

    <header class="bg-amber-400 text-white p-4 shadow-md">
        <div class="max-w-3xl mx-auto flex items-center justify-between">
            <h1 class="text-xl font-bold">Big Brew Feedback</h1>
            <img src="https://img.icons8.com/ios-filled/50/ffffff/coffee.png" alt="Logo" class="h-8 w-8">
        </div>
    </header>

    <main class="flex-1 flex items-center justify-center px-4 py-6">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-3xl shadow-xl p-6 relative overflow-hidden">
                <h2 class="text-2xl font-bold mb-2 text-center text-gray-800">Rate Your Experience</h2>
                <p class="text-gray-500 mb-4 text-center text-sm">1 = Poor, 5 = Excellent</p>

                <div class="w-full bg-gray-200 rounded-full h-3 mb-6">
                    <div id="progressBar" class="bg-amber-400 h-3 rounded-full w-0 transition-all"></div>
                </div>

                <div id="cardsContainer" class="relative h-72">
                    <?php
                    $questions = [
                        'q1' => ['Staff Attitude', 'Friendly, polite, and helpful during your visit.'],
                        'q2' => ['Accuracy of Product', 'Product served matches your order, nothing missing or wrong.'],
                        'q3' => ['Cleanliness', 'The store and tables are clean and tidy.'],
                        'q4' => ['Speed of Service', 'Order prepared and delivered in reasonable time.'],
                        'q5' => ['Overall Satisfaction', 'Your overall experience at the store today.']
                    ];
                    $first = true;
                    foreach ($questions as $key => $q) {
                        $translate = $first ? '' : 'translate-x-full';
                        echo '<div class="question-card absolute inset-0 bg-amber-50 p-5 rounded-xl shadow-lg flex flex-col justify-between transition-transform ' . $translate . '" data-question="' . $key . '">';
                        echo '<div>';
                        echo '<h3 class="text-lg font-semibold mb-1">' . $q[0] . '</h3>';
                        echo '<p class="text-gray-600 mb-4">' . $q[1] . '</p>';
                        echo '<div class="flex justify-center gap-2">';
                        for ($i = 1; $i <= 5; $i++) {
                            echo '<span class="star text-gray-300" data-value="' . $i . '">★</span>';
                        }
                        echo '</div></div></div>';
                        $first = false;
                    }
                    ?>
                </div>

                <textarea id="feedback" placeholder="Leave optional feedback..."
                    class="w-full border border-gray-300 rounded-xl p-3 h-24 resize-none focus:ring-2 focus:ring-amber-400 focus:outline-none transition my-4"></textarea>

                <div class="flex justify-between">
                    <button id="prevBtn" class="bg-gray-300 px-4 py-2 rounded-xl hover:bg-gray-400 transition" disabled>← Prev</button>
                    <button id="nextBtn" class="bg-amber-400 text-white px-4 py-2 rounded-xl hover:bg-amber-500 transition">Next →</button>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-amber-400 text-white text-center p-4 mt-auto shadow-inner">
        <p class="text-sm">&copy; 2025 Big Brew. Thank you for your feedback!</p>
    </footer>

    <script>
        let regTransactionId = null;

        Swal.fire({
            title: 'Enter Transaction Number',
            input: 'text',
            inputLabel: 'Reg Transaction ID',
            inputPlaceholder: 'Enter your transaction number',
            inputValidator: async (value) => {
                if (!value) return 'Transaction ID is required';
                let valid = false;
                try {
                    const res = await fetch('?check_trans=' + encodeURIComponent(value));
                    const data = await res.json();
                    if (!data.valid) return data.message;
                    valid = true;
                } catch (err) {
                    return 'Error checking Transaction ID';
                }
                if (valid) regTransactionId = value;
            },
            allowOutsideClick: false,
            allowEscapeKey: false
        });

        const cards = document.querySelectorAll('.question-card');
        const progressBar = document.getElementById('progressBar');
        let current = 0;
        const ratings = {
            q1: 0,
            q2: 0,
            q3: 0,
            q4: 0,
            q5: 0
        };

        function showCard(index) {
            cards.forEach((c, i) => c.style.transform = `translateX(${100*(i-index)}%)`);
            progressBar.style.width = `${(Object.values(ratings).filter(r=>r>0).length/5)*100}%`;
            document.getElementById('prevBtn').disabled = index === 0;
            document.getElementById('nextBtn').textContent = index === cards.length - 1 ? 'Submit' : 'Next →';
        }

        function submitFeedback() {
            if (!regTransactionId) {
                Swal.fire({
                    icon: 'error',
                    title: 'Missing Transaction ID',
                    text: 'Please enter your transaction number.'
                });
                return;
            }
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    ...ratings,
                    feedback_text: document.getElementById('feedback').value.trim(),
                    reg_transaction_id: regTransactionId
                })
            }).then(r => r.json()).then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thank You!',
                        text: 'Your feedback has been submitted.',
                        confirmButtonText: 'Close'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message,
                        confirmButtonText: 'Close'
                    });
                }
            });
        }

        cards.forEach(card => {
            const stars = card.querySelectorAll('.star');
            const question = card.dataset.question;
            stars.forEach((star, idx) => {
                star.addEventListener('click', () => {
                    ratings[question] = star.dataset.value;
                    stars.forEach(s => s.classList.remove('selected'));
                    for (let i = 0; i <= idx; i++) stars[i].classList.add('selected');
                    if (current < cards.length - 1) {
                        current++;
                        showCard(current);
                    } else submitFeedback();
                });
            });
        });

        document.getElementById('prevBtn').addEventListener('click', () => {
            if (current > 0) current--;
            showCard(current);
        });
        document.getElementById('nextBtn').addEventListener('click', () => {
            if (current < cards.length - 1) {
                current++;
                showCard(current);
            } else {
                if (Object.values(ratings).some(r => r == 0)) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Incomplete',
                        text: 'Please rate all questions.'
                    });
                    return;
                }
                submitFeedback();
            }
        });

        showCard(current);
    </script>
</body>

</html>