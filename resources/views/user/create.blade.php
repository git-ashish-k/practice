@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('User Form') }}</div>
                <div class="card-body">
                    {!! Form::model($user,array('method' => ($type=='edit')?'patch':'post','route' => array($action,$action_id??""),'id' => "user_form")) !!}
                    <div class="row mb-3">
                        {!! Form::label('name','Name',['class'=>'col-md-4 col-form-label text-md-end']) !!}

                        <div class="col-md-6">
                            {!! Form::text('name',$user->name,['class'=>'form-control']) !!}
                            @error('name')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message  }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        {!! Form::label('email','Email Address',['class'=>'col-md-4 col-form-label text-md-end']) !!}

                        <div class="col-md-6">
                            {!! Form::text('email',$user->email,['class'=>'form-control']) !!}
                            @error('email')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        {!! Form::label('password','Password',['class'=>'col-md-4 col-form-label text-md-end']) !!}

                        <div class="col-md-6">
                            {!! Form::input('password','password',null, ['id' => 'password', 'placeholder' => __('password'), 'class' => "form-control"]) !!}
                            @error('password')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        {!! Form::label('confirm','Confirm Password',['class'=>'col-md-4 col-form-label text-md-end']) !!}

                        <div class="col-md-6">
                            {!! Form::text('confirm',null,['class'=>'form-control']) !!}
                            @error('confirm')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        {!! Form::label('role_id','Role',['class'=>'col-md-4 col-form-label text-md-end']) !!}

                        <div class="col-md-6">
                            {!! Form::radio('role_id','1',false, ['class'=>'mx-1'] )!!} Admin
                            {!! Form::radio('role_id','2',false,['class'=>'mx-1'] ) !!} User
                            @error('role_id')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                Submit
                            </button>
                            <a href="{{ route('users.index')}}" class="btn btn-primary">
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