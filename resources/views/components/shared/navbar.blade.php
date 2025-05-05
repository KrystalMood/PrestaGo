
<nav class="bg-white border-b border-gray-200 shadow-sm">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-14">
                        <div class="flex items-center">
                            <button id="toggle-sidebar" class="lg:hidden text-gray-500 focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>

                            <div class="hidden lg:block ml-2">
                                <h1 class="text-xl font-semibold text-gray-800">{{ $title ?? 'Dashboard' }}</h1>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <div class="relative ml-3">
                                <button class="flex text-gray-500 hover:text-gray-700 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    <span class="bg-red-500 text-white rounded-full w-4 h-4 absolute -top-1 -right-1 text-xs flex items-center justify-center">2</span>
                                </button>
                            </div>

                            <div class="relative ml-4">
                                <div class="dropdown dropdown-end">
                                    <div tabindex="0" role="button" class="flex items-center cursor-pointer">
                                        <img class="h-8 w-8 rounded-full border-2 border-gray-200" src="https://ui-avatars.com/api/?name=Admin&background=4338ca&color=fff" alt="User avatar" />
                                        <span class="ml-2 text-sm font-medium text-gray-700 hidden sm:block">{{ auth() ? auth()->user()->name : 'Admin User' }}</span>
                                        <svg class="ml-1 h-4 w-4 text-gray-400 hidden sm:block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <ul tabindex="0" class="dropdown-content menu p-2 mt-1 bg-white border border-gray-200 rounded-lg shadow-custom w-48 z-10">
                                        <li class="p-2 text-sm font-medium border-b border-gray-100">
                                            <span class="block">{{ auth() ? auth()->user()->name : 'Unknown User' }}</span>
                                            <span class="block text-xs text-gray-500">{{ auth() ? auth()->user()->email : 'Unknown Email' }}</span>
                                        </li>
                                        <li><a class="text-sm p-2 hover:bg-gray-50 rounded-md">Profil</a></li>
                                        <li><a class="text-sm p-2 hover:bg-gray-50 rounded-md">Pengaturan</a></li>
                                        <li>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="w-full text-left text-sm p-2 text-red-500 hover:bg-gray-50 rounded-md">Logout</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
