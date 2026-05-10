<!-- Topbar/Navigation Partial -->
<header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 md:px-8 sticky top-0 z-30">
    <div class="flex items-center">
        <button 
            type="button" 
            class="p-2 -ml-2 text-slate-500 hover:text-slate-900 md:hidden transition-colors"
            @click="sidebarOpen = true"
            aria-label="Toggle sidebar">
            <i data-feather="menu" class="w-6 h-6"></i>
        </button>
    </div>

    <div class="flex items-center gap-2 md:gap-4">
        <!-- Notifications -->
        <div class="relative" x-data="{ open: false }">
            <button 
                @click="open = !open"
                class="relative p-2 text-slate-500 hover:text-slate-900 hover:bg-slate-100 rounded-full transition-all duration-200"
                aria-label="Notifications">
                <i data-feather="bell" class="w-5 h-5"></i>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="absolute top-1.5 right-1.5 flex h-4 w-4">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-4 w-4 bg-rose-500 text-[10px] font-bold text-white items-center justify-center">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    </span>
                @endif
            </button>

            <div 
                x-show="open" 
                @click.away="open = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute right-0 mt-2 w-80 bg-white border border-slate-200 rounded-xl shadow-xl z-50 overflow-hidden"
                x-cloak>
                
                <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <h3 class="text-sm font-semibold text-slate-900">Notifications</h3>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <form action="{{ route('admin.notifications.markAllAsRead') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs font-medium text-indigo-600 hover:text-indigo-700">Mark all as read</button>
                        </form>
                    @endif
                </div>

                <div class="max-h-[400px] overflow-y-auto">
                    @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                        <div class="p-4 hover:bg-slate-50 border-b border-slate-100 last:border-0 transition-colors {{ $notification->unread() ? 'bg-indigo-50/30' : '' }}">
                            <div class="flex gap-3">
                                <div class="flex-shrink-0 mt-0.5">
                                    @switch($notification->data['type'] ?? '')
                                        @case('new_order')
                                            <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                                <i data-feather="shopping-cart" class="w-4 h-4"></i>
                                            </div>
                                            @break
                                        @case('new_preorder')
                                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                                <i data-feather="clock" class="w-4 h-4"></i>
                                            </div>
                                            @break
                                        @case('new_complaint')
                                            <div class="w-8 h-8 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center">
                                                <i data-feather="alert-triangle" class="w-4 h-4"></i>
                                            </div>
                                            @break
                                        @default
                                            <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center">
                                                <i data-feather="bell" class="w-4 h-4"></i>
                                            </div>
                                    @endswitch
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-slate-900 leading-snug">
                                        {{ Str::limit($notification->data['message'] ?? 'No message', 60) }}
                                    </p>
                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <i data-feather="bell-off" class="w-8 h-8 text-slate-300 mx-auto mb-3"></i>
                            <p class="text-sm text-slate-500">No new notifications</p>
                        </div>
                    @endforelse
                </div>

                <a href="{{ route('admin.notifications.index') }}" class="block p-3 text-center text-sm font-medium text-slate-600 hover:bg-slate-50 border-t border-slate-100 transition-colors">
                    View All
                </a>
            </div>
        </div>

        <!-- User Menu -->
        <div class="relative" x-data="{ open: false }">
            <button 
                @click="open = !open"
                class="flex items-center gap-2 p-1 pl-2 pr-2 md:pr-3 text-slate-700 hover:bg-slate-100 rounded-full transition-all duration-200"
                aria-label="User menu">
                <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white text-sm font-bold shadow-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="hidden md:block text-left">
                    <p class="text-sm font-semibold leading-none">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-slate-500 mt-0.5">{{ auth()->user()->email }}</p>
                </div>
                <i data-feather="chevron-down" class="w-4 h-4 text-slate-400"></i>
            </button>

            <div 
                x-show="open" 
                @click.away="open = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute right-0 mt-2 w-48 bg-white border border-slate-200 rounded-xl shadow-xl z-50 overflow-hidden"
                x-cloak>
                
                <div class="p-4 border-b border-slate-100 bg-slate-50/50 md:hidden">
                    <p class="text-sm font-semibold text-slate-900">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-500">{{ auth()->user()->email }}</p>
                </div>

                <div class="py-1">
                    <a href="{{ route('profile.show') }}" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                        <i data-feather="user" class="w-4 h-4 mr-3 text-slate-400"></i>
                        Profile
                    </a>
                    <div class="border-t border-slate-100 my-1"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex w-full items-center px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 transition-colors">
                            <i data-feather="log-out" class="w-4 h-4 mr-3"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>