@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
    <div class="container">
        <h4 class="mb-4">My Notifications</h4>

        @forelse($notifications as $notification)
            <a href="{{ route('notification.show', $notification->id) }}" class="text-decoration-none text-dark">

                <div class="card mb-3 {{ $notification->status === 'unread' ? 'border-primary' : '' }}">
                    <div class="card-body">
                        <h6>{{ $notification->title }}</h6>
                        <p class="mb-1">{{ Str::limit($notification->subject, 80) }}</p>
                        <small class="text-muted">
                            {{ $notification->date->diffForHumans() }}
                        </small>
                    </div>
                </div>
            </a>
        @empty
            <div class="alert alert-secondary">No notifications available.</div>
        @endforelse
    </div>
@endsection
