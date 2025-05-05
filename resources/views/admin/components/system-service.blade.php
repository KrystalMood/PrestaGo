@props(['name', 'status'])

<tr>
    <td class="py-1 text-sm">{{ $name }}</td>
    <td class="py-1 text-right">
        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
            {{ $status }}
        </span>
    </td>
</tr> 