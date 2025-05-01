@props(['user' => Auth::user()])

<div class="dropdown dropdown-end">
    <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
        <div class="w-10 rounded-full">
            <img alt="Avatar" src="https://ui-avatars.com/api/?name={{ $user->name }}&background=4f46e5&color=fff" />
        </div>
    </div>
    <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-white rounded-box w-52">
        <li class="font-medium px-4 py-2 text-gray-700">{{ $user->name }}</li>
        <li class="divider"></li>
        <li>
            <a class="justify-between">
                Profile
                <span class="badge badge-sm badge-primary">New</span>
            </a>
        </li>
        <li><a>Settings</a></li>
        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100">Logout</button>
            </form>
        </li>
    </ul>
</div>