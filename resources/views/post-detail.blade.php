<!DOCTYPE html>
<html>
<head>
	<title>Article Submission Form</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

	<!-- jQuery library -->
	<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.slim.min.js"></script>

	<!-- Popper JS -->
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

	<script src="{{ asset('js/ckeditor/ckeditor.js') }}?ver=1"></script>


	<style type="text/css">
		form {
			display: flex;
			flex-direction: column;
			align-items: left;
			margin-top: 50px;
		}
		input[type="text"], textarea {
			width: 100%;
			padding: 12px 20px;
			margin: 8px 0;
			box-sizing: border-box;
			border-radius: 4px;
			border: 1px solid #ddd;
		}
		input[type="submit"] {
			background-color: #4CAF50;
			color: white;
			padding: 14px 20px;
			margin: 8px 0;
			border: none;
			border-radius: 4px;
			cursor: pointer;
		}
		input[type="submit"]:hover {
			background-color: #45a049;
		}
	</style>
</head>
<body>
	<div class="container">
		<form action="/submit-article" method="post">
			<label for="title">Title:</label>
			<input type="text" id="title" name="title"  value="{{ $data->title }}">
			
			<label for="content">Content:</label>
			<textarea id="content" name="content">{!! $data->content  !!}</textarea>

			<input type="submit" value="Submit">
		</form>
	</div>
	
</body>

<script type="text/javascript">
	CKEDITOR.replace('content');
</script>
</html>