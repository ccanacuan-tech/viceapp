<div class="relative">
    <button @click="open = !open" class="relative z-10 block h-8 w-8 rounded-full overflow-hidden shadow focus:outline-none">
        <svg class="h-full w-full object-cover" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
    </button>

    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl overflow-hidden z-10" style="display: none;">
        <div class="px-4 py-2 border-b">
            <h3 class="font-bold">Notificaciones</h3>
        </div>
        <div class="divide-y">
            @forelse (auth()->user()->unreadNotifications as $notification)
                <a href="#" class="block px-4 py-2 hover:bg-gray-100">
                    <p class="font-semibold">{{ $notification->data['title'] }}</p>
                    <p class="text-sm text-gray-600">{{ $notification->data['message'] }}</p>
                </a>
            @empty
                <p class="px-4 py-2 text-gray-500">No tienes notificaciones nuevas.</p>
            @endforelse
        </div>
    </div>
</div>
