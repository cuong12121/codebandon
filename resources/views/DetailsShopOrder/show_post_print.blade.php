<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách sản phẩm</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        h1 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <h1>Danh sách file in</h1>

    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Khung</th>
                <th>Sàn</th>
                <th>Kho</th>
                <th>Ngày</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                
                $dem = 0;
                $redis = new Redis();

                $redis->connect('127.0.0.1', 6379);
            ?>
            @foreach($data as $value)
            <?php 
                $dem++;
                $datePart = explode(' ', $value['created_time'])[0];


                $key_redis_push = 'order_packed_'.$value['id']; // hoặc 'order_packed_' . $orderId nếu bạn có ID đơn hàng
                $keyExists = $redis->exists($key_redis_push);
            ?>
            <tr>
                <td>{{ $dem }}</td>
                <td>{{ $gio_array[$value['house_id']] }}</td>
                <td>{{ $san_array[$value['platform_id']] }}</td>
                <td>{{ $kho_array[$value['warehouse_id']] }}</td>
                <td>{{ $datePart }}</td>
                <td>{{ $keyExists?'Đã kiểm kê':'Chưa kiểm kê'  }}</td>
                <td>
                    <a href="{{ route('show-print', $value['id']) }}?platform_id={{ $value['platform_id'] }}&warehouse_id={{ $value['warehouse_id'] }}&house_id={{ $value['house_id'] }}&created_time={{ $datePart }}">Duyệt hàng</a> 
                
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
