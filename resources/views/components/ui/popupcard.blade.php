@props([
    'title' => 'Card Title',
])

<div>
    <div class="modal-box min-w-[1000px] min-h-[700px] w-100 p-6 ">
        <!-- Header, konten dll -->
        <div id="popup_modal_content">
            <div class="flex justify-center items-center gap-2">
                <span class="text-center">Loading...</span>
                <span class="loading loading-dots loading-sm"></span>
            </div>
        </div>
    </div>
</div>


<script>
    function openPopup(url) {
        const modal = document.getElementById('modal');
        const container = document.getElementById('popup_modal_content');

        // Tampilkan loading dulu
        container.innerHTML = '<div class="flex justify-center items-center"><span class="loading loading-spinner loading-lg"></span></div>';

        // Load konten dari URL
        $.get(url, function(response) {
            container.innerHTML = response;
        });

        // Buka modal
        modal.showModal();
    }
</script>
