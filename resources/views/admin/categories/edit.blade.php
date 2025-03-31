@extends('admin.layouts.master')
@section('title')
{{ $category->name }}
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Update Category</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.categories.index') }}">Categories</a>
                    </li>
                    <li class="breadcrumb-item active">Update Category</li>
                </ol>
            </div>
        </div>


        <form id="category-form-edit-{{ $category->id }}" action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="projectname-input" class="form-label">Name</label>
                                <input id="projectname-input" name="name" type="text" class="form-control" placeholder="Enter category name..." value="{{ $category->name }}" required>
                                @error('name')
                                <div class="text-danger fst-italic">
                                    * {{ $message }}
                                </div>
                                @enderror
                            </div>


                            <div class="mb-3">
                                <label for="projectname-input" class="form-label">Slug</label>
                                <input id="projectname-input" name="slug" type="text" class="form-control" placeholder="Enter category slug..." value="{{ $category->slug }}" required>
                                <div class="invalid-feedback">Please enter a project name.</div>
                            </div>



                            <div class="mb-3">
                                <label for="projectname-input" class="form-label">Description</label>
                                <textarea name="description" type="text" class="form-control" placeholder="Enter category description..." required>
                                {{ $category->description }}
                                </textarea>
                                <div class="invalid-feedback">Please enter a project name.</div>
                            </div>
                        </div>
                        <!-- end card body -->
                    </div>
                </div>
                <!-- end col -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Publish</h5>

                            <div class="mb-3">
                                <div class="form-check form-switch mb-3">
                                    <label class="form-check-label">status</label>
                                    <input class="form-check-input" type="checkbox" {{ $category->status ?
                                    'checked' :
                                    ''
                                    }} name="status">
                                </div>
                            </div>

                            <div>
                                <div class="form-check form-switch mb-3">
                                    <label class="form-check-label">is_active</label>
                                    <input class="form-check-input" type="checkbox" {{ $category->is_active ?
                                    'checked' :
                                    ''
                                    }} name="is_active">
                                </div>
                            </div>
                        </div>
                        <!-- end card body -->
                    </div>
                </div>
                <!-- end col -->

                <div class="col-lg-8">
                    <div class="text-end mb-4">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/theme/admin/libs/dropzone/dropzone-min.js') }}"></script>
<script src="{{ asset('assets/js/admin/categories/update.js') }}"></script>
{{-- <script src="{{ asset('assets/theme/admin/js/pages/form-file-upload.init.js') }}"></script> --}}
{{-- <script src="{{ asset('assets/theme/admin/js/pages/project-create.init.js') }}"></script> --}}
@endsection
