<x-layouts.admin title="Pengaturan Website | EduPortal">
    <div x-data="previewSettings()" x-init="s = @js($settings); restore();" class="relative">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="font-headline-lg text-headline-lg text-primary">Pengaturan Website</h2>
                <p class="text-on-surface-variant font-body-md">Kelola identitas sekolah, konten halaman, dan
                    konfigurasi portal publik</p>
            </div>
            <button @click="showPreview = !showPreview" type="button"
                class="px-5 py-2.5 rounded-xl font-label-md flex items-center gap-2 transition-all"
                :class="showPreview ? 'bg-secondary text-on-secondary' : 'bg-surface-container-high text-on-surface-variant hover:bg-outline-variant'">
                <span class="material-symbols-outlined text-[20px]">visibility</span>
                <span x-text="showPreview ? 'Tutup Preview' : 'Live Preview'"></span>
            </button>
        </div>

        <div class="flex gap-gutter" :class="showPreview ? 'flex-col lg:flex-row' : ''">

            {{-- Form --}}
            <div class="flex-1 min-w-0">
                <form method="POST" action="{{ route('admin.pengaturan-website.store') }}"
                    enctype="multipart/form-data">
                    @csrf

                    {{-- Bento Grid --}}
                    <div class="grid grid-cols-12 gap-gutter">

                        {{-- Identitas Sekolah --}}
                        <section
                            class="col-span-12 lg:col-span-7 bg-surface-container-lowest border border-outline-variant rounded-xl p-6 card-shadow">
                            <div class="flex items-center gap-3 mb-6">
                                <span
                                    class="material-symbols-outlined text-primary bg-primary/5 p-2 rounded-lg">business</span>
                                <h3 class="font-headline-sm text-headline-sm text-primary">Identitas Sekolah</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="md:col-span-2">
                                    <label class="block font-label-md text-label-md text-on-surface-variant mb-2">Nama
                                        Sekolah</label>
                                    <input x-model="s.nama_sekolah" type="text" name="nama_sekolah"
                                        class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 text-body-md">
                                </div>
                                <div>
                                    <label
                                        class="block font-label-md text-label-md text-on-surface-variant mb-2">Akreditasi</label>
                                    <input x-model="s.akreditasi" type="text" name="akreditasi" placeholder="A"
                                        class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 text-body-md">
                                </div>
                                <div>
                                    <label class="block font-label-md text-label-md text-on-surface-variant mb-2">Tahun
                                        Ajaran Aktif</label>
                                    <input x-model="s.tahun_ajaran" type="text" name="tahun_ajaran"
                                        placeholder="2025/2026"
                                        class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 text-body-md">
                                </div>
                                <div class="md:col-span-2">
                                    <label
                                        class="block font-label-md text-label-md text-on-surface-variant mb-2">Alamat</label>
                                    <textarea x-model="s.alamat" name="alamat" rows="2"
                                        class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 text-body-md"></textarea>
                                </div>
                                <div>
                                    <label
                                        class="block font-label-md text-label-md text-on-surface-variant mb-2">Telepon</label>
                                    <input x-model="s.telepon" type="text" name="telepon"
                                        class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 text-body-md">
                                </div>
                                <div>
                                    <label
                                        class="block font-label-md text-label-md text-on-surface-variant mb-2">Email</label>
                                    <input x-model="s.email_sekolah" type="email" name="email_sekolah"
                                        class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 text-body-md">
                                </div>
                                <div>
                                    <label
                                        class="block font-label-md text-label-md text-on-surface-variant mb-2">Website</label>
                                    <input x-model="s.website" type="text" name="website"
                                        class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 text-body-md">
                                </div>
                            </div>
                        </section>

                        {{-- Aset Visual --}}
                        <section
                            class="col-span-12 lg:col-span-5 bg-surface-container-lowest border border-outline-variant rounded-xl p-6 card-shadow">
                            <div class="flex items-center gap-3 mb-6">
                                <span
                                    class="material-symbols-outlined text-primary bg-primary/5 p-2 rounded-lg">image</span>
                                <h3 class="font-headline-sm text-headline-sm text-primary">Aset Visual</h3>
                            </div>
                            <div class="space-y-6">
                                {{-- Logo Sekolah --}}
                                <div>
                                    <label class="block font-label-md text-label-md text-on-surface-variant mb-3">Logo
                                        Sekolah</label>
                                    <input type="file" name="logo_sekolah"
                                        accept="image/jpeg,image/png,image/jpg,image/webp" x-ref="logoInput"
                                        @change="handleImageSelect($event, 'logo')" class="hidden">
                                    <template x-if="!images.logo && !s.logo_sekolah">
                                        <div @dragover.prevent="dragTarget = 'logo'; dragging = true"
                                            @dragleave.prevent="dragTarget = null; dragging = false"
                                            @drop.prevent="handleImageDrop($event, 'logo')"
                                            class="relative border-2 border-dashed rounded-xl p-6 text-center transition-colors cursor-pointer"
                                            :class="dragging && dragTarget === 'logo' ? 'border-secondary bg-orange-50' : 'border-outline-variant hover:border-gray-400'"
                                            @click="selectImage('logo')">
                                            <span
                                                class="material-symbols-outlined text-3xl text-gray-300 mb-2">cloud_upload</span>
                                            <p class="text-sm text-gray-500">Seret logo ke sini atau <span
                                                    class="text-primary font-semibold">klik untuk memilih</span></p>
                                            <p class="text-xs text-gray-400 mt-1">JPEG, PNG, WebP. Maks 2MB</p>
                                        </div>
                                    </template>
                                    <template x-if="images.logo || s.logo_sekolah">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="relative inline-block">
                                                <img :src="images.logo || s.logo_sekolah"
                                                    class="max-h-24 rounded-lg mx-auto shadow-sm">
                                                <button type="button" @click.stop="removeImage('logo')"
                                                    class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-colors">
                                                    <span class="material-symbols-outlined !text-[14px]">close</span>
                                                </button>
                                            </div>
                                            <button type="button" @click="selectImage('logo')"
                                                class="text-xs text-primary font-semibold hover:underline">Ubah
                                                Logo</button>
                                        </div>
                                    </template>
                                </div>
                                {{-- Favicon --}}
                                <div class="border-t border-outline-variant pt-5">
                                    <label
                                        class="block font-label-md text-label-md text-on-surface-variant mb-3">Favicon</label>
                                    <input type="file" name="favicon" accept="image/x-icon,image/png,image/jpeg"
                                        x-ref="faviconInput" @change="handleImageSelect($event, 'favicon')"
                                        class="hidden">
                                    <template x-if="!images.favicon && !s.favicon">
                                        <div @dragover.prevent="dragTarget = 'favicon'; dragging = true"
                                            @dragleave.prevent="dragTarget = null; dragging = false"
                                            @drop.prevent="handleImageDrop($event, 'favicon')"
                                            class="relative border-2 border-dashed rounded-xl p-6 text-center transition-colors cursor-pointer"
                                            :class="dragging && dragTarget === 'favicon' ? 'border-secondary bg-orange-50' : 'border-outline-variant hover:border-gray-400'"
                                            @click="selectImage('favicon')">
                                            <span
                                                class="material-symbols-outlined text-3xl text-gray-300 mb-2">add_photo_alternate</span>
                                            <p class="text-sm text-gray-500">Seret favicon ke sini atau <span
                                                    class="text-primary font-semibold">klik untuk memilih</span></p>
                                            <p class="text-xs text-gray-400 mt-1">ICO, PNG. 32x32 px</p>
                                        </div>
                                    </template>
                                    <template x-if="images.favicon || s.favicon">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="relative inline-block">
                                                <img :src="images.favicon || s.favicon"
                                                    class="max-h-12 rounded mx-auto shadow-sm">
                                                <button type="button" @click.stop="removeImage('favicon')"
                                                    class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-colors">
                                                    <span class="material-symbols-outlined !text-[14px]">close</span>
                                                </button>
                                            </div>
                                            <button type="button" @click="selectImage('favicon')"
                                                class="text-xs text-primary font-semibold hover:underline">Ubah
                                                Favicon</button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </section>

                        {{-- Profil Sekolah --}}
                        <section
                            class="col-span-12 lg:col-span-7 bg-surface-container-lowest border border-outline-variant rounded-xl p-6 card-shadow">
                            <div class="flex items-center gap-3 mb-6">
                                <span
                                    class="material-symbols-outlined text-primary bg-primary/5 p-2 rounded-lg">school</span>
                                <h3 class="font-headline-sm text-headline-sm text-primary">Profil Sekolah</h3>
                            </div>
                            <div class="space-y-5">
                                <div>
                                    <label class="block font-label-md text-label-md text-on-surface-variant mb-2">Kepala
                                        Sekolah</label>
                                    <input x-model="s.kepala_sekolah" type="text" name="kepala_sekolah"
                                        class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 text-body-md">
                                </div>
                                <div>
                                    <label
                                        class="block font-label-md text-label-md text-on-surface-variant mb-2">Visi</label>
                                    <textarea x-model="s.visi" name="visi" rows="2"
                                        class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 text-body-md"></textarea>
                                </div>
                                <div>
                                    <label class="block font-label-md text-label-md text-on-surface-variant mb-2">Misi
                                        <span class="text-body-sm text-on-surface-variant">(pisahkan tiap baris dengan
                                            enter)</span></label>
                                    <textarea x-model="s.misi" name="misi" rows="3"
                                        class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 text-body-md"></textarea>
                                </div>
                                <div>
                                    <label
                                        class="block font-label-md text-label-md text-on-surface-variant mb-2">Tentang
                                        Sekolah</label>
                                    <textarea x-model="s.tentang" name="tentang" rows="3"
                                        class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 text-body-md"></textarea>
                                </div>
                            </div>
                        </section>

                        {{-- Hero Pages --}}
                        <section
                            class="col-span-12 lg:col-span-7 bg-surface-container-lowest border border-outline-variant rounded-xl p-6 card-shadow">
                            <div class="flex items-center gap-3 mb-6">
                                <span
                                    class="material-symbols-outlined text-primary bg-primary/5 p-2 rounded-lg">web</span>
                                <h3 class="font-headline-sm text-headline-sm text-primary">Hero Halaman</h3>
                            </div>
                            <div class="space-y-6">
                                <div>
                                    <div class="flex items-center gap-2 mb-3">
                                        <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                                        <h4 class="font-label-md text-label-md text-on-surface">Beranda</h4>
                                    </div>
                                    <div class="space-y-4 pl-4">
                                        <div>
                                            <label
                                                class="block font-label-md text-label-md text-on-surface-variant mb-2">Hero
                                                Title</label>
                                            <textarea x-model="s.home_hero_title" name="home_hero_title" rows="2"
                                                class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 text-body-md"></textarea>
                                        </div>
                                        <div>
                                            <label
                                                class="block font-label-md text-label-md text-on-surface-variant mb-2">Hero
                                                Subtitle</label>
                                            <textarea x-model="s.home_hero_subtitle" name="home_hero_subtitle" rows="2"
                                                class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 text-body-md"></textarea>
                                        </div>
                                        <div>
                                            <label
                                                class="block font-label-md text-label-md text-on-surface-variant mb-2">Hero
                                                Gambar Latar</label>
                                            <input type="file" name="home_hero_image"
                                                accept="image/jpeg,image/png,image/jpg,image/webp" x-ref="homeHeroInput"
                                                @change="handleImageSelect($event, 'home_hero')" class="hidden">
                                            <template x-if="!images.home_hero && !s.home_hero_image">
                                                <div @dragover.prevent="dragTarget = 'home_hero'; dragging = true"
                                                    @dragleave.prevent="dragTarget = null; dragging = false"
                                                    @drop.prevent="handleImageDrop($event, 'home_hero')"
                                                    class="relative border-2 border-dashed rounded-xl p-4 text-center transition-colors cursor-pointer"
                                                    :class="dragging && dragTarget === 'home_hero' ? 'border-secondary bg-orange-50' : 'border-outline-variant hover:border-gray-400'"
                                                    @click="selectImage('home_hero')">
                                                    <span
                                                        class="material-symbols-outlined text-2xl text-gray-300 mb-1">add_photo_alternate</span>
                                                    <p class="text-xs text-gray-500">Seret gambar atau <span
                                                            class="text-primary font-semibold">pilih</span></p>
                                                </div>
                                            </template>
                                            <template x-if="images.home_hero || s.home_hero_image">
                                                <div class="flex items-center gap-3">
                                                    <div class="relative inline-block flex-shrink-0">
                                                        <img :src="images.home_hero || s.home_hero_image"
                                                            class="h-16 w-24 object-cover rounded-lg shadow-sm border border-outline-variant">
                                                        <button type="button" @click.stop="removeImage('home_hero')"
                                                            class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-colors">
                                                            <span
                                                                class="material-symbols-outlined !text-[12px]">close</span>
                                                        </button>
                                                    </div>
                                                    <button type="button" @click="selectImage('home_hero')"
                                                        class="text-xs text-primary font-semibold hover:underline">Ubah</button>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                                <div class="border-t border-outline-variant pt-4">
                                    <div class="flex items-center gap-2 mb-3">
                                        <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                                        <h4 class="font-label-md text-label-md text-on-surface">Profil</h4>
                                    </div>
                                    <div class="space-y-4 pl-4">
                                        <div>
                                            <label
                                                class="block font-label-md text-label-md text-on-surface-variant mb-2">Hero
                                                Title</label>
                                            <input x-model="s.profil_hero_title" type="text" name="profil_hero_title"
                                                class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 text-body-md">
                                        </div>
                                        <div>
                                            <label
                                                class="block font-label-md text-label-md text-on-surface-variant mb-2">Hero
                                                Subtitle</label>
                                            <textarea x-model="s.profil_hero_subtitle" name="profil_hero_subtitle"
                                                rows="2"
                                                class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 text-body-md"></textarea>
                                        </div>
                                        <div>
                                            <label
                                                class="block font-label-md text-label-md text-on-surface-variant mb-2">Hero
                                                Gambar Latar</label>
                                            <input type="file" name="profil_hero_image"
                                                accept="image/jpeg,image/png,image/jpg,image/webp"
                                                x-ref="profilHeroInput"
                                                @change="handleImageSelect($event, 'profil_hero')" class="hidden">
                                            <template x-if="!images.profil_hero && !s.profil_hero_image">
                                                <div @dragover.prevent="dragTarget = 'profil_hero'; dragging = true"
                                                    @dragleave.prevent="dragTarget = null; dragging = false"
                                                    @drop.prevent="handleImageDrop($event, 'profil_hero')"
                                                    class="relative border-2 border-dashed rounded-xl p-4 text-center transition-colors cursor-pointer"
                                                    :class="dragging && dragTarget === 'profil_hero' ? 'border-secondary bg-orange-50' : 'border-outline-variant hover:border-gray-400'"
                                                    @click="selectImage('profil_hero')">
                                                    <span
                                                        class="material-symbols-outlined text-2xl text-gray-300 mb-1">add_photo_alternate</span>
                                                    <p class="text-xs text-gray-500">Seret gambar atau <span
                                                            class="text-primary font-semibold">pilih</span></p>
                                                </div>
                                            </template>
                                            <template x-if="images.profil_hero || s.profil_hero_image">
                                                <div class="flex items-center gap-3">
                                                    <div class="relative inline-block flex-shrink-0">
                                                        <img :src="images.profil_hero || s.profil_hero_image"
                                                            class="h-16 w-24 object-cover rounded-lg shadow-sm border border-outline-variant">
                                                        <button type="button" @click.stop="removeImage('profil_hero')"
                                                            class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-colors">
                                                            <span
                                                                class="material-symbols-outlined !text-[12px]">close</span>
                                                        </button>
                                                    </div>
                                                    <button type="button" @click="selectImage('profil_hero')"
                                                        class="text-xs text-primary font-semibold hover:underline">Ubah</button>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                                <div class="border-t border-outline-variant pt-4">
                                    <div class="flex items-center gap-2 mb-3">
                                        <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                                        <h4 class="font-label-md text-label-md text-on-surface">Akademik</h4>
                                    </div>
                                    <div class="space-y-4 pl-4">
                                        <div>
                                            <label
                                                class="block font-label-md text-label-md text-on-surface-variant mb-2">Hero
                                                Title</label>
                                            <input x-model="s.akademik_hero_title" type="text"
                                                name="akademik_hero_title"
                                                class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 text-body-md">
                                        </div>
                                        <div>
                                            <label
                                                class="block font-label-md text-label-md text-on-surface-variant mb-2">Hero
                                                Subtitle</label>
                                            <textarea x-model="s.akademik_hero_subtitle" name="akademik_hero_subtitle"
                                                rows="2"
                                                class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 text-body-md"></textarea>
                                        </div>
                                        <div>
                                            <label
                                                class="block font-label-md text-label-md text-on-surface-variant mb-2">Hero
                                                Gambar Latar</label>
                                            <input type="file" name="akademik_hero_image"
                                                accept="image/jpeg,image/png,image/jpg,image/webp"
                                                x-ref="akademikHeroInput"
                                                @change="handleImageSelect($event, 'akademik_hero')" class="hidden">
                                            <template x-if="!images.akademik_hero && !s.akademik_hero_image">
                                                <div @dragover.prevent="dragTarget = 'akademik_hero'; dragging = true"
                                                    @dragleave.prevent="dragTarget = null; dragging = false"
                                                    @drop.prevent="handleImageDrop($event, 'akademik_hero')"
                                                    class="relative border-2 border-dashed rounded-xl p-4 text-center transition-colors cursor-pointer"
                                                    :class="dragging && dragTarget === 'akademik_hero' ? 'border-secondary bg-orange-50' : 'border-outline-variant hover:border-gray-400'"
                                                    @click="selectImage('akademik_hero')">
                                                    <span
                                                        class="material-symbols-outlined text-2xl text-gray-300 mb-1">add_photo_alternate</span>
                                                    <p class="text-xs text-gray-500">Seret gambar atau <span
                                                            class="text-primary font-semibold">pilih</span></p>
                                                </div>
                                            </template>
                                            <template x-if="images.akademik_hero || s.akademik_hero_image">
                                                <div class="flex items-center gap-3">
                                                    <div class="relative inline-block flex-shrink-0">
                                                        <img :src="images.akademik_hero || s.akademik_hero_image"
                                                            class="h-16 w-24 object-cover rounded-lg shadow-sm border border-outline-variant">
                                                        <button type="button" @click.stop="removeImage('akademik_hero')"
                                                            class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-colors">
                                                            <span
                                                                class="material-symbols-outlined !text-[12px]">close</span>
                                                        </button>
                                                    </div>
                                                    <button type="button" @click="selectImage('akademik_hero')"
                                                        class="text-xs text-primary font-semibold hover:underline">Ubah</button>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                                <div class="border-t border-outline-variant pt-4">
                                    <div class="flex items-center gap-2 mb-3">
                                        <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                                        <h4 class="font-label-md text-label-md text-on-surface">Berita</h4>
                                    </div>
                                    <div class="pl-4">
                                        <label
                                            class="block font-label-md text-label-md text-on-surface-variant mb-2">Hero
                                            Title</label>
                                        <input x-model="s.berita_hero_title" type="text" name="berita_hero_title"
                                            class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 text-body-md">
                                    </div>
                                </div>
                            </div>
                        </section>

                        {{-- Statistik Beranda --}}
                        <section
                            class="col-span-12 lg:col-span-5 bg-surface-container-lowest border border-outline-variant rounded-xl p-6 card-shadow">
                            <div class="flex items-center gap-3 mb-6">
                                <span
                                    class="material-symbols-outlined text-primary bg-primary/5 p-2 rounded-lg">bar_chart</span>
                                <h3 class="font-headline-sm text-headline-sm text-primary">Statistik Beranda</h3>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-label-md text-label-md text-on-surface-variant mb-2">Label
                                        Siswa</label>
                                    <input x-model="s.home_stats_siswa_label" type="text" name="home_stats_siswa_label"
                                        placeholder="Siswa"
                                        class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 text-body-md">
                                </div>
                                <div>
                                    <label class="block font-label-md text-label-md text-on-surface-variant mb-2">Label
                                        Guru</label>
                                    <input x-model="s.home_stats_guru_label" type="text" name="home_stats_guru_label"
                                        placeholder="Guru"
                                        class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 text-body-md">
                                </div>
                                <div>
                                    <label class="block font-label-md text-label-md text-on-surface-variant mb-2">Label
                                        Ekskul</label>
                                    <input x-model="s.home_stats_ekskul_label" type="text"
                                        name="home_stats_ekskul_label" placeholder="Ekskul"
                                        class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 text-body-md">
                                </div>
                                <div>
                                    <label class="block font-label-md text-label-md text-on-surface-variant mb-2">Label
                                        Prestasi</label>
                                    <input x-model="s.home_stats_prestasi_label" type="text"
                                        name="home_stats_prestasi_label" placeholder="Prestasi"
                                        class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 text-body-md">
                                </div>
                            </div>
                        </section>

                        {{-- Halaman Kontak --}}
                        <section
                            class="col-span-12 bg-surface-container-lowest border border-outline-variant rounded-xl p-6 card-shadow">
                            <div class="flex items-center gap-3 mb-6">
                                <span
                                    class="material-symbols-outlined text-primary bg-primary/5 p-2 rounded-lg">contact_mail</span>
                                <h3 class="font-headline-sm text-headline-sm text-primary">Halaman Kontak</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                                <div class="md:col-span-2 lg:col-span-2">
                                    <label
                                        class="block font-label-md text-label-md text-on-surface-variant mb-2">Alamat</label>
                                    <textarea x-model="s.kontak_alamat" name="kontak_alamat" rows="2"
                                        class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 text-body-md"></textarea>
                                </div>
                                <div>
                                    <label
                                        class="block font-label-md text-label-md text-on-surface-variant mb-2">Telepon</label>
                                    <input x-model="s.kontak_telepon" type="text" name="kontak_telepon"
                                        class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 text-body-md">
                                </div>
                                <div>
                                    <label
                                        class="block font-label-md text-label-md text-on-surface-variant mb-2">Email</label>
                                    <input x-model="s.kontak_email" type="email" name="kontak_email"
                                        class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 text-body-md">
                                </div>
                                <div>
                                    <label class="block font-label-md text-label-md text-on-surface-variant mb-2">Google
                                        Maps URL</label>
                                    <input x-model="s.kontak_maps_url" type="text" name="kontak_maps_url"
                                        class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 text-body-md">
                                </div>
                                <div class="md:col-span-2 lg:col-span-3 border-t border-outline-variant pt-5 mt-2">
                                    <div class="flex items-center gap-2 mb-4">
                                        <span
                                            class="material-symbols-outlined text-secondary !text-[20px]">schedule</span>
                                        <h4 class="font-label-md text-label-md text-on-surface">Jam Operasional</h4>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                        <div>
                                            <label
                                                class="block font-label-md text-label-md text-on-surface-variant mb-2">Senin-Kamis</label>
                                            <input x-model="s.kontak_jam_senin_kamis" type="text"
                                                name="kontak_jam_senin_kamis" placeholder="07:00 - 15:30"
                                                class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 text-body-md">
                                        </div>
                                        <div>
                                            <label
                                                class="block font-label-md text-label-md text-on-surface-variant mb-2">Jumat</label>
                                            <input x-model="s.kontak_jam_jumat" type="text" name="kontak_jam_jumat"
                                                placeholder="07:00 - 11:30"
                                                class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 text-body-md">
                                        </div>
                                        <div>
                                            <label
                                                class="block font-label-md text-label-md text-on-surface-variant mb-2">Sabtu</label>
                                            <input x-model="s.kontak_jam_sabtu" type="text" name="kontak_jam_sabtu"
                                                placeholder="08:00 - 12:00"
                                                class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 text-body-md">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                    </div>

                    {{-- Save Bar --}}
                    <div class="mt-8 flex justify-end border-t border-outline-variant pt-6">
                        <button type="submit"
                            class="w-full sm:w-auto px-8 py-3 bg-primary text-on-primary rounded-xl font-label-md hover:bg-primary/90 transition-all flex items-center justify-center gap-2 shadow-lg hover:shadow-xl active:scale-95">
                            <span class="material-symbols-outlined">save</span>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            {{-- Live Preview Panel --}}
            <div x-show="showPreview" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 lg:translate-y-0 lg:translate-x-4"
                x-transition:enter-end="opacity-100 translate-y-0 lg:translate-x-0"
                class="w-full lg:w-[420px] xl:w-[480px] flex-shrink-0">
                <div class="sticky top-28 bg-white border border-outline-variant rounded-xl shadow-xl overflow-hidden">

                    {{-- Toolbar --}}
                    <div class="flex items-center justify-between px-3 py-2 bg-gray-50 border-b border-gray-200">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-red-400"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-yellow-400"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-green-400"></span>
                        </div>
                        <span class="text-[11px] text-gray-400 font-medium">Preview</span>
                        <div class="flex items-center gap-0.5">
                            <button @click="device = 'full'" type="button" class="p-1 rounded transition-colors"
                                :class="device === 'full' ? 'text-primary bg-primary/10' : 'text-gray-400 hover:text-gray-600'">
                                <span class="material-symbols-outlined !text-[16px]">desktop_windows</span>
                            </button>
                            <button @click="device = 'tablet'" type="button" class="p-1 rounded transition-colors"
                                :class="device === 'tablet' ? 'text-primary bg-primary/10' : 'text-gray-400 hover:text-gray-600'">
                                <span class="material-symbols-outlined !text-[16px]">tablet_mac</span>
                            </button>
                            <button @click="device = 'mobile'" type="button" class="p-1 rounded transition-colors"
                                :class="device === 'mobile' ? 'text-primary bg-primary/10' : 'text-gray-400 hover:text-gray-600'">
                                <span class="material-symbols-outlined !text-[16px]">phone_iphone</span>
                            </button>
                        </div>
                    </div>

                    {{-- Page tabs --}}
                    <div class="flex border-b border-gray-200 bg-white">
                        <template x-for="tab in ['Home','Profil','Akademik','Berita','Kontak']" :key="tab">
                            <button @click="page = tab.toLowerCase()" type="button"
                                class="flex-1 text-center text-[11px] font-medium py-2 transition-colors relative"
                                :class="page === tab.toLowerCase() ? 'text-primary' : 'text-gray-500 hover:text-gray-700'">
                                <span x-text="tab"></span>
                                <span x-show="page === tab.toLowerCase()"
                                    class="absolute bottom-0 left-2 right-2 h-0.5 bg-primary rounded-full"></span>
                            </button>
                        </template>
                    </div>

                    {{-- Preview content with device width constraint --}}
                    <div class="flex justify-center bg-gray-100 transition-all duration-300"
                        :class="device === 'full' ? 'p-0' : 'p-3'">
                        <div class="bg-white overflow-y-auto transition-all duration-300" :class="{
                            'w-full max-h-[65vh]': device === 'full',
                            'w-[420px] max-h-[65vh] rounded-lg shadow-lg': device === 'tablet',
                            'w-[320px] max-h-[70vh] rounded-xl shadow-xl': device === 'mobile'
                        }">

                            {{-- Home --}}
                            <div x-show="page === 'home'">
                                <div class="flex items-center justify-between px-4 py-2.5 border-b border-gray-100">
                                    <span class="font-bold text-sm"
                                        :class="s.nama_sekolah ? 'text-primary' : 'text-gray-300'"
                                        x-text="s.nama_sekolah || 'Nama Sekolah'"></span>
                                    <div class="flex gap-3 text-[11px] text-gray-400">
                                        <span>Beranda</span>
                                        <span>Profil</span>
                                        <span>Berita</span>
                                        <span>Kontak</span>
                                    </div>
                                </div>
                                <div class="bg-gradient-to-r from-primary to-primary/70 px-5 py-8 text-white">
                                    <div class="text-xl font-bold leading-tight mb-2"
                                        x-text="s.home_hero_title || 'Selamat Datang'"></div>
                                    <div class="text-[13px] text-white/80 mb-4 leading-relaxed"
                                        x-text="s.home_hero_subtitle || 'Membentuk Generasi Unggul...'"></div>
                                    <div class="flex gap-2">
                                        <span
                                            class="px-4 py-2 rounded-lg bg-secondary text-white text-[11px] font-bold">Daftar</span>
                                        <span
                                            class="px-4 py-2 rounded-lg border border-white text-white text-[11px] font-bold">Eksplor</span>
                                    </div>
                                </div>
                                <div class="grid grid-cols-4 divide-x divide-gray-200 py-3 text-center">
                                    <div>
                                        <div class="font-bold text-base text-primary"
                                            x-text="$store.preview?.totalSiswa || '0'"></div>
                                        <div class="text-[10px] text-gray-500 uppercase tracking-wider mt-0.5"
                                            x-text="s.home_stats_siswa_label || 'Siswa'"></div>
                                    </div>
                                    <div>
                                        <div class="font-bold text-base text-primary"
                                            x-text="$store.preview?.totalGuru || '0'"></div>
                                        <div class="text-[10px] text-gray-500 uppercase tracking-wider mt-0.5"
                                            x-text="s.home_stats_guru_label || 'Guru'"></div>
                                    </div>
                                    <div>
                                        <div class="font-bold text-base text-primary">24</div>
                                        <div class="text-[10px] text-gray-500 uppercase tracking-wider mt-0.5"
                                            x-text="s.home_stats_ekskul_label || 'Ekskul'"></div>
                                    </div>
                                    <div>
                                        <div class="font-bold text-base text-primary"
                                            x-text="$store.preview?.totalPrestasi || '0'"></div>
                                        <div class="text-[10px] text-gray-500 uppercase tracking-wider mt-0.5"
                                            x-text="s.home_stats_prestasi_label || 'Prestasi'"></div>
                                    </div>
                                </div>
                                <div class="bg-primary px-4 py-5 text-white text-[11px] space-y-1.5">
                                    <div class="font-bold text-sm" x-text="s.nama_sekolah || 'SMA Nusantara'"></div>
                                    <div class="text-white/70" x-text="s.tentang || 'Deskripsi sekolah...'"></div>
                                    <div class="text-white/40 pt-2 border-t border-white/10"
                                        x-text="'© ' + new Date().getFullYear()"></div>
                                </div>
                            </div>

                            {{-- Profil --}}
                            <div x-show="page === 'profil'">
                                <div class="bg-primary px-5 py-8 text-white">
                                    <div class="text-lg font-bold mb-1"
                                        x-text="s.profil_hero_title || 'Profil Sekolah'"></div>
                                    <div class="text-[12px] text-white/80"
                                        x-text="s.profil_hero_subtitle || 'Membangun generasi pemimpin...'"></div>
                                </div>
                                <div class="p-4 space-y-3">
                                    <div class="flex items-center gap-2 text-sm">
                                        <span class="text-gray-500">Kepala Sekolah:</span>
                                        <span class="font-semibold text-primary"
                                            x-text="s.kepala_sekolah || '-'"></span>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-lg">
                                        <div class="text-xs font-bold text-primary mb-1">Visi</div>
                                        <div class="text-[12px] text-gray-600 italic"
                                            x-text="s.visi || 'Visi sekolah...'"></div>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-primary mb-2">Misi</div>
                                        <template x-if="s.misi">
                                            <div class="space-y-1.5">
                                                <template x-for="(item, i) in s.misi.split('\n').filter(t => t.trim())"
                                                    :key="i">
                                                    <div class="flex gap-2 items-start text-[12px]">
                                                        <span
                                                            class="flex-shrink-0 w-4 h-4 rounded-full bg-secondary text-white flex items-center justify-center text-[8px] font-bold"
                                                            x-text="i + 1"></span>
                                                        <span class="text-gray-600" x-text="item"></span>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="pt-2 border-t border-gray-100">
                                        <div class="text-xs font-bold text-primary mb-1">Tentang</div>
                                        <div class="text-[12px] text-gray-600" x-text="s.tentang || '-'"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Akademik --}}
                            <div x-show="page === 'akademik'">
                                <div class="bg-primary px-5 py-8 text-white">
                                    <div class="text-lg font-bold mb-1"
                                        x-text="s.akademik_hero_title || 'Pusat Keunggulan Akademik'"></div>
                                    <div class="text-[12px] text-white/80"
                                        x-text="s.akademik_hero_subtitle || 'Membentuk generasi emas...'"></div>
                                </div>
                                <div class="p-4 space-y-3">
                                    <div class="flex items-center gap-2 p-3 bg-gray-50 rounded-lg">
                                        <span
                                            class="material-symbols-outlined text-secondary !text-[18px]">menu_book</span>
                                        <div>
                                            <div class="text-xs font-bold text-primary">Kurikulum Merdeka</div>
                                            <div class="text-[11px] text-gray-500">Pembelajaran berkualitas sesuai
                                                kebutuhan.</div>
                                        </div>
                                    </div>
                                    <div class="p-3 bg-primary text-white rounded-lg">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span
                                                class="material-symbols-outlined text-[#FEAF2C] !text-[18px]">calendar_month</span>
                                            <div class="text-xs font-bold">Kalender Akademik</div>
                                        </div>
                                        <div class="text-[11px] text-white/80">Download jadwal akademik 2024/2025.</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Berita --}}
                            <div x-show="page === 'berita'">
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <div class="font-bold text-primary flex items-center gap-1.5">
                                        <span
                                            class="material-symbols-outlined text-secondary !text-[18px]">campaign</span>
                                        <span x-text="s.berita_hero_title || 'Berita Terkini'"></span>
                                    </div>
                                </div>
                                <div class="p-4 space-y-3">
                                    <div class="bg-primary rounded-lg p-3 text-white">
                                        <span
                                            class="px-2 py-0.5 bg-secondary rounded-full text-[9px] font-bold">#Pengumuman</span>
                                        <div class="text-sm font-bold mt-2 mb-1">Judul Berita Utama</div>
                                        <div class="text-[10px] text-white/80 line-clamp-2">Konten berita utama yang
                                            menarik...</div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div class="border border-gray-200 rounded-lg p-2">
                                            <div
                                                class="h-16 bg-gray-100 rounded mb-1.5 flex items-center justify-center">
                                                <span
                                                    class="material-symbols-outlined text-gray-300 !text-[20px]">image</span>
                                            </div>
                                            <div class="text-[9px] text-secondary font-bold">#Prestasi</div>
                                            <div class="text-[11px] font-bold text-primary line-clamp-2">Judul Berita
                                            </div>
                                        </div>
                                        <div class="border border-gray-200 rounded-lg p-2">
                                            <div
                                                class="h-16 bg-gray-100 rounded mb-1.5 flex items-center justify-center">
                                                <span
                                                    class="material-symbols-outlined text-gray-300 !text-[20px]">image</span>
                                            </div>
                                            <div class="text-[9px] text-secondary font-bold">#Akademik</div>
                                            <div class="text-[11px] font-bold text-primary line-clamp-2">Judul Berita
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Kontak --}}
                            <div x-show="page === 'kontak'">
                                <div class="p-4 space-y-3">
                                    <div class="bg-primary text-white p-4 rounded-lg space-y-2.5">
                                        <div class="text-sm font-bold">Informasi Kontak</div>
                                        <div class="flex gap-2 text-[12px]">
                                            <span
                                                class="material-symbols-outlined text-[#FEAF2C] !text-[16px]">location_on</span>
                                            <span x-text="s.kontak_alamat || 'Alamat sekolah...'"></span>
                                        </div>
                                        <div class="flex gap-2 text-[12px]">
                                            <span
                                                class="material-symbols-outlined text-[#FEAF2C] !text-[16px]">call</span>
                                            <span x-text="s.kontak_telepon || 'Telepon...'"></span>
                                        </div>
                                        <div class="flex gap-2 text-[12px]">
                                            <span
                                                class="material-symbols-outlined text-[#FEAF2C] !text-[16px]">mail</span>
                                            <span x-text="s.kontak_email || 'Email...'"></span>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 border border-gray-200 p-3 rounded-lg">
                                        <div class="text-xs font-bold text-primary mb-2 flex items-center gap-1">
                                            <span class="material-symbols-outlined !text-[16px]">schedule</span>
                                            Jam Operasional
                                        </div>
                                        <div class="space-y-1 text-[11px]">
                                            <div class="flex justify-between"><span
                                                    class="text-gray-500">Senin-Kamis</span><span class="font-semibold"
                                                    x-text="s.kontak_jam_senin_kamis || '07:00-15:30'"></span></div>
                                            <div class="flex justify-between"><span
                                                    class="text-gray-500">Jumat</span><span class="font-semibold"
                                                    x-text="s.kontak_jam_jumat || '07:00-11:30'"></span></div>
                                            <div class="flex justify-between"><span
                                                    class="text-gray-500">Sabtu</span><span class="font-semibold"
                                                    x-text="s.kontak_jam_sabtu || '08:00-12:00'"></span></div>
                                            <div class="flex justify-between"><span
                                                    class="text-gray-500">Minggu</span><span
                                                    class="text-secondary italic">Tutup</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <x-slot:scripts>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.store('preview', {
                    totalSiswa: {{ $totalSiswa ?? 0 }},
                    totalGuru: {{ $totalGuru ?? 0 }},
                    totalPrestasi: {{ $totalPrestasi ?? 0 }},
                });

                Alpine.data('previewSettings', () => ({
                    s: {},
                    showPreview: false,
                    page: 'home',
                    device: 'full',
                    images: {},
                    dragging: false,
                    dragTarget: null,
                    restore() {
                        const saved = localStorage.getItem('pv_state');
                        if (!saved) return;
                        try {
                            const d = JSON.parse(saved);
                            this.showPreview = d.showPreview ?? false;
                            this.page = d.page ?? 'home';
                            this.device = d.device ?? 'full';
                        } catch (e) { }
                    },
                    save() {
                        localStorage.setItem('pv_state', JSON.stringify({
                            showPreview: this.showPreview,
                            page: this.page,
                            device: this.device,
                        }));
                    },
                    selectImage(key) {
                        const refMap = { logo: 'logoInput', favicon: 'faviconInput', home_hero: 'homeHeroInput', profil_hero: 'profilHeroInput', akademik_hero: 'akademikHeroInput' };
                        const ref = this.$refs[refMap[key]];
                        if (ref) ref.click();
                    },
                    handleImageSelect(event, key) {
                        const file = event.target.files[0];
                        if (file) this.setImage(file, key);
                    },
                    handleImageDrop(event, key) {
                        this.dragging = false;
                        this.dragTarget = null;
                        const file = event.dataTransfer.files[0];
                        if (file && file.type.startsWith('image/')) {
                            this.setImage(file, key);
                            const refMap = { logo: 'logoInput', favicon: 'faviconInput', home_hero: 'homeHeroInput', profil_hero: 'profilHeroInput', akademik_hero: 'akademikHeroInput' };
                            const ref = this.$refs[refMap[key]];
                            if (ref) {
                                const dt = new DataTransfer();
                                dt.items.add(file);
                                ref.files = dt.files;
                            }
                        }
                    },
                    setImage(file, key) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.images[key] = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    },
                    removeImage(key) {
                        this.images[key] = null;
                        const refMap = { logo: 'logoInput', favicon: 'faviconInput', home_hero: 'homeHeroInput', profil_hero: 'profilHeroInput', akademik_hero: 'akademikHeroInput' };
                        const ref = this.$refs[refMap[key]];
                        if (ref) ref.value = '';
                    },
                    init() {
                        this.restore();
                        this.$watch('showPreview', () => this.save());
                        this.$watch('page', () => this.save());
                        this.$watch('device', () => this.save());
                    },
                }));
            });
        </script>
    </x-slot:scripts>
</x-layouts.admin>