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
				text-align: left;
				display: table-cell;
				vertical-align: middle;
			}

			.content {
				text-align: left;
				display: inline-block;
			}

			.title {
				font-size: 14px;
				margin-bottom: 40px;
			}

			.quote {
				font-size: 12px;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="content">
				<div class="title">Hello <strong>{{$team_lead_first_name}} {{$team_lead_last_name}}</strong><br></div>
				<div>&nbsp;</div>
				<div class="quote">You have created a new candidate with name : <strong>{{$candidate_first_name}} {{$candidate_last_name}}</strong> .<br></div>
				<div>&nbsp;</div>
				<div class="quote">Candidate ID : <strong>{{$candidate_id}}</strong><br></div>
			</div>
		</div>
	</body>
</html>
