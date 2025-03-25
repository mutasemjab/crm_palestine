@extends('layouts.admin')

@section('css')
<style>
    .tasks-container {
        margin: 20px;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    .task-item {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        background-color: #ffffff;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .task-item:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }
    .task-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 10px;
        color: #333;
    }
    .task-meta {
        font-size: 14px;
        color: #555;
        margin-bottom: 10px;
    }
    .task-meta span {
        font-weight: bold;
        color: #444;
    }
    .task-status {
        padding: 5px 10px;
        border-radius: 5px;
        color: #fff;
        font-size: 12px;
        display: inline-block;
    }
    .status-pending {
        background-color: #f39c12;
    }
    .status-in-progress {
        background-color: #3498db;
    }
    .status-completed {
        background-color: #2ecc71;
    }
</style>
@endsection

@section('content')
<div class="tasks-container">
    @forelse($tasks as $task)
        <div class="task-item">
            <div class="task-title">{{ $task->title }}</div>
            <div class="task-meta">{{ $task->description }}</div>
            <div class="task-meta">
                <span>Status:</span>
                <span class="task-status 
                    {{ strtolower(str_replace(' ', '-', $task->status)) }}">
                    {{ ucfirst($task->status) }}
                </span>
            </div>
            <div class="task-meta"><span>Start Date:</span> {{ $task->start_date }}</div>
            <div class="task-meta"><span>Due Date:</span> {{ $task->due_date }}</div>
            <div class="task-meta"><span>Created By:</span> {{ $task->creator->name }}</div>
        </div>
    @empty
        <p>No tasks available.</p>
    @endforelse
</div>
@endsection
