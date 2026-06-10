<x-layouts.portal-siswa title="{{ $conversation->subjek ?? 'Pesan' }}">
<x-slot:styles>
<style>[x-cloak] { display: none !important; }</style>
</x-slot:styles>

<div x-data="{
    conversationId: {{ $conversation->id }},
    editingId: null,
    editBody: '',
    selectMode: false,
    selectedIds: [],
    loading: false,
    fileName: '',
    previewUrl: '',

    startEdit(id) {
        let el = document.querySelector(`[data-msg-id='${id}']`);
        if (!el) return;
        let bodyEl = el.querySelector('.msg-body');
        this.editingId = id;
        this.editBody = bodyEl ? bodyEl.textContent.trim() : '';
    },
    cancelEdit() {
        this.editingId = null;
        this.editBody = '';
    },
    saveEdit(id) {
        let body = this.editBody.trim();
        if (!body) return;
        let baseUrl = '{{ route('portal-siswa.pesan.update', [$conversation, '__MID__']) }}'.replace('__MID__', id);
        fetch(baseUrl, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' },
            body: JSON.stringify({ body }),
        }).then(r => { if (!r.ok) throw new Error('Gagal menyimpan'); return r.json(); })
        .then(() => {
            let el = document.querySelector(`[data-msg-id='${id}']`);
            if (el) {
                let bodyEl = el.querySelector('.msg-body');
                if (bodyEl) bodyEl.textContent = body;
            }
            this.cancelEdit();
        }).catch(err => alert(err.message));
    },
    toggleSelect(id) {
        let idx = this.selectedIds.indexOf(id);
        if (idx > -1) this.selectedIds.splice(idx, 1);
        else this.selectedIds.push(id);
    },
    deleteSelected() {
        if (!this.selectedIds.length) return;
        if (!confirm('Hapus ' + this.selectedIds.length + ' pesan terpilih?')) return;
        fetch('{{ route('portal-siswa.pesan.bulk-destroy', $conversation) }}', {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' },
            body: JSON.stringify({ ids: this.selectedIds }),
        }).then(r => { if (!r.ok) throw new Error('Gagal menghapus'); return r.json(); })
        .then(() => {
            this.selectedIds.forEach(id => {
                let el = document.querySelector(`[data-msg-id='${id}']`);
                if (el) el.remove();
            });
            this.selectedIds = [];
            this.selectMode = false;
        }).catch(err => alert(err.message));
    },
    previewFile() {
        let file = this.$refs.fileInput.files[0];
        if (!file || !file.type.startsWith('image/')) { this.previewUrl = ''; return; }
        let reader = new FileReader();
        reader.onload = (e) => { this.previewUrl = e.target.result; };
        reader.readAsDataURL(file);
    },
    send() {
        let body = this.$refs.input.value;
        let file = this.$refs.fileInput.files[0];
        if (!body.trim() && !file) return;
        this.loading = true;
        let fd = new FormData();
        fd.append('body', body);
        if (file) fd.append('file', file);
        fetch('{{ route('portal-siswa.pesan.reply', $conversation) }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' },
            body: fd,
        }).then(r => { if (!r.ok) throw new Error('Server error: ' + r.status); return r.json(); })
        .then(msg => {
            let container = document.getElementById('messageContainer');
            let els = container.querySelector('.empty-msg');
            if (els) els.remove();
            let wrapper = document.createElement('div');
            wrapper.className = 'flex justify-end';
            wrapper.dataset.msgId = msg.id;
            let inner = document.createElement('div');
            inner.className = 'max-w-[80%] bg-primary text-on-primary rounded-2xl px-4 py-2.5 relative';
            if (msg.body) { let p = document.createElement('p'); p.className = 'text-sm msg-body'; p.textContent = msg.body; inner.appendChild(p); }
            if (msg.file_url) {
                let link = document.createElement('a');
                link.href = msg.file_url;
                link.target = '_blank';
                if (msg.is_image) {
                    link.className = 'block';
                    let img = document.createElement('img');
                    img.src = msg.file_url;
                    img.className = 'max-w-36 max-h-20 rounded-lg mt-1 object-cover w-full';
                    link.appendChild(img);
                } else {
                    let icon = document.createElement('span');
                    icon.className = 'material-symbols-outlined text-[18px]';
                    icon.textContent = 'attach_file';
                    link.className = 'flex items-center gap-2 mt-1 text-sm underline';
                    link.appendChild(icon);
                    link.append(' ' + (msg.file_name || 'File'));
                }
                inner.appendChild(link);
            }
            if (msg.created_at) {
                let p2 = document.createElement('p');
                p2.className = 'text-xs mt-1 opacity-70';
                p2.textContent = new Date(msg.created_at).toLocaleString('id-ID');
                inner.appendChild(p2);
            }
            wrapper.appendChild(inner);
            container.appendChild(wrapper);
            container.scrollTop = container.scrollHeight;
            this.$refs.input.value = '';
            this.$refs.fileInput.value = '';
            this.fileName = '';
            this.previewUrl = '';
        }).catch(err => { console.error('Send error:', err); alert('Gagal mengirim: ' + err.message); })
        .finally(() => { this.loading = false; });
    }
}"
x-init="
    Echo.private('conversation.{{ Auth::id() }}')
        .listen('MessageSent', (e) => {
            if (e.conversation_id !== conversationId) return;
            try {
                let container = document.getElementById('messageContainer');
                if (!container) return;
                let wrapper = document.createElement('div');
                wrapper.className = 'flex ' + (e.sender_id === {{ Auth::id() }} ? 'justify-end' : 'justify-start');
                if (e.sender_id === {{ Auth::id() }}) wrapper.dataset.msgId = e.id;
                let align = e.sender_id === {{ Auth::id() }} ? 'bg-primary text-on-primary' : 'bg-surface-container-low text-on-surface';
                let html = '<div class=\'max-w-[80%] ' + align + ' rounded-2xl px-4 py-2.5\'>';
                if (e.body) html += '<p class=\'text-sm\'>' + e.body.replace(/</g, '&lt;') + '</p>';
                if (e.file_url) {
                    if (e.is_image) {
                        html += '<a href=\'' + e.file_url + '\' target=\'_blank\' class=\'block\'><img src=\'' + e.file_url + '\' class=\'max-w-36 max-h-20 rounded-lg mt-1 object-cover w-full\'></a>';
                    } else {
                        html += '<a href=\'' + e.file_url + '\' target=\'_blank\' class=\'flex items-center gap-2 mt-1 text-sm underline\'>' + (e.file_name || 'File') + '</a>';
                    }
                }
                if (e.created_at) {
                    html += '<p class=\'text-xs mt-1 opacity-70\'>' + new Date(e.created_at).toLocaleString('id-ID') + '</p>';
                }
                html += '</div>';
                wrapper.innerHTML = html;
                container.appendChild(wrapper);
                container.scrollTop = container.scrollHeight;
            } catch (err) { console.error('Echo error:', err); }
        });
