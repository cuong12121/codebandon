<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</head>
<body>
    <?php 
        $data = DB::table('post_crawl')->paginate(10);

        $page =  !empty($_GET['page'])?intval($_GET['page']):0;

        $i = !empty($page)?($page-1)*10:0;

    ?>
	<table class="table">
        <thead>
            <tr>
                <th>stt</th>
                <th></th>
                <th>Bài viết</th>
                <th>Xem bài viết</th>
                
                <th>Ngày crawl</th>
                <th>Ngày update</th>
                
                
            </tr>
        </thead>
        <tbody>
            @if(!empty($data)&& $data->count()>0)
            @foreach($data as  $val)
            <?php 

                $i++;
            ?>
            <tr>
                <td>{{ $i }}</td>
                <td></td>
                <td>{{ $val->title }}</td>
                <td><a href="{{ route('details', $val->id) }}">xem</td>

                <td>{{  \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $val->created_at)->format('d/m/Y')  }}</td>
                <td>{{  \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $val->updated_at)->format('d/m/Y')   }}</td>
                
            </tr>
            @endforeach
            @endif
            
        </tbody>
    </table>

    {{ $data->links() }}
	
</body>
</html>