<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Laravel 4 Queues</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css" rel="stylesheet">
    {{ HTML::script('js/script.js') }}
</head>

<body>
<div class="container">
    <h1>Laravel 4 Queues</h1>
    <p>
        <a href="#" class="btn btn-danger btn-large">Start background job <i class="icon-play-sign"></i></a>
    </p>

    <div id="status-section" class="hide">
    	{{-- Progress bar --}}
	    <div class="progress progress-striped progress-success active">
	    	<div class="bar" style="width: 0%;"></div>
		</div>

    	{{-- Status message --}}
		<div class="alert alert-success"></div>
	</div>
</div>
</body>
</html>
