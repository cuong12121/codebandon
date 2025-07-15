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
    <form id="skuForm" method="post" action="{{ route('push-sku') }}">
        @csrf
        <div class="form-group">
          <label for="sku">SKU</label>
          <input type="text" id="sku" name="sku" required autofocus>
        </div>

        <div class="form-group">
          <label for="quantity">Số lượng</label>
          <input type="number" id="quantity" name="quantity" value="1" min="1" required>
        </div>

        <div class="form-group">
          <label for="quantity">Sku thay thế</label>
          <input type="text" id="sku_replace" name="sku_replace" value="" min="">
        </div>

        <button type="submit">Bắn</button>
    </form>
    <br>

    <form id="confirm" method="post" action="{{ route('push-sku') }}">
        @csrf
        
        <button type="submit" style="background-color: red;">Xác nhận hoàn thành</button>
    </form>
    <br>
    <a href="{{ route('show-print') }}"><h3>Danh sách in </h3></a>


    <h1>Danh sách sản phẩm</h1>

    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Sku</th>
                <th>Số lượng file in</th>
                <th>Số lượng tồn</th>
                <th>Số lượng đã bắn</th>
                <th>Trạng thái</th>
               
            </tr>
        </thead>
        <tbody>
            @if(!empty($data) && count($data)>0)
            <?php 

                $dem=0;
                $redis = new Redis();
                $redis->connect('127.0.0.1', 6379);
                $data_json = $redis->get('sku_data');
                $datass = $data_json ? json_decode($data_json, true) : [];


                $needPrint = array_filter(
                    $datass,
                    function ($item) use ($datass) {
                        $replace = $item['sku_replace'] ?? '';
                        return !empty($replace) && isset($datass[$replace]);
                    }
                );
                if (!empty($needPrint)) {
                    echo "<h3>Danh sách thay thế sản phẩm:</h3>";
                    echo "<ul>";

                    foreach ($datass as $sku => $item) {
                        if (!empty($item['sku_replace'])) {
                            echo "<li>SKU: {$item['sku']} thay thế bằng: {$item['sku_replace']} — Số lượng: {$item['quantity']}</li>";
                        }
                    }
                    echo "</ul>";
                }    

           ?> 
            @foreach($data as $value)
            <?php 
                $dem++;
                $sku = $value['sku'].'-'.$value['color'].'-'.$value['size'];
                $result_push = !empty($datass[$sku]['quantity'])?$datass[$sku]['quantity']:0;

                if($item_total[$sku]==0){
                    $status ="Hết hàng";
                }
                else{
                    if(intval($result_push)===intval($itemSummary[$sku])){
                        $status = 'Đã bắn xong';
                    }
                    else{
                        $status = 'Chưa bắn xong';
                    }
                }
                
            $data_push    
            ?>
            <tr>
                <td>{{ $dem }}</td>
                <td> {{ $sku }}</td>
                <td>{{ $value['count'] }}</td>
                <td>{{ !empty($sku_quantity[$sku])?$sku_quantity[$sku]:0 }}</td>
                <td>{{ $result_push }} Tổng in:{{ $itemSummary[$sku] }}</td>
                <td> {{ $status }} </td>
                
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
<script>
document.getElementById('sku_replace').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault(); // Chặn không cho form submit
    }
});

document.getElementById('confirm').addEventListener('submit', function(e) {
    if (!confirm('Bạn muốn hoàn thành chứ?')) {
        e.preventDefault(); // Chặn submit nếu người dùng bấm Cancel
    }
});
</script>    

</body>
</html>
