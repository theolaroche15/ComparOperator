<?php
require __DIR__ . "/../config/db.php";
require __DIR__ . "/../config/autoloader.php";

function getImagesOnce(string $pattern): array
{
    $files = glob($pattern);
    if (!$files) return [];
    shuffle($files);
    return array_map('basename', $files);
}

$premiumImages = getImagesOnce(__DIR__ . '/../public/assets/Travel*Premium.webp');
$classicImages = getImagesOnce(__DIR__ . '/../public/assets/Travel*Classic.webp');

$pdo = getPDO();
$manage = new Manager($pdo);
$allDestinations = $manage->getAllDestination();

$destinationQuery = isset($_GET['destination']) ? trim($_GET['destination']) : '';
$matches = [];
if ($destinationQuery !== '') {
    foreach ($allDestinations as $destination) {
        if (isset($destination['location']) && mb_strtolower($destination['location'], 'UTF-8') === mb_strtolower($destinationQuery, 'UTF-8')) {
            $matches[] = $destination;
        }
    }
}

$displayLabel = $destinationQuery ?: 'No destination selected';
?>

<?php include __DIR__ . '/partials/header.php'; ?>

<main class="max-w-4xl mx-auto px-4 pt-6 pb-10 text-slate-900">
    <?php if ($destinationQuery !== ''): ?>
        <div class="flex items-center mb-10">
            <a href="index.php" class="inline-flex items-center justify-center w-10 h-10
          rounded-full bg-red-500 hover:bg-red-600 transition text-white shadow-md
          ml-2 mt-5">
                <i class="fa-solid fa-circle-xmark text-xl"></i>
            </a>
        </div>
    <?php endif; ?>

    <header class="mb-4">
        <h2 class="text-2xl font-bold text-slate-900">Offers for <span class="text-[#15B07A]"><?= htmlspecialchars($displayLabel) ?></span></h2>
        <p class="text-sm text-slate-500 mt-1"><?= count($matches) ?> offer<?= count($matches) !== 1 ? 's' : '' ?> found.</p>
    </header>

    <?php if (count($matches) === 0): ?>
        <div class="rounded-md bg-red-50 border border-red-200 p-6 text-center">
            <p class="text-red-700 text-lg font-medium">No offers for <?= htmlspecialchars($displayLabel) ?> at the moment.</p>
            <p class="text-sm text-slate-500 mt-2">Try another destination or come back later.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 gap-6">
            <?php foreach ($matches as $destination): ?>
                <?php
                $operator     = $manage->getOperatorById((int) $destination['tour_operator_id']);
                $location     = $destination['location'] ?? 'Destination';
                $price        = $destination['price'] ?? 0;
                $link         = $operator['link'] ?? '#';
                $operatorName = $operator['name'] ?? 'Tour operator';
                $isPremium    = isset($operator['is_premium']) && (int)$operator['is_premium'] === 1;
                $borderClass  = $isPremium ? 'border-2 border-yellow-400' : 'border border-slate-200';

                if ($isPremium) {
                    $imageFile = array_shift($premiumImages);
                } else {
                    $imageFile = array_shift($classicImages);
                }
                ?>
                <article
                    class="relative overflow-hidden rounded-3xl bg-white shadow-md <?= $borderClass ?> <?= $isPremium ? 'premium-card' : '' ?> transition-transform duration-150 hover:-translate-y-1 hover:shadow-xl">
                    <div class="flex flex-col md:flex-row md:min-h-[210px]">
                        <div class="relative md:w-5/12">
                            <?php if (!empty($imageFile)): ?>
                                <img src="../public/assets/<?= htmlspecialchars($imageFile) ?>"
                                    loading="lazy"
                                    alt="Picture of <?= htmlspecialchars($location) ?>"
                                    class="h-40 md:h-full w-full object-cover" />
                            <?php endif; ?>
                            <div class="absolute inset-x-0 bottom-0 h-20 bg-gradient-to-t from-black/40 to-transparent md:hidden"></div>

                            <?php if ($isPremium): ?>
                                <span class="absolute top-3 left-3 inline-flex items-center gap-1 rounded-full bg-yellow-400/95 text-xs font-semibold text-slate-900 px-3 py-1 shadow">
                                    <i class="fa-solid fa-crown"></i>
                                    Premium
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="flex-1 flex flex-col justify-between p-4 sm:p-5 gap-3 relative triangle-separator card-content">
                            <div class="space-y-2">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <h2 class="text-lg sm:text-xl font-semibold text-slate-900">
                                            <?= htmlspecialchars($location) ?>
                                        </h2>
                                        <p class="text-xs sm:text-sm text-slate-500 flex items-center gap-1">
                                            <i class="fa-solid fa-building card-icon"></i>
                                            <?= htmlspecialchars($operatorName) ?>
                                        </p>
                                    </div>
                                    <div class="text-right min-w-[90px]">
                                        <p class="<?= $isPremium ? 'yellow' : 'vert' ?> text-base sm:text-lg font-bold leading-tight">
                                            <?= number_format((float)$price, 2, ',', ' ') ?> $
                                        </p>
                                        <p class="text-[11px] text-slate-400">per traveler</p>
                                    </div>
                                </div>
                                <div class="border-t border-slate-200/80 pt-3 mt-1">
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-xs sm:text-[13px] text-slate-600">
                                        <p class="flex items-center gap-1.5">
                                            <i class="fa-solid fa-plane card-icon"></i>
                                            7 days / 6 nights
                                        </p>
                                        <p class="flex items-center gap-1.5">
                                            <i class="fa-solid fa-hotel card-icon"></i>
                                            4 star hotel
                                        </p>
                                        <p class="flex items-center gap-1.5">
                                            <i class="fa-solid fa-calendar-days card-icon"></i>
                                            15 October / 22 October
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2 pt-3 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <p class="text-xs sm:text-[13px] text-slate-500 flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-check card-icon"></i>
                                    Flight + hotel included · Free cancellation
                                </p>
                                <a href="<?= htmlspecialchars($link) ?>"
                                    target="_blank"
                                    class="inline-flex items-center justify-center px-6 py-2.5 rounded-full
                                          text-white text-sm font-semibold red-button shadow-md w-full sm:w-auto text-center hover:opacity-90 transition">
                                    <span>Show more</span>
                                    <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                                </a>
                            </div>
                            <?php
                            $reviews = $pdo->prepare("SELECT message, author, tour_operator_id, stars FROM review WHERE tour_operator_id = :id");
                            $reviews->execute([':id' => $destination['tour_operator_id']]);
                            $reviews = $reviews->fetchAll(PDO::FETCH_ASSOC);
                            $reviewsCount = count($reviews);
                            ?>
                            <details class="group mt-4 border-t border-slate-200 pt-3">
                                <summary class="flex items-center justify-between cursor-pointer list-none text-sm font-semibold text-slate-700 py-2 select-none">
                                    <span class="flex items-center gap-2">
                                        Comment (<?= $reviewsCount ?>)
                                    </span>
                                    <svg class="w-4 h-4 text-slate-400 transition-transform duration-200 group-open:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path d="M6 9l6 6 6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </summary>
                                <div class="mt-3 space-y-3">
                                    <?php if ($reviewsCount === 0): ?>
                                        <div class="text-sm text-slate-500">No comments yet, be the first !</div>
                                    <?php else: ?>
                                        <?php foreach ($reviews as $review): ?>
                                            <div class="p-3 bg-white rounded-md border border-slate-100 shadow-sm">
                                                <div class="flex items-center justify-between">
                                                    <div class="text-sm font-medium text-slate-800"><?= htmlspecialchars($review['author'] ?? 'Anonyme') ?></div>
                                                </div>
                                                <p class="mt-1 text-sm text-slate-600">
                                                    <?= nl2br(htmlspecialchars($review['message'])) ?>
                                                </p>
                                                <p class="mt-1 text-xs text-yellow-500">Note : <?= htmlspecialchars($review['stars']) ?>/5</p>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <form action="../process/save_review.php" method="POST" class="flex flex-wrap items-center gap-3 mt-2">
                                        <input type="hidden" name="tour_operator_id" value="<?= (int)$destination['tour_operator_id'] ?>">
                                        <input type="text" name="author" placeholder="Pseudo" required
                                            class="w-28 rounded-md border border-slate-300 p-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#15B07A]">
                                        <select name="stars" required
                                            class="w-20 rounded-md border border-slate-300 p-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#15B07A]">
                                            <option value="" disabled selected>⭐</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                        <input type="text" name="message" placeholder="Message..." required
                                            class="flex-1 min-w-[150px] rounded-md border border-slate-300 p-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#15B07A]">
                                        <button type="submit"
                                            class="px-4 py-2 rounded-full bg-[#15B07A] text-white text-sm font-semibold hover:opacity-90 transition">
                                            Send
                                        </button>
                                    </form>
                                </div>
                            </details>
                        </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>
<?php include __DIR__ . '/partials/footer.php'; ?>