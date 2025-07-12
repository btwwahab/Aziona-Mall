@extends('admin.admin-layout.master')

@section('title', 'Edit Product')
@section('content')
    <div class="page-content">
        <div class="container-xxl">
            <div class="row">
                <div class="col-xl-12">
                    <form id="productForm" action="{{ route('admin.products.update', $product->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Front Image -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Front Image</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="dropzone text-center" id="dropzone-box" onclick="triggerFileInput()"
                                            style="{{ $product->image_front ? 'display: none;' : '' }}">
                                            <div class="dz-message needsclick">
                                                <i class="bx bx-cloud-upload fs-48 text-primary"></i>
                                                <h3 class="mt-4">Drop front image here, or <span
                                                        class="text-primary">click to browse</span></h3>
                                                <span class="text-muted fs-13">1600 x 1200 (4:3) recommended. PNG, JPG, and
                                                    GIF files are allowed.</span>
                                            </div>
                                        </div>
                                        <input type="file" name="image_front" id="image-input" class="d-none"
                                            accept="image/*" onchange="previewImage(event)">
                                        @error('image_front')
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                        @enderror
                                        <div id="preview-container" class="dropzone-preview text-center mt-3"
                                            style="{{ $product->image_front ? '' : 'display: none;' }}">
                                            <img id="image-preview"
                                                src="{{ $product->image_front ? asset('storage/' . $product->image_front) : '' }}"
                                                alt="Front Image" class="img-fluid rounded"
                                                style="max-width: 100%; height: 300px; border: 2px dashed #007bff; padding: 10px;">
                                            <div class="mt-2">
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="removeImage()">Remove Image</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Back Image -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Back Image (Optional)</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="dropzone text-center" id="dropzone-box-back"
                                            onclick="triggerFileInput_back()"
                                            style="{{ $product->image_back ? 'display: none;' : '' }}">
                                            <div class="dz-message needsclick">
                                                <i class="bx bx-cloud-upload fs-48 text-primary"></i>
                                                <h3 class="mt-4">Drop back image here, or <span class="text-primary">click
                                                        to browse</span></h3>
                                                <span class="text-muted fs-13">1600 x 1200 (4:3) recommended. PNG, JPG, and
                                                    GIF files are allowed.</span>
                                            </div>
                                        </div>
                                        <input type="file" name="image_back" id="image-input-back" class="d-none"
                                            accept="image/*" onchange="previewImage_back(event)">
                                        @error('image_back')
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                        @enderror
                                        <div id="preview-container-back" class="dropzone-preview text-center mt-3"
                                            style="{{ $product->image_back ? '' : 'display: none;' }}">
                                            <img id="image-preview-back"
                                                src="{{ $product->image_back ? asset('storage/' . $product->image_back) : '' }}"
                                                alt="Back Image" class="img-fluid rounded"
                                                style="max-width: 100%; height: 300px; border: 2px dashed #007bff; padding: 10px;">
                                            <div class="mt-2">
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="removeImage_back()">Remove Image</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product Information -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h4 class="card-title">Product Information</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Product Name</label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ $product->name }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Category</label>
                                            <select name="category_id" id="category_id" class="form-control choices_input"
                                                required>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Price</label>
                                            <input type="number" name="price" id="price" class="form-control"
                                                value="{{ $product->price }}" step="0.01" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Discount Price</label>
                                            <input type="number" name="discount_price" id="discount_price"
                                                value="{{ $product->discount_price }}" class="form-control"
                                                step="0.01">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Stock</label>
                                            <input type="number" name="stock" class="form-control"
                                                value="{{ $product->stock }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control" rows="4" required>{{ $product->description }}</textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Tags</label>
                                            <input type="text" name="tags" class="form-control tagsinput"
                                                data-role="tagsinput" value="{{ $product->tags }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <select name="status" id="status" class="form-control choices_input"
                                                required>
                                                <option value="1" {{ $product->status == 1 ? 'selected' : '' }}>
                                                    Active</option>
                                                <option value="0" {{ $product->status == 0 ? 'selected' : '' }}>
                                                    Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="text-end mb-3">
                            <button type="submit" class="btn btn-primary">Update Product</button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize tagsinput
            $('.tagsinput').tagsinput({
                trimValue: true,
                confirmKeys: [13, 32, 44],
                tagClass: 'badge bg-primary'
            });

            // Form validation
            $("#productForm").validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 3
                    },
                    category_id: {
                        required: true
                    },
                    price: {
                        required: true,
                        number: true,
                        min: 0
                    },
                    discount_price: {
                        number: true,
                        min: 0,
                        less_than: '#price'
                    },
                    stock: {
                        required: true,
                        digits: true,
                        min: 0
                    },
                    status: {
                        required: true
                    },
                    image_front: {
                        required: true,
                        extension: "jpeg|png|jpg|gif|svg"
                    },
                    image_back: {
                        extension: "jpeg|png|jpg|gif|svg"
                    },
                    description: {
                        required: true,
                        minlength: 10
                    }
                },
                messages: {
                    name: {
                        required: "Please enter a product name",
                        minlength: "Product name must be at least 3 characters long"
                    },
                    category_id: {
                        required: "Please select a category"
                    },
                    price: {
                        required: "Please enter a price",
                        number: "Please enter a valid number",
                        min: "Price cannot be negative"
                    },
                    discount_price: {
                        number: "Please enter a valid number",
                        min: "Discount price cannot be negative"
                    },
                    stock: {
                        required: "Please enter stock quantity",
                        digits: "Please enter a whole number",
                        min: "Stock cannot be negative"
                    },
                    status: {
                        required: "Please select a status"
                    },
                    image_front: {
                        required: "Please select a front image",
                        extension: "Only JPEG, PNG, JPG, GIF, or SVG files are allowed"
                    },
                    image_back: {
                        extension: "Only JPEG, PNG, JPG, GIF, or SVG files are allowed"
                    },
                    description: {
                        required: "Please enter a product description",
                        minlength: "Description must be at least 10 characters long"
                    }
                },
                errorPlacement: function(error, element) {
                    error.addClass("text-danger");
                    if (element.attr("name") === "image_front" || element.attr("name") ===
                        "image_back") {
                        $("#image-preview-container").after(error);
                    } else {
                        element.closest(".mb-3, .form-group").append(error);
                    }
                },
                highlight: function(element) {
                    $(element).addClass("is-invalid").removeClass("is-valid");
                },
                unhighlight: function(element) {
                    $(element).removeClass("is-invalid").addClass("is-valid");
                }
            });
        });

        // Front Image Functions
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

        // Back Image Functions
        function triggerFileInput_back() {
            document.getElementById('image-input-back').click();
        }

        function previewImage_back(event) {
            let input = event.target;
            let reader = new FileReader();
            let dropzoneBox = document.getElementById('dropzone-box-back');
            let previewContainer = document.getElementById('preview-container-back');
            let previewImage = document.getElementById('image-preview-back');

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

        function removeImage_back() {
            let dropzoneBox = document.getElementById('dropzone-box-back');
            let previewContainer = document.getElementById('preview-container-back');
            let imageInput = document.getElementById('image-input-back');
            let previewImage = document.getElementById('image-preview-back');

            imageInput.value = "";
            previewContainer.style.display = 'none';
            dropzoneBox.style.display = 'block';
            previewImage.style.width = "";
            previewImage.style.height = "";
        }
    </script>
@endpush
