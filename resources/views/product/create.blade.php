@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div id="message"></div>
            <div class="card">
                <div class="card-header">{{ __('Product Form') }}</div>
                <div class="card-body">
                    {!! Form::model($product,array('enctype' => 'multipart/form-data', 'method' => ($type=='edit')?'patch':'post','route' => array($action,$action_id??""),'id' => "product_form")) !!}
                    <div class="row mb-3">
                        {!! Form::label('name','Name',['class'=>'col-md-4 col-form-label text-md-end']) !!}

                        <div class="col-md-6">
                            {!! Form::text('name',$product->name,['class'=>'form-control']) !!}
                            @error('name')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message  }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        {!! Form::label('price','Price',['class'=>'col-md-4 col-form-label text-md-end']) !!}

                        <div class="col-md-6">
                            {!! Form::number('price',$product->price,['class'=>'form-control']) !!}
                            @error('price')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        {!! Form::label('brand_id','Brand',['class'=>'col-md-4 col-form-label text-md-end']) !!}

                        <div class="col-md-6">
                            {!! Form::select('brand_id', $brandlist, null, ['placeholder' => 'Select product brand', 'class'=>'form-control']); !!}

                            @error('brand_id')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        {!! Form::label('images','Product Image',['class'=>'col-md-4 col-form-label text-md-end']) !!}

                        <div class="col-md-6">
                            {!! Form::file('images[]', ['multiple' => true, 'accept' => 'image/*']) !!}
                            <br />
                            @error('images')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="image row">
                        @foreach($product->media as $photo)
                        <div class="col-md-3" id="media_{{ $photo->id }}">
                            <div class="img-wrap" style="position: relative; height:200px; width:200px;">
                                <span class="close delete_image" onclick="deleteMedia({{ $photo->id }})" data-id="{{ $photo->id }}" style="position: absolute;top: 2px; right: 2px;z-index: 100;">&times;</span>
                                {{ $photo->img()->attributes(['class' => 'my-class','height' => '200', 'weight' => '200']) }}
                            </div>
                        </div>
                        @endforeach

                    </div>
                    <br />
                    <br />
                    <div class="row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                Submit
                            </button>
                            <a href="{{ route('products.index')}}" class="btn btn-primary">
                                Cancel
                            </a>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
<script type="text/javascript" src="https://code.jquery.com/jquery-1.7.1.min.js"></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function deleteMedia(id) {
        $.ajax({
            url: '/media/' + id,
            type: 'DELETE',
            dataType: 'JSON',
            data: {
                'media': id,
                '_token': '{{ csrf_token() }}',
            },
            success: function() {
                var divid = "#media_" + id;
                $(divid).remove();
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        })
    }
</script>