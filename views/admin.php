<?php

declare(strict_types=1);

require __DIR__ . '/../config/db.php';
require __DIR__ . '/../config/autoloader.php';

$manager = new Manager(getPDO());
$adminManager = new AdminManager($manager);

$errors = [];
$success = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'create_operator':
            $result = $adminManager->createOperator(
                trim($_POST['name'] ?? ''),
                trim($_POST['link'] ?? ''),
                isset($_POST['is_premium'])
            );

            $result === true
                ? $success[] = 'Tour operator successfully created'
                : $errors[]  = $result;
            break;

        case 'create_destination':
            $result = $adminManager->createDestination(
                trim($_POST['location'] ?? ''),
                (float) ($_POST['price'] ?? 0),
                (int) ($_POST['tour_operator_id'] ?? 0)
            );

            $result === true
                ? $success[] = 'Destination successfully created'
                : $errors[]  = $result;
            break;

        case 'set_premium':
            $result = $adminManager->setPremium(
                (int) ($_POST['tour_operator_id'] ?? 0)
            );

            $result === true
                ? $success[] = 'Tour operator is now premium'
                : $errors[]  = $result;
            break;
    }
}

$data = $adminManager->getStats();

$operators = $data['operators'];
$destinations = $data['destinations'];
$nbOperators = $data['nbOperators'];
$nbDestinations = $data['nbDestinations'];
$nbPremium = $data['nbPremium'];

$nbOperators = count($operators);
$nbDestinations = count($destinations);
$nbPremium = 0;

foreach ($operators as $op) {
    if (!empty($op['is_premium'])) {
        $nbPremium++;
    }
}

?>

