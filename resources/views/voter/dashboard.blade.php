@foreach($elections as $election)
<div class="bg-white p-4 rounded shadow flex justify-between items-center">

    <div>
        <h2 class="font-semibold text-lg">{{ $election->title }}</h2>
        <p class="text-sm text-gray-500">
            Status: {{ ucfirst($election->status) }}
        </p>
    </div>

    <div class="flex gap-2">
        @if(in_array($election->status, ['closed', 'declared']))
    <a href="{{ route('results.show', $election) }}"
       class="bg-green-600 text-white px-4 py-2 rounded">
        View Results
    </a>
@else
    <button disabled
        class="bg-gray-200 text-gray-500 px-4 py-2 rounded cursor-not-allowed">
        View Results
    </button>
@endif

    </div>
</div>
@endforeach
