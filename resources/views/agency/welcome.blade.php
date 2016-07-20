<html>
	<head>
		<title>Laravel</title>
		
		<link href='//fonts.googleapis.com/css?family=Lato:100' rel='stylesheet' type='text/css'>

		<style>
			body {
				margin: 0;
				padding: 0;
				width: 100%;
				height: 100%;
				color: #B0BEC5;
				display: table;
				font-weight: 100;
				font-family: 'Lato';
			}

			.container {
				text-align: center;
				display: table-cell;
				vertical-align: middle;
			}

			.content {
				text-align: center;
				display: inline-block;
			}

			.title {
				font-size: 96px;
				margin-bottom: 40px;
			}

			.quote {
				font-size: 24px;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="content">
				<div class="title">Hello <strong>{{$first_name}} {{$last_name}}</strong><br></div>
				<div>&nbsp;</div>
				<div class="quote">Welcome to Delta.<br></div>
				<div>&nbsp;</div>
				<div class="quote">You Account is created and Associated with <strong>{{$agency_name}}</strong> agency.<br></div>
				<div>&nbsp;</div>
				<div class="quote">To manage your agency, please activate your account. Please <a href="{{$activation_link}}">click this link to activate your accout.</a><br></div>
				<div>&nbsp;</div>
				<div class="quote">Activation Link : <a href="{{$activation_link}}">{{$activation_link}}</a><br></div>
			</div>
		</div>
	</body>
</html>
