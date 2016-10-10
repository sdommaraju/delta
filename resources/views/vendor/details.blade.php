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
			.text {
				font-size : 12px;
				margin-top:10px; 
			}
			.jobDetails {
				font-size: 12px;
				border: 1px solid black;
				width:600px;
				border-collapse: collapse;
			}
			.jobDetails td,th{
				padding:10px;
				border: 1px solid black;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="content">
				<div class="title">Hello <strong>{{$vendor->first_name}} {{$vendor->last_name}} </strong></div>
				<div class="quote">Welcome to <strong>{{$agency->name}}</strong>.<br></div>
				<div class="text">We Have Openings for <strong>"{{$job->title}}"</strong> position. Please see the below details.</div>
				<div>&nbsp;</div>
				<table class="jobDetails">
					<tr>
						<th>Title</th>
						<td>{{$job->title}}</td>
						<th>Position Type</th>
						<td>{{$job->position_type}}</td>
					</tr>
					<tr>
						<th>Billing Rate</th>
						<td>{{$job->bill_rate}}</td>
						<th>Pay Rate</th>
						<td>{{$job->pay_rate}}</td>
					</tr>
					<tr>
						<th>Start Date</th>
						<td>{{$job->start_date}}</td>
						<th>End Date</th>
						<td>{{$job->end_date}}</td>
					</tr>
					<tr>
						<th colspan="4" class="quote" align="center">Client Details</th>
					</tr>
					<tr>
						<th>Client Name</th>
						<td>{{$job->client_name}}</td>
						<th>Contact Person</th>
						<td>{{$job->contact_name}}</td>
					</tr>
					<tr>
						<th>Contact Email</th>
						<td>{{$job->contact_email}}</td>
						<th>Location</th>
						<td>{{$job->contact_location}}</td>
					</tr>
					<tr>
						<th colspan="4" class="quote"  align="center">Job Description</th>
					</tr>
					<tr>
						<td colspan="4" class="quote">{{$job->description}}</td>
					</tr>
				</table>
			</div>
		</div>
	</body>
</html>
