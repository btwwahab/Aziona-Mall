@extends('admin.admin-layout.master')

@section('title', 'Manage Payment Methods')

@section('content')
    <div class="page-content">
        <div class="container-xxl">
            <div class="row">
                <div class="col-xl-12 col-lg-8">
                    <h2 class="mb-4">Manage Payment Methods</h2>

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($methods as $method)
                                        <tr>
                                            <td>
                                                <i class="fas fa-money-bill-wave"></i> {{ $method->name }}
                                            </td>
                                            <td>
                                                <span class="badge {{ $method->status == 1 ? 'bg-success' : 'bg-danger' }} ">
                                                    {{ $method->status == 1 ? 'Enabled' : 'Disabled' }}
                                                </span>
                                            </td>
                                            <td>
                                                <form action="{{ route('admin.payment.methods.update', $method->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('POST')
                                                
                                                    <select name="status" class="form-select form-select-sm d-inline w-auto" onchange="this.form.submit()">
                                                        <option value="1" {{ $method->status == 1 ? 'selected' : '' }}>Enabled</option>
                                                        <option value="0" {{ $method->status == 0 ? 'selected' : '' }}>Disabled</option>
                                                    </select>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div> <!-- End Card -->
                </div>
            </div>
        </div>
    </div>
@endsection
