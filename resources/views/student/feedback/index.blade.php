@extends('layouts.mahasiswa', ['title' => 'Feedback Lomba'])

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Feedback Pengalaman Lomba</h1>
            <p class="text-gray-600 mt-1">Bagikan pengalaman lomba kamu untuk membantu kami meningkatkan program lomba di masa depan.</p>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <form id="feedback-form" action="{{ route('student.feedback.store') }}" method="POST">
                    @csrf
                    
                    <x-ui.form-select
                        name="competition_id"
                        id="competition_id"
                        label="Lomba/Kompetisi"
                        :options="[
                            '1' => 'Gemastik 2023 - Web Development',
                            '2' => 'Startup Weekend 2023',
                            '3' => 'Competitive Programming Challenge 2023'
                        ]"
                        required
                        placeholder="Pilih Lomba/Kompetisi"
                        :hasError="$errors->has('competition_id')"
                        :errorMessage="$errors->first('competition_id')"
                    />
                    
                    <div class="mb-6">
                        <span class="block text-sm font-medium text-gray-700 mb-3">Penilaian Keseluruhan</span>
                        <div class="flex items-center">
                            <div class="flex items-center space-x-1" id="rating-stars">
                                <button type="button" class="star-btn" data-rating="1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-300 hover:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                    </svg>
                                </button>
                                <button type="button" class="star-btn" data-rating="2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-300 hover:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                    </svg>
                                </button>
                                <button type="button" class="star-btn" data-rating="3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-300 hover:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                    </svg>
                                </button>
                                <button type="button" class="star-btn" data-rating="4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-300 hover:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                    </svg>
                                </button>
                                <button type="button" class="star-btn" data-rating="5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-300 hover:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                    </svg>
                                </button>
                            </div>
                            <span class="ml-3 text-sm text-gray-500" id="rating-text">Pilih rating</span>
                            <input type="hidden" name="overall_rating" id="overall_rating" value="">
                        </div>
                        @error('overall_rating')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-md font-medium text-gray-700 mb-3">Aspek Lomba/Kompetisi</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <x-ui.form-select
                                    name="organization_rating"
                                    id="organization_rating"
                                    label="Kualitas Penyelenggaraan"
                                    :options="[
                                        '5' => 'Sangat Baik',
                                        '4' => 'Baik',
                                        '3' => 'Cukup',
                                        '2' => 'Kurang',
                                        '1' => 'Sangat Kurang'
                                    ]"
                                    required
                                    placeholder="Pilih Rating"
                                    :hasError="$errors->has('organization_rating')"
                                    :errorMessage="$errors->first('organization_rating')"
                                />
                                
                                <x-ui.form-select
                                    name="judging_rating"
                                    id="judging_rating"
                                    label="Kualitas Penilaian/Juri"
                                    :options="[
                                        '5' => 'Sangat Baik',
                                        '4' => 'Baik',
                                        '3' => 'Cukup',
                                        '2' => 'Kurang',
                                        '1' => 'Sangat Kurang'
                                    ]"
                                    required
                                    placeholder="Pilih Rating"
                                    :hasError="$errors->has('judging_rating')"
                                    :errorMessage="$errors->first('judging_rating')"
                                />
                                
                                <x-ui.form-select
                                    name="learning_rating"
                                    id="learning_rating"
                                    label="Kesempatan Belajar & Pengembangan"
                                    :options="[
                                        '5' => 'Sangat Baik',
                                        '4' => 'Baik',
                                        '3' => 'Cukup',
                                        '2' => 'Kurang',
                                        '1' => 'Sangat Kurang'
                                    ]"
                                    required
                                    placeholder="Pilih Rating"
                                    :hasError="$errors->has('learning_rating')"
                                    :errorMessage="$errors->first('learning_rating')"
                                />
                                
                                <x-ui.form-select
                                    name="materials_rating"
                                    id="materials_rating"
                                    label="Kualitas Material/Soal"
                                    :options="[
                                        '5' => 'Sangat Baik',
                                        '4' => 'Baik',
                                        '3' => 'Cukup',
                                        '2' => 'Kurang',
                                        '1' => 'Sangat Kurang'
                                    ]"
                                    required
                                    placeholder="Pilih Rating"
                                    :hasError="$errors->has('materials_rating')"
                                    :errorMessage="$errors->first('materials_rating')"
                                />
                            </div>
                        </div>
                        
                        <x-ui.form-input
                            type="textarea"
                            name="strengths"
                            id="strengths"
                            label="Kelebihan Lomba/Kompetisi"
                            placeholder="Apa saja kelebihan dari lomba/kompetisi ini?"
                            required
                            :hasError="$errors->has('strengths')"
                            :errorMessage="$errors->first('strengths')"
                            rows="3"
                        />
                        
                        <x-ui.form-input
                            type="textarea"
                            name="improvements"
                            id="improvements"
                            label="Saran Perbaikan"
                            placeholder="Apa saja yang perlu ditingkatkan dari lomba/kompetisi ini?"
                            required
                            :hasError="$errors->has('improvements')"
                            :errorMessage="$errors->first('improvements')"
                            rows="3"
                        />
                        
                        <x-ui.form-input
                            type="textarea"
                            name="skills_gained"
                            id="skills_gained"
                            label="Keterampilan yang Diperoleh"
                            placeholder="Keterampilan apa saja yang kamu peroleh selama mengikuti lomba/kompetisi?"
                            required
                            :hasError="$errors->has('skills_gained')"
                            :errorMessage="$errors->first('skills_gained')"
                            rows="3"
                        />
                        
                        <div>
                            <label for="recommendation" class="block text-sm font-medium text-gray-700 mb-2">
                                Apakah Kamu Merekomendasikan Lomba/Kompetisi Ini?
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1 space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="recommendation" value="yes" class="form-radio h-4 w-4 text-brand focus:ring-brand">
                                    <span class="ml-2 text-sm text-gray-700">Ya</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="recommendation" value="maybe" class="form-radio h-4 w-4 text-yellow-500 focus:ring-yellow-500">
                                    <span class="ml-2 text-sm text-gray-700">Mungkin</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="recommendation" value="no" class="form-radio h-4 w-4 text-red-500 focus:ring-red-500">
                                    <span class="ml-2 text-sm text-gray-700">Tidak</span>
                                </label>
                            </div>
                            @error('recommendation')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <x-ui.form-input
                            type="textarea"
                            name="additional_comments"
                            id="additional_comments"
                            label="Komentar Tambahan (Opsional)"
                            placeholder="Komentar atau masukan tambahan..."
                            :hasError="$errors->has('additional_comments')"
                            :errorMessage="$errors->first('additional_comments')"
                            rows="3"
                        />
                    </div>
                    
                    <div class="mt-8 flex justify-end">
                        <x-ui.button
                            type="submit"
                            variant="primary"
                            icon='<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>'
                            iconPosition="left"
                        >
                            Kirim Feedback
                        </x-ui.button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Previous Feedback (if any) -->
        <div class="mt-8 bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-medium text-gray-800">Feedback yang Telah Dikirim</h3>
            </div>
            
            <div id="previous-feedback-container">
                <!-- This would be populated with the user's previous feedback entries -->
                <div class="p-6 text-center text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    <p class="text-lg">Belum ada feedback yang dikirimkan</p>
                    <p class="mt-1">Isi formulir di atas untuk memberikan feedback tentang pengalaman lomba kamu</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Star rating functionality
            const stars = document.querySelectorAll('.star-btn');
            const ratingText = document.getElementById('rating-text');
            const ratingInput = document.getElementById('overall_rating');
            const ratingTexts = ['Sangat Buruk', 'Buruk', 'Cukup', 'Baik', 'Sangat Baik'];
            
            if (stars.length && ratingText && ratingInput) {
                stars.forEach(star => {
                    // Click handler for stars
                    star.addEventListener('click', function() {
                        const rating = parseInt(this.dataset.rating);
                        ratingInput.value = rating;
                        
                        // Update text
                        ratingText.textContent = ratingTexts[rating - 1];
                        
                        // Update stars
                        stars.forEach((s, index) => {
                            const starSvg = s.querySelector('svg');
                            if (index < rating) {
                                starSvg.classList.remove('text-gray-300');
                                starSvg.classList.add('text-yellow-400');
                                starSvg.setAttribute('fill', 'currentColor');
                            } else {
                                starSvg.classList.add('text-gray-300');
                                starSvg.classList.remove('text-yellow-400');
                                starSvg.setAttribute('fill', 'none');
                            }
                        });
                    });
                    
                    // Hover effects
                    star.addEventListener('mouseenter', function() {
                        const rating = parseInt(this.dataset.rating);
                        
                        stars.forEach((s, index) => {
                            const starSvg = s.querySelector('svg');
                            if (index < rating) {
                                starSvg.classList.add('text-yellow-400');
                                starSvg.classList.remove('text-gray-300');
                            }
                        });
                    });
                    
                    star.addEventListener('mouseleave', function() {
                        const currentRating = parseInt(ratingInput.value) || 0;
                        
                        stars.forEach((s, index) => {
                            const starSvg = s.querySelector('svg');
                            if (index < currentRating) {
                                starSvg.classList.add('text-yellow-400');
                                starSvg.classList.remove('text-gray-300');
                            } else {
                                starSvg.classList.remove('text-yellow-400');
                                starSvg.classList.add('text-gray-300');
                            }
                        });
                    });
                });
            }
            
            // Form validation
            const form = document.getElementById('feedback-form');
            
            if (form && ratingInput) {
                form.addEventListener('submit', function(e) {
                    if (!ratingInput.value) {
                        e.preventDefault();
                        alert('Mohon berikan penilaian keseluruhan dengan memilih bintang.');
                    }
                });
            }
        });
    </script>
@endsection 