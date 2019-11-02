@extends('layouts.app')

@section('content')
    <header class="flex items-center mb-4 py-4">
        <div class="flex justify-between items-en w-full">
            <h2 class="text-muted text-base font-light">My Projects</h2>
            <a href="/projects/create" class="button">New Project</a>
        </div>
    </header>

    <main class="lg:flex lg:flex-wrap -mx-3">
        @forelse($projects as $project)
            @include('projects.card')
        @empty
            <li>No Project yet.</li>
        @endforelse
    </main>
@endsection
