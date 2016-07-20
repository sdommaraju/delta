@extends('agency')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Account Activation</div>
				<div class="panel-body">
				<ul style="color:red">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

				@if (isset($email))
					{!! Form::open(array('route' => 'account.activation.update', 'class' => 'form')) !!}
						{!! Form::hidden('access_token',$access_token) !!}
                        <div class="form-group">
                            {!! Form::label('email', 'Email:') !!}
                            {!! Form::text('email', $email, ['class' => 'form-control-field','disabled'=>true]) !!}
                        </div>
                         <div class="form-group">
                            {!! Form::label('password', 'Password:') !!}
                            {!! Form::password('password',['class' => 'form-control-field']) !!}
                        </div>
                         <div class="form-group">
                            {!! Form::label('confirm_password', 'Confirm Password:') !!}
                            {!! Form::password('password_confirmation',['class' => 'form-control-field']) !!}
                        </div>
                        
                        <div class="form-group">
                        	{!! Form::submit('Submit',array('class'=>'btn btn-primary')) !!}
                        </div>
                        {!! Form::close() !!}
				@else
					{{$message}}
				@endif
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
