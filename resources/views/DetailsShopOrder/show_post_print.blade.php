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

    <h1>Danh sách sản phẩm</h1>

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
            <tr>
                <td>1</td>
                <td>7h30</td>
                <td>Shopee</td>
                <td>VLA</td>
                <td>Đủ hàng</td>
                <td>
                    <a href="sua.html?id=1">Duyệt hàng</a> 
                
                </td>
            </tr>
            
        </tbody>
    </table>

</body>
</html>
