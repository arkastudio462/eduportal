<div x-data="pwaInstall()" x-show="show" x-cloak>
    <button @click="install" class="hover:bg-surface-container-high rounded-full p-2 transition-colors relative group" title="Install Aplikasi">
        <span class="material-symbols-outlined text-primary">install_mobile</span>
        <span class="absolute top-full mt-2 right-0 bg-surface-container-high text-on-surface text-xs px-3 py-1.5 rounded-lg whitespace-nowrap shadow-lg opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50">
            Install Aplikasi
        </span>
    </button>
</div>

@once
    <script>
        function pwaInstall() {
            return {
                show: false,
                deferredPrompt: null,
                init() {
                    var self = this;
                    window.addEventListener('beforeinstallprompt', function(e) {
                        e.preventDefault();
                        self.deferredPrompt = e;
                        self.show = true;
                    });
                    window.addEventListener('appinstalled', function() {
                        self.show = false;
                        self.deferredPrompt = null;
                    });
                    if (window.matchMedia('(display-mode: standalone)').matches) {
                        self.show = false;
                    }
                },
                install() {
                    var self = this;
                    if (!this.deferredPrompt) return;
                    this.deferredPrompt.prompt();
                    this.deferredPrompt.userChoice.then(function(choiceResult) {
                        if (choiceResult.outcome === 'accepted') {
                            self.show = false;
                        }
                        self.deferredPrompt = null;
                    });
                }
            };
        }
    </script>
@endonce
