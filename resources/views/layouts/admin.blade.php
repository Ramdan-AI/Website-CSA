<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>CSA Admin</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body>

<nav class="navbar navbar-dark bg-dark">

    <div class="container-fluid">

        <span class="navbar-brand">

            CSA ADMIN PANEL

        </span>

        <div class="d-flex align-items-center gap-3">

            <span class="text-white">

                {{ auth()->user()->name }}
                ({{ auth()->user()->role->name }})

            </span>

            <form
                action="{{ route('logout') }}"
                method="POST">

                @csrf

                <button
                    class="btn btn-danger btn-sm">

                    Logout

                </button>

            </form>

        </div>

    </div>

</nav>

<div class="container-fluid">

    <div class="row">

        <div class="col-md-2 bg-light min-vh-100 p-3">

            <h5>Menu</h5>

            <ul class="nav flex-column">

                {{-- Dashboard (Semua Role) --}}

                <li class="nav-item">

                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="nav-link">

                        Dashboard

                    </a>

                </li>

                {{-- Super Admin Only --}}

                @if(
                    auth()->user()->role->slug
                    === 'super-admin'
                )

                    <li class="nav-item">

                        <a
                            href="{{ route('admin.divisions.index') }}"
                            class="nav-link">

                            Divisions

                        </a>

                    </li>

                    <li class="nav-item">

                        <a
                            href="{{ route('admin.positions.index') }}"
                            class="nav-link">

                            Positions

                        </a>

                    </li>

                    <li class="nav-item">

                        <a
                            href="{{ route('admin.settings.edit') }}"
                            class="nav-link">

                            Settings

                        </a>

                    </li>

                @endif

                {{-- Sekretaris + Super Admin --}}

                @if(
                    in_array(
                        auth()->user()->role->slug,
                        [
                            'super-admin',
                            'sekretaris'
                        ]
                    )
                )

                    <li class="nav-item">

                        <a
                            href="{{ route('admin.news-categories.index') }}"
                            class="nav-link">

                            News Categories

                        </a>

                    </li>

                    <li class="nav-item">

                        <a
                            href="{{ route('admin.news.index') }}"
                            class="nav-link">

                            News

                        </a>

                    </li>

                    <li class="nav-item">

                        <a
                            href="{{ route('admin.documents.index') }}"
                            class="nav-link">

                            Documents

                        </a>

                    </li>

                @endif

                {{-- Bendahara + Super Admin --}}

                @if(
                    in_array(
                        auth()->user()->role->slug,
                        [
                            'super-admin',
                            'bendahara'
                        ]
                    )
                )

                    <li class="nav-item">

                        <a
                            href="{{ route('admin.incomes.index') }}"
                            class="nav-link">

                            Incomes

                        </a>

                    </li>

                    <li class="nav-item">

                        <a
                            href="{{ route('admin.expenses.index') }}"
                            class="nav-link">

                            Expenses

                        </a>

                    </li>

                @endif

                {{-- Kepala Divisi + Super Admin --}}

                @if(
                    in_array(
                        auth()->user()->role->slug,
                        [
                            'super-admin',
                            'kepala-divisi'
                        ]
                    )
                )

                    <li class="nav-item">

                        <a
                            href="{{ route('admin.members.index') }}"
                            class="nav-link">

                            Members

                        </a>

                    </li>

                    <li class="nav-item">

                        <a
                            href="{{ route('admin.events.index') }}"
                            class="nav-link">

                            Events

                        </a>

                    </li>

                    <li class="nav-item">

                        <a
                            href="{{ route('admin.event-registrations.index') }}"
                            class="nav-link">

                            Event Registrations

                        </a>

                    </li>

                    <li class="nav-item">

                        <a
                            href="{{ route('admin.attendances.index') }}"
                            class="nav-link">

                            Attendances

                        </a>

                    </li>

                @endif

                {{-- Ketua + Super Admin --}}

                @if(
                    in_array(
                        auth()->user()->role->slug,
                        [
                            'super-admin',
                            'ketua'
                        ]
                    )
                )

                    <li class="nav-item">

                        <a
                            href="{{ route('admin.albums.index') }}"
                            class="nav-link">

                            Albums

                        </a>

                    </li>

                    <li class="nav-item">

                        <a
                            href="{{ route('admin.galleries.index') }}"
                            class="nav-link">

                            Galleries

                        </a>

                    </li>

                @endif

            </ul>

        </div>

        <div class="col-md-10 p-4">

            @if(session('success'))

                <div class="alert alert-success">

                    {{ session('success') }}

                </div>

            @endif

            @yield('content')

        </div>

    </div>

</div>

</body>
</html>