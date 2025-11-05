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
            /* Amber */
        }

        .star.selected {
            color: #f59e0b;
        }
    </style>
</head>

<body class="bg-amber-50 min-h-screen flex flex-col">

    <!-- HEADER -->
    <header class="bg-amber-400 text-white p-4 shadow-md">
        <div class="max-w-3xl mx-auto flex items-center justify-between">
            <h1 class="text-xl font-bold">Big Brew Feedback</h1>
            <img src="https://img.icons8.com/ios-filled/50/ffffff/coffee.png" alt="Logo" class="h-8 w-8">
        </div>
    </header>

    <!-- MAIN -->
    <main class="flex-1 flex items-center justify-center px-4 py-6">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-3xl shadow-xl p-6 relative overflow-hidden">
                <h2 class="text-2xl font-bold mb-2 text-center text-gray-800">Rate Your Experience</h2>
                <p class="text-gray-500 mb-4 text-center text-sm">1 = Poor, 5 = Excellent</p>

                <!-- Progress -->
                <div class="w-full bg-gray-200 rounded-full h-3 mb-6">
                    <div id="progressBar" class="bg-amber-400 h-3 rounded-full w-0 transition-all"></div>
                </div>

                <div id="cardsContainer" class="relative h-72">
                    <div id="cardsContainer" class="relative h-72">
                        <!-- Q1 -->
                        <div class="question-card absolute inset-0 bg-amber-50 p-5 rounded-xl shadow-lg flex flex-col justify-between transition-transform" data-question="q1">
                            <div>
                                <h3 class="text-lg font-semibold mb-1">1. Staff Attitude</h3>
                                <p class="text-gray-600 mb-4">Friendly, polite, and helpful during your visit.</p>
                                <div class="flex justify-center gap-2">
                                    <span class="star text-gray-300" data-value="1">★</span>
                                    <span class="star text-gray-300" data-value="2">★</span>
                                    <span class="star text-gray-300" data-value="3">★</span>
                                    <span class="star text-gray-300" data-value="4">★</span>
                                    <span class="star text-gray-300" data-value="5">★</span>
                                </div>
                            </div>
                        </div>

                        <!-- Q2 -->
                        <div class="question-card absolute inset-0 bg-amber-50 p-5 rounded-xl shadow-lg flex flex-col justify-between transition-transform translate-x-full" data-question="q2">
                            <div>
                                <h3 class="text-lg font-semibold mb-1">2. Accuracy of Product</h3>
                                <p class="text-gray-600 mb-4">Product served matches your order, nothing missing or wrong.</p>
                                <div class="flex justify-center gap-2">
                                    <span class="star text-gray-300" data-value="1">★</span>
                                    <span class="star text-gray-300" data-value="2">★</span>
                                    <span class="star text-gray-300" data-value="3">★</span>
                                    <span class="star text-gray-300" data-value="4">★</span>
                                    <span class="star text-gray-300" data-value="5">★</span>
                                </div>
                            </div>
                        </div>

                        <!-- Q3 -->
                        <div class="question-card absolute inset-0 bg-amber-50 p-5 rounded-xl shadow-lg flex flex-col justify-between transition-transform translate-x-full" data-question="q3">
                            <div>
                                <h3 class="text-lg font-semibold mb-1">3. Cleanliness</h3>
                                <p class="text-gray-600 mb-4">The store and tables are clean and tidy.</p>
                                <div class="flex justify-center gap-2">
                                    <span class="star text-gray-300" data-value="1">★</span>
                                    <span class="star text-gray-300" data-value="2">★</span>
                                    <span class="star text-gray-300" data-value="3">★</span>
                                    <span class="star text-gray-300" data-value="4">★</span>
                                    <span class="star text-gray-300" data-value="5">★</span>
                                </div>
                            </div>
                        </div>

                        <!-- Q4 -->
                        <div class="question-card absolute inset-0 bg-amber-50 p-5 rounded-xl shadow-lg flex flex-col justify-between transition-transform translate-x-full" data-question="q4">
                            <div>
                                <h3 class="text-lg font-semibold mb-1">4. Speed of Service</h3>
                                <p class="text-gray-600 mb-4">Order prepared and delivered in reasonable time.</p>
                                <div class="flex justify-center gap-2">
                                    <span class="star text-gray-300" data-value="1">★</span>
                                    <span class="star text-gray-300" data-value="2">★</span>
                                    <span class="star text-gray-300" data-value="3">★</span>
                                    <span class="star text-gray-300" data-value="4">★</span>
                                    <span class="star text-gray-300" data-value="5">★</span>
                                </div>
                            </div>
                        </div>

                        <!-- Q5 -->
                        <div class="question-card absolute inset-0 bg-amber-50 p-5 rounded-xl shadow-lg flex flex-col justify-between transition-transform translate-x-full" data-question="q5">
                            <div>
                                <h3 class="text-lg font-semibold mb-1">5. Overall Satisfaction</h3>
                                <p class="text-gray-600 mb-4">Your overall experience at the store today.</p>
                                <div class="flex justify-center gap-2">
                                    <span class="star text-gray-300" data-value="1">★</span>
                                    <span class="star text-gray-300" data-value="2">★</span>
                                    <span class="star text-gray-300" data-value="3">★</span>
                                    <span class="star text-gray-300" data-value="4">★</span>
                                    <span class="star text-gray-300" data-value="5">★</span>
                                </div>
                            </div>
                        </div>
                    </div>

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

    <!-- FOOTER -->
    <footer class="bg-amber-400 text-white text-center p-4 mt-auto shadow-inner">
        <p class="text-sm">&copy; 2025 Big Brew. Thank you for your feedback!</p>
    </footer>

    <script>
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
            cards.forEach((card, i) => card.style.transform = `translateX(${100*(i-index)}%)`);
            progressBar.style.width = `${(Object.values(ratings).filter(r=>r>0).length/5)*100}%`;
            document.getElementById('prevBtn').disabled = index === 0;
            document.getElementById('nextBtn').textContent = index === cards.length - 1 ? 'Submit' : 'Next →';
        }

        cards.forEach(card => {
            const stars = card.querySelectorAll('.star');
            const question = card.dataset.question;
            stars.forEach((star, idx) => {
                star.addEventListener('click', () => {
                    // Set rating
                    ratings[question] = star.dataset.value;
                    stars.forEach(s => s.classList.remove('selected'));
                    for (let i = 0; i <= idx; i++) stars[i].classList.add('selected');

                    // Move to next card automatically
                    if (current < cards.length - 1) {
                        current++;
                        showCard(current);
                    } else {
                        // Last card: show thank you
                        const feedback = document.getElementById('feedback').value.trim();
                        Swal.fire({
                            icon: 'success',
                            title: 'Thank You!',
                            text: 'Please Come Again!.',
                            confirmButtonText: 'Close'
                        });
                    }
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
                const unanswered = Object.values(ratings).some(r => r == 0);
                if (unanswered) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Incomplete',
                        text: 'Please rate all questions.'
                    });
                    return;
                }
                const feedback = document.getElementById('feedback').value.trim();
                Swal.fire({
                    icon: 'success',
                    title: 'Thank You!',
                    text: 'Please Come Again!.',

                    confirmButtonText: 'Close'
                });
            }
        });

        showCard(current);
    </script>
</body>

</html>