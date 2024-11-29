@include('admin.includes.header')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

<div class="main-content" style="margin-left: 0 !important;">
    <div class="page-content">
        <div class="container-fluid">
            <h2 class="mb-4 text-center" style="font-size: 2rem;">Orders Report</h2>

            <div class="row g-4">

                <!-- Daily Orders -->
                <div class="col-lg-3 col-md-6">
                    <div class="card border-success shadow-sm">
                        <div class="card-body text-center">
                            <h5 class="card-title text-success" style="font-size: 1.5rem;">Daily Orders</h5>
                            <p class="card-text fw-bold" style="font-size: 1.25rem;">Count: {{ $dailyOrders->order_count }}</p>
                            <p class="card-text" style="font-size: 1.25rem;">Total Amount: <span class="fw-bold">{{ $dailyOrders->total_amount }}

                                </span></p>
                        </div>
                    </div>
                </div>

                <!-- Weekly Orders -->
                <div class="col-lg-3 col-md-6">
                    <div class="card border-primary shadow-sm">
                        <div class="card-body text-center">
                            <h5 class="card-title text-primary" style="font-size: 1.5rem;">Weekly Orders</h5>
                            <p class="card-text fw-bold" style="font-size: 1.25rem;">Count: {{ $weeklyOrders->order_count }}</p>
                            <p class="card-text" style="font-size: 1.25rem;">Total Amount: <span class="fw-bold">{{ $weeklyOrders->total_amount }}

                                </span></p>
                        </div>
                    </div>
                </div>

                <!-- Monthly Orders -->
                <div class="col-lg-3 col-md-6">
                    <div class="card border-warning shadow-sm">
                        <div class="card-body text-center">
                            <h5 class="card-title text-warning" style="font-size: 1.5rem;">Monthly Orders</h5>
                            <p class="card-text fw-bold" style="font-size: 1.25rem;">Count: {{ $monthlyOrders->order_count }}</p>
                            <p class="card-text" style="font-size: 1.25rem;">Total Amount: <span class="fw-bold">{{ $monthlyOrders->total_amount }}

                                </span></p>
                        </div>
                    </div>
                </div>

                <!-- Total Orders -->
                <div class="col-lg-3 col-md-6">
                    <div class="card border-dark shadow-sm">
                        <div class="card-body text-center">
                            <h5 class="card-title text-dark" style="font-size: 1.5rem;">Total Orders</h5>
                            <p class="card-text fw-bold" style="font-size: 1.25rem;">Count: {{ $totalOrders->order_count }}</p>
                            <p class="card-text" style="font-size: 1.25rem;">Total Amount: <span class="fw-bold">{{ $totalOrders->total_amount }}

                                </span></p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@include('admin.includes.footer')
