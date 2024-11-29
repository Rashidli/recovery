@include('admin.includes.header')
<style>
    input, select, textarea, .select2-container--default .select2-selection, tr, td, th {
        font-weight: bold !important;
    }
    label {
        font-weight: bold !important;
    }
    .select2-container--default .select2-selection--single {
        font-weight: bold !important;
    }
    .select2-container--default .select2-results__option {
        font-weight: bold !important;
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@eonasdan/tempus-dominus@6.9.4/dist/css/tempus-dominus.min.css" crossorigin="anonymous">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<div class="main-content" style="margin-left: 0 !important;">
    <div class="page-content">
        <div class="container-fluid">

            <form action="{{ route('orders.update', $order->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card">

                    <div class="card-body">
                        @if(session('message'))
                            <div class="alert alert-success">{{session('message')}}</div>
                        @endif
                        <a href="{{ session('page') }}" class="btn btn-primary">Back to list</a><br><br>
                        <a href="{{route('orders.edit',$order->id)}}" class="btn btn-primary">Refresh</a>
                        <br>
                        <br>
                        <h4 class="card-title">Edit Order</h4>

                        <!-- Customer Details Section -->
                        <h5 class="section-heading" style="background-color: #ffc425; padding: 10px;">Customer
                            Details</h5>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="reference_number">Reference Number</label>
                                    <input id="reference_number"
                                           {{ $isAdmin || !$hasDriver ? '' : 'disabled' }} class="form-control"
                                           type="text"
                                           name="reference_number"
                                           value="{{ old('reference_number', $order->reference_number) }}">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="customer_name">Customer Name</label>
                                    <input id="customer_name"
                                           {{ $isAdmin || !$hasDriver ? '' : 'disabled' }} class="form-control"
                                           type="text" name="customer_name"
                                           value="{{ old('customer_name', $order->customer_name) }}">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="vehicle_make">Vehicle Make</label>
                                    <select id="vehicle_make"
                                            {{ $isAdmin || !$hasDriver ? '' : 'disabled' }} class="form-control js-example-basic-single"
                                            name="vehicle_make">
                                        <option value="">Select Make</option>
                                        @foreach($vehicle_makes as $vehicle_make)
                                            <option data-id="{{ $vehicle_make->id }}" value="{{ $vehicle_make->make }}"
                                                {{ old('vehicle_make', $order->vehicle_make) == $vehicle_make->make ? 'selected' : '' }}>
                                                {{ $vehicle_make->make }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="vehicle_plate_no">Vehicle Plate / Classic Number</label>
                                    <input id="vehicle_plate_no"
                                           {{ $isAdmin || !$hasDriver ? '' : 'disabled' }} class="form-control"
                                           type="text"
                                           name="vehicle_plate_no"
                                           value="{{ old('vehicle_plate_no', $order->vehicle_plate_no) }}">
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="time">Date / Time</label>
                                    <input id="time" class="form-control"
                                           disabled type="date"
                                           name="time"
                                           value="{{ old('time', \Carbon\Carbon::parse($order->time)->format('Y-m-d')) }}">

                                </div>
                                <div class="form-group mb-3">
                                    <label for="phone">Customer Contact Number</label>
                                    <input id="phone"
                                           {{ $isAdmin || !$hasDriver ? '' : 'disabled' }} class="form-control"
                                           type="text" name="phone"
                                           value="{{ old('phone', $order->phone) }}">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="vehicle_model">Vehicle Model</label>
                                    <select id="vehicle_model"
                                            {{ $isAdmin || !$hasDriver ? '' : 'disabled' }} class="form-control js-example-basic-single"
                                            name="vehicle_model">
                                        @if($order->vehicle_model)
                                            <option value="{{ $order->vehicle_model }}"
                                                    selected>{{ $order->vehicle_model }}</option>
                                        @else
                                            <option value="">Select Model</option>
                                        @endif
                                    </select>
                                </div>


                            </div>
                        </div>

                        <!-- Service Entry Section -->
                        <h5 class="section-heading" style="background-color: #ffc425; padding: 10px;">Service Entry</h5>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="service_category">Service Category</label>
                                    <select id="service_category"
                                            {{ $isAdmin || !$hasDriver ? '' : 'disabled' }} class="form-control js-example-basic-single"
                                            name="service_category">
                                        <option value="">Select Category</option>
                                        @foreach($service_categories as $service_category)
                                            <option data-id="{{ $service_category->id }}"
                                                    value="{{ $service_category->name }}"
                                                {{ old('service_category', $order->service_category) == $service_category->name ? 'selected' : '' }}>
                                                {{ $service_category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="from_city">From City</label>
                                    <select id="from_city"
                                            {{ $isAdmin || !$hasDriver ? '' : 'disabled' }} class="form-control js-example-basic-single"
                                            name="from_city">
                                        <option value="">Select City</option>
                                        @foreach($from_cities as $from_city)
                                            <option data-id="{{ $from_city->id }}" value="{{ $from_city->name }}"
                                                {{ old('from_city', $order->from_city) == $from_city->name ? 'selected' : '' }}>
                                                {{ $from_city->name }}
                                            </option>
                                        @endforeach

                                        <!-- Add the $order->from_city if it doesn't exist in the $from_cities -->
                                        @if ($order->from_city && !in_array($order->from_city, $from_cities->pluck('name')->toArray()))
                                            <option value="{{ $order->from_city }}" selected>{{ $order->from_city }}</option>
                                        @endif
                                    </select>


                                </div>

                                <div class="form-group mb-3">
                                    <label for="from_area">From Area</label>
                                    <select id="from_area"
                                            {{ $isAdmin || !$hasDriver ? '' : 'disabled' }} class="form-control js-example-basic-single"
                                            name="from_area">
                                        <option value="">Select an area</option>
                                        @if($order->from_area)
                                            <option value="{{ $order->from_area }}"
                                                    selected>{{ $order->from_area }}</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="from_location_details">Pick up Location ( Al Futtaim Service centers and
                                        storage locations)</label>
                                    <input id="from_location_details"
                                           {{ $isAdmin || !$hasDriver ? '' : 'disabled' }} class="form-control"
                                           type="text" name="from_location_details"
                                           value="{{ old('from_location_details', $order->from_location_details) }}">
                                </div>
                                {{--                                <div class="form-group mb-3">--}}
                                {{--                                    <label for="from_gps_coordinates">From GPS Coordinates</label>--}}
                                {{--                                    <input id="from_gps_coordinates"--}}
                                {{--                                           {{ $isAdmin || !$hasDriver ? '' : 'disabled' }} class="form-control"--}}
                                {{--                                           type="text" name="from_gps_coordinates"--}}
                                {{--                                           value="{{ old('from_gps_coordinates', $order->from_gps_coordinates) }}">--}}
                                {{--                                </div>--}}


                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="service_type">Service Type</label>
                                    <select id="service_type"
                                            {{ $isAdmin || !$hasDriver ? '' : 'disabled' }} class="form-control js-example-basic-single"
                                            name="service_type">
                                        @if($order->service_type)
                                            <option value="{{ $order->service_type }}"
                                                    selected>{{ $order->service_type }}</option>
                                        @else
                                            <option value="">Select Type</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="to_city">To city</label>
                                    <select id="to_city"
                                            {{ $isAdmin || !$hasDriver ? '' : 'disabled' }} class="form-control js-example-basic-single"
                                            name="to_city">
                                        <option value="">Select City</option>
                                        @foreach($from_cities as $from_city)
                                            <option data-id="{{ $from_city->id }}" value="{{ $from_city->name }}"
                                                {{ old('to_city', $order->to_city) == $from_city->name ? 'selected' : '' }}>
                                                {{ $from_city->name }}
                                            </option>
                                        @endforeach

                                        <!-- Add the $order->to_city if it doesn't exist in the $from_cities -->
                                        @if ($order->to_city && !in_array($order->to_city, $from_cities->pluck('name')->toArray()))
                                            <option value="{{ $order->to_city }}" selected>{{ $order->to_city }}</option>
                                        @endif
                                    </select>

                                </div>
                                <div class="form-group mb-3">
                                    <label for="to_area">To area</label>
                                    <select id="to_area"
                                            {{ $isAdmin || !$hasDriver ? '' : 'disabled' }} class="form-control js-example-basic-single"
                                            name="to_area">
                                        <option value="">Select an area</option>
                                        @if($order->to_area)
                                            <option value="{{ $order->to_area }}"
                                                    selected>{{ $order->to_area }}</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="to_location_details">Drop off Location ( Al Futtaim Service centers and
                                        storage locations)</label>
                                    <input id="to_location_details"
                                           {{ $isAdmin || !$hasDriver ? '' : 'disabled' }} class="form-control"
                                           type="text" name="to_location_details"
                                           value="{{ old('to_location_details', $order->to_location_details) }}">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="comment">Comment</label>
                                    <textarea id="comment"
                                              {{ $isAdmin || !$hasDriver ? '' : 'disabled' }} class="form-control"
                                              name="comment">{{ old('comment', $order->comment) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Trip Details Section -->
                        <h5 class="section-heading" style="background-color: #ffc425; padding: 10px;">Trip Details</h5>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="trip_number">Trip Number</label>
                                    <input id="trip_number" class="form-control"
                                           {{ $isAdmin ? '' : 'disabled' }} type="text" name="trip_number"
                                           value="{{ old('trip_number', $order->trip_number) }}">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="estimated_amt">Estimated Amount</label>
                                    <input id="estimated_amt" class="form-control" type="text" name="estimated_amt"
                                           value="{{ old('estimated_amt', $order->estimated_amt) }}" {{ $isAdmin ? '' : 'disabled' }}>
                                    @if (!$isAdmin)
                                        <small class="text-muted">You do not have permission to edit this field.</small>
                                    @endif
                                </div>
                                @if($isAdmin)
                                    <div class="form-group mb-3">
                                        <label for="vendor_amount">Vendor Amount</label>
                                        <input id="vendor_amount" class="form-control" type="text" name="vendor_amount"
                                               value="{{ old('vendor_amount', $order->vendor_amount) }}" {{ $isAdmin ? '' : 'disabled' }}>
                                        @if($errors->first('vendor_amount')) <small class="form-text text-danger">{{ $errors->first('vendor_amount') }}</small> @endif
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="driver_status">Vendor name</label>
                                        <select class="form-control js-example-basic-single" id="vendor_name" name="vendor_name">
                                            <option value="">Select</option>
                                            @foreach($vendors as $vendor)
                                                <option value="{{$vendor->title}}" {{ $order->vendor_name == $vendor->title ? 'selected' : '' }}>{{$vendor->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <div class="form-group mb-3">
                                    <label for="vendor_amount">Waiting charge</label>
                                    <input id="vendor_amount" class="form-control" type="text" name="waiting_charge"
                                           value="{{ old('waiting_charge', $order->waiting_charge) }}" {{ $isAdmin ? '' : 'disabled' }}>
                                    @if($errors->first('waiting_charge')) <small class="form-text text-danger">{{ $errors->first('waiting_charge') }}</small> @endif
                                </div>

                                <div class="form-group mb-3">
                                    <label for="zone_codes">zone codes</label>
                                    <input id="zone_codes" class="form-control" type="text" name="zone_codes"
                                           value="{{ old('zone_codes', $order->zone_codes) }}">
                                    {{--                                    @if (!$isAdmin)--}}
                                    {{--                                        <small class="text-muted">You do not have permission to edit this field.</small>--}}
                                    {{--                                    @endif--}}
                                </div>
                                {{--                                @if($order->file)--}}
                                {{--                                    <a target="_blank" href="{{asset('storage/'. $order->file)}}">See Invoice</a>--}}
                                {{--                                @endif--}}
                                {{--                                <div class="form-group mb-3">--}}
                                {{--                                    <label for="file">Invoice</label>--}}
                                {{--                                    <input id="file" class="form-control" {{ $isAdmin ? '' : 'disabled' }} type="file"--}}
                                {{--                                           name="file">--}}
                                {{--                                </div>--}}
                            </div>
                            <div class="col-md-6">

                                <div class="form-group mb-3">
                                    <label for="status">Status</label>
                                    <select class="form-control js-example-basic-single"
                                            {{ $isAdmin ? '' : 'disabled' }} name="status">
                                        <option
                                            value="new" {{ old('status', $order->status) == 'new' ? 'selected' : '' }}>
                                            New
                                        </option>
                                        <option
                                            value="accepted" {{ old('status', $order->status) == 'accepted' ? 'selected' : '' }}>
                                            Accepted
                                        </option>
                                        <option
                                            value="in_progress" {{ old('status', $order->status) == 'in_progress' ? 'selected' : '' }}>
                                            In Progress
                                        </option>
                                        <option
                                            value="canceled" {{ old('status', $order->status) == 'canceled' ? 'selected' : '' }}>
                                            Canceled
                                        </option>
                                        <option
                                            value="completed" {{ old('status', $order->status) == 'completed' ? 'selected' : '' }}>
                                            Completed
                                        </option>
                                        <option
                                            value="reached_cancelled" {{ old('status', $order->status) == 'reached_cancelled' ? 'selected' : '' }}>
                                            Reached and Cancelled
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="order_files">Images</label>
                                    <input {{ $isAdmin ? '' : 'disabled' }} id="order_files" class="form-control"
                                           type="file" name="order_files[]" multiple>
                                </div>
                                <div class="form-group mb-3 d-flex flex-wrap">
                                    @foreach($order->images ?? [] as $image)
                                        <div class="image-container position-relative m-2" >
                                            <a href="{{ asset('storage/' . $image->order_file) }}" target="_blank" class="d-block">
                                                <img class="img-thumbnail" style="width: 100px; height: 100px;" src="{{ asset('storage/' . $image->order_file) }}" alt="">
                                            </a>
                                            <a href="{{ route('delete-slider-image', ['id' => $image->id]) }}"
                                               class="btn btn-danger btn-sm delete-btn position-absolute"
                                               style="top: 5px; right: 5px;"
                                               onclick="return confirm('Are you sure you want to delete this image?');">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <!-- Customer Details Section -->
                        <h5 class="section-heading" style="background-color: #ffc425; padding: 10px;">Driver
                            Details</h5>
                        <br>
                        @if($order->drivers->count() > 0)
                            @foreach($order->drivers as $driver)
                                <div class="row">
                                    <!-- Column 1: Driver Fullname -->
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="driver_name_{{ $loop->index }}">Driver Fullname</label>
                                            <input type="hidden" name="drivers[{{ $loop->index }}][driver_id]"
                                                   value="{{ old('drivers.'.$loop->index.'.id', $driver->id) }}">
                                            <input id="driver_name_{{ $loop->index }}"
                                                   {{ $isAdmin ? '' : 'disabled' }} class="form-control" type="text"
                                                   name="drivers[{{ $loop->index }}][driver_name]"
                                                   value="{{ old('drivers.'.$loop->index.'.driver_name', $driver->driver_name) }}">
                                        </div>
                                    </div>

                                    <!-- Column 2: Driver Car Number -->
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="driver_car_number_{{ $loop->index }}">Driver Car Number</label>
                                            <input id="driver_car_number_{{ $loop->index }}"
                                                   {{ $isAdmin ? '' : 'disabled' }} class="form-control" type="text"
                                                   name="drivers[{{ $loop->index }}][driver_car_number]"
                                                   value="{{ old('drivers.'.$loop->index.'.driver_car_number', $driver->driver_car_number) }}">
                                        </div>
                                    </div>

                                    <!-- Column 3: Driver Phone Number -->
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="driver_phone_{{ $loop->index }}">Driver Phone Number</label>
                                            <input id="driver_phone_{{ $loop->index }}"
                                                   {{ $isAdmin ? '' : 'disabled' }} class="form-control" type="text"
                                                   name="drivers[{{ $loop->index }}][driver_phone]"
                                                   value="{{ old('drivers.'.$loop->index.'.driver_phone', $driver->driver_phone) }}">
                                        </div>
                                    </div>

                                    {{--                                    <!-- Column 4: Google Map Link -->--}}
                                    {{--                                    <div class="col-md-2">--}}
                                    {{--                                        <div class="form-group mb-3">--}}
                                    {{--                                            <label for="google_map_{{ $loop->index }}">Google Map Link</label>--}}
                                    {{--                                            @if($driver->google_map)--}}
                                    {{--                                                <a href="{{$driver->google_map}}" target="_blank">See map</a>--}}
                                    {{--                                            @endif--}}
                                    {{--                                            <input id="google_map_{{ $loop->index }}"--}}
                                    {{--                                                   {{ $isAdmin ? '' : 'disabled' }} class="form-control" type="text"--}}
                                    {{--                                                   name="drivers[{{ $loop->index }}][google_map]"--}}
                                    {{--                                                   value="{{ old('drivers.'.$loop->index.'.google_map', $driver->google_map) }}">--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}

                                    {{--                                    <!-- Column 5: Driver Apply Time -->--}}
                                    {{--                                    <div class="col-md-2">--}}
                                    {{--                                        <div class="form-group mb-3">--}}
                                    {{--                                            <label for="driver_apply_time_{{ $loop->index }}">Time</label>--}}
                                    {{--                                            <input id="driver_apply_time_{{ $loop->index }}"--}}
                                    {{--                                                   {{ $isAdmin ? '' : 'disabled' }} class="form-control"--}}
                                    {{--                                                   type="date"--}}
                                    {{--                                                   name="drivers[{{ $loop->index }}][driver_apply_time]"--}}
                                    {{--                                                   value="{{ old('drivers.'.$loop->index.'.driver_apply_time', $driver->driver_apply_time) }}">--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                </div>
                            @endforeach
                        @endif

                        @if($order->driver_logs->count() > 0)
                            <div class="row">
                                <div class="col-md-12">
                                    <h5>Driver logs</h5>
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>№</th>
                                            <th>Driver name</th>
                                            <th>Time</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($order->driver_logs as $key => $log)
                                            <tr>
                                                <td>{{$key + 1}}</td>
                                                <td>
                                                    {{$log->driver_name}}
                                                </td>
                                                <td>{{ $log->logged_at }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        @endif
                        <!-- Template for adding new drivers -->
                        @if($isAdmin)
                            @if(!$order->drivers->count() > 0)
                                <div class="row">
                                    <div class="col-md-12 text-start">
                                        @if(session('error'))
                                            <h3 style="color: red">{{session('error')}}</h3>
                                        @endif
                                        <p id="add-driver-btn" class="btn btn-success mb-3">+ Add Driver</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Driver form row -->
                            <div id="new-driver-form" style="display: none;">
                                <div class="row">
                                    <!-- Column 1: Driver Fullname -->
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="driver_name_new">Driver Fullname</label>
                                            <input type="hidden" name="drivers[new][driver_id]">
                                            <input id="driver_name_new" class="form-control" type="text"
                                                   name="drivers[new][driver_name]"
                                                   value="{{ old('drivers.new.driver_name') }}">
                                        </div>
                                    </div>

                                    <!-- Column 2: Driver Car Number -->
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="driver_car_number_new">Driver Car Number</label>
                                            <input id="driver_car_number_new" class="form-control" type="text"
                                                   name="drivers[new][driver_car_number]"
                                                   value="{{ old('drivers.new.driver_car_number') }}">
                                        </div>
                                    </div>

                                    <!-- Column 3: Driver Phone Number -->
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="driver_phone_new">Driver Phone Number</label>
                                            <input id="driver_phone_new" class="form-control" type="text"
                                                   name="drivers[new][driver_phone]"
                                                   value="{{ old('drivers.new.driver_phone') }}">
                                        </div>
                                    </div>

                                    {{--                                    <!-- Column 4: Google Map Link -->--}}
                                    {{--                                    <div class="col-md-2">--}}
                                    {{--                                        <div class="form-group mb-3">--}}
                                    {{--                                            <label for="google_map_new">Google Map Link</label>--}}
                                    {{--                                            <input id="google_map_new" class="form-control" type="text"--}}
                                    {{--                                                   name="drivers[new][google_map]"--}}
                                    {{--                                                   value="{{ old('drivers.new.google_map') }}">--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}

                                    {{--                                    <!-- Column 5: Driver Apply Time -->--}}
                                    {{--                                    <div class="col-md-2">--}}
                                    {{--                                        <div class="form-group mb-3">--}}
                                    {{--                                            <label for="driver_apply_time_new">Time</label>--}}
                                    {{--                                            <input id="driver_apply_time_new" class="form-control" type="date"--}}
                                    {{--                                                   name="drivers[new][driver_apply_time]"--}}
                                    {{--                                                   value="{{ old('drivers.new.driver_apply_time') }}">--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                </div>
                            </div>
                        @endif

                        <!-- Service Entry Section -->
                        <h5 class="section-heading" style="background-color: #ffc425; padding: 10px;">Service logs</h5>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                @if($isAdmin)
                                    <button type="button" class="btn btn-primary" id="btn_create">Create</button>
                                @endif
                                <br><br>
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>№</th>
                                        <th>Log</th>
                                        <th>Coordinates</th>
                                        <th>Timestamp</th>
                                        <th>User</th>
                                        @if($isAdmin)
                                            <th>Action</th>
                                        @endif
                                    </tr>
                                    </thead>
                                    {{--                                    <livewire:order_log-sort-table />--}}
                                    {{--                                    <livewire:order_log-sort-table :order="$order"/>--}}
                                    <tbody>
                                    @foreach($order->order_logs as $key => $log)
                                        <tr>
                                            <td>{{$key + 1}}</td>
                                            <td>
                                                @if($log->status)
                                                    @if($log->status == 'new')
                                                        Request Generated by AFL
                                                    @elseif($log->status == 'accepted')
                                                        Request Accepted by 166
                                                    @else
                                                        Order status changed to {{ $log->status }}
                                                    @endif
                                                @endif
                                                @if($log->driver_status)
                                                    @if($log->driver_status === 'driver_assigned')
                                                        {{ $log->status_text }} {{ $order->drivers()->first()?->driver_name ?? 'No driver assigned' }}
                                                    @else
                                                        {{ $log->status_text }}
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{$log->driver_status_gps}}">{{Str::limit($log->driver_status_gps,50)}}</a>
                                            </td>
                                            <td>{{ $log->logged_at }}</td>
                                            <td>{{ $log->user }}</td>
                                            @if($log->driver_status && auth()->user()->hasRole('Admin'))
                                                <td>
                                                    @if($log->driver_status !== 'driver_assigned')
                                                        <button class="btn btn-warning" data-id="{{ $log->id }}"
                                                                type="button">Edit
                                                        </button>
                                                    @else
                                                        <button class="btn btn-warning" data-id="{{ $log->id }}"
                                                                type="button">Edit
                                                        </button>
                                                    @endif

                                                    @if($loop->last && $log->driver_status !== 'driver_assigned')
                                                        {{-- Sadece son kayıt için silme butonunu göster --}}
                                                        <button class="btn btn-danger btn_danger"
                                                                data-id="{{ $log->id }}" type="button">Delete
                                                        </button>
                                                    @endif

                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                        <div class="form-group text-center mb-3">
                            <button class="btn btn-primary" type="submit">Save</button>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Create/Edit Modal -->
<div class="modal fade" id="logModal" tabindex="-1" role="dialog" aria-labelledby="logModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="logForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logModalLabel">Create/Edit Log</h5>
                    <button type="button" class="close close_modal" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="error-message" class="alert alert-danger d-none"></div>
                    <input type="hidden" name="order_id" value="{{$order->id}}" id="">
                    <div class="form-group mb-3">
                        <label for="driver_status">Driver Status</label>
                        <select class="form-control" id="driver_status" name="driver_status">
                            <option value="">Select</option>
                            <option value="driver_assigned">Driver Assigned</option>
                            <option value="driver_reached_customer">Driver Reached at the customer location</option>
                            <option value="driver_pick">Driver Pick up the car</option>
                            <option value="driver_reached_drop">Driver Reached at the drop off location</option>
                            <option value="driver_drop">Driver Drop of the car</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="driver_status_gps">Driver gps coordinate</label>
                        <input id="driver_status_gps" class="form-control" type="text" name="driver_status_gps">
                    </div>
                    <div class="form-group mb-3">
                        <label for="logged_atInput" class="form-label">Logged At</label>
                        <div class="input-group" id="logged_at" data-td-target-input="nearest" data-td-target-toggle="nearest">
                            <input id="logged_atInput" type="text" name="logged_at" class="form-control" data-td-target="#logged_at" value="">
                            <span class="input-group-text" data-td-target="#logged_at" data-td-toggle="datetimepicker">
                <span class="fas fa-calendar"></span>
            </span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close_modal" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
                </div>
            </div>
        </form>
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

<script>
    const loggedAtPicker = new tempusDominus.TempusDominus(document.getElementById('logged_at'), {
        localization: {
            hourCycle: 'h24',
            format: 'MM/dd/yyyy HH:mm'
        },
    });
    $(document).ready(function () {
        $('#add-driver-btn').on('click', function (e) {
            e.preventDefault(); // Prevent the default button action
            $('#new-driver-form').slideToggle('fast'); // Slide up/down the driver form
        });
        // Preload models if there's a selected make and model
        var makeId = $('#vehicle_make').find('option:selected').data('id');
        var selectedModel = "{{ $order->vehicle_model }}"; // Preselected model from the order

        if (makeId) {
            loadVehicleModels(makeId, selectedModel); // Load models on page load
        }

        // Change event to load models based on selected make
        $('#vehicle_make').on('change', function () {
            var makeId = $(this).find('option:selected').data('id');
            loadVehicleModels(makeId, null); // Load new models when make changes, no preselected model
        });

        function loadVehicleModels(makeId, selectedModel) {
            if (makeId) {
                $.ajax({
                    url: '/get-vehicle-models/' + makeId,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $('#vehicle_model').empty();
                        $('#vehicle_model').append('<option value="">Select Model</option>');

                        $.each(data, function (key, value) {
                            $('#vehicle_model').append('<option value="' + value.model + '"' + (value.model === selectedModel ? ' selected' : '') + '>' + value.model + '</option>');
                        });

                        // Add custom model if it's not in the list
                        if (selectedModel && !data.some(model => model.model === selectedModel)) {
                            $('#vehicle_model').append('<option value="' + selectedModel + '" selected>' + selectedModel + '</option>');
                        }

                        // Reinitialize Select2
                        $('#vehicle_model').select2({
                            tags: true // Allow custom tags
                        });
                    }
                });
            } else {
                $('#vehicle_model').empty();
                $('#vehicle_model').append('<option value="">Select Model</option>');

                // Reinitialize Select2
                $('#vehicle_model').select2({
                    tags: true // Allow custom tags
                });
            }
        }


        var categoryId = $('#service_category').find('option:selected').data('id');
        var selectedServiceType = "{{ $order->service_type }}"; // Preselected service type from the order

        if (categoryId) {
            loadServiceTypes(categoryId, selectedServiceType);
        }

        // Load service types when category is changed
        $('#service_category').on('change', function () {
            var categoryId = $(this).find('option:selected').data('id');
            loadServiceTypes(categoryId, null); // Pass null for no preselected service type when changing category
        });

        function loadServiceTypes(categoryId, selectedServiceType) {
            if (categoryId) {
                $.ajax({
                    url: '/get-service-types/' + categoryId,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $('#service_type').empty(); // Clear the service types dropdown
                        $('#service_type').append('<option value="">Select Type</option>'); // Add default option

                        $.each(data, function (key, value) {
                            if (value.name === selectedServiceType) {
                                $('#service_type').append('<option value="' + value.name + '" selected>' + value.name + '</option>');
                            } else {
                                $('#service_type').append('<option value="' + value.name + '">' + value.name + '</option>');
                            }
                        });
                    }
                });
            } else {
                $('#service_type').empty(); // Clear the service types dropdown
                $('#service_type').append('<option value="">Select Type</option>'); // Add default option
            }
        }

        var cityId = $('#from_city').find('option:selected').data('id');
        var selectedFromArea = "{{ $order->from_area }}"; // Preselected area from the order

        if (cityId) {
            loadAreas(cityId, selectedFromArea);
        }

        // Load areas when the city is changed
        $('#from_city').on('change', function () {
            var cityId = $(this).find(':selected').data('id');
            loadAreas(cityId, null); // Pass null for no preselected area when changing city
        });

        function loadAreas(cityId, selectedFromArea) {
            $('#from_area').empty(); // Clear the area dropdown
            $('#from_area').append('<option value="">Select an area</option>'); // Add default option

            if (cityId) {
                $.ajax({
                    url: '/get-areas/' + cityId,
                    type: 'GET',
                    success: function (data) {
                        // Populate the area dropdown
                        $.each(data, function (index, area) {
                            if (area.name === selectedFromArea) {
                                $('#from_area').append('<option value="' + area.name + '" selected>' + area.name + '</option>');
                            } else {
                                $('#from_area').append('<option value="' + area.name + '">' + area.name + '</option>');
                            }
                        });
                    },
                    error: function (xhr) {
                        console.error(xhr);
                        alert('Failed to fetch areas. Please try again.');
                    }
                });
            }
        }

        var toCityId = $('#to_city').find('option:selected').data('id');
        var selectedToArea = "{{ $order->to_area }}"; // Preselected area to the order

        if (toCityId) {
            loadToAreas(toCityId, selectedToArea);
        }

        // Load areas when the city is changed
        $('#to_city').on('change', function () {
            var toCityId = $(this).find(':selected').data('id');
            loadToAreas(toCityId, null); // Pass null for no preselected area when changing city
        });

        function loadToAreas(toCityId, selectedToArea) {
            $('#to_area').empty(); // Clear the area dropdown
            $('#to_area').append('<option value="">Select an area</option>'); // Add default option

            if (toCityId) {
                $.ajax({
                    url: '/get-areas/' + toCityId,
                    type: 'GET',
                    success: function (data) {
                        // Populate the area dropdown
                        $.each(data, function (index, area) {
                            if (area.name === selectedToArea) {
                                $('#to_area').append('<option value="' + area.name + '" selected>' + area.name + '</option>');
                            } else {
                                $('#to_area').append('<option value="' + area.name + '">' + area.name + '</option>');
                            }
                        });
                    },
                    error: function (xhr) {
                        console.error(xhr);
                        alert('Failed to fetch areas. Please try again.');
                    }
                });
            }
        }

        // Open Modal for Create
        $('#btn_create').click(function () {
            $('#logForm').trigger("reset");
            $('#logModalLabel').text("Create Log");
            $('#logModal').modal('show');
            $('#saveBtn').data('action', 'create'); // Set action to 'create'
        });
        $('.close_modal').click(function () {
            $('#logModal').modal('hide');
        })

        // Open Modal for Edit
        $('.btn-warning').click(function () {
            var logId = $(this).data('id');
            $.get('/logs/' + logId + '/edit', function (data) {
                $('#logModalLabel').text("Edit Log");
                $('#driver_status').val(data.driver_status);
                $('#driver_status_gps').val(data.driver_status_gps);
                $('#logged_atInput').val(data.logged_at);
                $('#logModal').modal('show');
                $('#saveBtn').data('action', 'edit').data('id', logId); // Set action to 'edit'
            });
        });

        $('#logForm').submit(function (e) {
            e.preventDefault();

            var formData = $(this).serialize();
            var action = $('#saveBtn').data('action');
            var url = (action == 'create') ? '/logs' : '/logs/' + $('#saveBtn').data('id');
            var method = (action == 'create') ? 'POST' : 'PUT';

            $.ajax({
                url: url,
                type: method,
                data: formData,
                success: function (response) {
                    $('#logModal').modal('hide');
                    location.reload(); // Reload the page to see the updated list
                },
                error: function (xhr) {
                    // Clear any previous error message
                    $('#error-message').addClass('d-none').html('');

                    // Check if the response contains an error message
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        // Show the error message in the modal
                        $('#error-message').removeClass('d-none').html(xhr.responseJSON.error);
                    } else {
                        // Log any unexpected error
                        console.log(xhr.responseText);
                    }
                }
            });
        });

        // Delete Log
        $('.btn_danger').click(function () {
            if (confirm('Məlumatın silinməyin təsdiqləyin')) {
                var logId = $(this).data('id');
                $.ajax({
                    url: '/logs/' + logId,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Add CSRF token here
                    },
                    success: function (response) {
                        location.reload();
                    },
                    error: function (xhr) {
                        console.log('An error occurred');
                    }
                });
            }
        });


    });
</script>
