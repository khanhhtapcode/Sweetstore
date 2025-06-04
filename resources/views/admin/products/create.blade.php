{{-- resources/views/admin/products/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Thêm Sản Phẩm Mới')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Thêm Sản Phẩm Mới</h3>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay Lại
                        </a>
                    </div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-8">
                                    <!-- Basic Information -->
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Thông Tin Cơ Bản</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="name">Tên Sản Phẩm <span class="text-danger">*</span></label>
                                                <input type="text"
                                                       class="form-control @error('name') is-invalid @enderror"
                                                       id="name"
                                                       name="name"
                                                       value="{{ old('name') }}"
                                                       required>
                                                @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="slug">Slug</label>
                                                <input type="text"
                                                       class="form-control @error('slug') is-invalid @enderror"
                                                       id="slug"
                                                       name="slug"
                                                       value="{{ old('slug') }}">
                                                @error('slug')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <small class="form-text text-muted">Để trống để tự động tạo từ tên sản phẩm</small>
                                            </div>

                                            <div class="form-group">
                                                <label for="short_description">Mô Tả Ngắn</label>
                                                <textarea class="form-control @error('short_description') is-invalid @enderror"
                                                          id="short_description"
                                                          name="short_description"
                                                          rows="3">{{ old('short_description') }}</textarea>
                                                @error('short_description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="description">Mô Tả Chi Tiết</label>
                                                <textarea class="form-control @error('description') is-invalid @enderror"
                                                          id="description"
                                                          name="description"
                                                          rows="8">{{ old('description') }}</textarea>
                                                @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Pricing -->
                                    <div class="card mt-3">
                                        <div class="card-header">
                                            <h5>Giá Bán</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="price">Giá Gốc <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <input type="number"
                                                                   class="form-control @error('price') is-invalid @enderror"
                                                                   id="price"
                                                                   name="price"
                                                                   value="{{ old('price') }}"
                                                                   min="0"
                                                                   step="1000"
                                                                   required>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">₫</span>
                                                            </div>
                                                            @error('price')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="sale_price">Giá Khuyến Mãi</label>
                                                        <div class="input-group">
                                                            <input type="number"
                                                                   class="form-control @error('sale_price') is-invalid @enderror"
                                                                   id="sale_price"
                                                                   name="sale_price"
                                                                   value="{{ old('sale_price') }}"
                                                                   min="0"
                                                                   step="1000">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">₫</span>
                                                            </div>
                                                            @error('sale_price')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Inventory -->
                                    <div class="card mt-3">
                                        <div class="card-header">
                                            <h5>Kho Hàng</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="sku">Mã SKU</label>
                                                        <input type="text"
                                                               class="form-control @error('sku') is-invalid @enderror"
                                                               id="sku"
                                                               name="sku"
                                                               value="{{ old('sku') }}">
                                                        @error('sku')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="stock_quantity">Số Lượng Tồn Kho</label>
                                                        <input type="number"
                                                               class="form-control @error('stock_quantity') is-invalid @enderror"
                                                               id="stock_quantity"
                                                               name="stock_quantity"
                                                               value="{{ old('stock_quantity', 0) }}"
                                                               min="0">
                                                        @error('stock_quantity')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <!-- Product Settings -->
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Cài Đặt</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="category_id">Danh Mục <span class="text-danger">*</span></label>
                                                <select class="form-control @error('category_id') is-invalid @enderror"
                                                        id="category_id"
                                                        name="category_id"
                                                        required>
                                                    <option value="">-- Chọn danh mục --</option>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}"
                                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('category_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox"
                                                           class="custom-control-input"
                                                           id="is_active"
                                                           name="is_active"
                                                           value="1"
                                                        {{ old('is_active', 1) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="is_active">Kích Hoạt</label>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox"
                                                           class="custom-control-input"
                                                           id="is_featured"
                                                           name="is_featured"
                                                           value="1"
                                                        {{ old('is_featured') ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="is_featured">Sản Phẩm Nổi Bật</label>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="sort_order">Thứ Tự Sắp Xếp</label>
                                                <input type="number"
                                                       class="form-control @error('sort_order') is-invalid @enderror"
                                                       id="sort_order"
                                                       name="sort_order"
                                                       value="{{ old('sort_order', 0) }}"
                                                       min="0">
                                                @error('sort_order')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Product Images -->
                                    <div class="card mt-3">
                                        <div class="card-header">
                                            <h5>Hình Ảnh</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="image">Hình Ảnh Chính</label>
                                                <input type="file"
                                                       class="form-control-file @error('image') is-invalid @enderror"
                                                       id="image"
                                                       name="image"
                                                       accept="image/*">
                                                @error('image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div id="image-preview" class="mt-2"></div>
                                            </div>

                                            <div class="form-group">
                                                <label for="gallery">Thư Viện Ảnh</label>
                                                <input type="file"
                                                       class="form-control-file @error('gallery') is-invalid @enderror"
                                                       id="gallery"
                                                       name="gallery[]"
                                                       multiple
                                                       accept="image/*">
                                                @error('gallery')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <small class="form-text text-muted">Có thể chọn nhiều ảnh</small>
                                                <div id="gallery-preview" class="mt-2 row"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- SEO -->
                                    <div class="card mt-3">
                                        <div class="card-header">
                                            <h5>SEO</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="meta_title">Meta Title</label>
                                                <input type="text"
                                                       class="form-control @error('meta_title') is-invalid @enderror"
                                                       id="meta_title"
                                                       name="meta_title"
                                                       value="{{ old('meta_title') }}">
                                                @error('meta_title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="meta_description">Meta Description</label>
                                                <textarea class="form-control @error('meta_description') is-invalid @enderror"
                                                          id="meta_description"
                                                          name="meta_description"
                                                          rows="3">{{ old('meta_description') }}</textarea>
                                                @error('meta_description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Lưu Sản Phẩm
                                        </button>
                                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> Hủy
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize CKEditor
            CKEDITOR.replace('description');

            // Auto generate slug from name
            $('#name').on('input', function() {
                var name = $(this).val();
                var slug = name.toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .trim('-');
                $('#slug').val(slug);
            });

            // Auto generate SKU from name
            $('#name').on('input', function() {
                if (!$('#sku').val()) {
                    var name = $(this).val();
                    var sku = name.toUpperCase()
                        .replace(/[^A-Z0-9\s]/g, '')
                        .replace(/\s+/g, '-')
                        .substring(0, 10) + '-' + Math.floor(Math.random() * 1000);
                    $('#sku').val(sku);
                }
            });

            // Preview main image
            $('#image').on('change', function() {
                var file = this.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#image-preview').html(
                            '<img src="' + e.target.result + '" class="img-thumbnail" style="max-width: 200px;">'
                        );
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Preview gallery images
            $('#gallery').on('change', function() {
                var files = this.files;
                $('#gallery-preview').empty();

                for (var i = 0; i < files.length; i++) {
                    var file = files[i];
                    if (file) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            $('#gallery-preview').append(
                                '<div class="col-3 mb-2">' +
                                '<img src="' + e.target.result + '" class="img-thumbnail" style="width: 100%; height: 100px; object-fit: cover;">' +
                                '</div>'
                            );
                        };
                        reader.readAsDataURL(file);
                    }
                }
            });

            // Validate sale price
            $('#sale_price').on('input', function() {
                var price = parseFloat($('#price').val()) || 0;
                var salePrice = parseFloat($(this).val()) || 0;

                if (salePrice > 0 && salePrice >= price) {
                    alert('Giá khuyến mãi phải nhỏ hơn giá gốc!');
                    $(this).val('');
                }
            });
        });
    </script>
@endsection
