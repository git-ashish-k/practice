@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Brand Form') }}</div>
                <div class="card-body">
                    {!! Form::model($brand,array('method' => ($type=='edit')?'patch':'post','route' => array($action,$action_id??""),'id' => "brand_form")) !!}
                    <div class="row mb-3">
                        {!! Form::label('name','Name',['class'=>'col-md-4 col-form-label text-md-end']) !!}

                        <div class="col-md-6">
                            {!! Form::text('name',$brand->name,['class'=>'form-control']) !!}
                            <span class="text-danger name_error" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                    <div class="row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                Submit
                            </button>
                            <a href="{{ route('brands.index')}}" class="btn btn-primary">
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
    $(function() {
        $(document).on('keyup', '#name', function() {
            var name = $(this).val();

            $.ajax({
                type: 'get',
                url: '/checkbrand/' + name,
                dataType: 'json', //return data will be json
                success: function() {
                    $('.name_error').html('');
                },
                error: function() {
                    $('.name_error').html('The name has already been taken.');
                }
            });
        });

        $("#brand_form").on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: new FormData(this),
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function() {
                    $(document).find('span.text-danger').text('');
                },
                success: function(data) {
                    var url = "{{ route('brands.index') }}"; //the url I want to redirect to
                    window.location.href = url;
                },
                error: function(data) {
                    if (data.status === 422) {
                        var errors = $.parseJSON(data.responseText);
                        $.each(errors, function(key, value) {
                            if (value.name) {
                                $('span.name_error').text(value.name[0]);
                            }
                        });
                    }
                }

            });
        });
    });
</script>