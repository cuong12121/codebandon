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

    <style>
    body {
      font-family: Arial, sans-serif;
    }
    /* Nút mở popup */
    .open-popup {
      padding: 10px 20px;
      background: #007bff;
      color: white;
      border: none;
      cursor: pointer;
      border-radius: 4px;
    }

    /* Overlay mờ */
    .popup-overlay {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.5);
      z-index: 999;
    }

    /* Popup */
    .popup {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: #fff;
      padding: 20px;
      width: 400px;
      max-height: 400px;
      overflow-y: auto;
      border-radius: 8px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }

    /* Đóng */
    .close-popup {
      float: right;
      font-size: 18px;
      cursor: pointer;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    th, td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: center;
    }

    th {
      background: #f2f2f2;
    }
  </style>
</head>
<?php

$redis = new Redis();


$redis->connect('127.0.0.1', 6379);
$key_redis_push = 'order_packed_'.$id; // hoặc 'order_packed_' . $orderId nếu bạn có ID đơn hàng
$keyExists = $redis->exists($key_redis_push);
?>


<body>
    @if(!$keyExists)

     @if (session('success'))
        <div class="alert alert-success">
            <span style="color:green;">{{ session('success') }}</span>
        </div>
    @endif
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

        <input type="hidden" name="id" value="{{ $id }}">

        <button type="submit">Bắn</button>
    </form>
    @endif
    <br>

   
    @if($keyExists)
        <button type="submit" style="background-color: green;">Kiểm kê  đã hoàn tất</button>
    
    @else
    <form id="confirm" method="post" action="{{ route('update-ton-in') }}">
        @csrf
        <input type="hidden" name="id" value="{{ $id }}">
        <input type="hidden" name="key_cache" value="{{ $cache_key }}">
        <input type="hidden" name="warehouse_id" value="{{ $warehouse_id }}">
        <button type="submit" style="background-color: red;">Xác nhận hoàn thành</button>
    </form>

    @endif
    <br>
    <a href="{{ route('show-print') }}"><h3>Danh sách in </h3></a>

    <button class="open-popup" onclick="openPopup()">Xem tồn kho</button>

    <!-- Overlay + Popup -->
    <div class="popup-overlay" id="popupOverlay">
      <div class="popup">
        <span class="close-popup" onclick="closePopup()">&times;</span>
        <h3>Danh sách SKU tồn kho</h3>
        <table>
          <thead>
            <tr>
              <th>SKU</th>
              <th>Số lượng tồn</th>
            </tr>
          </thead>
          <tbody id="stockTableBody">
            <!-- Dữ liệu được thêm bằng JS -->
          </tbody>
        </table>
      </div>
    </div>
    <?php 
        $gio = $_GET['house_id'];
        $created_time = $_GET['created_time'];
        $kho_define = $_GET['warehouse_id'];
    ?>

    <h1>Danh sách sản phẩm in  {{ $kho_array[$kho_define] }}  khung {{ $gio_array[$gio] }} ngày {{ $created_time }}  </h1>

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
                $data_full = [];
                $dem=0;
                
                $data_json = $redis->get('sku_data_'.$id);
                $datass = $data_json ? json_decode($data_json, true) : [];
               

                foreach ($datass as $sku => $item) {
                    
                    // // Kiểm tra nếu có sku_replace và sku_replace tồn tại trong keys
                    if (!empty($item['sku_replace']) ) {
                        echo "<span style='color: red;''>SKU {$item['sku_replace']} đang thay thế cho SKU {$item['sku']} với số lượng {$item['quantity']}</span><br>";
                    }
                }
                $out_stock = [];


               

           ?> 
            @foreach($data as $value)
            <?php 
                $dem++;
                $sku = $value['sku'].'-'.$value['color'].'-'.$value['size'];

               
                $result_push = !empty($datass[$sku]['quantity'])?$datass[$sku]['quantity']:0;

                if(empty($item_total[$sku]) || $item_total[$sku]==0){
                    array_push($out_stock, $sku);
                    $status = '<span style="color: red;">Hết hàng</span>';
                }
                else{
                    if(intval($result_push)===intval($itemSummary[$sku])){
                        array_push($data_full, $sku);
                        // lấy sku nào đã bắn xong để không cho bắn nữa

                        $status = '<span style="color: green;">Đã bắn xong</span>';
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
                <td> {!! $status !!} </td>
                
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>

<script>



const disabledSkus = <?php echo json_encode($data_full); ?>;

document.getElementById('skuForm').addEventListener('submit', function(e) {
    const skuInput = document.getElementById('sku');
    const sku = skuInput.value.trim();
    const quantityInput = document.getElementById('quantity');
    
    const itemSummary = <?php echo json_encode($itemSummary); ?>;

    const out_stock = <?php echo json_encode($out_stock); ?>

    const quantity = parseInt(quantityInput.value.trim());

    if (itemSummary.hasOwnProperty(sku)) {
        const maxQuantity = itemSummary[sku];

        if (quantity > maxQuantity) {
            alert("Số lượng sản phẩm đóng lớn hơn số lượng sản phẩm in");
            e.preventDefault();
        } 
    } 
    else{
        isValid = false;
        alert(`SKU "${sku}" không tồn tại trong file in này.`);
        e.preventDefault();
    }

    if (disabledSkus.includes(sku)) {
        isValid = false;
        alert(`SKU "${sku}" đã bắn đủ số lượng, không thể bắn thêm, vui lòng kiểm tra lại.`);
        e.preventDefault(); // Chặn form submit
    }
    if(out_stock.includes(sku)){
         isValid = false;
        alert(`SKU "${sku}" bắn bị sai mã, sản phẩm đã hết hàng.`);
        e.preventDefault(); // Chặn form submit
    }

}); 

document.getElementById('sku_replace').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault(); // Chặn không cho form submit
    }
});

document.getElementById('confirm').addEventListener('submit', function(e) {
    if (!confirm('Bạn muốn hoàn thành chứ, sẽ trừ tồn ngay các sản phẩm đã bắn xong ?')) {
        e.preventDefault(); // Chặn submit nếu người dùng bấm Cancel
    }
});
</script>  


<script>


    const stockData = @json(
        $data_redis
    ) 


  function openPopup() {
    document.getElementById('popupOverlay').style.display = 'block';
    const tbody = document.getElementById('stockTableBody');
    tbody.innerHTML = '';
    stockData.forEach(item => {
      const row = `<tr>
        <td>${item.sku}</td>
        <td style="color: ${item.qty === 0 ? 'red' : 'black'}">${item.qty}</td>
      </tr>`;
      tbody.innerHTML += row;
    });
  }

  function closePopup() {
    document.getElementById('popupOverlay').style.display = 'none';
  }  


</script>

</body>
</html>
