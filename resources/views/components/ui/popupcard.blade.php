@props([
    'title' => 'Card Title',
])

<div>
    <div class="modal-box min-w-[1000px] min-h-[700px] w-100 p-6 ">
        <!-- Header with close button -->
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold" id="popup_modal_title">{{ $title }}</h3>
            <button onclick="closePopup()" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </div>
        
        <!-- Content area -->
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

        container.innerHTML = '<div class="flex justify-center items-center"><span class="loading loading-spinner loading-lg"></span></div>';

        fetch(url)
            .then(response => response.text())
            .then(html => {
                container.innerHTML = html;
            })
            .catch(error => {
                container.innerHTML = '<div class="text-red-500 p-4">Error loading content: ' + error.message + '</div>';
            });

        modal.showModal();
    }
    
    function closePopup() {
        const modal = document.getElementById('modal');
        modal.close();
    }
</script>
