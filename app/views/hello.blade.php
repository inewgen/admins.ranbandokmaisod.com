<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Laravel PHP Framework</title>
	<style>
		@import url(//fonts.googleapis.com/css?family=Lato:700);

		body {
			margin:0;
			font-family:'Lato', sans-serif;
			text-align:center;
			color: #999;
		}

		.welcome {
			margin: 0 auto;
		}

		a, a:visited {
			text-decoration:none;
		}

		h1 {
			font-size: 32px;
			margin: 16px 0 0 0;
		}
	</style>
</head>
<body>
	<div class="welcome">
		<a href="{{$web_url}}" title="ranbandokmaisod"><img src="{{$web_image}}" alt="ranbandokmaisod"></a>
		<br/>
		@if(Session::has('message'))
			<p>{{Session::get('message')}}</p>
		@endif

		@if(empty($data))
			<h1>Hello</h1>
			{{HTML::link('/login/fb', 'Login with facebook..')}}
		@else
			<h1>Hello {{$data->name}}</h1>
			<img src="{{$data->photo}}" alt=""/>
			<p>{{$data->email}}</p>
			{{HTML::link('/logout', 'Logout')}}
		@endif
		<h1>You have arrived.</h1>
	</div>
</body>
</html>
