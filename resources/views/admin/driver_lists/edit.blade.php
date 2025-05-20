@include('admin.includes.header')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <form action="{{ route('driver_lists.update', $driver_list->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Redaktə et</h4>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="col-form-label">Driver name</label>
                                    <input class="form-control" type="text" name="title" value="{{ old('title', $driver_list->title) }}">
                                    @if($errors->first('title')) <small class="form-text text-danger">{{ $errors->first('title') }}</small> @endif
                                </div>
                                <div class="mb-3">
                                    <label class="col-form-label">Phone number</label>
                                    <input class="form-control" type="text" name="phone" value="{{ old('phone', $driver_list->phone) }}">
                                    @if($errors->first('phone')) <small class="form-text text-danger">{{ $errors->first('phone') }}</small> @endif
                                </div>
                                <div class="mb-3">
                                    <label class="col-form-label">Driver car number</label>
                                    <input class="form-control" type="text" name="plate_no" value="{{ old('plate_no', $driver_list->plate_no) }}">
                                    @if($errors->first('plate_no')) <small class="form-text text-danger">{{ $errors->first('plate_no') }}</small> @endif
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <button class="btn btn-primary">Yadda saxla</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@include('admin.includes.footer')
