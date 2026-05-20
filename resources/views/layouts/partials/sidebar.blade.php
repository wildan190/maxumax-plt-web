<!-- Sidebar Partial -->
<aside 
    id="adminSidebar"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-300 transition-transform duration-300 ease-in-out transform md:relative md:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    x-cloak>
    
    <div class="flex flex-col h-full">
        <!-- Sidebar Header -->
        <div class="flex items-center justify-between h-16 px-6 bg-slate-950/50">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                <span class="text-xl font-bold text-white tracking-tight">
                    {{ config('app.name', 'Maxumax') }}<span class="text-indigo-500">.</span>
                </span>
            </a>
            <!-- Close Button for Mobile -->
            <button 
                type="button" 
                class="md:hidden text-slate-400 hover:text-white transition-colors"
                @click="sidebarOpen = false"
                aria-label="Close sidebar">
                <i data-feather="x" class="w-5 h-5"></i>
            </button>
        </div>

        <!-- Sidebar Nav -->
        <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-8 scrollbar-thin scrollbar-thumb-slate-700 scrollbar-track-transparent">
            <!-- Main Section -->
            <div>
                <h3 class="px-2 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-4">Main Menu</h3>
                <div class="space-y-1">
                    <a href="{{ route('dashboard') }}" 
                        class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'hover:bg-slate-800 hover:text-white' }}">
                        <i data-feather="grid" class="w-4 h-4 mr-3 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('profile.show') }}"
                        class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('profile.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'hover:bg-slate-800 hover:text-white' }}">
                        <i data-feather="user" class="w-4 h-4 mr-3 {{ request()->routeIs('profile.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                        <span>Profile</span>
                    </a>
                </div>
            </div>

            <!-- Management Section -->
            <div>
                <h3 class="px-2 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-4">Management</h3>
                <div class="space-y-1">
                    <a href="{{ route('admin.preorders.index') }}"
                        class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.preorders.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'hover:bg-slate-800 hover:text-white' }}">
                        <i data-feather="shopping-bag" class="w-4 h-4 mr-3 {{ request()->routeIs('admin.preorders.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                        <span class="flex-1">Preorders</span>
                        @if(auth()->user()->unreadNotifications->where('data.type', 'new_preorder')->count() > 0)
                            <span class="flex-shrink-0 w-2 h-2 bg-rose-500 rounded-full"></span>
                        @endif
                    </a>

                    <a href="{{ route('admin.orders.index') }}"
                        class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.orders.index') || request()->routeIs('admin.orders.show') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'hover:bg-slate-800 hover:text-white' }}">
                        <i data-feather="package" class="w-4 h-4 mr-3 {{ request()->routeIs('admin.orders.index') || request()->routeIs('admin.orders.show') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                        <span class="flex-1">Orders</span>
                        @if(auth()->user()->unreadNotifications->whereIn('data.type', ['new_order', 'replacement_ready'])->count() > 0)
                            <span class="flex-shrink-0 w-2 h-2 bg-rose-500 rounded-full"></span>
                        @endif
                    </a>

                    <a href="{{ route('admin.products.index') }}"
                        class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.products.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'hover:bg-slate-800 hover:text-white' }}">
                        <i data-feather="box" class="w-4 h-4 mr-3 {{ request()->routeIs('admin.products.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                        <span>Products</span>
                    </a>

                    <a href="{{ route('admin.orders.history') }}"
                        class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.orders.history') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'hover:bg-slate-800 hover:text-white' }}">
                        <i data-feather="clock" class="w-4 h-4 mr-3 {{ request()->routeIs('admin.orders.history') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                        <span>Orders History</span>
                    </a>

                    <a href="{{ route('admin.shipping.myparcel.index') }}"
                        class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.shipping.myparcel.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'hover:bg-slate-800 hover:text-white' }}">
                        <i data-feather="truck" class="w-4 h-4 mr-3 {{ request()->routeIs('admin.shipping.myparcel.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                        <span>MyParcel Asia</span>
                    </a>

                    <a href="{{ route('admin.reports.index') }}"
                        class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.reports.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'hover:bg-slate-800 hover:text-white' }}">
                        <i data-feather="bar-chart-2" class="w-4 h-4 mr-3 {{ request()->routeIs('admin.reports.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                        <span>Reports</span>
                    </a>

                    <a href="{{ route('admin.galleries.index') }}"
                        class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.galleries.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'hover:bg-slate-800 hover:text-white' }}">
                        <i data-feather="image" class="w-4 h-4 mr-3 {{ request()->routeIs('admin.galleries.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                        <span>Gallery</span>
                    </a>

                    <a href="{{ route('admin.landing-page.edit') }}"
                        class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.landing-page.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'hover:bg-slate-800 hover:text-white' }}">
                        <i data-feather="layout" class="w-4 h-4 mr-3 {{ request()->routeIs('admin.landing-page.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                        <span>Landing page</span>
                    </a>

                    <a href="{{ route('admin.complaints.index') }}"
                        class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.complaints.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'hover:bg-slate-800 hover:text-white' }}">
                        <i data-feather="message-square" class="w-4 h-4 mr-3 {{ request()->routeIs('admin.complaints.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                        <span class="flex-1">Complaints</span>
                        @if(auth()->user()->unreadNotifications->where('data.type', 'new_complaint')->count() > 0)
                            <span class="flex-shrink-0 w-2 h-2 bg-rose-500 rounded-full"></span>
                        @endif
                    </a>

                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.users.index') }}"
                            class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'hover:bg-slate-800 hover:text-white' }}">
                            <i data-feather="users" class="w-4 h-4 mr-3 {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                            <span>Users</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Account Section -->
            <div class="pt-8 border-t border-slate-800">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                        class="group flex w-full items-center px-3 py-2 text-sm font-medium text-slate-400 rounded-lg hover:bg-rose-600/10 hover:text-rose-500 transition-all duration-200">
                        <i data-feather="log-out" class="w-4 h-4 mr-3 text-slate-400 group-hover:text-rose-500"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </nav>
    </div>
</aside>