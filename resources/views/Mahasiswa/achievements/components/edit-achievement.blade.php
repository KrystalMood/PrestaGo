<div class="flex items-center justify-between">
    <h3 class="text-lg font-medium text-gray-900">Edit Prestasi</h3>
    <form method="dialog">
        <button class="text-gray-400 hover:text-gray-500">
            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </form>
</div>

<form action="" method="POST" enctype="multipart/form-data" class="space-y-6 w-full px-2">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Judul Prestasi -->
        <div class="md:col-span-2">
            <x-ui.form-input 
                name="title" 
                label="Judul Prestasi" 
                placeholder="Masukkan judul prestasi" 
                value="{{ $achievement->title }}"
                required="true"
                helperText="Contoh: Juara 1 Hackathon, Best Paper Award"
            />
        </div>

        <!-- Nama Kompetisi -->
        <div class="md:col-span-2">
            <x-ui.form-input 
                name="competition_name" 
                label="Nama Kompetisi/Event" 
                placeholder="Nama kompetisi atau event" 
                value="{{ $achievement->competition_name }}"
                required="true"
                helperText="Contoh: Gemastik 2025, IEEE Conference 2025"
            />
        </div>

        <!-- Tipe dan Level Prestasi -->
        <div class="md:pr-3">
            <x-ui.form-select 
                name="type" 
                label="Jenis Prestasi" 
                required="true"
                :options="[
                    'academic' => 'Akademik',
                    'technology' => 'Teknologi',
                    'arts' => 'Seni',
                    'sports' => 'Olahraga',
                    'entrepreneurship' => 'Kewirausahaan'
                ]"
                placeholder="Pilih jenis prestasi"
            />
        </div>

        <div class="md:pl-3">
            <x-ui.form-select 
                name="level" 
                label="Tingkat Prestasi" 
                required="true"
                :options="[
                    'international' => 'Internasional',
                    'national' => 'Nasional',
                    'regional' => 'Regional'
                ]"
                :value="[
                    'Internasional' => 'international',
                    'Nasional' => 'national',
                    'Regional' => 'regional'
                ][$achievement->level] ?? null"
                placeholder="Pilih tingkat prestasi"
            />
        </div>

        <!-- Tanggal -->
        <div class="md:col-span-2">
            <x-ui.form-input 
                type="date" 
                name="date" 
                label="Tanggal Prestasi"
                value="{{ $achievement->date->format('Y-m-d') }}" 
                required="true"
            />
        </div>

        <!-- Deskripsi -->
        <div class="md:col-span-2">
            <div class="form-group mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi Prestasi <span class="text-red-500">*</span>
                </label>
                <textarea 
                    id="description" 
                    name="description" 
                    rows="5" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-brand focus:border-brand"
                    placeholder="Jelaskan detail prestasi yang diraih"
                    required
                >{{ old('description', $achievement->description ?? '') }}</textarea>
                <p class="mt-1.5 text-sm text-gray-500">Jelaskan bagaimana Anda mendapatkan prestasi ini, konteks, dan pencapaian yang diraih</p>
            </div>
        </div>

        <!-- Pilih Kompetisi Terkait (Opsional) -->
        <div class="md:col-span-2">
            <x-ui.form-select 
                name="competition_id" 
                label="Kompetisi Terkait (Opsional)" 
                :options="$competitions"
                placeholder="-- Pilih jika prestasi terkait dengan kompetisi terdaftar --"
                helperText="Kosongkan jika prestasi ini tidak terkait kompetisi yang terdaftar di sistem"
                :value="$achievement->competition_id ?? null"
            />
        </div>

        <!-- Bukti/Lampiran -->
        <div class="md:col-span-2">
            <x-ui.form-attachment 
                name="attachments[]" 
                label="Bukti Prestasi" 
                required="true"
                accept=".pdf,.jpg,.jpeg,.png" 
                multiple
                helperText="Unggah sertifikat atau bukti prestasi (Format: PDF, JPG, JPEG, PNG. Maks 2MB)"
            />
        </div>
    </div>

    <div class="flex justify-end space-x-4 mt-8">
        <button type="button" class="btn btn-ghost px-6" onclick="document.getElementById('modal').close()">
            Batal
        </button>
        <button type="submit" class="btn btn-primary px-8">
            Simpan Prestasi
        </button>
    </div>
</form>
