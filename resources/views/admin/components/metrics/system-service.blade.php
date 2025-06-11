@props(['name', 'status'])

<tr class="border-b border-gray-50 last:border-0">
    <td class="py-2 text-sm font-medium text-gray-900">{{ $name }}</td>
    <td class="py-2 text-right">
        <span class="text-xs font-medium px-2 py-1 rounded-full bg-green-100 text-green-800">
            {{ $status }}
        </span>
    </td>
</tr> 