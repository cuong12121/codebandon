<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách sản phẩm</title>
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
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $gio_array = [
                    0 => "-- Giờ --",
                    1 => "7H",
                    2 => "18H15",
                    3 => "8H",
                    4 => "8H30",
                    5 => "10H",
                    6 => "11H",
                    7 => "13H",
                    8 => "15H30",
                    9 => "9H",
                    10 => "14H",
                    11 => "15H",
                    12 => "16H",
                    13 => "7H10", // Value 13 tương ứng với 7H10
                    14 => "9H30", // Value 14 tương ứng với 9H30
                    15 => "14H30",
                    16 => "12H30",
                    17 => "22H",
                    18 => "12H40",
                    19 => "17H",
                    20 => "16H20",
                    21 => "18H"
                ];
                $kho_array = [
                    0 => "-- Kho --",
                    1 => "Kho Hà Nội",
                    2 => "Kho Hồ Chí Minh",
                    4 => "Kho hàng Cao Duy Hoan",
                    6 => "Kho Văn La",
                    7 => "Kho Văn Phú"
                ];

                $san_array = [
                    0 => "-- Sàn --",
                    1 => "Lazada",
                    2 => "Shopee",
                    3 => "Tiki",
                    4 => "Lex ngoài HCM",
                    6 => "Đơn ngoài",
                    8 => "Best",
                    9 => "Ticktok",
                    10 => "Viettel",
                    11 => "Shopee ngoài",
                    12 => "Giao hàng tiết kiệm",
                    13 => "Giao hàng nhanh"
                ];
                                $dem = 0;
            ?>
            @foreach($data as $value)
            <?php 
                $dem++;
                $datePart = explode(' ', $value['created_time'])[0];
            ?>
            <tr>
                <td>{{ $dem }}</td>
                <td>{{ $gio_array[$value['house_id']] }}</td>
                <td>{{ $san_array[$value['platform_id']] }}</td>
                <td>{{ $kho_array[$value['warehouse_id']] }}</td>
                <td>Đủ hàng</td>
                <td>
                    <a href="{{ route('show-print', $value['id']) }}?platform_id={{ $value['platform_id'] }}&warehouse_id={{ $value['warehouse_id'] }}&house_id={{ $value['house_id'] }}&created_time={{ $datePart }}">Duyệt hàng</a> 
                
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
