@props(['items' => []])

@if(!empty($items) && count($items) > 0)
    <nav class="flex px-4 py-3 text-sm text-gray-600 bg-gray-50 rounded-md mb-6" aria-label="Breadcrumb">
        <ol class="flex space-x-2 items-center">
            @foreach($items as $key => $item)
                <li class="flex items-center">
                    @if($item['url'] ?? null)
                        <a href="{{ $item['url'] }}" class="text-blue-600 hover:text-blue-700 transition">
                            {{ $item['label'] }}
                        </a>
                    @else
                        <span class="text-gray-800 font-medium">{{ $item['label'] }}</span>
                    @endif

                    @if(!$loop->last)
                        <svg class="w-4 h-4 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif