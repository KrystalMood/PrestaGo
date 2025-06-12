@props(['
    title' => 'Card Title',
])

<div>
    <div class="modal-box modal-bottom max-w-4xl w-11/12 p-6">
        <!-- header -->

            <div class="text-center mb-4">
                <h4 class="font-semibold leading-tight">
                    {{ $title }}
                </h4>
            </div>
            <form method="dialog">
      <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
    </form>
    <div id="popup_modal_content">
      <!-- Konten dari AJAX akan dimuat di sini -->
      <div class="text-center">Loading...................................................</div>
    </div>
    </div>
</div>


<script>
    function openPopup(url) {
        const modal = document.getElementById('modal');
        const container = document.getElementById('popup_modal_content');

        // Tampilkan loading dulu
        container.innerHTML = '<div class="text-center py-8">Loading...</div>';

        // Load konten dari URL
        $.get(url, function(response) {
            container.innerHTML = response;
        });

        // Buka modal
        modal.showModal();
    }
</script>
