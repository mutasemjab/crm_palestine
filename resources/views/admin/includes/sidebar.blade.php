
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="{{ asset('assets/admin/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Ata</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('assets/admin/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @if (
                    $user->can('jobOrderType-table') ||
                    $user->can('jobOrderType-add') ||
                    $user->can('jobOrderType-edit') ||
                    $user->can('jobOrderType-delete'))
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="fas fa-cog nav-icon text-primary"></i>
                        <p>
                            {{ __('messages.Settings') }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (
                            $user->can('jobOrderType-table') ||
                            $user->can('jobOrderType-add') ||
                            $user->can('jobOrderType-edit') ||
                            $user->can('jobOrderType-delete'))
                            <li class="nav-item">
                                <a href="{{ route('jobOrderTypes.index') }}" class="nav-link">
                                    <i class="fas fa-globe nav-icon text-success"></i>
                                    <p> {{__('messages.jobOrderTypes')}} </p>
                                </a>
                            </li>
                        @endif
                        <!-- types -->
                        @if (
                            $user->can('type-table') ||
                            $user->can('type-add') ||
                            $user->can('type-edit') ||
                            $user->can('type-delete'))
                            <li class="nav-item">
                                <a href="{{ route('types.index') }}" class="nav-link">
                                    <i class="fas fa-plane nav-icon text-danger"></i>
                                    <p> {{__('messages.types')}} </p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                @endif


               @if (
                    $user->can('country-table') ||
                    $user->can('country-add') ||
                    $user->can('country-edit') ||
                    $user->can('country-delete'))
                    <li class="nav-item">
                        <a href="{{ route('countries.index') }}" class="nav-link" title="{{ __('messages.countries') }}">
                            <i class="fas fa-globe nav-icon text-info"></i>
                            <p> {{ __('messages.countries') }} </p>
                        </a>
                    </li>
                @endif


              <!-- Tasks -->
                @if (
                    $user->can('task-table') ||
                    $user->can('task-add') ||
                    $user->can('task-edit') ||
                    $user->can('task-delete'))
                    <li class="nav-item">
                        <a href="{{ route('tasks.index') }}" class="nav-link" title="{{ __('messages.tasks') }}">
                            <i class="fas fa-tasks nav-icon text-secondary"></i>
                            <p > {{ __('messages.tasks') }} </p>
                        </a>
                    </li>
                @endif

                @if (
                    $user->can('taskCompleted-table') ||
                    $user->can('taskCompleted-add') ||
                    $user->can('taskCompleted-edit') ||
                    $user->can('taskCompleted-delete'))
                    <li class="nav-item">
                        <a href="{{ route('taskCompleted.index') }}" class="nav-link" title="{{ __('messages.Task Completed') }}">
                            <i class="fas fa-tasks nav-icon text-secondary"></i>
                            <p > {{ __('messages.Task Completed') }} </p>
                        </a>
                    </li>
                @endif

                @if (
                    $user->can('taskApproval-table') ||
                    $user->can('taskApproval-add') ||
                    $user->can('taskApproval-edit') ||
                    $user->can('taskApproval-delete'))
                    <li class="nav-item">
                        <a href="{{ route('taskApproval.index') }}" class="nav-link" title="{{ __('messages.Task That need approval') }}">
                            <i class="fas fa-tasks nav-icon text-secondary"></i>
                            <p > {{ __('messages.Task That need approval') }} </p>
                        </a>
                    </li>
                @endif

                @php
                use Carbon\Carbon;
                $hasTasksForToday = \App\Models\Task::where('job_order_status', 'تأجيل ليوم اخر')
                    ->whereDate('date_time', Carbon::today())
                    ->exists();
                @endphp

                @if (
                    $user->can('taskInDay-table') ||
                    $user->can('taskInDay-add') ||
                    $user->can('taskInDay-edit') ||
                    $user->can('taskInDay-delete')
                )
                    <li class="nav-item">
                        <a href="{{ route('taskInDay.index') }}" class="nav-link" title="{{ __('messages.Task in another day') }}">
                            <i class="fas fa-tasks nav-icon {{ $hasTasksForToday ? 'text-danger' : 'text-secondary' }}"></i>
                            <p class="{{ $hasTasksForToday ? 'text-danger' : '' }}">
                                {{ __('messages.Task in another day') }}
                            </p>
                        </a>
                    </li>
                @endif

                @if (
                    $user->can('warehouse-table') ||
                    $user->can('warehouse-add') ||
                    $user->can('warehouse-edit') ||
                    $user->can('warehouse-delete'))
                    <li class="nav-item">
                        <a href="{{ route('warehouses.index') }}" class="nav-link" title="{{ __('messages.warehouses') }}">
                            <i class="fas fa-warehouse nav-icon"></i>
                            <p > {{ __('messages.warehouses') }} </p>
                        </a>
                    </li>
                @endif

                @if ($user->can('unit-table') || $user->can('unit-add') || $user->can('unit-edit') || $user->can('unit-delete'))
                    <li class="nav-item">
                        <a href="{{ route('units.index') }}" class="nav-link" title="{{ __('messages.units') }}">
                            <i class="fas fa-balance-scale nav-icon"></i>
                            <p > {{ __('messages.units') }} </p>
                        </a>
                    </li>
                @endif

                @if (
                    $user->can('product-table') ||
                    $user->can('product-add') ||
                    $user->can('product-edit') ||
                    $user->can('product-delete'))
                    <li class="nav-item">
                        <a href="{{ route('products.index') }}" class="nav-link" title="{{ __('messages.products') }}">
                            <i class="fas fa-box-open nav-icon"></i>
                            <p > {{ __('messages.products') }} </p>
                        </a>
                    </li>
                @endif

                @if (
                    $user->can('noteVoucherType-table') ||
                    $user->can('noteVoucherType-add') ||
                    $user->can('noteVoucherType-edit') ||
                    $user->can('noteVoucherType-delete'))
                    <li class="nav-item">
                        <a href="{{ route('noteVoucherTypes.index') }}" class="nav-link" title="{{ __('messages.noteVoucherTypes') }}">
                            <i class="fas fa-receipt nav-icon"></i>
                            <p > {{ __('messages.noteVoucherTypes') }} </p>
                        </a>
                    </li>
                @endif

            @if (
                    $user->can('noteVoucher-table') ||
                    $user->can('noteVoucher-add') ||
                    $user->can('noteVoucher-edit') ||
                    $user->can('noteVoucher-delete'))

                @php
                $noteVouchertypes = App\Models\NoteVoucherType::get();
                @endphp
                @foreach ($noteVouchertypes as $noteVouchertype)
                    <li class="nav-item">
                        <a href="{{ route('noteVouchers.create', ['id' => $noteVouchertype->id]) }}" class="nav-link" title="{{ $noteVouchertype->name }}">
                            <i class="fas fa-file-alt nav-icon"></i>
                            <p > {{ $noteVouchertype->name }} </p>
                        </a>
                    </li>
                @endforeach

            @endif



                @if (
                    $user->can('noteVoucher-table') ||
                    $user->can('noteVoucher-add') ||
                    $user->can('noteVoucher-edit') ||
                    $user->can('noteVoucher-delete'))
                    <li class="nav-item">
                        <a href="{{ route('noteVouchers.index') }}" class="nav-link" title="{{ __('messages.noteVouchers') }}">
                            <i class="fas fa-clipboard-list nav-icon"></i>
                            <p > {{ __('messages.noteVouchers') }} </p>
                        </a>
                    </li>
                @endif

                @if (
                    $user->can('rolloutSuperVisor-table') ||
                    $user->can('rolloutSuperVisor-add') ||
                    $user->can('rolloutSuperVisor-edit') ||
                    $user->can('rolloutSuperVisor-delete'))
                    <li class="nav-item">
                        <a href="{{ route('rolloutSuperVisors.index') }}" class="nav-link" title="{{ __('messages.rolloutSuperVisors') }}">
                            <i class="fas fa-clipboard-list nav-icon"></i>
                            <p > {{ __('messages.rolloutSuperVisors') }} </p>
                        </a>
                    </li>
                @endif

                @if (
                    $user->can('excavation-table') ||
                    $user->can('excavation-add') ||
                    $user->can('excavation-edit') ||
                    $user->can('excavation-delete'))
                    <li class="nav-item">
                        <a href="{{ route('excavations.index') }}" class="nav-link" title="{{ __('messages.excavations') }}">
                            <i class="fas fa-clipboard-list nav-icon"></i>
                            <p > {{ __('messages.excavations') }} </p>
                        </a>
                    </li>
                @endif


                @if (
                    $user->can('report-table') ||
                    $user->can('report-add') ||
                    $user->can('report-edit') ||
                    $user->can('report-delete'))
                <!-- Reports -->
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="fas fa-chart-bar nav-icon text-warning"></i>
                        <p>
                            {{ __('messages.Reports') }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('reports.financial') }}" class="nav-link">
                                <i class="far fa-file-alt nav-icon text-primary"></i>
                                <p> {{ __('messages.Task Financial Report') }} </p>
                            </a>
                        </li>

                    </ul>
                </li>
                @endif

                {{-- <!-- Notifications -->
                <li class="nav-item">
                    <a href="{{ route('notifications.create') }}" class="nav-link">
                        <i class="fas fa-bell nav-icon text-danger"></i>
                        <p>{{__('messages.notifications')}} </p>
                    </a>
                </li> --}}

                @if ($user->is_super)
                <!-- Admin Account -->
                <li class="nav-item">
                    <a href="{{ route('admin.login.edit',auth()->user()->id) }}" class="nav-link">
                        <i class="fas fa-user-cog nav-icon text-info"></i>
                        <p>{{__('messages.Admin_account')}} </p>
                    </a>
                </li>
                @endif

                <!-- Roles -->
                @if ($user->can('role-table') || $user->can('role-add') || $user->can('role-edit') || $user->can('role-delete'))
                <li class="nav-item">
                    <a href="{{ route('admin.role.index') }}" class="nav-link">
                        <i class="fas fa-user-shield nav-icon text-success"></i>
                        <span>{{__('messages.Roles')}} </span>
                    </a>
                </li>
                @endif

                <!-- Employees -->
                @if (
                    $user->can('employee-table') ||
                    $user->can('employee-add') ||
                    $user->can('employee-edit') ||
                    $user->can('employee-delete'))
                <li class="nav-item">
                    <a href="{{ route('admin.employee.index') }}" class="nav-link">
                        <i class="fas fa-user-tie nav-icon text-warning"></i>
                        <span> {{__('messages.Employee')}} </span>
                    </a>
                </li>
                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

