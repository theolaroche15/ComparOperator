(function () {
    const input = document.getElementById('user-search');
    const box = document.getElementById('user-search-results');
    if (!input || !box) return;

    let timer = null;

    function escapeHtml(str) {
        if (typeof str !== 'string') return '';
        return str.replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", "&#039;");
    }

    function render(results) {
        if (!Array.isArray(results) || results.length === 0) {
            box.innerHTML = '<div class="px-3 py-2 text-sm text-neutral-400">Aucun résultat</div>';
            box.classList.remove('hidden');
            return;
        }

        box.innerHTML = results.map(u => {
            const username = escapeHtml(u.username || '');
            const nickname = escapeHtml(u.nickname || '');
            const avatar = u.avatar_path;
            const href = `./profil.php?u=${encodeURIComponent(username)}`;

            return `
                <a href="${href}" class="flex items-center gap-3 px-3 py-2 hover:bg-neutral-800/60">
                    <img src="${avatar}" class="w-8 h-8 rounded-full object-cover" alt="${username}" />
                    <div class="min-w-0">
                        <div class="text-sm font-medium truncate">${username}</div>
                        <div class="text-xs text-neutral-400 truncate">@${nickname || username}</div>
                    </div>
                </a>`;
        }).join('');

        box.classList.remove('hidden');
    }

    async function search(q) {
        if (!q || q.trim().length < 2) {
            box.classList.add('hidden');
            return;
        }

        try {
            box.classList.remove('hidden');
            box.innerHTML = '<div class="px-3 py-2 text-sm text-neutral-400">Recherche…</div>';

            const res = await fetch(`../utils/search-users.php?q=${encodeURIComponent(q)}`);
            const data = await res.json();

            render(Array.isArray(data) ? data : []);
        } catch (err) {
            box.innerHTML = '<div class="px-3 py-2 text-sm text-amber-400">Erreur de recherche</div>';
        }
    }

    input.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(() => search(input.value), 250);
    });

    input.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            const first = box.querySelector('a');
            if (first) {
                e.preventDefault();
                window.location.href = first.href;
            }
        } else if (e.key === 'Escape') {
            box.classList.add('hidden');
        }
    });

    document.addEventListener('click', (e) => {
        if (!box.contains(e.target) && e.target !== input) {
            box.classList.add('hidden');
        }
    });
})();
