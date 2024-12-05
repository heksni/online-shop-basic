@extends('admin.layouts.app')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Product</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('products.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form action="" name="productForm" id="productForm" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="title">Title</label>
                                            <input type="text" name="title" id="title" class="form-control"
                                                placeholder="Title" value="{{ $product->title }}">
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="title">Slug</label>
                                            <input readonly type="text" name="slug" id="slug"
                                                class="form-control" placeholder="Slug" value="{{ $product->slug }}">
                                            <p class="error">
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description">Description</label>
                                            <textarea name="description" id="description" cols="30" rows="10" class="summernote"
                                                placeholder="Description">{{ $product->description }}</textarea>
                                            <p></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Media</h2>
                                <div id="image" class="dropzone dz-clickable">
                                    <div class="dz-message needsclick">
                                        <br>Drop files here or click to upload.<br><br>
                                    </div>
                                </div>
                               
                            </div>
                        </div>
                        <div class="row" id="product-gallery">
                            @foreach ($prodImages as $image)
                                <div class="col-md-3" id="image-row-{{$image->id}}">
                                    <div class="card">
                                        <input type="hidden" name="image_array[]" value="{{$image->id}}">
                                            <img class="p-2 card-img-top" src="{{ asset('uploads/product/small/'.$image->image)}}" alt="" >
                                        <div class="card-body">
                                            <a href="javascript:void(0)" class="btn btn-danger" onclick="deleteThumb({{$image->id}})">Delete</a>
                                        </div>
    
                                    </div>
                            </div>
                        @endforeach
                            
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Pricing</h2>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="price">Price</label>
                                            <input type="text" name="price" id="price" class="form-control"
                                                placeholder="Price" value="{{ $product->price }}">
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="compare_price">Compare at Price</label>
                                            <input type="text" name="compare_price" id="compare_price"
                                                class="form-control" placeholder="Compare Price"
                                                value="{{ $product->compare_price }}">
                                            <p></p>
                                            <p class="text-muted mt-3">
                                                To show a reduced price, move the product’s original price into Compare at
                                                price. Enter a lower value into Price.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Inventory</h2>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sku">SKU (Stock Keeping Unit)</label>
                                            <input type="text" name="sku" id="sku" class="form-control"
                                                placeholder="sku" value="{{ $product->sku }}">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="barcode">Barcode</label>
                                            <input type="text" name="barcode" id="barcode" class="form-control"
                                                placeholder="Barcode" value="{{ $product->barcode }}">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="hidden" name="track_qty" value="No">
                                                <input class="custom-control-input" type="checkbox" id="track_qty"
                                                    name="track_qty" value="Yes"
                                                    {{ $product->track_qty == 'Yes' ? 'checked' : '' }}>
                                                <label for="track_qty" class="custom-control-label">Track Quantity</label>
                                                <p class="error"></p>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <input type="number" min="0" name="qty" id="qty"
                                                class="form-control" placeholder="Qty" value="{{ $product->qty }}">
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Product status</h2>
                                <div class="mb-3">
                                    <select name="status" id="status" class="form-control">
                                        <option value="1" {{ $product->status == 1 ? 'selected' : '' }}>
                                            Active</option>
                                        <option value="0" {{ $product->status == 0 ? 'selected' : '' }}>
                                            Block</option>
                                    </select>
                                    <p class="error"></p>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h2 class="h4  mb-3">Product Category</h2>
                                <div class="mb-3">
                                    {{($product->category_id)}}

                                    <label for="category_id">Category</label>
                                    <select name="category_id" id="category_id" class="form-control">
                                        <option value="" {{ $product->category_id ? 'hidden' : '' }}>Select Category
                                        </option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <p class="error"></p>
                                </div>
                                <div class="mb-3">
                                    {{($product->sub_category_id)}}

                                    <label for="sub_category_id">Sub category</label>
                                    <select name="sub_category_id" id="sub_category_id" class="form-control">
                                        @foreach ($sub_categories as $sub_category)
                                            <option {{ $sub_category->id == $product->sub_category_id ? 'selected' : '' }}
                                                value="{{ $sub_category->id }}">
                                                {{ $sub_category->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Product brand</h2>
                                <div class="mb-3">
                                    <select name="brand_id" id="brand_id" class="form-control">
                                        <option value="">Select a Brand</option>
                                        @foreach ($brands as $brand)
                                            <option
                                            value ="{{$brand->id}}"
                                                {{ $product->brand_id == $brand->id ? ' selected' : '' }}>
                                                {{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Featured product</h2>
                                <div class="mb-3">
                                    <select name="is_featured" id="is_featured" class="form-control">
                                        <option value="Yes" {{ $product->is_featured == 'Yes' ? 'selected' : '' }}>
                                            Yes</option>
                                        <option value="No" {{ $product->is_featured == 'No' ? 'selected' : '' }}>
                                            No</option>
                                    </select>
                                    <p class="error"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pb-5 pt-3">
                    <button class="btn btn-primary">Update</button>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('customJS')
    <script>
        // function fetchSubCategories(category_id) {
        //     $.ajax({
        //         url: '{{ route('products.sub.categories.index') }}',
        //         type: 'GET',
        //         data: {
        //             category_id: category_id
        //         },
        //         dataType: 'json',
        //         success: function(response) {
        //             console.log(response);
        //             $("#sub_category_id").find("option").not(":first").remove();
        //             $.each(response["subCategories"], function(key, item) {
        //                 $("#sub_category_id").append(
        //                     `<option value='${item.id}'>${item.name}</option>`
        //                 );
        //             });

        //             // Preselect sub-category if applicable
        //             let selectedSubCategory = "{{ old('sub_category_id', $product->sub_category_id ?? '') }}";
        //             if (selectedSubCategory) {
        //                 $("#sub_category_id").val(selectedSubCategory).change();
        //             }
        //         },
        //         error: function() {
        //             console.log("Something went wrong");
        //         }
        //     });
        // }

        // $(document).ready(function() {
        //     // Trigger on category change
        //     $('#category_id').change(function() {
        //         var category_id = $(this).val();
        //         if (category_id) {
        //             fetchSubCategories(category_id);
        //         }
        //     });

        //     // Trigger manually if editing (when category is pre-selected)
        //     var initialCategory = $('#category_id').val();
        //     if (initialCategory) {
        //         fetchSubCategories(initialCategory);
        //     }
        // });

        $('#category_id').change(function() {

                var category_id = $(this).val();
                $.ajax({
                    url: '{{ route('products.sub.categories.index') }}',
                    type: 'get',
                    data: {
                        category_id: category_id
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
                        $("#sub_category_id").find("option").not(":first").remove();
                        $.each(response["subCategories"], function(key, item) {
                            $("#sub_category_id").append(
                                `<option value='${item.id}'>${item.name}</option>`)
                        })
                    },
                    error: function() {
                        console.log("Something went wrong");
                    }
                })
                })
        $("#productForm").submit(function(event) {
            event.preventDefault();

            var formArray = $(this).serializeArray();

            $.ajax({
                url: '{{ route('products.update',$product->id) }}',
                type: 'put',
                data: formArray,
                dataType: 'json',
                success: function(response) {

                    if (response['status'] == true) {
                        window.location.href = "{{ route('products.index') }}"

                    } else {
                        var errors = response['errors'];

                        // if (errors['title']) {
                        //     $("#title").addClass('is-invalid')
                        //         .siblings('p')
                        //         .addClass('invalid-feedback')
                        //         .html(errors['title']);
                        // } else {
                        //     $("#title").removeClass('is-invalid')
                        //         .siblings('p')
                        //         .removeClass('invalid-feedback')
                        //         .html("");
                        // }

                        $(".error").removeClass('invalid-feedback').html('');
                        $("input[type='text'], select").removeClass('is-invalid');

                        $.each(errors, function(key, value) {
                            $(`#${key}`).addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(value)
                        })
                    }
                },
                error: function(jqXHR, exception) {
                    console.log('something went wrong');
                }
            })
        });

        $("#title").change(function() {

            element = $(this);

            $("button[type=submit]").prop('disabled', true);
            $.ajax({
                url: '{{ route('getSlug') }}',
                type: 'get',
                data: {
                    title: element.val()
                },
                dataType: 'json',
                success: function(response) {

                    $("button[type=submit]").prop('disabled', false);

                    if (response["status"] == true) {
                        $("#slug").val(response["slug"]);
                    }
                }
            });
        });

        Dropzone.autoDiscover = false;
        const dropzone = $('#image').dropzone({
            
            url: "{{ route('products.images.update') }}",
            maxFiles: 10,
            paramName: 'image',
            params:{'product_id': '{{$product->id}}'},
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(file, response) {
                // $("#image_id").val(response.image_id);
                console.log(response);

                var html = `<div class="col-md-3" id="image-row-${response.image_id}">
                                <div class="card">
                                    <img src="${response.ImagePath}" class="card-img-top p-2"  alt="">
                                     <input type="hidden" name="image_array[]" value="${response.image_id}">
                                    <div class="card-body">
                                        <a href="javascript:void(0)" class="btn btn-danger" onclick="deleteThumb(${response.image_id})">Delete</a>
                                    </div>
                                </div>
                            </div>`;

                $("#product-gallery").append(html);
            },
            complete: function(file){
                this.removeFile(file);
            }
    });

        function deleteThumb(id) {
            if(confirm("Are you sure you want to delete Image?")){
                $.ajax({
                        url:'{{ route("products.images.delete")}}',
                        type: 'delete',
                        data:{id:id},
                        success: function(response){
                            if(response.status == true){
                                alert(response.message);
                                $("#image-row-"+id).remove();

                            }else{
                                alert(response.message);
                            }
                        }
                    });
                }
            }
    </script>
@endsection
