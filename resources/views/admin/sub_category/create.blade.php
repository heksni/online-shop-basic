@extends('admin.layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Sub Category</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('sub.categories.index')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="" name="subCategoryForm" id="subCategoryForm" >
            @csrf
            <div class="card">
                <div class="card-body">								
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="name">Category</label>
                                @if($categories->isNotEmpty())
                                    <select name="category_id" id="category" class="form-control">
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category )
                                            <option value="{{ ($category->id) }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <p class="error"></p>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Name">	
                                <p class="error"></p>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Slug</label>
                                <input readonly type="text" name="slug" id="slug" class="form-control" placeholder="Slug">	
                                <p class="error"></p>
                            </div>

                        </div>	
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">Block</option>
                                </select>
                                <p class="error"></p>
                            </div>
                        </div>	
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="show_home">Show on Home</label>
                                <select name="show_home" id="show_home" class="form-control">
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>																
                    </div>
                </div>							
            </div>
            <div class="pb-5 pt-3">
                <button class="btn btn-primary" type="submit">Create</button>
                <a href="{{ route('sub.categories.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
  
@endsection

@section('customJS')
  <script>
        $("#subCategoryForm").submit(function(event){
        event.preventDefault();
        var element = $(this)

        $("button[type=submit]").prop('disabled',true);

        $.ajax({
            url: '{{ route("sub.categories.store") }}',
            type: 'post',
            data: element.serializeArray(),
            dataType: 'json',
            success: function(response){

                $("button[type=submit]").prop('disabled',false);

                if(response["status"] == true){

                    window.location.href="{{ route('sub.categories.index') }}"

                    $("#name").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html("")

                    $("#slug").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html("")


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
            error: function(jqXHR, exception){
                console.log('something went wrong',jqXHR,exception);
                var errors = jqXHR.responseJSON['message'];

                    if(errors){
                            $("#slug").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback')
                            .html(errors);
                        } else {
                            $("#slug").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html("")
                        }
            }
        })
    });

    $("#name").change(function(){

        element = $(this);
        $("button[type=submit]").prop('disabled',true);
        $.ajax({
                url: '{{ route("getSlug") }}',
                type: 'get',
                data: {title: element.val()},
                dataType: 'json',
                success: function(response){
                    
                    $("button[type=submit]").prop('disabled',false);

                    if(response["status"] == true) {
                        $("#slug").val(response["slug"]);
                    }
                },
                
        });
    });
  </script>
@endsection
