@if(session('success') || session('error') || session('info') || session('warning'))
    <div class="container mx-auto px-4 py-4">
        @if(session('success'))
            <x-ui.alert 
                type="success" 
                :message="session('success')" 
                :autoDismiss="true"
                :dismissAfter="3000"
            />
        @endif
        
        @if(session('error'))
            <x-ui.alert 
                type="error" 
                :message="session('error')" 
                :autoDismiss="true"
                :dismissAfter="3000"
            />
        @endif
        
        @if(session('info'))
            <x-ui.alert 
                type="info" 
                :message="session('info')" 
                :autoDismiss="true"
                :dismissAfter="3000"
            />
        @endif
        
        @if(session('warning'))
            <x-ui.alert 
                type="warning" 
                :message="session('warning')" 
                :autoDismiss="true"
                :dismissAfter="3000"
            />
        @endif
    </div>
@endif