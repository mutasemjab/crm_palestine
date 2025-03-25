@extends('layouts.admin')

@section('content')
<table class="table table-bordered table-hover" id="data-table">
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
                    <!-- Accept Button -->
                    <form method="POST" action="{{ route('tasks.updateStatus', ['id' => $task->id]) }}" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status_key" value="job_order_status">
                        <input type="hidden" name="status_value" value="delivered">
                        <button type="submit" class="btn btn-success btn-sm">{{ __('Accept') }}</button>
                    </form>



                    <!-- Reject Button -->
                    <form method="POST" action="{{ route('tasks.updateStatus', ['id' => $task->id]) }}" style="display: inline;" onsubmit="return showRejectionModal(event, {{ $task->id }})">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status_key" value="job_order_status">
                        <input type="hidden" name="status_value" value="rejected">
                        <input type="hidden" name="note_of_reject" id="note_of_reject_input_{{ $task->id }}">
                        <button type="submit" class="btn btn-danger btn-sm">{{ __('Reject') }}</button>
                    </form>


                    <!-- WhatsApp Button -->
                    @php
                        $adminCountry = \App\Models\Country::where('id', $task->admin->country_id)->first(); // Assuming admin relation exists
                        $whatsappLink = $adminCountry ? $adminCountry->whatsapp_link : null;
                    @endphp
                    @if($whatsappLink)
                        <a href="{{ $whatsappLink }}" target="_blank" class="btn btn-info btn-sm">
                            {{ __('Send to WhatsApp') }}
                        </a>
                    @else
                        <button class="btn btn-secondary btn-sm" disabled>{{ __('WhatsApp Not Available') }}</button>
                    @endif

                    <a href="{{ route('export.feedback',$task->id) }}" class="btn btn-success">{{ __('Export Feedback as Excel') }}</a>

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

                @if(isset($feedbackData) && is_array($feedbackData) && count($feedbackData) > 0)
                    <ul>
                        @foreach($feedbackData as $key => $value)
                            @if(in_array($key, ['table1', 'table2', 'table3']) && is_array($value) && count($value) > 0)
                                {{-- Handle Tables with Data --}}
                                <table border="1" width="100%">
                                    <thead>
                                        <tr>
                                            <th colspan="{{ count($value[0]) }}">{{ ucfirst($key) }}</th>
                                        </tr>
                                        <tr>
                                            @foreach(array_keys($value[0]) as $colName)
                                                <th>{{ ucfirst(str_replace('_', ' ', $colName)) }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($value as $row)
                                            <tr>
                                                @foreach($row as $colValue)
                                                    <td>{{ $colValue }}</td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @elseif(!in_array($key, ['table1', 'table2', 'table3']))
                                {{-- Handle Simple Key-Value Pairs --}}
                                <li><strong>{{ ucfirst($key) }}:</strong> 
                                    {{ is_array($value) ? implode(', ', $value) : ($value ?? 'N/A') }}
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @else
                    <p>No feedback available.</p>
                @endif


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
