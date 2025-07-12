@extends('admin.admin-layout.master')

@section('title', 'Add Product')
@section('content')
    <div class="page-content">
        <div class="container-xxl">
            <div class="row">
                <!-- Product Preview Card -->
                {{-- <div class="col-xl-3 col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <img id="preview-image" src="{{ asset('admin-assets/images/product/default.png') }}" alt=""
                                class="img-fluid rounded bg-light">
                            <div class="mt-3">
                                <h4 id="preview-name">Product Name</h4>
                                <h5 class="text-dark fw-medium mt-3">Price:</h5>
                                <h4 class="fw-semibold text-dark mt-2 d-flex align-items-center gap-2">
                                    <span id="preview-price">$0.00</span>
                                    <span id="preview-discount" class="text-muted"></span>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div> --}}

                <!-- Product Form -->
                <div class="col-xl-12 ">
                    <form id="productForm" action="{{ route('admin.products.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <!-- Replace the existing Product Images card with this -->
                        <div class="row">
                            <!-- Front Image -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Front Image</h4>
                                    </div>
                                    <div class="card-body">
                                        <!-- Dropzone Container -->
                                        <div class="dropzone text-center" id="front-dropzone-box"
                                            onclick="triggerFileInput('front')">
                                            <div class="dz-message needsclick">
                                                <i class="bx bx-cloud-upload fs-48 text-primary"></i>
                                                <h3 class="mt-4">Drop front image here, or <span
                                                        class="text-primary">click to browse</span></h3>
                                                <span class="text-muted fs-13">
                                                    1600 x 1200 (4:3) recommended. PNG, JPG, and GIF files are allowed.
                                                </span>
                                            </div>
                                        </div>
                                        <!-- Hidden File Input -->
                                        <input type="file" name="image_front" id="front-image-input" class="d-none"
                                            accept="image/*" onchange="previewImage(event, 'front')" required>
                                        @error('image_front')
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                        @enderror
                                        <!-- Image Preview -->
                                        <div id="front-preview-container" class="dropzone-preview text-center mt-3"
                                            style="display: none;">
                                            <img id="front-preview" src="#" alt="Front Image"
                                                class="img-fluid rounded"
                                                style="max-width: 100%; height: auto; border: 2px dashed #007bff; padding: 10px;">
                                            <div class="mt-2">
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="removeImage('front')">Remove Image</button>
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
                                        <!-- Dropzone Container -->
                                        <div class="dropzone text-center" id="back-dropzone-box"
                                            onclick="triggerFileInput('back')">
                                            <div class="dz-message needsclick">
                                                <i class="bx bx-cloud-upload fs-48 text-primary"></i>
                                                <h3 class="mt-4">Drop back image here, or <span class="text-primary">click
                                                        to browse</span></h3>
                                                <span class="text-muted fs-13">
                                                    1600 x 1200 (4:3) recommended. PNG, JPG, and GIF files are allowed.
                                                </span>
                                            </div>
                                        </div>
                                        <!-- Hidden File Input -->
                                        <input type="file" name="image_back" id="back-image-input" class="d-none"
                                            accept="image/*" onchange="previewImage(event, 'back')">
                                        @error('image_back')
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                        @enderror
                                        <!-- Image Preview -->
                                        <div id="back-preview-container" class="dropzone-preview text-center mt-3"
                                            style="display: none;">
                                            <img id="back-preview" src="#" alt="Back Image" class="img-fluid rounded"
                                                style="max-width: 100%; height: auto; border: 2px dashed #007bff; padding: 10px;">
                                            <div class="mt-2">
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="removeImage('back')">Remove Image</button>
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
                                            <input type="text" name="name" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Category</label>
                                            <select name="category_id" id="category_id"
                                                class="form-control choices_input" data-choices data-choices-groups
                                                data-placeholder="Select Category" required>
                                                @if (isset($categories) && $categories->count() > 0)
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                @else
                                                    <option value="">No categories available</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Price</label>
                                            <input type="number" name="price" id="price" class="form-control"
                                                step="0.01" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Discount Price</label>
                                            <input type="number" name="discount_price" id="discount_price"
                                                class="form-control" step="0.01">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Stock</label>
                                            <input type="number" name="stock" class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control" rows="4" required></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Tags</label>
                                            <input type="text" name="tags" class="form-control tagsinput" 
                                                   data-role="tagsinput" placeholder="Add tags">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <select name="status" id="status" class="form-control choices_input"
                                                data-choices data-choices-groups data-placeholder="Select Status" required>
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="text-end mb-3">
                            <button type="submit" class="btn btn-primary">Create Product</button>
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
                        less_than: '#price' // Custom rule to ensure discount is less than price
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

            // Custom validation method for discount price
            $.validator.addMethod("less_than", function(value, element, param) {
                if (value === "") return true; // Skip validation if empty
                let price = $(param).val();
                return parseFloat(value) < parseFloat(price);
            }, "Discount price must be less than regular price");
        });
    </script>
    <script>
        function triggerFileInput(type) {
            document.getElementById(`${type}-image-input`).click();
        }

        function previewImage(event, type) {
            let input = event.target;
            let reader = new FileReader();
            let dropzoneBox = document.getElementById(`${type}-dropzone-box`);
            let previewContainer = document.getElementById(`${type}-preview-container`);
            let previewImage = document.getElementById(`${type}-preview`);

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

        function removeImage(type) {
            let dropzoneBox = document.getElementById(`${type}-dropzone-box`);
            let previewContainer = document.getElementById(`${type}-preview-container`);
            let imageInput = document.getElementById(`${type}-image-input`);
            let previewImage = document.getElementById(`${type}-preview`);

            imageInput.value = "";
            previewContainer.style.display = 'none';
            dropzoneBox.style.display = 'block';
            previewImage.style.width = "";
            previewImage.style.height = "";
        }
        $(document).ready(function() {
            $('.tagsinput').tagsinput({
                trimValue: true,
                confirmKeys: [13, 32, 44], // Enter, space, comma
                tagClass: 'badge bg-primary'
            });
        });
    </script>
@endpush
