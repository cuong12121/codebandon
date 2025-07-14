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

        body {
      font-family: Arial, sans-serif;
      padding: 40px;
      background-color: #f5f5f5;
    }

    .form-container {
      background: #fff;
      padding: 20px 30px;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      max-width: 400px;
      margin: auto;
    }

    .form-group {
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-bottom: 6px;
      font-weight: bold;
    }

    input[type="text"],
    input[type="number"] {
      width: 100%;
      padding: 8px 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    button {
      padding: 10px 20px;
      background-color: #007bff;
      border: none;
      color: white;
      font-size: 16px;
      border-radius: 6px;
      cursor: pointer;
    }

    button:hover {
      background-color: #0056b3;
    }

    .result {
      margin-top: 20px;
      font-size: 16px;
      color: green;
    }
    </style>
</head>
<body>
        <form id="skuForm">
            <div class="form-group">
              <label for="sku">SKU</label>
              <input type="text" id="sku" name="sku" required autofocus>
            </div>

            <div class="form-group">
              <label for="quantity">Số lượng</label>
              <input type="number" id="quantity" name="quantity" value="1" min="1" required>
            </div>

            <button type="submit">Bắn</button>
        </form>
    <h1>Danh sách sản phẩm</h1>

    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Sku</th>
                <th>Số lượng</th>
                <th>Tồn</th>
                <th>Số lượng đã bắn</th>
                <th>Trạng thái</th>
               
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>7h30</td>
                <td>1</td>
                <td>1</td>
                <td>1</td>
                <td>Chưa bắn xong</td>
                
            </tr>
            
        </tbody>
    </table>

</body>
</html>
