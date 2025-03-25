@extends('layouts.admin')
@section('title', __('messages.Home'))

@section('css')
<style>
    .dashboard {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        padding: 20px;
    }

    .task-card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        transition: transform 0.3s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
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
@if($admin->is_super || $admin->is_constructor == 0)


<form method="GET" action="{{ route('admin.dashboard') }}">
    <div class="row mb-3">

        <div class="col-md-3">
            <label for="search">{{ __('Search') }}</label>
            <input type="text" id="search" name="search" class="form-control" value="{{ request('search') }}" placeholder="{{ __('Search...') }}">
        </div>

        <!-- Country Filter -->
        <div class="col-md-2">
            <label for="country">{{ __('Country') }}</label>
            <select name="country" id="country" class="form-control">
                <option value="">{{ __('All') }}</option>
                @foreach ($countries as $country)
                    <option value="{{ $country->id }}" {{ request('country') == $country->id ? 'selected' : '' }}>
                        {{ $country->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Employee Filter -->
        <div class="col-md-2">
            <label for="employee">{{ __('Employee') }}</label>
            <select name="employee" id="employee" class="form-control">
                <option value="">{{ __('All') }}</option>
                @foreach ($employees as $employee)
                    <option value="{{ $employee->id }}" {{ request('employee') == $employee->id ? 'selected' : '' }}>
                        {{ $employee->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Job Order Type Filter -->
        <div class="col-md-2">
            <label for="job_order_type">{{ __('Job Order Type') }}</label>
            <select name="job_order_type" id="job_order_type" class="form-control">
                <option value="">{{ __('All') }}</option>
                @foreach ($jobOrderTypes as $type)
                    <option value="{{ $type->id }}" {{ request('job_order_type') == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label for="jobOrderStatus">{{ __('Job Order Status') }}</label>
            <select name="jobOrderStatus" id="jobOrderStatus" class="form-control">
                <option value="">{{ __('All Statuses') }}</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status }}" {{ request('jobOrderStatus') == $status ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Submit Button -->
        <div class="col-md-3 align-self-end">
            <button type="submit" class="btn btn-primary">{{ __('Filter') }}</button>
        </div>
    </div>
</form>

<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>{{ __('Task ID') }}</th>
            <th>{{ __('Customer Name') }}</th>
            <th>{{ __('Job Order Type') }}</th>
            <th>{{ __('Job Order Status') }}</th>
            <th>{{ __('Postal Code') }}</th>
            <th>{{ __('Actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tasks as $task)
            <tr style="background-color:
                @if($task->job_order_status === 'opened') #f8f9fa; /* Light Gray */
                @elseif($task->job_order_status === 'بحاجة لكشف مهندس') #fff3cd; /* Light Yellow */
                @elseif($task->job_order_status === 'تأجيل بنفس اليوم') #d1ecf1; /* Light Cyan */
                @elseif($task->job_order_status === 'تأجيل ليوم اخر') #d4edda; /* Light Green */
                @elseif($task->job_order_status === 'بحاجة عرض سعر') #fce4d6; /* Light Orange */
                @elseif($task->job_order_status === 'الغاء المعاملة') #f8d7da; /* Light Red */
                @elseif($task->job_order_status === 'بحاجة لاعادة تخطيط') #e2e3e5; /* Light Gray */
                @elseif($task->job_order_status === 'completed') #d9edf7; /* Light Blue */
                @elseif($task->job_order_status === 'delivered') #d4edda; /* Light Green */
                @elseif($task->job_order_status === 'rejected') #f8d7da; /* Light Red */
                @else #ffffff; /* Default White */
                @endif">
                <!-- Task ID -->
                <td>
                    <a href="#" data-toggle="modal" data-target="#taskDetailsModal{{ $task->id }}">
                        {{ $task->id }}
                    </a>
                </td>

                <!-- Customer Name -->
                <td>{{ $task->customer_name }}</td>

                <!-- Job Order Type -->
                <td>{{ $task->jobOrderType->name }}</td>

                <!-- Job Order Status -->
                <td>{{ ucfirst($task->job_order_status) }}</td>

                <!-- Postal Code -->
                <td>
                    <a href="#" onclick="copyToClipboard('{{ $task->building }}')">
                        {{ $task->building }}
                    </a>
                    |
                    <a href="https://www.google.com/maps/search/?api=1&query={{ $task->building }}" target="_blank">
                        {{ __('View on Map') }}
                    </a>
                </td>

                <!-- Actions -->
                <td>
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#feedbackModal{{ $task->id }}">
                        {{ __('View Feedback') }}
                    </button>

                    @if (auth()->user()->can('submit-button'))
                    <!-- Accept Button -->
                    <form method="POST" action="{{ route('tasks.updateStatus', ['id' => $task->id]) }}" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status_key" value="job_order_status">
                        <input type="hidden" name="status_value" value="delivered">
                        <button type="submit" class="btn btn-success btn-sm">{{ __('Accept') }}</button>
                    </form>
                    @endif

                    @if (auth()->user()->can('reject-button'))
                    <!-- Reject Button -->
                    <form method="POST" action="{{ route('tasks.updateStatus', ['id' => $task->id]) }}" style="display: inline;" onsubmit="return showRejectionModal(event, {{ $task->id }})">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status_key" value="job_order_status">
                        <input type="hidden" name="status_value" value="rejected">
                        <input type="hidden" name="note_of_reject" id="note_of_reject_input_{{ $task->id }}">
                        <button type="submit" class="btn btn-danger btn-sm">{{ __('Reject') }}</button>
                    </form>
                    @endif

                    @if (auth()->user()->can('whatsapp-button'))
                    <!-- WhatsApp Button -->
                    @php
                    $adminCountry = null;
                    if ($task->admin) {
                        $adminCountry = \App\Models\Country::where('id', $task->admin->country_id)->first();
                    }
                    $whatsappLink = $adminCountry ? $adminCountry->whatsapp_link : null;

                    // Task details
                    $message = "*Task Details #{$task->id}*%0A";
                    $message .= "🏢 *Building:* {$task->building}%0A";
                    $message .= "📍 *District:* {$task->district}%0A";
                    $message .= "📌 *Area:* {$task->area}%0A";
                    $message .= "⏰ *Assigned Time:* {$task->assigned_time}%0A";
                    $message .= "👤 *Customer Name:* {$task->customer_name}%0A";
                    $message .= "📞 *Customer Mobile:* {$task->customer_mobile}%0A";
                    $message .= "🏠 *Customer Address:* {$task->customer_address}%0A";
                    $message .= "📋 *Note:* {$task->note}%0A";
                    $message .= "🚀 *Job Order Status:* " . ucfirst($task->job_order_status) . "%0A%0A";

                    // Feedback details
                    $message .= "*Feedback Details:*%0A";
                    if ($task->feedback && $task->feedback->data) {
                        $feedbackData = json_decode($task->feedback->data, true);
                        foreach ($feedbackData as $key => $value) {
                            $formattedValue = is_array($value) ? implode(', ', $value) : $value;
                            $message .= "🔹 *" . ucfirst($key) . ":* {$formattedValue}%0A";
                        }
                    } else {
                        $message .= "❌ No feedback available.%0A";
                    }

                    // Photo Links
                    $message .= "%0A📸 *Uploaded Photos:*%0A";
                    if ($task->feedback && $task->feedback->photos->isNotEmpty()) {
                        foreach ($task->feedback->photos as $photo) {
                            $photoUrl = asset('assets/admin/uploads/' . $photo->photo_path);
                            $message .= "{$photoUrl}%0A";
                        }
                    } else {
                        $message .= "❌ No photos uploaded.%0A";
                    }

                    // Encode message for URL
                    $whatsappUrl = $whatsappLink ? "{$whatsappLink}?text={$message}" : null;
                    @endphp

                    @if($whatsappUrl)
                        <a href="{{ $whatsappUrl }}" target="_blank" class="btn btn-info btn-sm">
                            {{ __('Send to WhatsApp') }}
                        </a>
                    @else
                        <button class="btn btn-secondary btn-sm" disabled>{{ __('WhatsApp Not Available') }}</button>
                    @endif
                    @endif
                </td>
            </tr>

        @endforeach
    </tbody>
</table>
 @foreach ($tasks as $task)

 <div class="modal fade" id="rejectionNoteModal{{ $task->id }}" tabindex="-1" aria-labelledby="rejectionNoteModalLabel{{ $task->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectionNoteModalLabel{{ $task->id }}">{{ __('Rejection Note for Task') }} #{{ $task->id }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="note_of_reject">{{ __('Rejection Note') }}</label>
                    <textarea
                        id="note_of_reject_textarea_{{ $task->id }}"
                        class="form-control"
                        rows="4"
                        placeholder="{{ __('Enter the reason for rejection...') }}"
                        required></textarea>
                </div>
                <div class="form-group">
                    <label for="photo_of_reject">{{ __('Rejection Photo') }}</label>
                    <input
                        type="file"
                        name="photo_of_reject"
                        id="photo_of_reject_{{ $task->id }}"
                        class="form-control"
                        accept="image/*">
                </div>
                <button type="button" class="btn btn-danger" onclick="submitRejectionForm({{ $task->id }})">{{ __('Submit') }}</button>
            </div>
        </div>
    </div>
</div>

         {{-- Modal for view of admin --}}
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


<div class="modal fade" id="feedbackModal{{ $task->id }}" tabindex="-1" role="dialog" aria-labelledby="feedbackModalLabel{{ $task->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="feedbackModalLabel{{ $task->id }}">{{ __('Feedback for Task') }} #{{ $task->id }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h6>{{ __('Feedback Details') }}</h6>
                @if($task->feedback && $task->feedback->data)
                @php
                    $feedbackData = json_decode($task->feedback->data, true);
                @endphp
                <ul>
                    @foreach($feedbackData as $key => $value)
                        <li><strong>{{ ucfirst($key) }}:</strong> {{ is_array($value) ? implode(', ', $value) : $value }}</li>
                    @endforeach
                </ul>
                @else
                    <p>{{ __('No feedback available.') }}</p>
                @endif

                <h6 class="mt-4">{{ __('Uploaded Photos') }}</h6>
                @if($task->feedback && $task->feedback->photos->isNotEmpty())
                    <div class="row">
                        @foreach ($task->feedback->photos as $photo)
                            <div class="col-md-3">
                                <img src="{{ asset('assets/admin/uploads/' . $photo->photo_path) }}" class="img-fluid img-thumbnail" alt="{{ __('Photo') }}">
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>{{ __('No photos uploaded.') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach


<div class="container my-5">
    <h2 class="mb-4">Status Color Map</h2>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Status</th>
                <th>Color</th>
                <th>Meaning</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>opened</td>
                <td style="background-color: #f8f9fa;">#f8f9fa</td>
                <td>Light Gray - Task is opened.</td>
            </tr>
            <tr>
                <td>بحاجة لكشف مهندس</td>
                <td style="background-color: #fff3cd;">#fff3cd</td>
                <td>Light Yellow - Requires engineer's inspection.</td>
            </tr>
            <tr>
                <td>تأجيل بنفس اليوم</td>
                <td style="background-color: #d1ecf1;">#d1ecf1</td>
                <td>Light Cyan - Postponed for the same day.</td>
            </tr>
            <tr>
                <td>تأجيل ليوم اخر</td>
                <td style="background-color: #d4edda;">#d4edda</td>
                <td>Light Green - Postponed to another day.</td>
            </tr>
            <tr>
                <td>بحاجة عرض سعر</td>
                <td style="background-color: #fce4d6;">#fce4d6</td>
                <td>Light Orange - Needs a price offer.</td>
            </tr>
            <tr>
                <td>الغاء المعاملة</td>
                <td style="background-color: #f8d7da;">#f8d7da</td>
                <td>Light Red - Transaction canceled.</td>
            </tr>
            <tr>
                <td>بحاجة لاعادة تخطيط</td>
                <td style="background-color: #e2e3e5;">#e2e3e5</td>
                <td>Light Gray - Requires rescheduling.</td>
            </tr>
            <tr>
                <td>completed</td>
                <td style="background-color: #d9edf7;">#d9edf7</td>
                <td>Light Blue - Task is completed.</td>
            </tr>
            <tr>
                <td>delivered</td>
                <td style="background-color: #d4edda;">#d4edda</td>
                <td>Light Green - Task is delivered.</td>
            </tr>
            <tr>
                <td>rejected</td>
                <td style="background-color: #f8d7da;">#f8d7da</td>
                <td>Light Red - Task is rejected.</td>
            </tr>
            <tr>
                <td>Default</td>
                <td style="background-color: #ffffff;">#ffffff</td>
                <td>Default White - No specific status.</td>
            </tr>
        </tbody>
    </table>
</div>

{{-- End Modal for view of admin --}}
@endif
@endsection


@section('js')
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('{{ __("Postal code copied to clipboard!") }}');
        }).catch(err => {
            alert('{{ __("Failed to copy postal code.") }}');
        });
    }
</script>
<script>
    function showRejectionModal(event, taskId) {
    event.preventDefault(); // Prevent default form submission
    const modal = document.getElementById(`rejectionNoteModal${taskId}`);
    $(modal).modal('show'); // Display the modal
    return false; // Stop form submission
}

function submitRejectionForm(taskId) {
    const textarea = document.getElementById(`note_of_reject_textarea_${taskId}`);
    const input = document.getElementById(`note_of_reject_input_${taskId}`);

    if (textarea.value.trim() === '') {
        alert('{{ __("Please enter a rejection note.") }}');
        return;
    }

    input.value = textarea.value; // Assign the rejection note to the hidden input
    const form = document.querySelector(`form[onsubmit*="${taskId}"]`);
    form.submit(); // Submit the form
}

    </script>
@endsection