">

    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('portal-siswa.pesan') }}" class="inline-flex items-center gap-1 text-outline hover:text-primary transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div class="flex-1">
            <h2 class="font-headline-lg text-headline-lg text-primary">{{ $conversation->subjek ?? 'Pesan' }}</h2>
            <p class="text-on-surface-variant font-body-md">
                {{ $conversation->participants->where('id', '!=', Auth::id())->first()?->name ?? 'Unknown' }}
            </p>
        </div>
        <button @click="selectMode = !selectMode; if(!selectMode) selectedIds=[]; else cancelEdit()" class="p-2 rounded-lg border border-outline-variant hover:bg-surface-container transition-colors" title="Pilih pesan">
            <span class="material-symbols-outlined text-on-surface-variant" x-text="selectMode ? 'close' : 'checklist'">checklist</span>
        </button>
    </div>

    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-4 mb-4 space-y-4 max-h-[60vh] overflow-y-auto" id="messageContainer">
        @forelse($conversation->messages as $message)
        <div class="flex {{ $message->sender_id === Auth::id() ? 'justify-end' : 'justify-start' }}" data-msg-id="{{ $message->id }}">
            @if($message->sender_id === Auth::id())
            <div class="flex items-center" x-show="selectMode" x-cloak>
                <input type="checkbox" value="{{ $message->id }}" :checked="selectedIds.includes({{ $message->id }})" @change="toggleSelect({{ $message->id }})" class="w-4 h-4 accent-primary mr-2">
            </div>
            @endif
            <div class="max-w-[80%] {{ $message->sender_id === Auth::id() ? 'bg-primary text-on-primary' : 'bg-surface-container-low text-on-surface' }} rounded-2xl px-4 py-2.5 relative group">
                @if($message->sender_id === Auth::id() && $message->body)
                <button @click="startEdit({{ $message->id }})" x-show="!selectMode" class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity p-0.5 rounded hover:bg-black/10" title="Edit">
                    <span class="material-symbols-outlined text-[16px]">edit</span>
                </button>
                @endif
                @if($message->body)
                <p class="text-sm msg-body" x-show="editingId !== {{ $message->id }}">{{ $message->body }}</p>
                @endif
                @if($message->sender_id === Auth::id())
                <div x-show="editingId === {{ $message->id }}" x-cloak class="space-y-1">
                    <textarea x-model="editBody" class="w-full px-2 py-1 border border-outline-variant rounded text-sm text-on-surface bg-surface resize-none focus:ring-2 focus:ring-primary outline-none" rows="2"></textarea>
                    <div class="flex gap-1 justify-end">
                        <button @click.stop="saveEdit({{ $message->id }})" class="text-xs px-2 py-0.5 bg-primary text-on-primary rounded hover:opacity-90">Simpan</button>
                        <button @click.stop="cancelEdit()" class="text-xs px-2 py-0.5 bg-outline text-on-surface rounded hover:opacity-90">Batal</button>
                    </div>
                </div>
                @endif
                @if($message->file_path)
                    @if($message->isImage())
                    <a href="{{ $message->fileUrl() }}" target="_blank" class="block">
                        <img src="{{ $message->fileUrl() }}" class="max-w-36 max-h-20 rounded-lg mt-1 object-cover w-full" loading="lazy">
                    </a>
                    @else
                    <a href="{{ $message->fileUrl() }}" target="_blank" class="flex items-center gap-2 mt-1 text-sm underline">
                        <span class="material-symbols-outlined text-[18px]">attach_file</span>
                        {{ $message->file_name }}
                    </a>
                    @endif
                @endif
                <p class="text-xs mt-1 opacity-70">{{ $message->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
        @empty
        <p class="text-center text-outline py-8 empty-msg">Belum ada pesan</p>
        @endforelse
    </div>

    <div x-show="selectMode && selectedIds.length > 0" x-transition x-cloak class="mb-4">
        <button @click="deleteSelected()" class="w-full bg-error text-on-error py-2 rounded-xl font-label-md hover:bg-error/90 transition-colors flex items-center justify-center gap-2">
            <span class="material-symbols-outlined">delete</span>
            Hapus <span x-text="selectedIds.length"></span> pesan
        </button>
    </div>

    <form @submit.prevent="send" class="bg-white rounded-xl border border-outline-variant card-shadow p-4 flex gap-3 items-end">
        @csrf
        <div class="flex-1 flex flex-col gap-2">
            <template x-if="previewUrl">
                <div class="relative inline-block self-start">
                    <img :src="previewUrl" class="max-h-24 max-w-48 rounded-lg border border-outline-variant">
                    <button type="button" @click="previewUrl = ''; $refs.fileInput.value = ''; fileName = ''" class="absolute -top-2 -right-2 bg-surface text-on-surface rounded-full w-5 h-5 flex items-center justify-center shadow text-xs leading-none">&times;</button>
                </div>
            </template>
            <div class="flex gap-2 items-end">
                <textarea x-ref="input" name="body" rows="2" class="flex-1 px-3 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none resize-none" placeholder="Ketik pesan..."></textarea>
                <button type="button" @click="$refs.fileInput.click()" class="p-2 rounded-lg border border-outline-variant hover:bg-surface-container transition-colors" title="Lampirkan file">
                    <span class="material-symbols-outlined text-on-surface-variant">attach_file</span>
                </button>
                <button type="submit" :disabled="loading" class="bg-primary text-on-primary px-4 py-2 rounded-xl font-label-md hover:bg-primary-container transition-colors disabled:opacity-50 self-end">
                    <span class="material-symbols-outlined" x-text="loading ? 'hourglass_top' : 'send'">send</span>
                </button>
            </div>
            <input type="file" x-ref="fileInput" name="file" accept="image/*,.pdf,.doc,.docx,.zip,.rar" class="hidden" @change="fileName = $el.files[0]?.name || ''; previewFile()">
            <template x-if="fileName">
                <span class="text-xs text-on-surface-variant flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">attach_file</span>
                    <span x-text="fileName"></span>
                </span>
            </template>
        </div>
    </form>
</div>
</x-layouts.portal-siswa>
