@extends('layouts.admin')

@section('content')
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

                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addNoteModal{{ $task->id }}">
                        {{ __('Add Note') }}
                    </button>

                    <!-- Accept Button -->
                    <form method="POST" action="{{ route('tasks.return_to_contractor', ['id' => $task->id]) }}" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success btn-sm">{{ __('Retrun to contractor') }}</button>
                    </form>

                </td>
            </tr>

        @endforeach
    </tbody>
</table>
@foreach ($tasks as $task)


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

<!-- Add Note Modal -->
<div class="modal fade" id="addNoteModal{{ $task->id }}" tabindex="-1" aria-labelledby="addNoteModalLabel{{ $task->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNoteModalLabel{{ $task->id }}">{{ __('Add Note to Task') }} #{{ $task->id }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('tasks.note_of_task_that_need_approve', ['id' => $task->id]) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="note">{{ __('Note') }}</label>
                        <textarea class="form-control" name="note_of_task_that_need_approve" id="note" rows="3" required></textarea>
                    </div>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="note">{{ __('Put the price to Contractor') }}</label>
                        <input class="form-control" name="price_offer_from_engineer" id="price_offer_from_engineer">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Save Note') }}</button>
                </div>
            </form>
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

@endsection

