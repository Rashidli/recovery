@include('admin.includes.header')
<style>
    .form-control {
        font-weight: bold !important;
    }

    .form-control option {
        font-weight: bold !important;
    }

    select option {
        font-weight: bold !important;
    }

    .select2-results__option {
        font-weight: bold !important;
    }
</style>
<div class="main-content" style="margin-left: 0 !important;">
    <div class="page-content">
        <div class="container-fluid">
            <form action="{{ route('orders.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Create Order</h4>
                        <a href="{{route('orders.index')}}" class="btn btn-primary">Back to list</a><br><br>

                        <!-- Customer Details Section -->
                        <h5 class="section-heading" style="background-color: #ffc425; padding: 10px;">Customer
                            Details</h5>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="reference_number">Reference Number</label>
                                    <input id="reference_number" class="form-control" type="text"
                                           name="reference_number" value="{{ old('reference_number') }}">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="customer_name">Customer Name</label>
                                    <input id="customer_name" class="form-control" type="text" name="customer_name"
                                           value="{{ old('customer_name') }}">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="vehicle_make">Vehicle Make</label>
                                    <select id="vehicle_make" class="form-control js-example-basic-single"
                                            name="vehicle_make">
                                        <option value="">Select Make</option>
                                        @foreach($vehicle_makes as $vehicle_make)
                                            <option data-id="{{$vehicle_make->id}}"
                                                    value="{{ $vehicle_make->make }}" {{ old('vehicle_make') == $vehicle_make->make ? 'selected' : '' }}>{{ $vehicle_make->make }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="vehicle_plate_no">Vehicle Plate / Classic Number</label>
                                    <input id="vehicle_plate_no" class="form-control" type="text"
                                           name="vehicle_plate_no" value="{{ old('vehicle_plate_no') }}">
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="phone">Date & Time</label>
                                    <input id="time" class="form-control" type="date" name="time"
                                           value="{{ old('time') }}">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="phone">Customer Contact Number</label>
                                    <input id="phone" class="form-control" type="text" name="phone"
                                           value="{{ old('phone') }}">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="vehicle_model">Vehicle Model</label>
                                    <select id="vehicle_model" class="form-control js-example-basic-single"
                                            name="vehicle_model">
                                        <option value="">Select Model</option>
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
                                    <select required id="service_category" class="form-control js-example-basic-single"
                                            name="service_category">
                                        <option value="">Select Category</option>
                                        @foreach($service_categories as $service_category)
                                            <option data-id="{{ $service_category->id }}"
                                                    value="{{ $service_category->name }}"
                                                {{ old('service_category') == $service_category->name ? 'selected' : '' }}>
                                                {{ $service_category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="from_city">From City</label>
                                    <select required id="from_city" class="form-control js-example-basic-single"
                                            name="from_city">
                                        <option value="">Select City</option>
                                        @foreach($from_cities as $from_city)
                                            <option data-id="{{$from_city->id}}"
                                                    value="{{$from_city->name}}" {{ old('from_city') == $from_city->name ? 'selected' : '' }}>{{$from_city->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="from_area">From Area</label>
                                    <select id="from_area" class="form-control js-example-basic-single"
                                            name="from_area">
                                        <option value="">Select an area</option>
                                        @if(old('from_area'))
                                            <option value="{{ old('from_area') }}"
                                                    selected>{{ old('from_area') }}</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="from_location_details">Pick up Location ( Al Futtaim Service centers and
                                        storage locations)</label>
                                    <input id="from_location_details" class="form-control" type="text"
                                           name="from_location_details" value="{{ old('from_location_details') }}">
                                </div>

                                {{--                                <div class="form-group mb-3">--}}
                                {{--                                    <label for="from_gps_coordinates">From GPS Coordinates</label>--}}
                                {{--                                    <input id="from_gps_coordinates" class="form-control" type="text" name="from_gps_coordinates" value="{{ old('from_gps_coordinates') }}">--}}
                                {{--                                </div>--}}
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="service_type">Service Type</label>
                                    <select required id="service_type" class="form-control js-example-basic-single"
                                            name="service_type">
                                        <option value="">Select Type</option>
                                        <!-- Service types will be populated dynamically here -->
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="to_location_details">Drop off Location (Al Futtaim Service centers and
                                        storage locations)</label>
                                    <select id="to_location_details" class="form-control js-example-basic-single"
                                            name="to_location_details" disabled>
                                        <option value="">Select Location</option>
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="to_city">To City</label>
                                    <input required id="to_city" class="form-control" type="text" name="to_city"
                                           value="{{ old('to_city') }}" readonly>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="to_area">To Area</label>
                                    <select id="to_area" class="form-control js-example-basic-single" name="to_area">
                                        <option value="">Select an Area</option>
                                        @if(old('to_area'))
                                            <option value="{{ old('to_area') }}" selected>{{ old('to_area') }}</option>
                                        @endif
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="comment">Comment</label>
                                    <textarea id="comment" class="form-control"
                                              name="comment">{{ old('comment') }}</textarea>
                                </div>
                            </div>
                        </div>
                        @if($isAdmin)
                            <!-- Trip Details Section -->
                            <h5 class="section-heading" style="background-color: #ffc425; padding: 10px;">Trip
                                Details</h5>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="trip_number">Trip Number</label>
                                        <input id="trip_number" class="form-control" type="text" name="trip_number"
                                               value="{{ old('trip_number') }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="starting_time">Starting Time</label>
                                        <input id="starting_time" class="form-control" type="datetime-local"
                                               name="starting_time" value="{{ old('starting_time') }}">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="estimated_amt">Estimated Amount</label>
                                        <input id="estimated_amt" class="form-control" type="text" readonly
                                               name="estimated_amt" value="0">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="ending_time">Ending Time</label>
                                        <input id="ending_time" class="form-control" type="datetime-local"
                                               name="ending_time" value="{{ old('ending_time') }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="reached_time">Reached Time</label>
                                        <input id="reached_time" class="form-control" type="datetime-local"
                                               name="reached_time" value="{{ old('reached_time') }}">
                                    </div>

                                </div>
                            </div>
                        @endif
                        <div class="form-group text-center mb-3">
                            <button class="btn btn-primary" type="submit">Save</button>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@include('admin.includes.footer')
<script>

    $(document).ready(function () {

        const serviceTypeSelect = $('#service_type');

        // Elements to switch labels and names
        const toCity = $('#to_city');
        const toArea = $('#to_area');
        const toLocationDetails = $('#to_location_details');

        const fromCity = $('#from_city');
        const fromArea = $('#from_area');
        const fromLocationDetails = $('#from_location_details');

        const toCityLabel = $("label[for='to_city']");
        const toAreaLabel = $("label[for='to_area']");
        const toLocationDetailsLabel = $("label[for='to_location_details']");

        const fromCityLabel = $("label[for='from_city']");
        const fromAreaLabel = $("label[for='from_area']");
        const fromLocationDetailsLabel = $("label[for='from_location_details']");

        // Function to swap labels and names
        function toggleLabelsAndNames(isCustomerDrop) {
            if (isCustomerDrop) {
                // Change To fields to From fields
                toCityLabel.text('From City');
                toAreaLabel.text('From Area');
                toLocationDetailsLabel.text('Pick up Location (Al Futtaim Service centers and storage locations)');

                toCity.attr('name', 'from_city');
                toArea.attr('name', 'from_area');
                toLocationDetails.attr('name', 'from_location_details');

                // Change From fields to To fields
                fromCityLabel.text('To City');
                fromAreaLabel.text('To Area');
                fromLocationDetailsLabel.text('Drop off Location (Al Futtaim Service centers and storage locations)');

                fromCity.attr('name', 'to_city');
                fromArea.attr('name', 'to_area');
                fromLocationDetails.attr('name', 'to_location_details');
            } else {
                // Revert to original labels and names
                toCityLabel.text('To City');
                toAreaLabel.text('To Area');
                toLocationDetailsLabel.text('Drop off Location (Al Futtaim Service centers and storage locations)');

                toCity.attr('name', 'to_city');
                toArea.attr('name', 'to_area');
                toLocationDetails.attr('name', 'to_location_details');

                fromCityLabel.text('From City');
                fromAreaLabel.text('From Area');
                fromLocationDetailsLabel.text('Pick up Location (Al Futtaim Service centers and storage locations)');

                fromCity.attr('name', 'from_city');
                fromArea.attr('name', 'from_area');
                fromLocationDetails.attr('name', 'from_location_details');
            }
        }


        $('#vehicle_make').on('change', function () {
            var makeId = $(this).find('option:selected').data('id');
            console.log(makeId);
            if (makeId) {
                $.ajax({
                    url: '/get-vehicle-models/' + makeId,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $('#vehicle_model').empty();
                        $('#vehicle_model').append('<option value="">Select Model</option>');
                        $.each(data, function (key, value) {
                            $('#vehicle_model').append('<option value="' + value.model + '">' + value.model + '</option>');
                        });
                    }
                });
            } else {
                $('#vehicle_model').empty();
                $('#vehicle_model').append('<option value="">Select Model</option>');
            }
            if (makeId) {
                $.ajax({
                    url: '/fetch-service-centers/' + makeId, // Adjust this URL as needed
                    method: 'GET',
                    success: function (data) {
                        $('#to_location_details').empty().prop('disabled', false).append('<option value="">Select Location</option>');
                        if (data.length > 0) {
                            $.each(data, function (index, serviceCenter) {
                                $('#to_location_details').append('<option value="' + serviceCenter.name + '" data-city="' + serviceCenter.city + '">' + serviceCenter.name + '</option>');
                            });
                        } else {
                            // Clear the to_location_details dropdown and the to_city input if no data is returned
                            $('#to_location_details').append('<option value="">No Service Centers Available</option>');
                            $('#to_city').val('');
                            $('#to_city').prop('readonly', false);
                        }
                    },
                    error: function () {
                        // Handle error response here
                        $('#to_location_details').empty().append('<option value="">Error fetching data</option>');
                        $('#to_city').val('');
                        $('#to_city').prop('readonly', false);
                    }
                });
            } else {
                $('#to_location_details').empty().prop('disabled', true);
                $('#to_city').val('');
            }
        });

        $('#to_location_details').change(function () {
            var selectedOption = $(this).find(':selected');
            var city = selectedOption.data('city');
            $('#to_city').val(city).prop('readonly', true);
        });


        var categoryId = $('#service_category').find('option:selected').data('id');
        if (categoryId) {
            loadServiceTypes(categoryId);
        }

        // Load service types when category is changed
        $('#service_category').on('change', function () {
            var categoryId = $(this).find('option:selected').data('id');
            loadServiceTypes(categoryId);
        });

        function loadServiceTypes(categoryId) {
            if (categoryId) {
                $.ajax({
                    url: '/get-service-types/' + categoryId,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $('#service_type').empty(); // Clear the service types dropdown
                        $('#service_type').append('<option value="">Select Type</option>'); // Add default option
                        $.each(data, function (key, value) {
                            $('#service_type').append('<option value="' + value.name + '">' + value.name + '</option>');
                        });
                    }
                });
            } else {
                $('#service_type').empty(); // Clear the service types dropdown
                $('#service_type').append('<option value="">Select Type</option>'); // Add default option
            }
        }

        serviceTypeSelect.on('change', function () {
            const isCustomerDrop = serviceTypeSelect.find(':selected').val() === 'Customer Drop';
            toggleLabelsAndNames(isCustomerDrop);
        });

        $('#from_city').change(function () {
            var cityId = $(this).find(':selected').data('id');
            $('#from_area').empty(); // Clear the area dropdown
            $('#from_area').append('<option value="">Select an area</option>'); // Add default option

            // Make AJAX request to fetch areas
            $.ajax({
                url: '/get-areas/' + cityId, // Adjust the URL as necessary
                type: 'GET',
                success: function (data) {
                    // Populate the area dropdown
                    $.each(data, function (index, area) {
                        $('#from_area').append('<option value="' + area.name + '">' + area.name + '</option>');
                    });
                },
                error: function (xhr) {
                    console.error(xhr);
                    alert('Failed to fetch areas. Please try again.');
                }
            });
        });

        $('#to_city').change(function () {
            var cityId = $(this).find(':selected').data('id');
            $('#to_area').empty(); // Clear the area dropdown
            $('#to_area').append('<option value="">Select an area</option>'); // Add default option

            // Make AJAX request to fetch areas
            $.ajax({
                url: '/get-areas/' + cityId, // Adjust the URL as necessary
                type: 'GET',
                success: function (data) {
                    // Populate the area dropdown
                    $.each(data, function (index, area) {
                        $('#to_area').append('<option value="' + area.name + '">' + area.name + '</option>');
                    });
                },
                error: function (xhr) {
                    console.error(xhr);
                    alert('Failed to fetch areas. Please try again.');
                }
            });
        });

    });
</script>
