@extends('layouts.admin')

@section('css')
<style>
    .notifications-container {
        margin: 20px;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    .notification-item {
        border-bottom: 1px solid #ddd;
        padding: 15px 0;
    }
    .notification-item:last-child {
        border-bottom: none;
    }
    .notification-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 10px;
        color: #333;
    }
    .notification-body {
        font-size: 14px;
        margin-bottom: 10px;
        color: #555;
    }
    .notification-meta {
        font-size: 12px;
        color: #777;
    }
</style>
@endsection

@section('content')
<div class="notifications-container">
    @forelse($notifications as $notification)
        <div class="notification-item">
            <div class="notification-title">{{ $notification->title }}</div>
            <div class="notification-body">{{ $notification->body }}</div>
            <div class="notification-meta">
                {{ $notification->admin_in == auth()->user()->id ? 'For me' : 'For all' }}
            </div>
        </div>
    @empty
        <p>No notifications available.</p>
    @endforelse
</div>
@endsection
