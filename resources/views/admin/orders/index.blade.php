@include('admin.includes.header')
<style>
    .table th, .table td {
        transition: all 0.3s ease;
        font-weight: 600;
    }

    .table td {
        font-size: 16px;
    }

    .highlight {
        animation: blink 1s infinite;
        background-color: #f8d7da; /* Açıq qırmızı fon */
    }

    @keyframes blink {
        50% {
            opacity: 0.5;
        }
    }
    @keyframes shine {
        0% { opacity: 0.5; }
        50% { opacity: 1; }
        100% { opacity: 0.5; }
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@eonasdan/tempus-dominus@6.9.4/dist/css/tempus-dominus.min.css" crossorigin="anonymous">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

<div class="main-content" style="margin-left: 0 !important;">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            @if(session('message'))
                                <div class="alert alert-success">{{session('message')}}</div>
                            @endif
                            <h4 class="card-title">Orders</h4>

                            <!-- Filter Form -->
                            <form action="{{ route('orders.index') }}" method="GET" class="mb-4">
                                <div class="row align-items-end">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="limit">Show</label>
                                            <select id="limit" name="limit" class="form-control">
                                                <option value="">Choose</option>
                                                <option value="10" {{ request('limit') == 10 ? 'selected' : '' }}>10
                                                </option>
                                                <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>50
                                                </option>
                                                <option value="200" {{ request('limit') == 200 ? 'selected' : '' }}>
                                                    200
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="limit">Status</label>
                                            <select class="form-control" name="status">
                                                <option value="">Choose</option>
                                                <option
                                                    value="new" {{ request()->status == 'new' ? 'selected' : '' }}>
                                                    New
                                                </option>
                                                <option
                                                    value="accepted" {{ request()->status == 'accepted' ? 'selected' : '' }}>
                                                    Accepted
                                                </option>
                                                <option
                                                    value="in_progress" {{ request()->status == 'in_progress' ? 'selected' : '' }}>
                                                    In Progress
                                                </option>
                                                <option
                                                    value="canceled" {{ request()->status == 'canceled' ? 'selected' : '' }}>
                                                    Canceled
                                                </option>
                                                <option
                                                    value="completed" {{ request()->status == 'completed' ? 'selected' : '' }}>
                                                    Completed
                                                </option>
                                                <option
                                                    value="reached_cancelled" {{ request()->status == 'reached_cancelled' ? 'selected' : '' }}>
                                                    Reached and Cancelled
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="reference_number">Reference Number</label>
                                            <input type="text" id="reference_number" name="reference_number"
                                                   class="form-control" placeholder="Reference Number"
                                                   value="{{ request('reference_number') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="phone">Phone</label>
                                            <input type="text" id="phone" name="phone" class="form-control"
                                                   placeholder="Phone" value="{{ request('phone') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="start_dateInput" class="form-label">Start date</label>
                                            <div class="input-group" id="start_date" data-td-target-input="nearest" data-td-target-toggle="nearest">
                                                <input id="start_dateInput" type="text" name="start_date" class="form-control" data-td-target="#start_date" value="{{ request()->start_date }}">
                                                <span class="input-group-text" data-td-target="#start_date" data-td-toggle="datetimepicker">
                <span class="fas fa-calendar"></span>
            </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="end_dateInput" class="form-label">End date</label>
                                            <div class="input-group" id="end_date" data-td-target-input="nearest" data-td-target-toggle="nearest">
                                                <input id="end_dateInput" type="text" name="end_date" class="form-control" data-td-target="#end_date" value="{{ request()->end_date }}">
                                                <span class="input-group-text" data-td-target="#end_date" data-td-toggle="datetimepicker">
                <span class="fas fa-calendar"></span>
            </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 text-right">
                                        <button type="submit" class="btn btn-primary mt-3">Search</button>
                                        <a href="{{route('orders.index')}}" class="btn btn-primary mt-3">Refresh</a>
                                        <a href="{{ route('orders.export.csv', request()->all()) }}"
                                           class="btn btn-success mt-3">Export Orders to CSV</a>

                                    </div>
                                </div>
                            </form>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <h5>Total Orders Today:
                                        <a href="{{ route('orders.index', ['date' => today()->toDateString()]) }}"
                                           class="btn btn-outline-primary {{ request()->get('date') == today()->toDateString() && !request()->has('status') ? 'active' : '' }}">
                                            {{ $totalOrdersToday }}
                                        </a>
                                    </h5>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2 mb-3">
                                    <a href="{{ route('orders.index', ['status' => 'new', 'date' => today()->toDateString()]) }}"
                                       class="btn btn-outline-primary w-100 {{ (request()->status == 'new' || (request()->get('date') == today()->toDateString() && !request()->has('status'))) ? 'active' : '' }}">
                                        New <span class="badge bg-primary">{{ $statusCounts['new'] ?? 0 }}</span>
                                    </a>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <a href="{{ route('orders.index', ['status' => 'accepted', 'date' => today()->toDateString()]) }}"
                                       class="btn btn-outline-success w-100 {{ (request()->status == 'accepted' || (request()->get('date') == today()->toDateString() && !request()->has('status'))) ? 'active' : '' }}">
                                        Accepted <span
                                            class="badge bg-success">{{ $statusCounts['accepted'] ?? 0 }}</span>
                                    </a>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <a href="{{ route('orders.index', ['status' => 'in_progress', 'date' => today()->toDateString()]) }}"
                                       class="btn btn-outline-warning w-100 {{ (request()->status == 'in_progress' || (request()->get('date') == today()->toDateString() && !request()->has('status'))) ? 'active' : '' }}">
                                        In Progress <span
                                            class="badge bg-warning">{{ $statusCounts['in_progress'] ?? 0 }}</span>
                                    </a>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <a href="{{ route('orders.index', ['status' => 'canceled', 'date' => today()->toDateString()]) }}"
                                       class="btn btn-outline-danger w-100 {{ (request()->status == 'canceled' || (request()->get('date') == today()->toDateString() && !request()->has('status'))) ? 'active' : '' }}">
                                        Canceled <span
                                            class="badge bg-danger">{{ $statusCounts['canceled'] ?? 0 }}</span>
                                    </a>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <a href="{{ route('orders.index', ['status' => 'completed', 'date' => today()->toDateString()]) }}"
                                       class="btn btn-outline-info w-100 {{ (request()->status == 'completed' || (request()->get('date') == today()->toDateString() && !request()->has('status'))) ? 'active' : '' }}">
                                        Completed <span
                                            class="badge bg-info">{{ $statusCounts['completed'] ?? 0 }}</span>
                                    </a>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <a href="{{ route('orders.index', ['status' => 'reached_cancelled', 'date' => today()->toDateString()]) }}"
                                       class="btn btn-outline-secondary w-100 {{ (request()->status == 'reached_cancelled' || (request()->get('date') == today()->toDateString() && !request()->has('status'))) ? 'active' : '' }}">
                                        Reached and Cancelled <span
                                            class="badge bg-secondary">{{ $statusCounts['reached_cancelled'] ?? 0 }}</span>
                                    </a>
                                </div>
                            </div>
                            <!-- Checkboxes to control column visibility -->
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#columnModal">
                                Customize Columns
                            </button>

                            <div class="modal fade" id="columnModal" tabindex="-1" aria-labelledby="columnModalLabel"
                                 aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="columnModalLabel">Select Columns to Display</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Column Selection Checkboxes -->
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input toggle-column"
                                                       id="column3" data-column="3" checked>
                                                <label for="column3" class="form-check-label">Reference number</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input toggle-column"
                                                       id="column4" data-column="4" checked>
                                                <label for="column4" class="form-check-label">Customer name</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input toggle-column"
                                                       id="column5" data-column="5" checked>
                                                <label for="column5" class="form-check-label">Phone</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input toggle-column"
                                                       id="column6" data-column="6" checked>
                                                <label for="column6" class="form-check-label">Driver name</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input toggle-column"
                                                       id="column7" data-column="7" checked>
                                                <label for="column7" class="form-check-label">Driver phone</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input toggle-column"
                                                       id="column8" data-column="8" checked>
                                                <label for="column8" class="form-check-label">Service category</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input toggle-column"
                                                       id="column9" data-column="9" checked>
                                                <label for="column9" class="form-check-label">Time</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input toggle-column"
                                                       id="column10" data-column="10" checked>
                                                <label for="column10" class="form-check-label">Trip number</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input toggle-column"
                                                       id="column11" data-column="11" checked>
                                                <label for="column11" class="form-check-label">Status</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input toggle-column"
                                                       id="column12" data-column="12" checked>
                                                <label for="column12" class="form-check-label">Pick up</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input toggle-column"
                                                       id="column13" data-column="13" checked>
                                                <label for="column13" class="form-check-label">To area</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input toggle-column"
                                                       id="column14" data-column="14" checked>
                                                <label for="column14" class="form-check-label">Vehicle make</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input toggle-column"
                                                       id="column15" data-column="15" checked>
                                                <label for="column15" class="form-check-label">Vehicle model</label>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if(!auth()->user()->hasRole('Admin'))
                                <a href="{{route('orders.create')}}" class="btn btn-primary">+</a>
                            @endif
                            <br><br>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover mb-0 align-middle table-nowrap">
                                    <thead class="table-light">
                                    <tr>
                                        <th>№</th>
                                        <th>Edit</th>
                                        <th>Reference number</th>
                                        <th>Customer name</th>
                                        <th>Phone</th>
                                        <th>Driver name</th>
                                        <th>Driver phone</th>
                                        <th>Service category</th>
                                        <th>Time</th>
                                        <th>Trip number</th>
                                        <th>Status</th>
                                        <th>Pick up</th>
                                        <th>To area</th>
                                        <th>Vehicle make</th>
                                        <th>Vehicle model</th>
                                        @can('delete-orders')
                                            <th>Operation</th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($orders as $key => $order)
                                        <tr class="
            @if($order->status === 'new') table-primary
            @elseif($order->status === 'in_progress') table-warning
            @elseif($order->status === 'accepted') table-info
            @elseif($order->status === 'canceled') table-danger
            @elseif($order->status === 'completed') table-success
            @elseif($order->status === 'reached_cancelled') table-secondary
            @endif

        ">
                                            <th scope="row">{{ $key + 1 }}</th>
                                            <td><a href="{{ route('orders.edit', $order->id) }}" class="btn btn-warning"
                                                   style="margin-right: 15px">Edit</a></td>
                                            <td class="@if($order->drivers->isEmpty() && !in_array($order->status, ['canceled', 'reached_cancelled'])) highlight @endif"
                                            >
                                                {{ $order->reference_number }}
                                            </td>
                                            <td>
                                                {{ $order->customer_name }}
                                                @if($isAdmin)
                                                    @if($order->is_pending_driver_assignment)
                                                        <!-- Shining star göstər -->
                                                        <i class="fa fa-star text-warning" style="animation: shine 2s infinite;"></i>
                                                    @else
                                                        <!-- Adi star göstər -->
                                                        <i class="fa fa-star text-muted"></i>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>{{ $order->phone }}</td>
                                            <td>
                                                @foreach($order->drivers as $driver)
                                                    {{$driver->driver_name}}<br>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($order->drivers as $driver)
                                                    {{$driver->driver_phone}}<br>
                                                @endforeach
                                            </td>
                                            <td>
                                                {{$order->service_category}}
                                            </td>
                                            <td>{{ $order->time }}</td>
                                            <td>{{ $order->trip_number }}</td>
                                            <td>
               <span style="font-size: 16px" class="badge
@if($order->status === 'new') bg-primary
@elseif($order->status === 'in_progress') bg-warning text-dark
@elseif($order->status === 'accepted') bg-info
@elseif($order->status === 'canceled') bg-danger
@elseif($order->status === 'completed') bg-success
@elseif($order->status === 'reached_cancelled') bg-secondary
@endif">
    {{ ucfirst($order->status) }}
</span>
                                            </td>
                                            <td style="max-width: 200px; text-wrap: wrap">{{$order->from_location_details}}</td>
                                            <td style="max-width: 200px; text-wrap: wrap">{{$order->to_area}}</td>
                                            <td>{{$order->vehicle_make}}</td>
                                            <td>{{$order->vehicle_model}}</td>
                                            @can('delete-orders')
                                                <td>
                                                    @if($isAdmin && $order->status === 'new')
                                                        <a href="{{ route('change.status', $order->id) }}"
                                                           class="btn btn-info" style="margin-right: 15px">Accept</a>
                                                    @endif


                                                    <form action="{{ route('orders.destroy', $order->id) }}"
                                                          method="post" style="display: inline-block">
                                                        {{ method_field('DELETE') }}
                                                        @csrf
                                                        <button
                                                            onclick="return confirm('Confirm delete')"
                                                            type="submit" class="btn btn-danger">Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                    </tbody>

                                </table>

                                <br>
                                {{ $orders->links('admin.vendor.pagination.bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.includes.footer')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<!-- Tempus Dominus JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/@eonasdan/tempus-dominus@6.9.4/dist/js/tempus-dominus.min.js" crossorigin="anonymous"></script>

<!-- Tempus Dominus Styles -->

<script>
    // Initialize the start date picker
    const startDatePicker = new tempusDominus.TempusDominus(document.getElementById('start_date'), {
        localization: {
            hourCycle: 'h24',  // Enforces 24-hour format
            format: 'MM/dd/yyyy HH:mm'
        },

    });

    // Initialize the end date picker
    const endDatePicker = new tempusDominus.TempusDominus(document.getElementById('end_date'), {
        localization: {
            hourCycle: 'h24',  // Enforces 24-hour format
            format: 'MM/dd/yyyy HH:mm'
        },

    });

    // Hər 5 saniyədən bir səhifəni yeniləmək
    setInterval(function () {
        location.reload(); // Səhifəni yeniləyir
    }, 59000); // 5000 millisekund = 5 saniyə
    document.addEventListener('DOMContentLoaded', function () {
        // İlk dəfə səhifə yüklənəndə localStorage-dan oxu və tətbiq et
        const checkboxes = document.querySelectorAll('.toggle-column');
        checkboxes.forEach(function (checkbox) {
            const column = checkbox.dataset.column;
            const isChecked = localStorage.getItem('column_' + column);

            if (isChecked === 'false') {
                checkbox.checked = false;
                toggleColumnVisibility(column, false);
            } else {
                checkbox.checked = true;
                toggleColumnVisibility(column, true);
            }
        });

        // Checkbox dəyişəndə kolonların görünməsini/gizlənməsini tənzimlə
        checkboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                const column = this.dataset.column;
                const isChecked = this.checked;

                // LocalStorage-də məlumatı yadda saxla
                localStorage.setItem('column_' + column, isChecked);

                // Kolonun görünməsini/gizlənməsini tənzimlə
                toggleColumnVisibility(column, isChecked);
            });
        });

        // Kolonların görünməsini/gizlənməsini idarə edən funksiya
        function toggleColumnVisibility(column, isChecked) {
            const table = document.querySelector('table');
            table.querySelectorAll('tr').forEach(function (row) {
                const cell = row.querySelector(`:nth-child(${column})`);
                if (cell) {
                    cell.style.display = isChecked ? '' : 'none';
                }
            });
        }
    });


</script>