<?php include __DIR__ . '/../views/partials/header.php'; ?>
<main class="text-slate-900 text-[15px]">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row">
        <aside class="hidden md:flex md:flex-col w-64 min-h-[calc(100vh-4rem)] bg-white border-r border-slate-200 p-4 sticky top-[4rem] text-sm">
            <div class="flex items-center gap-2 mb-6">
                <div class="w-10 h-10 rounded-xl bg-pink-600 flex items-center justify-center">
                    <i class="fa-solid fa-gauge-high text-white text-lg"></i>
                </div>
                <div>
                    <p class="text-[11px] uppercase tracking-wide text-slate-400">Admin</p>
                    <p class="font-semibold text-sm text-slate-800">ComparOperator</p>
                </div>
            </div>

            <nav class="flex-1 space-y-1 text-sm">
                <p class="text-[11px] font-semibold text-slate-400 uppercase mb-2">Navigation</p>
                <a href="#overview" class="flex items-center gap-2 px-3 py-2 rounded-lg bg-pink-50 text-pink-700 hover:bg-pink-100">
                    <i class="fa-solid fa-chart-line"></i>
                    <span>Overview</span>
                </a>
                <a href="#form-operator" class="flex items-center gap-2 px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-100">
                    <i class="fa-solid fa-building text-blue-500"></i>
                    <span>Tour operators</span>
                </a>
                <a href="#form-destination" class="flex items-center gap-2 px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-100">
                    <i class="fa-solid fa-location-dot text-emerald-500"></i>
                    <span>Destinations</span>
                </a>
                <a href="#premium" class="flex items-center gap-2 px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-100">
                    <i class="fa-solid fa-crown text-yellow-500"></i>
                    <span>Premium</span>
                </a>
            </nav>

            <div class="mt-4 pt-4 border-t border-slate-200 text-xs text-slate-500">
                <p>Logged in as <span class="text-slate-800">Admin</span></p>
                <a href="index.php" class="inline-flex items-center gap-1 text-pink-600 mt-2 hover:text-pink-700">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back to site
                </a>
            </div>
        </aside>
        <section class="flex-1 px-4 md:px-8 py-6 space-y-6">
            <div class="md:hidden sticky top-[0px] z-30 bg-slate-100/90 backdrop-blur border-b border-slate-200 -mx-4 px-4 pb-2 mb-4">
                <div class="flex items-center justify-between pt-2 pb-1">
                    <button id="mobile-menu-open" class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-white border border-slate-200 shadow-sm">
                        <i class="fa-solid fa-bars text-slate-700"></i>
                    </button>

                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-pink-600 flex items-center justify-center">
                            <i class="fa-solid fa-gauge-high text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase tracking-wide text-slate-400">Admin</p>
                            <p class="font-semibold text-sm text-slate-800">ComparOperator</p>
                        </div>
                    </div>

                    <a href="../../index.php" class="text-xs inline-flex items-center gap-1 text-pink-600">
                        <i class="fa-solid fa-arrow-left"></i>
                        Site
                    </a>
                </div>
                <nav class="flex gap-2 overflow-x-auto no-scrollbar text-[13px] mt-2 pb-1">
                    <a href="#overview" class="px-3 py-1.5 rounded-full bg-pink-600 text-white flex items-center gap-1 whitespace-nowrap">
                        <i class="fa-solid fa-chart-line"></i> Overview
                    </a>
                    <a href="#form-operator" class="px-3 py-1.5 rounded-full bg-white border border-slate-200 text-slate-700 flex items-center gap-1 whitespace-nowrap">
                        <i class="fa-solid fa-building text-blue-500"></i> Operators
                    </a>
                    <a href="#form-destination" class="px-3 py-1.5 rounded-full bg-white border border-slate-200 text-slate-700 flex items-center gap-1 whitespace-nowrap">
                        <i class="fa-solid fa-location-dot text-emerald-500"></i> Destinations
                    </a>
                    <a href="#premium" class="px-3 py-1.5 rounded-full bg-white border border-slate-200 text-slate-700 flex items-center gap-1 whitespace-nowrap">
                        <i class="fa-solid fa-crown text-yellow-500"></i> Premium
                    </a>
                </nav>
            </div>
            <div id="mobile-menu" class="fixed inset-0 z-40 hidden">
                <div id="mobile-menu-overlay" class="absolute inset-0 bg-slate-900/40"></div>

                <div class="relative w-64 max-w-[80vw] h-full bg-white border-r border-slate-200 p-4 flex flex-col">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-9 h-9 rounded-xl bg-pink-600 flex items-center justify-center">
                                <i class="fa-solid fa-gauge-high text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase tracking-wide text-slate-400">Admin</p>
                                <p class="font-semibold text-sm text-slate-800">ComparOperator</p>
                            </div>
                        </div>
                        <button id="mobile-menu-close" class="w-8 h-8 inline-flex items-center justify-center rounded-full bg-slate-100">
                            <i class="fa-solid fa-xmark text-slate-700"></i>
                        </button>
                    </div>

                    <nav class="flex-1 space-y-1 text-[14px]">
                        <p class="text-[11px] font-semibold text-slate-400 uppercase mb-2">Navigation</p>
                        <a href="#overview" class="flex items-center gap-2 px-3 py-2 rounded-lg bg-pink-50 text-pink-700 hover:bg-pink-100">
                            <i class="fa-solid fa-chart-line"></i>
                            <span>Overview</span>
                        </a>
                        <a href="#form-operator" class="flex items-center gap-2 px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-100">
                            <i class="fa-solid fa-building text-blue-500"></i>
                            <span>Tour operators</span>
                        </a>
                        <a href="#form-destination" class="flex items-center gap-2 px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-100">
                            <i class="fa-solid fa-location-dot text-emerald-500"></i>
                            <span>Destinations</span>
                        </a>
                        <a href="#premium" class="flex items-center gap-2 px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-100">
                            <i class="fa-solid fa-crown text-yellow-500"></i>
                            <span>Premium</span>
                        </a>
                    </nav>

                    <div class="pt-3 border-t border-slate-200 text-[12px] text-slate-500">
                        <p>Logged in as <span class="text-slate-800">Admin</span></p>
                        <a href="../../index.php" class="inline-flex items-center gap-1 text-pink-600 mt-2">
                            <i class="fa-solid fa-arrow-left"></i>
                            Back to site
                        </a>
                    </div>
                </div>
            </div>

            <?php if (!empty($success)): ?>
                <div class="rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-[14px] text-emerald-700">
                    <i class="fa-solid fa-circle-check mr-2"></i>
                    <?= implode('<br>', array_map('htmlspecialchars', $success)) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-[14px] text-red-700">
                    <i class="fa-solid fa-triangle-exclamation mr-2"></i>
                    <?= implode('<br>', array_map('htmlspecialchars', $errors)) ?>
                </div>
            <?php endif; ?>

            <header id="overview" class="space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <p class="text-[11px] uppercase text-slate-400 tracking-wide">Dashboard</p>
                        <h1 class="text-2xl sm:text-3xl font-semibold text-slate-900">Overview</h1>
                        <p class="text-[13px] text-slate-500 mt-1">
                            Manage tour operators, destinations and premium offers.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="rounded-2xl bg-white border border-slate-200 p-4 flex flex-col gap-1 shadow-sm">
                        <div class="flex items-center justify-between">
                            <p class="text-[13px] text-slate-600">Tour operators</p>
                            <i class="fa-solid fa-building text-blue-500"></i>
                        </div>
                        <p class="text-2xl font-semibold text-slate-900"><?= $nbOperators ?></p>
                        <p class="text-[11px] text-slate-400">Active in the comparator</p>
                    </div>

                    <div class="rounded-2xl bg-white border border-slate-200 p-4 flex flex-col gap-1 shadow-sm">
                        <div class="flex items-center justify-between">
                            <p class="text-[13px] text-slate-600">Destinations</p>
                            <i class="fa-solid fa-location-dot text-emerald-500"></i>
                        </div>
                        <p class="text-2xl font-semibold text-slate-900"><?= $nbDestinations ?></p>
                        <p class="text-[11px] text-slate-400">Available destinations</p>
                    </div>

                    <div class="rounded-2xl bg-white border border-yellow-200 p-4 flex flex-col gap-1 shadow-sm">
                        <div class="flex items-center justify-between">
                            <p class="text-[13px] text-slate-600">Premium TO</p>
                            <i class="fa-solid fa-crown text-yellow-500"></i>
                        </div>
                        <p class="text-2xl font-semibold text-slate-900"><?= $nbPremium ?></p>
                        <p class="text-[11px] text-slate-400">Highlighted on homepage</p>
                    </div>
                </div>
            </header>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <section id="form-operator" class="rounded-2xl bg-white border border-slate-200 p-5 space-y-4 shadow-sm">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-slate-900 flex items-center">
                            <i class="fa-solid fa-building mr-2 text-blue-500"></i>
                            Add a tour operator
                        </h2>
                        <span class="text-[11px] text-slate-400 uppercase tracking-wide hidden sm:inline">Operators</span>
                    </div>

                    <form method="post" class="space-y-3">
                        <input type="hidden" name="action" value="create_operator">

                        <div>
                            <label class="block text-[13px] mb-1 text-slate-700 font-medium">Name</label>
                            <input type="text" name="name"
                                class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2.5 text-[15px] text-slate-900 focus:outline-none focus:ring-2 focus:ring-pink-500"
                                placeholder="Salaun Holidays" required>
                        </div>

                        <div>
                            <label class="block text-[13px] mb-1 text-slate-700 font-medium">Website URL</label>
                            <input type="url" name="link"
                                class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2.5 text-[15px] text-slate-900 focus:outline-none focus:ring-2 focus:ring-pink-500"
                                placeholder="https://www.example.com" required>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="is_premium" name="is_premium"
                                class="h-4 w-4 rounded border-slate-300">
                            <label for="is_premium" class="text-[13px] text-slate-700 flex items-center gap-1">
                                <i class="fa-solid fa-crown text-yellow-500"></i> Premium
                            </label>
                        </div>

                        <button type="submit"
                            class="mt-2 inline-flex items-center justify-center gap-2 px-4 py-2.5 w-full sm:w-auto rounded-full bg-pink-600 hover:bg-pink-500 text-[14px] text-white font-semibold shadow-sm">
                            <i class="fa-solid fa-plus"></i>
                            Add tour operator
                        </button>
                    </form>
                </section>

                <section id="form-destination" class="rounded-2xl bg-white border border-slate-200 p-5 space-y-4 shadow-sm">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-slate-900 flex items-center">
                            <i class="fa-solid fa-location-dot mr-2 text-emerald-500"></i>
                            Add a destination
                        </h2>
                        <span class="text-[11px] text-slate-400 uppercase tracking-wide hidden sm:inline">Destinations</span>
                    </div>

                    <form method="post" class="space-y-3">
                        <input type="hidden" name="action" value="create_destination">

                        <div>
                            <label class="block text-[13px] mb-1 text-slate-700 font-medium">Destination name</label>
                            <input type="text" name="location"
                                class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2.5 text-[15px] text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                placeholder="Rome, Tunis, ..." required>
                        </div>

                        <div>
                            <label class="block text-[13px] mb-1 text-slate-700 font-medium">Price</label>
                            <input type="number" step="1" name="price"
                                class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2.5 text-[15px] text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                placeholder="1650" required>
                        </div>

                        <div>
                            <label class="block text-[13px] mb-1 text-slate-700 font-medium">Tour operator</label>
                            <select name="tour_operator_id"
                                class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2.5 text-[15px] text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                required>
                                <?php foreach ($operators as $op): ?>
                                    <option value="<?= (int)$op['id'] ?>">
                                        <?= htmlspecialchars($op['name']) ?>
                                        <?= $op['is_premium'] ? ' (Premium)' : '' ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <button type="submit"
                            class="mt-2 inline-flex items-center justify-center gap-2 px-4 py-2.5 w-full sm:w-auto rounded-full bg-emerald-600 hover:bg-emerald-500 text-[14px] text-white font-semibold shadow-sm">
                            <i class="fa-solid fa-plus"></i>
                            Add destination
                        </button>
                    </form>
                </section>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" id="premium">
                <section class="rounded-2xl bg-white border border-yellow-200 p-5 space-y-4 shadow-sm lg:col-span-1">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-crown text-yellow-500"></i>
                            Manage premium
                        </h2>
                    </div>

                    <p class="text-[13px] text-slate-600">
                        Set a tour operator as premium to highlight it on the homepage.
                    </p>

                    <form method="post" class="space-y-3">
                        <input type="hidden" name="action" value="set_premium">

                        <select name="tour_operator_id"
                            class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2.5 text-[15px] text-slate-900 focus:outline-none focus:ring-2 focus:ring-yellow-500"
                            required>
                            <option value="">Select a tour operator</option>
                            <?php foreach ($operators as $op): ?>
                                <option value="<?= (int)$op['id'] ?>">
                                    <?= htmlspecialchars($op['name']) ?>
                                    <?= $op['is_premium'] ? '(already premium)' : '' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 w-full sm:w-auto rounded-full bg-yellow-500 hover:bg-yellow-400 text-[14px] text-slate-900 font-semibold shadow-sm">
                            <i class="fa-solid fa-arrow-up-right-dots"></i>
                            Set as premium
                        </button>
                    </form>
                </section>

                <section class="rounded-2xl bg-white border border-slate-200 p-5 space-y-3 shadow-sm lg:col-span-2">
                    <div class="flex items-center justify-between mb-1">
                        <h2 class="text-xl font-semibold text-slate-900">
                            Existing tour operators
                        </h2>
                        <span class="text-[12px] text-slate-500">
                            <?= $nbOperators ?> operators • <?= $nbPremium ?> premium
                        </span>
                    </div>
                    <div class="overflow-x-auto hidden md:block">
                        <table class="min-w-full text-[14px]">
                            <thead class="bg-slate-50 text-slate-600">
                                <tr>
                                    <th class="px-3 py-2 text-left">ID</th>
                                    <th class="px-3 py-2 text-left">Name</th>
                                    <th class="px-3 py-2 text-left">Website</th>
                                    <th class="px-3 py-2 text-left">Premium</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php foreach ($operators as $op): ?>
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-3 py-2"><?= (int)$op['id'] ?></td>
                                        <td class="px-3 py-2">
                                            <?= htmlspecialchars($op['name']) ?>
                                        </td>
                                        <td class="px-3 py-2">
                                            <a href="<?= htmlspecialchars($op['link']) ?>" target="_blank"
                                                class="text-blue-600 underline break-all">
                                                <?= htmlspecialchars($op['link']) ?>
                                            </a>
                                        </td>
                                        <td class="px-3 py-2">
                                            <?php if (!empty($op['is_premium'])): ?>
                                                <span class="inline-flex items-center gap-1 rounded-full bg-yellow-50 text-yellow-700 border border-yellow-200 px-2 py-0.5 text-[11px]">
                                                    <i class="fa-solid fa-crown"></i> Premium
                                                </span>
                                            <?php else: ?>
                                                <span class="text-[12px] text-slate-500">Standard</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($operators)): ?>
                                    <tr>
                                        <td colspan="4" class="px-3 py-3 text-center text-slate-500 text-[13px]">
                                            No tour operator yet.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="space-y-3 md:hidden">
                        <?php foreach ($operators as $op): ?>
                            <article class="rounded-xl border border-slate-200 bg-white p-3.5 shadow-sm">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <p class="text-[11px] text-slate-400">#<?= (int)$op['id'] ?></p>
                                        <h3 class="text-[15px] font-semibold text-slate-900">
                                            <?= htmlspecialchars($op['name']) ?>
                                        </h3>
                                    </div>
                                    <?php if (!empty($op['is_premium'])): ?>
                                        <span class="inline-flex items-center gap-1 rounded-full bg-yellow-50 text-yellow-700 border border-yellow-200 px-2 py-0.5 text-[11px] whitespace-nowrap">
                                            <i class="fa-solid fa-crown"></i> Premium
                                        </span>
                                    <?php else: ?>
                                        <span class="text-[12px] text-slate-500 whitespace-nowrap">Standard</span>
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($op['link'])): ?>
                                    <a href="<?= htmlspecialchars($op['link']) ?>" target="_blank"
                                        class="mt-2 inline-flex items-center gap-1 text-[12px] text-blue-600 underline break-all">
                                        <i class="fa-solid fa-link"></i>
                                        <?= htmlspecialchars($op['link']) ?>
                                    </a>
                                <?php endif; ?>
                            </article>
                        <?php endforeach; ?>

                        <?php if (empty($operators)): ?>
                            <p class="text-center text-slate-500 text-[13px]">
                                No tour operator yet.
                            </p>
                        <?php endif; ?>
                    </div>
                </section>
            </div>
        </section>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const openBtn = document.getElementById('mobile-menu-open');
        const closeBtn = document.getElementById('mobile-menu-close');
        const menu = document.getElementById('mobile-menu');
        const overlay = document.getElementById('mobile-menu-overlay');

        function openMenu() {
            if (!menu) return;
            menu.classList.remove('hidden');
        }

        function closeMenu() {
            if (!menu) return;
            menu.classList.add('hidden');
        }

        if (openBtn) openBtn.addEventListener('click', openMenu);
        if (closeBtn) closeBtn.addEventListener('click', closeMenu);
        if (overlay) overlay.addEventListener('click', closeMenu);
    });
</script>

<?php include __DIR__ . '/../views/partials/footer.php'; ?>