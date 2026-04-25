@php $mine = $msg->sender_id === auth()->id(); @endphp

<div id="msg-{{ $msg->id }}" class="flex {{ $mine ? 'justify-end' : 'justify-start' }}">
    <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-2xl text-sm
                {{ $mine ? 'bg-indigo-600 text-white rounded-br-none' : 'bg-gray-100 text-gray-900 rounded-bl-none' }}">
        <p>{!! nl2br(e($msg->body)) !!}</p>
        <p class="text-xs mt-1 text-right {{ $mine ? 'text-indigo-200' : 'text-gray-400' }}">
            {{ $msg->created_at->format(config('marketplace.datetime_display', 'd-m-Y H:i')) }}
        </p>
    </div>
</div>
