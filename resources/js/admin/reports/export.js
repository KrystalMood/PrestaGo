document.addEventListener('DOMContentLoaded', function() {
    setupFormListeners();
    setupDateRangeInfo();
});

function setupFormListeners() {
    const exportForm = document.getElementById('export-form');
    const reportTypeSelect = document.getElementById('report_type_comprehensive');
    const reportTypeSummary = document.getElementById('report_type_summary');
    
    if (!exportForm) return;
    
    if (reportTypeSelect && reportTypeSummary) {
        reportTypeSelect.addEventListener('change', function() {
            if (this.checked) {
                console.log('Comprehensive report selected');
            }
        });
        
        reportTypeSummary.addEventListener('change', function() {
            if (this.checked) {
                console.log('Summary report selected');
            }
        });
    }
    
    if (exportForm) {
        exportForm.addEventListener('submit', function(e) {
            const submitButton = this.querySelector('button[type="submit"]');
            if (submitButton) {
                const originalText = submitButton.innerHTML;
                submitButton.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...';
            }
        });
    }
}

function setupDateRangeInfo() {
    const dateRangeSelect = document.getElementById('date_range');
    const infoDiv = document.createElement('div');
    infoDiv.className = 'text-sm text-gray-500 mt-1';
    infoDiv.id = 'date-range-info';
    
    if (dateRangeSelect) {
        dateRangeSelect.parentNode.insertBefore(infoDiv, dateRangeSelect.nextSibling);
        
        updateDateRangeInfo(dateRangeSelect.value);
        
        dateRangeSelect.addEventListener('change', function() {
            updateDateRangeInfo(this.value);
        });
    }
}

function updateDateRangeInfo(selectedValue) {
    const infoDiv = document.getElementById('date-range-info');
    if (!infoDiv) return;
    
    let infoText = '';
    
    switch (selectedValue) {
        case 'current_semester':
            fetch('/api/current-period')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.period) {
                        infoDiv.textContent = `Periode: ${data.period.name} (${data.period.start_date} - ${data.period.end_date})`;
                    } else {
                        infoDiv.textContent = 'Menggunakan semester berjalan berdasarkan tanggal saat ini';
                    }
                })
                .catch(error => {
                    console.error('Error fetching period info:', error);
                    infoDiv.textContent = 'Menggunakan semester berjalan berdasarkan tanggal saat ini';
                });
            break;
        case 'current_year':
            const currentYear = new Date().getFullYear();
            infoDiv.textContent = `Periode: 1 Januari ${currentYear} - 31 Desember ${currentYear}`;
            break;
        case 'last_year':
            const lastYear = new Date().getFullYear() - 1;
            infoDiv.textContent = `Periode: 1 Januari ${lastYear} - 31 Desember ${lastYear}`;
            break;
        case 'all_time':
            infoDiv.textContent = 'Semua data prestasi yang terverifikasi';
            break;
        default:
            infoDiv.textContent = '';
    }
} 