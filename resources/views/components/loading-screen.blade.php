<div id="loading-screen" class="fixed inset-0 z-[9999] flex items-center justify-center bg-surface transition-opacity duration-300" style="opacity: 1;">
    <div class="text-center">
        <div class="w-12 h-12 border-4 border-primary/30 border-t-primary rounded-full animate-spin mx-auto mb-4"></div>
        <p class="font-headline-sm text-headline-sm text-primary">EduPortal</p>
        <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">Memuat...</p>
    </div>
</div>
<noscript><style>#loading-screen{display:none!important}</style></noscript>
<script>
    (function() {
        var el = document.getElementById('loading-screen');
        if (!el) return;
        function hide() {
            el.style.opacity = '0';
            setTimeout(function() { el.style.display = 'none'; }, 300);
        }
        if (document.readyState !== 'loading') { hide(); return; }
        document.addEventListener('DOMContentLoaded', hide);
        setTimeout(hide, 2000);
    })();
</script>