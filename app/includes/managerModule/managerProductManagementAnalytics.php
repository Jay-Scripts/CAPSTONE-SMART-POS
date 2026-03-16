<!-- =============================================
     DASHBOARD CONTENT CONTAINER
     ================================================= -->
<div class="p-4 sm:p-6 space-y-4 bg-[var(--background-color)]">

    <!-- ── ROW 1: Overview charts (2 cols) ── -->
    <section class="grid grid-cols-1 xl:grid-cols-2 gap-4" aria-label="overview charts">

        <!-- Sold by Category -->
        <article class="glass-card rounded-2xl border border-[var(--container-border)] bg-[var(--glass-bg)] shadow-sm p-5 flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-500/15 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-[var(--text-color)] leading-tight">Sold by Category</h2>
                        <p class="text-xs opacity-50 text-[var(--text-color)]">Sales distribution per category</p>
                    </div>
                </div>
            </div>
            <div class="relative h-64 sm:h-72">
                <canvas id="soldProductByCategoryChart" class="max-h-full"></canvas>
            </div>
        </article>

        <!-- Sold Add-ons -->
        <article class="glass-card rounded-2xl border border-[var(--container-border)] bg-[var(--glass-bg)] shadow-sm p-5 flex flex-col gap-3">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-amber-500/15 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 8v4l3 3" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-[var(--text-color)] leading-tight">Sold Add-ons</h2>
                    <p class="text-xs opacity-50 text-[var(--text-color)]">Add-on sales breakdown</p>
                </div>
            </div>
            <div class="relative h-64 sm:h-72">
                <canvas id="soldAddOnsChart" class="max-h-full"></canvas>
            </div>
        </article>

    </section>

    <!-- ── ROW 2: Per-category charts (3 cols) ── -->
    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4" aria-label="category charts">

        <!-- Iced Coffee -->
        <article class="glass-card rounded-2xl border border-[var(--container-border)] bg-[var(--glass-bg)] shadow-sm p-5 flex flex-col gap-3">
            <div class="flex items-center gap-2">
                <div class="w-2 h-8 rounded-full bg-blue-300 shrink-0"></div>
                <div>
                    <h3 class="text-sm font-bold text-[var(--text-color)] leading-tight">Iced Coffee</h3>
                    <p class="text-xs opacity-50 text-[var(--text-color)]">Per product breakdown</p>
                </div>
            </div>
            <div class="relative h-56">
                <canvas id="srIcedCoffeeChart"></canvas>
            </div>
        </article>

        <!-- Fruit Tea -->
        <article class="glass-card rounded-2xl border border-[var(--container-border)] bg-[var(--glass-bg)] shadow-sm p-5 flex flex-col gap-3">
            <div class="flex items-center gap-2">
                <div class="w-2 h-8 rounded-full bg-green-500 shrink-0"></div>
                <div>
                    <h3 class="text-sm font-bold text-[var(--text-color)] leading-tight">Fruit Tea</h3>
                    <p class="text-xs opacity-50 text-[var(--text-color)]">Per product breakdown</p>
                </div>
            </div>
            <div class="relative h-56">
                <canvas id="srFruitteaChart"></canvas>
            </div>
        </article>

        <!-- Hot Brew -->
        <article class="glass-card rounded-2xl border border-[var(--container-border)] bg-[var(--glass-bg)] shadow-sm p-5 flex flex-col gap-3">
            <div class="flex items-center gap-2">
                <div class="w-2 h-8 rounded-full bg-yellow-500 shrink-0"></div>
                <div>
                    <h3 class="text-sm font-bold text-[var(--text-color)] leading-tight">Hot Brew</h3>
                    <p class="text-xs opacity-50 text-[var(--text-color)]">Per product breakdown</p>
                </div>
            </div>
            <div class="relative h-56">
                <canvas id="srHotbrewChart"></canvas>
            </div>
        </article>

        <!-- Praf -->
        <article class="glass-card rounded-2xl border border-[var(--container-border)] bg-[var(--glass-bg)] shadow-sm p-5 flex flex-col gap-3">
            <div class="flex items-center gap-2">
                <div class="w-2 h-8 rounded-full bg-amber-600 shrink-0"></div>
                <div>
                    <h3 class="text-sm font-bold text-[var(--text-color)] leading-tight">Praf</h3>
                    <p class="text-xs opacity-50 text-[var(--text-color)]">Per product breakdown</p>
                </div>
            </div>
            <div class="relative h-56">
                <canvas id="srPrafChart"></canvas>
            </div>
        </article>

        <!-- Milk Tea -->
        <article class="glass-card rounded-2xl border border-[var(--container-border)] bg-[var(--glass-bg)] shadow-sm p-5 flex flex-col gap-3">
            <div class="flex items-center gap-2">
                <div class="w-2 h-8 rounded-full bg-blue-500 shrink-0"></div>
                <div>
                    <h3 class="text-sm font-bold text-[var(--text-color)] leading-tight">Milk Tea</h3>
                    <p class="text-xs opacity-50 text-[var(--text-color)]">Per product breakdown</p>
                </div>
            </div>
            <div class="relative h-56">
                <canvas id="srMilkteaChart"></canvas>
            </div>
        </article>

        <!-- Brosty -->
        <article class="glass-card rounded-2xl border border-[var(--container-border)] bg-[var(--glass-bg)] shadow-sm p-5 flex flex-col gap-3">
            <div class="flex items-center gap-2">
                <div class="w-2 h-8 rounded-full bg-purple-400 shrink-0"></div>
                <div>
                    <h3 class="text-sm font-bold text-[var(--text-color)] leading-tight">Brosty</h3>
                    <p class="text-xs opacity-50 text-[var(--text-color)]">Per product breakdown</p>
                </div>
            </div>
            <div class="relative h-56">
                <canvas id="srBrostyChart"></canvas>
            </div>
        </article>

    </section>

</div>