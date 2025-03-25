@extends('layouts.admin')
@section('title', __('messages.Home'))

@section('css')
<style>
     .job-order-buttons {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin-bottom: 20px;
    }

    .job-order-buttons button {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .job-order-buttons button:hover {
        background-color: #0056b3;
    }

    .job-order-buttons button.active {
        background-color: #28a745;
    }

    .dashboard {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }

    .task-card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .task-card h5 {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .task-card p {
        margin: 5px 0;
    }


    .task-card:hover {
        transform: scale(1.02);
    }

    .task-header {
        font-weight: bold;
        font-size: 18px;
        margin-bottom: 10px;
        color: #333;
        display: flex;
        justify-content: space-between;
    }

    .task-details {
        margin-bottom: 20px;
        font-size: 14px;
    }

    .task-details p {
        margin: 5px 0;
        color: #555;
    }

    .status-section {
        margin-bottom: 15px;
    }

    .status-section label {
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
    }

    .status-select {
        width: 100%;
        padding: 5px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
    }

    .btn-group {
        display: flex;
        justify-content: space-between;
        gap: 10px;
    }

    .btn-action {
        padding: 8px 12px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        font-weight: bold;
        transition: background-color 0.3s ease;
        color: #fff;
    }

    .btn-note {
        background-color: #ffc107;
    }

    .btn-note:hover {
        background-color: #e0a800;
    }

    .btn-save {
        background-color: #007bff;
    }

    .btn-save:hover {
        background-color: #0056b3;
    }

    /* Modal styling */
    .note-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .note-modal-content {
        background: white;
        border-radius: 10px;
        padding: 20px;
        width: 90%;
        max-width: 400px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        position: relative;
        text-align: center;
    }

    .note-modal-header {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .note-modal-close {
        position: absolute;
        top: 10px;
        right: 10px;
        cursor: pointer;
        font-size: 20px;
        font-weight: bold;
        color: #333;
    }

    .note-modal textarea {
        width: 100%;
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 10px;
        margin-bottom: 15px;
        font-size: 14px;
    }

    .note-modal .btn-action {
        display: block;
        margin: 0 auto;
    }

    /* Mobile adjustments */
    @media (max-width: 768px) {
        .dashboard {
            grid-template-columns: 1fr;
        }

        .btn-group {
            flex-direction: column;
        }
    }
</style>
@endsection

@section('content')

<a href="{{ route('noteVouchers.create', ['id' => 2]) }}"><button type="button" class="btn-action btn-note">
    {{ __('سند اخراج') }}
</button>
</a>

<div class="date-filters" style="display: flex; align-items: center; gap: 10px;">
    <form method="GET" action="{{ route('admin.dashboard') }}" class="date-filter-form" style="display: flex; align-items: center; gap: 10px;">
        <div style="display: flex; flex-direction: column;">
            <label for="from_date">{{ __('From Date') }}</label>
            <input type="date" id="from_date" name="from_date" class="form-control" value="{{ request('from_date', $fromDate) }}">
        </div>
        <div style="display: flex; flex-direction: column;">
            <label for="to_date">{{ __('To Date') }}</label>
            <input type="date" id="to_date" name="to_date" class="form-control" value="{{ request('to_date', $toDate) }}">
        </div>
        <button type="submit" class="btn btn-primary" style="align-self: flex-end; margin-top: 6px;">{{ __('Filter') }}</button>
    </form>
</div>

<br>

    <div class="job-order-buttons">
        <button onclick="filterTasks('')" class="{{ request('jobOrderType') == '' ? 'active' : '' }}">
            {{ __('All') }}
        </button>
        @foreach ($jobOrderTypes as $type)
            <button onclick="filterTasks('{{ $type->id }}')" class="{{ request('jobOrderType') == $type->id ? 'active' : '' }}">
                {{ $type->name }}
            </button>
        @endforeach
    </div>

<div class="dashboard"  id="tasksContainer">
    @foreach ($tasks as $index => $task)
        <div  class="task-card {{ $task->job_order_status === 'delivered' ? 'bg-success' : ($task->job_order_status === 'rejected' ? 'bg-danger' : '') }}"
            data-job-order-type="{{ $task->job_order_type_id }}">
            <!-- Always display the task header -->
            <div class="task-header">
                <span>
             
                    <a href="#" data-toggle="modal" data-target="#taskDetailsModal{{ $task->id }}">
                        {{ __('Task') }} #{{ $task->id }}
                    </a>   

                </span>
                <span class="status">{{ ucfirst($task->job_order_status) }}</span>
            </div>

            <!-- Conditionally display task details and form -->
                @if (
                    $index === 0 ||
                    (
                        $tasks[$index - 1]->job_order_status === 'completed'
                        &&
                        $tasks[$index - 1]->feedback_submitted
                    ) ||
                    (
                        in_array($tasks[$index - 1]->job_order_status, [
                            'تأجيل بنفس اليوم',
                            'تأجيل ليوم اخر',
                            'بحاجة عرض سعر',
                            'الغاء المعاملة',
                            'بحاجة لكشف مهندس',
                            'بحاجة لاعادة تخطيط',
                            'delivered',
                            'rejected'
                        ])
                    )
                )
                <div class="task-details">
                    <p><strong>{{ __('Building') }}:</strong> {{ $task->building }}</p>
                    <p><strong>{{ __('Customer') }}:</strong> {{ $task->customer_name }}</p>
                    <p><strong>{{ __('Mobile') }}:</strong> {{ $task->customer_mobile }}</p>
                    <p><strong>{{ __('Address') }}:</strong> {{ $task->customer_address }}</p>
                    <p><strong>{{ __('Assign Time') }}:</strong> {{ $task->assigned_time }}</p>
                    <p><strong>{{ __('Job order type') }}:</strong>
                        <select class="job-order-type-select" onchange="updateJobOrderType({{ $task->id }}, this.value)">
                            @foreach ($jobOrderTypes as $type)
                                <option value="{{ $type->id }}" {{ $task->job_order_type_id == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </p>
                   <!-- Conditionally display note_of_reject -->
                   @if ($task->job_order_status === 'rejected')
                   <p><strong>{{ __('Reason for Rejection') }}:</strong> {{ $task->note_of_reject }}</p>
                  @endif
                </div>
                <form>
                    @csrf
                    @method('PATCH')
                    <div class="status-section">
                        <label>{{ __('Job Order Status') }}</label>
                        <select
                        id="statusSelect-{{ $task->id }}"
                        class="status-select"
                        onchange="handleStatusChange({{ $task->id }}, this.value)"
                        data-current-status="{{ $task->job_order_status }}"
                        data-return-to-contractor="{{ $task->return_to_contractor }}"
                        {{ in_array($task->job_order_status, ['بحاجة لكشف مهندس', 'بحاجة عرض سعر', 'بحاجة لاعادة تخطيط']) && $task->return_to_contractor == 1 ? 'disabled' : '' }}
                    >
                        @foreach (['opened', 'بحاجة لكشف مهندس', 'تأجيل بنفس اليوم', 'تأجيل ليوم اخر', 'بحاجة عرض سعر', 'الغاء المعاملة', 'بحاجة لاعادة تخطيط', 'completed'] as $status)
                            <option value="{{ $status }}" {{ $task->job_order_status === $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>

                    </div>

                    <!-- Additional Inputs for Specific Statuses -->
                    <div id="timeInput-{{ $task->id }}" style="display: none; margin-top: 10px;">
                        <label>{{ __('Add Time') }}</label>
                        <input
                            type="time"
                            id="taskTime-{{ $task->id }}"
                            class="form-control"
                            value="{{ old('time', $task->time ? \Carbon\Carbon::parse($task->time)->format('H:i') : '') }}"
                            onchange="updateTaskTime({{ $task->id }}, this.value)"
                        />
                    </div>
                    <div id="dateTimeInput-{{ $task->id }}" style="display: none; margin-top: 10px;">
                        <label>{{ __('Add Date and Time') }}</label>
                        <input
                            type="datetime-local"
                            id="taskDateTime-{{ $task->id }}"
                            class="form-control"
                            value="{{ old('date_time', $task->date_time ? \Carbon\Carbon::parse($task->date_time)->format('Y-m-d\TH:i') : '') }}"
                            onchange="updateTaskDateTime({{ $task->id }}, this.value)"
                        />
                    </div>

                    <div class="btn-group">
                        <button type="button" class="btn-action btn-note" onclick="openNoteModal({{ $task->id }})">
                            {{ __('Add Note') }}
                        </button>
                        @if($task->job_order_status === 'completed')
                        <button type="button" class="btn-action btn-feedback" data-toggle="modal" data-target="#feedbackModal"
                        onclick="loadFeedbackForm({{ $task->id }}, {{ $task->jobOrderType->id }})">
                        {{ __('Feedback') }}
                        </button>
                        @endif

                             @if (
                                $index === 0 ||
                                (
                                    $tasks[$index - 1]->job_order_status === 'completed'
                                    &&
                                    $tasks[$index - 1]->feedback_submitted
                                )
                            )

                        @php
                        $adminCountry = \App\Models\Country::where('id', $task->admin->country_id)->first(); // Assuming admin relation exists
                        $whatsappLink = $adminCountry ? $adminCountry->whatsapp_link : null;
                        @endphp
                        @if($whatsappLink)
                            <a href="{{ $whatsappLink }}{{$task}}" target="_blank" class="btn btn-info btn-sm">
                                {{ __('Send to WhatsApp') }}
                            </a>
                        @else
                            <button class="btn btn-secondary btn-sm" disabled>{{ __('WhatsApp Not Available') }}</button>
                        @endif
                        @endif

                    </div>
                </form>
            @else
                <!-- Show a message when details are hidden -->
                <p class="text-center text-muted">{{ __('Complete the previous task and submit feedback to unlock details.') }}</p>
            @endif
        </div>
    @endforeach
</div>



<!-- Note Modal -->
<div id="noteModal" class="note-modal">
    <div class="note-modal-content">
        <div class="note-modal-close" onclick="closeNoteModal()">×</div>
        <h4 class="note-modal-header">{{ __('Add Note') }}</h4>
        <form id="noteForm" method="POST" action="">
            @csrf
            <textarea name="note" rows="4" placeholder="{{ __('Enter your note here...') }}"></textarea>
            <button type="submit" class="btn-action btn-note">{{ __('Save Note') }}</button>
        </form>
    </div>
</div>

<!-- Feedback Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1" role="dialog" aria-labelledby="feedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="feedbackModalLabel">{{ __('Feedback') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="feedbackForm" method="POST" action="" enctype="multipart/form-data">
                    @csrf
                    <div id="dynamicFeedbackInputs"></div>
                    <div class="form-group">
                        <label>{{ __('Upload Photos') }}</label>
                        <input type="file" name="photos[]" class="form-control" multiple>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">{{ __('Submit Feedback') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@foreach ($tasks as $index =>  $task)
    @if (
        $index === 0 ||
        (
            $tasks[$index - 1]->job_order_status === 'completed'
            &&
            $tasks[$index - 1]->feedback_submitted
        ) ||
        (
            in_array($tasks[$index - 1]->job_order_status, [
                'تأجيل بنفس اليوم',
                'تأجيل ليوم اخر',
                'بحاجة عرض سعر',
                'الغاء المعاملة',
                'بحاجة لكشف مهندس',
                'بحاجة لاعادة تخطيط',
                'delivered',
                'rejected'
            ])
        )
    )
        <div class="modal fade" id="taskDetailsModal{{ $task->id }}" tabindex="-1" aria-labelledby="taskDetailsModalLabel{{ $task->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="taskDetailsModalLabel{{ $task->id }}">{{ __('Task Details') }} #{{ $task->id }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <tr><th>{{ __('Building') }}</th><td>{{ $task->building }}</td></tr>
                            <tr><th>{{ __('District') }}</th><td>{{ $task->district }}</td></tr>
                            <tr><th>{{ __('Area') }}</th><td>{{ $task->area }}</td></tr>
                            <tr><th>{{ __('Assigned Time') }}</th><td>{{ $task->assigned_time }}</td></tr>
                            <tr><th>{{ __('Customer Name') }}</th><td>{{ $task->customer_name }}</td></tr>
                            <tr><th>{{ __('Customer Username') }}</th><td>{{ $task->customer_username }}</td></tr>
                            <tr><th>{{ __('Customer Mobile') }}</th><td>{{ $task->customer_mobile }}</td></tr>
                            <tr><th>{{ __('Customer Address') }}</th><td>{{ $task->customer_address }}</td></tr>
                            <tr><th>{{ __('Splitting') }}</th><td>{{ $task->splitting }}</td></tr>
                            <tr><th>{{ __('SSPL No Planned') }}</th><td>{{ $task->sspl_no_planned }}</td></tr>
                            <tr><th>{{ __('RSPL No') }}</th><td>{{ $task->rspl_no }}</td></tr>
                            <tr><th>{{ __('Through') }}</th><td>{{ $task->through }}</td></tr>
                            <tr><th>{{ __('Core Color') }}</th><td>{{ $task->core_color }}</td></tr>
                            <tr><th>{{ __('Note') }}</th><td>{{ $task->note }}</td></tr>
                            <tr><th>{{ __('Job Order Status') }}</th><td>{{ ucfirst($task->job_order_status) }}</td></tr>
                            <tr><th>{{ __('Contractor Status') }}</th><td>{{ ucfirst($task->contractor_status) }}</td></tr>
                            <tr><th>{{ __('Customer Service Status') }}</th><td>{{ ucfirst($task->customer_service_status) }}</td></tr>
                            <tr><th>{{ __('Postal Code Status') }}</th><td>{{ ucfirst($task->postal_code_status) }}</td></tr>
                            <tr><th>{{ __('Assigned Admin ID') }}</th><td>{{ $task->admin_id }}</td></tr>
                            <tr><th>{{ __('Created By') }}</th><td>{{ $task->created_by }}</td></tr>
                            <tr><th>{{ __('Updated By') }}</th><td>{{ $task->updated_by }}</td></tr>
                            <tr><th>{{ __('Job Order Type') }}</th><td>{{ $task->jobOrderType->name ?? __('N/A') }}</td></tr>
                            <tr><th>{{ __('Type ID') }}</th><td>{{ $task->type_id }}</td></tr>
                            <tr><th>{{ __('Created At') }}</th><td>{{ $task->created_at }}</td></tr>
                            <tr><th>{{ __('Updated At') }}</th><td>{{ $task->updated_at }}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
    @else

    <div class="modal fade" id="taskDetailsModal{{ $task->id }}" tabindex="-1" aria-labelledby="taskDetailsModalLabel{{ $task->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="taskDetailsModalLabel{{ $task->id }}">{{ __('Task Details') }} #{{ $task->id }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
 
                 <p class="text-center text-muted">{{ __('Complete the previous task and submit feedback to unlock details.') }}</p>

                </div>
            </div>
        </div>
    </div>

    @endif

@endforeach
@endsection


@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tasks = @json($tasks);

    tasks.forEach(task => {
        handleStatusChange(task.id, task.job_order_status, true);
    });
});

$('#feedbackModal').on('hidden.bs.modal', function () {
    const inputsContainer = document.getElementById('dynamicFeedbackInputs');
    inputsContainer.innerHTML = ''; // Clear dynamic inputs
});
</script>

<script>
    // Filter tasks by job order type
    function filterTasks(jobOrderTypeId) {
        const url = new URL(window.location.href);
        url.searchParams.set('jobOrderType', jobOrderTypeId);
        window.location.href = url.toString();
    }
</script>
<script>
    const basePath = "{{ url('admin/tasks') }}";

    function openNoteModal(taskId) {
        const modal = document.getElementById('noteModal'); // Get the modal
        const form = document.getElementById('noteForm');   // Get the form

        // Build the form's action dynamically
        form.action = `${basePath}/${taskId}/addNote`;

        // Show the modal
        modal.style.display = 'flex';
    }

    function closeNoteModal() {
        const modal = document.getElementById('noteModal');
        modal.style.display = 'none';
    }

    function updateJobOrderType(taskId, jobOrderTypeId) {
    fetch(`{{ route('tasks.updateJobOrderType', ':id') }}`.replace(':id', taskId), {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ job_order_type_id: jobOrderTypeId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload(); // Refresh the page
        } else {
            alert(data.error || 'Something went wrong!');
        }
    })
    .catch(error => console.error('Error:', error));
}

function handleStatusChange(taskId, status, isInitialLoad = false) {
    console.log('Status changed for task:', taskId, 'to:', status);

    const timeInput = document.getElementById(`timeInput-${taskId}`);
    const dateTimeInput = document.getElementById(`dateTimeInput-${taskId}`);

    // Show or hide the inputs based on the status
    if (status === 'تأجيل بنفس اليوم') {
        timeInput.style.display = 'block';
        dateTimeInput.style.display = 'none';
    } else if (status === 'تأجيل ليوم اخر') {
        timeInput.style.display = 'none';
        dateTimeInput.style.display = 'block';
    } else {
        timeInput.style.display = 'none';
        dateTimeInput.style.display = 'none';
    }

    // Skip backend update if this is the initial load
    if (isInitialLoad) return;

    // Update the status in the backend
    updateStatus(taskId, 'job_order_status', status);
}



function updateStatus(taskId, statusKey, statusValue) {
    console.log('Updating status for task:', taskId, statusKey, statusValue);

    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token not found');
        return;
    }

    const url = `{{ route('tasks.updateStatus', ':id') }}`.replace(':id', taskId);

    fetch(url, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
        },
        body: JSON.stringify({
            status_key: statusKey,
            status_value: statusValue
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Failed to update status');
        }
        return response.json();
    })
    .then(data => {
        console.log('Server response:', data); // Debugging log
        if (data.success) {
            alert(data.message);
            location.reload(); // Reload to reflect the change
        } else {
            alert('Error updating status: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}



function updateTaskTime(taskId, time) {
    fetch(`{{ route('tasks.updateTime', ['id' => ':id']) }}`.replace(':id', taskId), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ time: time })
    }).then(response => response.json()).then(data => {
        alert(data.message);
    }).catch(error => console.error('Error:', error));
}

function updateTaskDateTime(taskId, dateTime) {
    fetch(`{{ route('tasks.updateDateTime', ['id' => ':id']) }}`.replace(':id', taskId), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ dateTime: dateTime })
    }).then(response => response.json()).then(data => {
        alert(data.message);
    }).catch(error => console.error('Error:', error));
}


</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Function to calculate link distance after 120m
        function calculateLinkDistance() {
            const hookReading = parseFloat(document.getElementById('hook_reading')?.value) || 0;
            const endReading = parseFloat(document.getElementById('end_reading')?.value) || 0;
            const difference = Math.abs(endReading - hookReading);

            const linkDistanceField = document.getElementById('link_distance_after_120m');
            if (linkDistanceField) {
                linkDistanceField.value = difference > 120 ? difference : '';
            }
        }

        // Function to load feedback form
        function loadFeedbackForm(taskId, jobOrderTypeId) {
            const tasks = @json($tasks ?? []);
            const task = tasks.find(task => task.id === taskId);
            const form = document.getElementById('feedbackForm');
            const inputsContainer = document.getElementById('dynamicFeedbackInputs');

            // Set form action dynamically
            form.action = `{{ route('tasks.feedback', ':id') }}`.replace(':id', taskId);

            // Clear existing inputs
            inputsContainer.innerHTML = '';

            const feedbackData = task?.feedback ? JSON.parse(task.feedback.data) : null;

            // Generate dynamic inputs based on jobOrderType
            let inputs = '';
             // For jobOrderTypeId === 5, display two tables with dynamic rows
             if (jobOrderTypeId === 5) {
                // Table 1
                if (feedbackData.table1 && feedbackData.table1.length > 0) {
                    inputs += `
                        <div>
                            <h4>Table 1</h4>
                            <table class="table table-bordered" id="table1">
                                <thead>
                                    <tr>
                                        <th>ROOT Splitter No.</th>
                                        <th>Start Reading</th>
                                        <th>Postal Code</th>
                                        <th>End Reading</th>
                                        <th>Length (m)</th>
                                        <th>المنطقة</th>
                                        <th>التاريخ</th>
                                        <th>u</th>
                                        <th>كلبس</th>
                                        <th>مرابط</th>
                                        <th>Postal</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>`;

                    // Loop through `table1` data and populate rows
                    feedbackData.table1.forEach(row => {
                        inputs += `
                            <tr>
                                <td><input type="text" name="root_splitter_no[]" class="form-control" value="${row.root_splitter_no}" required></td>
                                <td><input type="number" name="start_reading[]" class="form-control start-reading" value="${row.start_reading}" oninput="updateLength(this)" required></td>
                                <td><input type="text" name="postal_code[]" class="form-control" value="${row.postal_code}" required></td>
                                <td><input type="number" name="end_reading[]" class="form-control end-reading" value="${row.end_reading}" oninput="updateLength(this)" required></td>
                                <td><input type="text" name="length[]" class="form-control length" value="${row.length}" readonly></td>
                                <td><input type="text" name="location[]" class="form-control" value="${row.location || ''}" required></td>
                                <td><input type="date" name="date[]" class="form-control" value="${row.date || ''}" required></td>
                                <td><input type="text" name="u[]" class="form-control" value="${row.u || ''}" required></td>
                                <td><input type="text" name="clamps[]" class="form-control" value="${row.clamps || ''}" required></td>      
                                <td><input type="text" name="studs[]" class="form-control" value="${row.studs || ''}" required></td>      
                                <td><input type="text" name="postal[]" class="form-control" value="${row.postal || ''}" required></td>      

                                <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Remove</button></td>
                            </tr>`;
                    });

                    inputs += `
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-primary btn-sm" onclick="addRow('table1')">Add Row</button>
                        </div>`;

                }else{

                    inputs += `
                        <div>
                            <h4>Table 1</h4>
                            <table class="table table-bordered" id="table1">
                                <thead>
                                    <tr>
                                        <th>ROOT Splitter No.</th>
                                        <th>Start Reading</th>
                                        <th>Postal Code</th>
                                        <th>End Reading</th>
                                        <th>Length (m)</th>
                                        <th>المنطقة</th>
                                        <th>التاريخ</th>
                                        <th>u</th>
                                        <th>كلبس</th>
                                        <th>مرابط</th>
                                        <th>Postal</th>                        
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                    <td><input type="text" name="root_splitter_no[]" class="form-control" required></td>
                                    <td><input type="number" name="start_reading[]" class="form-control start-reading" oninput="updateLength(this)" required></td>
                                    <td><input type="text" name="postal_code[]" class="form-control" required></td>
                                    <td><input type="number" name="end_reading[]" class="form-control end-reading" oninput="updateLength(this)" required></td>
                                    <td><input type="text" name="length[]" class="form-control length" readonly></td>
                                    <td><input type="text" name="location[]" class="form-control" required></td>
                                    <td><input type="date" name="date[]" class="form-control" required></td>
                                    <td><input type="text" name="u[]" class="form-control" required></td>
                                    <td><input type="text" name="clamps[]" class="form-control" required></td>      
                                    <td><input type="text" name="studs[]" class="form-control" required></td>      
                                    <td><input type="text" name="postal[]" class="form-control" required></td>      
                                    <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Remove</button></td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-primary btn-sm" onclick="addRow('table1')">Add Row</button>
                        </div>
                    `;                    
                }

                // Table 2
                if (feedbackData.table2 && feedbackData.table2.length > 0) {
                    inputs += `
                        <div>
                            <h4>Table 2</h4>
                            <table class="table table-bordered" id="table2">
                                <thead>
                                    <tr>
                                        <th>R or S / SPLITTER No.</th>
                                        <th>JOINT No.</th>
                                        <th>Power In</th>
                                        <th>Power Out</th>
                                        <th>Core's Colour</th>
                                        <th>Splitter Qty.</th>
                                        <th>لحام شعرة</th>
                                        <th>تركيب مروحة</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>`;

                    // Loop through `table2` data and populate rows
                    feedbackData.table2.forEach(row => {
                        inputs += `
                            <tr>
                                <td><input type="text" name="splitter_no[]" class="form-control" value="${row.splitter_no}" required></td>
                                <td><input type="text" name="joint_no[]" class="form-control" value="${row.joint_no}" required></td>
                                <td><input type="text" name="power_in[]" class="form-control" value="${row.power_in}" required></td>
                                <td><input type="text" name="power_out[]" class="form-control" value="${row.power_out}" required></td>
                                <td><input type="text" name="core_color[]" class="form-control" value="${row.core_color}" required></td>
                                <td><input type="text" name="splitter_qty[]" class="form-control" value="${row.splitter_qty}" required></td>
                                <td><input type="text" name="lham_shara[]" class="form-control" value="${feedbackData.lham_shara ? feedbackData.lham_shara[0] : ''}" required></td>
                                <td>
                                    <select name="tarkeb_marwaha[]" class="form-control" required>
                                        <option value="1" ${feedbackData.tarkeb_marwaha && feedbackData.tarkeb_marwaha[0] == "1" ? "selected" : ""}>Yes</option>
                                        <option value="2" ${feedbackData.tarkeb_marwaha && feedbackData.tarkeb_marwaha[0] == "2" ? "selected" : ""}>No</option>
                                    </select>
                                </td>
                                <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Remove</button></td>
                            </tr>`;
                    });

                    inputs += `
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-primary btn-sm" onclick="addRow('table2')">Add Row</button>
                        </div>`;

                }else{


                    // Table 2
                    inputs += `
                        <div>
                            <h4>Table 2</h4>
                            <table class="table table-bordered" id="table2">
                                <thead>
                                    <tr>
                                        <th>R or S / SPLITTER No.</th>
                                        <th>JOINT No.</th>
                                        <th>Power In</th>
                                        <th>Power Out</th>
                                        <th>Core's Colour</th>
                                        <th>Splitter Qty.</th>
                                        <th>لحام شعرة</th>
                                        <th>تركيب مروحة</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" name="splitter_no[]" class="form-control" required></td>
                                        <td><input type="text" name="joint_no[]" class="form-control" required></td>
                                        <td><input type="text" name="power_in[]" class="form-control" required></td>
                                        <td><input type="text" name="power_out[]" class="form-control" required></td>
                                        <td><input type="text" name="core_color[]" class="form-control" required></td>
                                        <td><input type="text" name="splitter_qty[]" class="form-control" required></td>
                                        <td><input type="text" name="lham_shara[]" class="form-control" required></td>
                                        <td>
                                            <select name="tarkeb_marwaha[]" class="form-control" required>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </select>
                                        </td>
                                        <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Remove</button></td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-primary btn-sm" onclick="addRow('table2')">Add Row</button>
                        </div>
                    `;                    
                }              
            } else if(jobOrderTypeId === 6){
                 // Table 3
                if (feedbackData.table3 && feedbackData.table3.length > 0) {
                    inputs += `
                        <div>
                            <h4>Table 3</h4>
                            <table class="table table-bordered" id="table3">
                                <thead>
                                    <tr>
                                        <th>JOINT OR Root Splitter NO.</th>
                                        <th>Start Reading</th>
                                        <th>JOINT OR Root Splitter NO.</th>
                                        <th>End Reading</th>
                                        <th>FO Size(عدد الشعرات)</th>
                                        <th>Length(m)</th>
                                        <th>Port</th>
                                        <th>Postal</th>
                                        <th>التاريخ</th>
                                        <th>المنطقة</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>`;

                    // Loop through `table3` data and populate rows
                    feedbackData.table3.forEach(row => {
                        inputs += `
                            <tr>
                                <td><input type="text" name="from_joint_root_splitter_no[]" class="form-control" value="${row.from_joint_root_splitter_no}" required></td>
                                <td><input type="text" name="start_reading[]" class="form-control start-reading" value="${row.start_reading}" oninput="updateLength(this)" required></td>
                                <td><input type="text" name="to_joint_root_splitter_no[]" class="form-control" value="${row.to_joint_root_splitter_no}" required></td>
                                <td><input type="number" name="end_reading[]" class="form-control end-reading" value="${row.end_reading}" oninput="updateLength(this)" required></td>
                                <td><input type="text" name="fo_size[]" class="form-control" value="${row.fo_size}" required></td>
                                <td><input type="number" name="length_rollout[]" class="form-control length" value="${row.length_rollout}" readonly></td>
                                <td><input type="text" name="port[]" class="form-control" value="${row.port || ''}" required></td>                            
                                <td><input type="text" name="postal[]" class="form-control" value="${row.postal || ''}" required></td>                            
                                <td><input type="date" name="date[]" class="form-control" value="${row.date || ''}" required></td>     
                                <td><input type="text" name="location[]" class="form-control" value="${row.location || ''}" required></td>                            
                                <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Remove</button></td>
                            </tr>`;
                    });

                    inputs += `
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-primary btn-sm" onclick="addRow('table3')">Add Row</button>
                        </div>`;
                }else{

                 // Table 3
                 inputs += `
                    <div>
                        <h4>Table 3</h4>
                        <table class="table table-bordered" id="table3">
                            <thead>
                                <tr>
                                    <th> JOINT OR Root Spliter NO.</th>
                                    <th>Start Reading</th>
                                    <th> JOINT OR Root Spliter NO.</th>
                                    <th>End Reading</th>
                                    <th>FO Size(عدد الشعرات)</th>
                                    <th>Length(m)</th>
                                    <th>Port</th>
                                    <th>Postal</th>
                                    <th>التاريخ</th>
                                    <th>المنطقة</th>                    
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" name="from_joint_root_splitter_no[]" class="form-control" required></td>
                                    <td><input type="text" name="start_reading[]" class="form-control start-reading" oninput="updateLength(this)" required></td>
                                    <td><input type="text" name="to_joint_root_splitter_no[]" class="form-control" required></td>
                                     <td><input type="number" name="end_reading[]" class="form-control end-reading" oninput="updateLength(this)" required></td>
                                    <td><input type="text" name="fo_size[]" class="form-control" required></td>
                                     <td><input type="number" name="length_rollout[]" class="form-control length" readonly></td>
                                    <td><input type="text" name="port[]" class="form-control" required></td>                            
                                    <td><input type="text" name="postal[]" class="form-control" required></td>                            
                                    <td><input type="date" name="date[]" class="form-control" required></td>     
                                    <td><input type="text" name="location[]" class="form-control" required></td>                         
                                    <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Remove</button></td>
                                </tr>
                            </tbody>
                        </table>
                       <button type="button" class="btn btn-primary btn-sm" onclick="addRow('table3')">Add Row</button>
                    </div>
                `;
                                    
                }
            }else if (jobOrderTypeId === 1 || jobOrderTypeId === 4 ) {
                inputs += `
                    <div class="form-group" id="quantityPipesGroup">
                        <label>{{ __('الكمية') }}</label>
                        <input type="text" name="quantity_pipes" class="form-control" value="${feedbackData?.quantity_pipes || ''}" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('مواسير') }}</label>
                        <select name="pipes" class="form-control" id="pipesSelect">
                            <option value="1" {{ old('pipes') == 1 ? 'selected' : '' }}>مواسير</option>
                            <option value="2" {{ old('pipes') == 2 ? 'selected' : '' }}>ترنكات</option>
                            <option value="3" {{ old('pipes') == 3 ? 'selected' : '' }}>برابيش</option>
                            <option value="4" {{ old('pipes') == 4 ? 'selected' : '' }}>تدكيك </option>
                            <option value="5" {{ old('pipes') == 5 ? 'selected' : '' }}>تدكيك بمسار مغلق </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('رقم البوستال') }}</label>
                        <input type="text" name="postal_number" class="form-control" value="${feedbackData?.postal_number || ''}" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('قراءة البور على الصندوق') }}</label>
                        <input type="text" name="box_power_reading" class="form-control" value="${feedbackData?.box_power_reading || ''}" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('قراءة البور عند المشترك') }}</label>
                        <input type="text" name="subscriber_power_reading" class="form-control" value="${feedbackData?.subscriber_power_reading || ''}" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('رقم البورت على الصندوق') }}</label>
                        <input type="text" name="port_in_box" class="form-control" value="${feedbackData?.port_in_box || ''}" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('تركيب راوتر') }}</label>
                        <select name="router" class="form-control">
                            <option value="1" {{ old('router') == 1 ? 'selected' : '' }}>{{ __('messages.Yes') }}</option>
                            <option value="2" {{ old('router') == 2 ? 'selected' : '' }}>{{ __('messages.No') }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('تركيب مدى tv') }}</label>
                        <select name="mada_tv" class="form-control">
                            <option value="1" {{ old('mada_tv') == 1 ? 'selected' : '' }}>{{ __('messages.Yes') }}</option>
                            <option value="2" {{ old('mada_tv') == 2 ? 'selected' : '' }}>{{ __('messages.No') }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('لحام شعرات') }}</label>
                        <select name="le7am_sh3raat" class="form-control">
                            <option value="1" {{ old('le7am_sh3raat') == 1 ? 'selected' : '' }}>{{ __('messages.Yes') }}</option>
                            <option value="2" {{ old('le7am_sh3raat') == 2 ? 'selected' : '' }}>{{ __('messages.No') }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('ملاحظات أخرى') }}</label>
                        <input type="text" name="note" class="form-control" value="${feedbackData?.note || ''}">
                    </div>
                `;
            } else if (jobOrderTypeId === 2 || jobOrderTypeId === 3 ) {
                inputs += `
                    <div class="form-group">
                        <label>{{ __('التاريخ') }}</label>
                        <input type="date" name="date" class="form-control" value="${feedbackData?.date || ''}">
                    </div>
                    <div class="form-group">
                        <label>{{ __('البوستل الصحيح') }}</label>
                        <input type="text" name="postal" class="form-control" value="${feedbackData?.postal || ''}" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('طريقة الدخول') }}</label>
                        <select name="entry_method" class="form-control">
                            <option value="1" {{ old('entry_method') == 1 ? 'selected' : '' }}>هوائي</option>
                            <option value="2" {{ old('entry_method') == 2 ? 'selected' : '' }}>مواسير</option>
                            <option value="3" {{ old('entry_method') == 3 ? 'selected' : '' }}>تدكيك</option>
                            <option value="4" {{ old('entry_method') == 4 ? 'selected' : '' }}>تدكيك مسار مغلق</option>
                            <option value="5" {{ old('entry_method') == 5 ? 'selected' : '' }}>تثمينة</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('رقم الستريت') }}</label>
                        <input type="text" name="street_number" class="form-control" value="${feedbackData?.street_number || ''}" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('رقم الروت') }}</label>
                        <input type="text" name="root_number" class="form-control" value="${feedbackData?.root_number || ''}" required>
                    </div>                    
                    <div class="form-group">
                    <label>{{ __('قراءة البداية') }}</label>
                    <input type="text" name="start_reading" class="form-control"  value="${feedbackData?.start_reading || ''}" required>
                     </div>
                    <div class="form-group">
                        <label>{{ __('قراءة الهوك') }}</label>
                        <input type="text" id="hook_reading" name="hook_reading" class="form-control" value="${feedbackData?.hook_reading || ''}" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('قراءة النهاية') }}</label>
                        <input type="text" id="end_reading" name="end_reading" class="form-control" value="${feedbackData?.end_reading || ''}" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('طول الكابل (متر)') }}</label>
                        <input type="text" id="cable_length" name="cable_length" class="form-control" value="${feedbackData?.cable_length || ''}" required>
                    </div>   
                    <div class="form-group">
                        <label>{{ __('طول الربط') }}</label>
                        <input type="text" id="binding_length" name="binding_length" class="form-control" value="${feedbackData?.binding_length || ''}" required>
                    </div>                                        
                    <div class="form-group">
                        <label>{{ __('مسافة الربط بعد 120 متر') }}</label>
                        <input type="text" id="link_distance_after_120m" name="link_distance_after_120m" class="form-control" value="${feedbackData?.link_distance_after_120m || ''}" readonly>
                    </div>
                      <div class="form-group">
                    <label>{{ __('المواسير بعد 5متر') }}</label>
                    <input type="text" name="pipes_after_5m" class="form-control" value="${feedbackData?.pipes_after_5m || ''}" required>
                </div>
                <div class="form-group">
                    <label>{{ __('عدد مرابط الشد') }}</label>
                    <input type="text" name="clamp_count" class="form-control" value="${feedbackData?.clamp_count || ''}" required>
                </div>
                <div class="form-group">
                    <label>{{ __('عدد مرابط التعليق') }}</label>
                    <input type="text" name="suspension_brackets_count" class="form-control" value="${feedbackData?.suspension_brackets_count || ''}" required>
                </div> 
                <div class="form-group">
                    <label>{{ __('u') }}</label>
                    <input type="text" name="u" class="form-control" value="${feedbackData?.u || ''}" required>
                </div>      
                <div class="form-group">
                    <label>{{ __('عدد الكلبسات') }}</label>
                    <input type="text" name="clamps_count" class="form-control" value="${feedbackData?.clamps_count || ''}" required>
                </div>                                                    
                <div class="form-group">
                    <label>{{ __('قراءة البور على السبلتر (عامود)') }}</label>
                    <input type="text" name="splitter_power_reading" class="form-control" value="${feedbackData?.splitter_power_reading || ''}" required>
                </div>
                <div class="form-group">
                    <label>{{ __('قراءة البور على المبنى \\ المشترك') }}</label>
                    <input type="text" name="building_power_reading" class="form-control" value="${feedbackData?.building_power_reading || ''}" required>
                </div>
                <div class="form-group">
                    <label>{{ __('ملاحظات') }}</label>
                    <textarea name="notes" class="form-control" rows="4" placeholder="{{ __('Enter your notes here...') }}"></textarea>
                </div>
                `;
            }

            // Append inputs to the container
            inputsContainer.innerHTML = inputs;

            // Reattach event listeners for dynamically generated inputs
            if (document.getElementById('hook_reading') && document.getElementById('end_reading')) {
                document.getElementById('hook_reading').addEventListener('input', calculateLinkDistance);
                document.getElementById('end_reading').addEventListener('input', calculateLinkDistance);
            }

               // Reattach event listener to dynamically created dropdown
               const pipesSelect = document.getElementById('pipesSelect');
            const quantityPipesGroup = document.getElementById('quantityPipesGroup');

            if (pipesSelect && quantityPipesGroup) {
                pipesSelect.addEventListener('change', function () {
                    if (pipesSelect.value === '4') {
                        quantityPipesGroup.style.display = 'none';
                    } else {
                        quantityPipesGroup.style.display = 'block';
                    }
                });

                // Initialize visibility based on current selection
                if (pipesSelect.value === '4') {
                    quantityPipesGroup.style.display = 'none';
                } else {
                    quantityPipesGroup.style.display = 'block';
                }
            }
        }

                // Function to add a row to a specific table
            window.addRow = function (tableId) {
                const table = document.getElementById(tableId).querySelector('tbody');
                let newRow = '';

                if (tableId === 'table1') {
                    // Logic for table1 (jobOrderTypeId === 5)
                    newRow = `
                        <tr>
                        <td><input type="text" name="root_splitter_no[]" class="form-control" required></td>
                        <td><input type="number" name="start_reading[]" class="form-control start-reading" oninput="updateLength(this)" required></td>
                        <td><input type="text" name="postal_code[]" class="form-control" required></td>
                        <td><input type="number" name="end_reading[]" class="form-control end-reading" oninput="updateLength(this)" required></td>
                        <td><input type="text" name="length[]" class="form-control length" readonly></td>
                        <td><input type="text" name="location[]" class="form-control" required></td>
                        <td><input type="date" name="date[]" class="form-control" required></td>
                        <td><input type="text" name="u[]" class="form-control" required></td>
                        <td><input type="text" name="clamps[]" class="form-control" required></td>      
                        <td><input type="text" name="studs[]" class="form-control" required></td>      
                        <td><input type="text" name="postal[]" class="form-control" required></td>  
                        <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Remove</button></td>
                        </tr>
                    `;
                } else if (tableId === 'table2') {
                    // Logic for table2 (jobOrderTypeId === 5)
                    newRow = `
                        <tr>
                            <td><input type="text" name="splitter_no[]" class="form-control" required></td>
                            <td><input type="text" name="joint_no[]" class="form-control" required></td>
                            <td><input type="text" name="power_in[]" class="form-control" required></td>
                            <td><input type="text" name="power_out[]" class="form-control" required></td>
                            <td><input type="text" name="core_color[]" class="form-control" required></td>
                            <td><input type="text" name="splitter_qty[]" class="form-control" required></td>
                            <td><input type="text" name="lham_shara[]" class="form-control" required></td>
                            <td>
                                 <select name="tarkeb_marwaha[]" class="form-control" required>
                                    <option value="1">Yes</option>
                                    <option value="2">No</option>
                                 </select>
                            </td>
                            <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Remove</button></td>
                        </tr>
                    `;
                } else if (tableId === 'table3') {
                    // Logic for table3 (jobOrderTypeId === 6)
                    newRow = `
                       <tr>
                            <td><input type="text" name="from_joint_root_splitter_no[]" class="form-control" required></td>
                            <td><input type="number" name="start_reading[]" class="form-control start-reading" oninput="updateLength(this)" required></td>
                            <td><input type="text" name="to_joint_root_splitter_no[]" class="form-control" required></td>
                            <td><input type="number" name="end_reading[]" class="form-control end-reading" oninput="updateLength(this)" required></td>
                            <td><input type="number" name="fo_size[]" class="form-control" required></td>
                            <td><input type="number" name="length_rollout[]" class="form-control length" readonly></td>
                            <td><input type="text" name="port[]" class="form-control" required></td>                            
                            <td><input type="text" name="postal[]" class="form-control" required></td>                            
                            <td><input type="date" name="date[]" class="form-control" required></td>     
                            <td><input type="text" name="location[]" class="form-control" required></td>                          
                            <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Remove</button></td>
                       </tr>
                    `;
                }

                table.insertAdjacentHTML('beforeend', newRow);
            };

        // Function to update the length value in Table 3
        window.updateLength = function (inputElement) {
            const row = inputElement.closest('tr'); // Get the row of the current input
            const startReading = parseFloat(row.querySelector('.start-reading')?.value) || 0; // Get start_reading
            const endReading = parseFloat(row.querySelector('.end-reading')?.value) || 0; // Get end_reading
            const lengthField = row.querySelector('.length'); // Get the length field

            // Calculate and update length
            if (lengthField) {
                lengthField.value = Math.abs(endReading - startReading);
            }
        };

        // Function to remove a row
        window.removeRow = function (button) {
            const row = button.closest('tr');
            row.parentNode.removeChild(row);
        };

        // Export the function globally (if needed)
        window.loadFeedbackForm = loadFeedbackForm;
    });
</script>

@endsection
