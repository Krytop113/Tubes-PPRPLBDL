@extends('layouts.app')

@section('title', 'Notification Detail')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h3>{{ $notification->title }}</h3>
                <h5 class="text-muted">{{ $notification->subject }}</h5>
                <p class="mt-3">{{ $notification->message }}</p>

                <small class="text-muted">
                    {{ $notification->date->format('d M Y H:i') }}
                </small>

                <div class="mt-4 d-flex gap-2">
                    <a href="{{ route('notification.index') }}" class="btn btn-secondary btn-sm">
                        Back
                    </a>

                    <form method="POST" action="{{ route('notification.delete', $notification->id) }}">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
