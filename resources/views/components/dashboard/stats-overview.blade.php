@props(['stats' => []])

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    @foreach($stats as $stat)
        <x-dashboard.stat-card 
            :icon="$stat['icon']" 
            :title="$stat['title']" 
            :value="$stat['value']" 
            :trend="$stat['trend'] ?? ''" 
            :color="$stat['color'] ?? 'indigo'" 
        />
    @endforeach
</div>