@extends('admin.admin-layout.master')

@section('title', 'Edit Category')
@section('content')
    {{-- START Wrapper --}}

    {{-- Header --}}

    {{-- Right Sidebar --}}

    {{-- Sidebar Here --}}

    <!-- ==================================================== -->
    <!-- Start right Content here -->
    <!-- ==================================================== -->
    <div class="page-content">

        <!-- Start Container Fluid -->
        <div class="container-xxl">

            <div class="row">
            
                <div class="col-xl-9 col-lg-8 ">
                    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                    

                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Category Image</h4>
                            </div>
                            <div class="card-body">
                                <div class="dropzone text-center" id="dropzone-box"
                                    onclick="triggerFileInput()" style="{{ $category->image ? 'display: none;' : '' }}">
                                    <div class="dz-message needsclick">
                                        <i class="bx bx-cloud-upload fs-48 text-primary"></i>
                                        <h3 class="mt-4">Drop image here, or <span class="text-primary">click to browse</span></h3>
                                        <span class="text-muted fs-13">1600 x 1200 (4:3) recommended. PNG, JPG, and GIF files are allowed.</span>
                                    </div>
                                </div>
                                <input type="file" name="image" id="image-input" class="d-none"
                                    accept="image/*" onchange="previewImage(event)">
                                @error('image')
                                    <span class="text-danger d-block mt-2">{{ $message }}</span>
                                @enderror
                                <div id="preview-container" class="dropzone-preview text-center mt-3"
                                    style="{{ $category->image ? '' : 'display: none;' }}">
                                    <img id="image-preview" src="{{ $category->image ? asset('storage/' . $category->image) : '' }}" 
                                        alt="Category Image"
                                        class="img-fluid rounded"
                                        style="max-width: 100%; height: 300px; border: 2px dashed #007bff; padding: 10px;">
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-danger btn-sm"
                                            onclick="removeImage()">Remove Image</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- General Information -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">General Information</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="category-title" class="form-label">Category Name</label>
                                            <input type="text" id="category-title" class="form-control" name="name"
                                                value="{{ old('name', $category->name) }}">
                                            @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-control choices_input" id="status" name="status"
                                            data-choices data-choices-groups data-placeholder="Select Crater">
                                            <option value="">Select Status</option>
                                            <option value="1" {{ $category->status == 1 ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ $category->status == 0 ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                        @error('status')
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="p-3 bg-light mb-3 rounded">
                            <div class="row justify-content-end g-2">
                                <div class="col-lg-2">
                                    <button type="submit" class="btn btn-outline-secondary w-100">Update Category</button>
                                </div>
                                <div class="col-lg-2">
                                    <a href="{{ route('admin.categories.index') }}" class="btn btn-primary w-100">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
        <!-- End Container Fluid -->
    @endsection
    @section('scripts')

    @push('scripts')
    <script>
        $(document).ready(function () {
            $("form").validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 3
                    },
                    image: {
                        required: true,
                        extension: "jpeg|png|jpg|gif|svg"
                    },
                    status: {
                        required: true
                    }
                },
                messages: {
                    name: {
                        required: "Please enter a category name",
                        minlength: "Category name must be at least 3 characters long"
                    },
                    image: {
                        required: "Please select an image",
                        extension: "Only JPEG, PNG, JPG, GIF, or SVG files are allowed"
                    },
                    status: {
                        required: "Please select a status"
                    }
                },
                errorPlacement: function (error, element) {
                    error.addClass("text-danger");
                    if (element.attr("name") === "image") {
                        $("#image-preview-container").after(error);
                    } else {
                        element.closest(".mb-3, .form-group").append(error);
                    }
                },
                highlight: function (element) {
                    $(element).addClass("is-invalid").removeClass("is-valid");
                },
                unhighlight: function (element) {
                    $(element).removeClass("is-invalid").addClass("is-valid");
                }
            });
        });

       
    function triggerFileInput() {
        document.getElementById('image-input').click();
    }

    function previewImage(event) {
        let input = event.target;
        let reader = new FileReader();
        let dropzoneBox = document.getElementById('dropzone-box');
        let previewContainer = document.getElementById('preview-container');
        let previewImage = document.getElementById('image-preview');

        reader.onload = function() {
            previewImage.src = reader.result;
            previewImage.style.width = "100%";
            previewImage.style.height = "300px";
            previewImage.style.objectFit = "contain";
            dropzoneBox.style.display = 'none';
            previewContainer.style.display = 'block';
        };

        if (input.files && input.files[0]) {
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeImage() {
        let dropzoneBox = document.getElementById('dropzone-box');
        let previewContainer = document.getElementById('preview-container');
        let imageInput = document.getElementById('image-input');
        let previewImage = document.getElementById('image-preview');

        imageInput.value = "";
        previewContainer.style.display = 'none';
        dropzoneBox.style.display = 'block';
        previewImage.style.width = "";
        previewImage.style.height = "";
    }
    </script>
@endpush
