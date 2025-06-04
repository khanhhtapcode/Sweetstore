{{-- resources/views/admin/categories/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Chỉnh Sửa Danh Mục')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Chỉnh Sửa Danh Mục</h3>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
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

                        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="name">Tên Danh Mục <span class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control @error('name') is-invalid @enderror"
                                               id="name"
                                               name="name"
                                               value="{{ old('name', $category->name) }}"
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
                                               value="{{ old('slug', $category->slug) }}">
                                        @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Để trống để tự động tạo từ tên danh mục</small>
                                    </div>

                                    <div class="form-group">
                                        <label for="description">Mô Tả</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror"
                                                  id="description"
                                                  name="description"
                                                  rows="4">{{ old('description', $category->description) }}</textarea>
                                        @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="parent_id">Danh Mục Cha</label>
                                        <select class="form-control @error('parent_id') is-invalid @enderror"
                                                id="parent_id"
                                                name="parent_id">
                                            <option value="">-- Không có danh mục cha --</option>
                                            @foreach($categories as $cat)
                                                @if($cat->id != $category->id)
                                                    <option value="{{ $cat->id }}"
                                                        {{ old('parent_id', $category->parent_id) == $cat->id ? 'selected' : '' }}>
                                                        {{ $cat->name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('parent_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="image">Hình Ảnh</label>
                                        <input type="file"
                                               class="form-control-file @error('image') is-invalid @enderror"
                                               id="image"
                                               name="image"
                                               accept="image/*">
                                        @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                        @if($category->image)
                                            <div class="mt-2">
                                                <p class="mb-1">Hình ảnh hiện tại:</p>
                                                <img src="{{ asset('storage/categories/' . $category->image) }}"
                                                     alt="{{ $category->name }}"
                                                     class="img-thumbnail"
                                                     style="max-width: 200px;">
                                            </div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="sort_order">Thứ Tự Sắp Xếp</label>
                                        <input type="number"
                                               class="form-control @error('sort_order') is-invalid @enderror"
                                               id="sort_order"
                                               name="sort_order"
                                               value="{{ old('sort_order', $category->sort_order ?? 0) }}"
                                               min="0">
                                        @error('sort_order')
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
                                                {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="is_active">Kích Hoạt</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox"
                                                   class="custom-control-input"
                                                   id="show_in_menu"
                                                   name="show_in_menu"
                                                   value="1"
                                                {{ old('show_in_menu', $category->show_in_menu) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="show_in_menu">Hiển Thị Trong Menu</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Cập Nhật
                                </button>
                                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Hủy
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
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

            // Preview image on change
            $('#image').on('change', function() {
                var file = this.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        // Remove existing preview
                        $('.image-preview').remove();

                        // Add new preview
                        var preview = $('<div class="image-preview mt-2">' +
                            '<p class="mb-1">Hình ảnh mới:</p>' +
                            '<img src="' + e.target.result + '" class="img-thumbnail" style="max-width: 200px;">' +
                            '</div>');
                        $('#image').parent().append(preview);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
@endsection
