@props([
    'headers' => [],
    'data' => [],
    'actions' => false
])

<div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
    <table class="min-w-full divide-y divide-gray-300">
        <thead class="bg-gray-50">
            <tr>
                @foreach($headers as $header)
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ $header }}
                    </th>
                @endforeach
                @if($actions)
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Thao tác</span>
                    </th>
                @endif
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($data as $row)
                <tr class="hover:bg-gray-50">
                    @foreach($headers as $key => $header)
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if(isset($row[$key]))
                                {{ $row[$key] }}
                            @else
                                {{ $row->{$key} ?? '' }}
                            @endif
                        </td>
                    @endforeach
                    @if($actions)
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            {{ $actions }}
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($headers) + ($actions ? 1 : 0) }}" class="px-6 py-4 text-center text-sm text-gray-500">
                        Không có dữ liệu
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
