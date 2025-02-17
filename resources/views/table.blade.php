<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Update Giá </title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>


        <style type="text/css">
            .modal-content{
                width: auto;
            }

            .modal-dialog {
                max-width: none !important;
                
            }

            input{
                width: 100px;
            }
            td{
                width: 100px;
            }
            table{
                width: auto !important;
            }

            #modal-price{
                width: 59%;
            }

            /* Absolute Center Spinner */
            .loading {
              position: fixed;
              z-index: 999;
              height: 1em;
              width: 2em;
              overflow: show;
              margin: auto;
              top: 0;
              left: 0;
              bottom: 0;
              right: 0;
            }

            /* Transparent Overlay */
            .loading:before {
              content: '';
              display: block;
              position: fixed;
              top: 0;
              left: 0;
              width: 100%;
              height: 100%;
              background-color: rgba(0,0,0,0.3);
            }

            /* :not(:required) hides these rules from IE9 and below */
            .loading:not(:required) {
              /* hide "loading..." text */
              font: 0/0 a;
              color: transparent;
              text-shadow: none;
              background-color: transparent;
              border: 0;
            }

            .loading:not(:required):after {
              content: '';
              display: block;
              font-size: 10px;
              width: 1em;
              height: 1em;
              margin-top: -0.5em;
              -webkit-animation: spinner 1500ms infinite linear;
              -moz-animation: spinner 1500ms infinite linear;
              -ms-animation: spinner 1500ms infinite linear;
              -o-animation: spinner 1500ms infinite linear;
              animation: spinner 1500ms infinite linear;
              border-radius: 0.5em;
              -webkit-box-shadow: rgba(0, 0, 0, 0.75) 1.5em 0 0 0, rgba(0, 0, 0, 0.75) 1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) 0 1.5em 0 0, rgba(0, 0, 0, 0.75) -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.5) -1.5em 0 0 0, rgba(0, 0, 0, 0.5) -1.1em -1.1em 0 0, rgba(0, 0, 0, 0.75) 0 -1.5em 0 0, rgba(0, 0, 0, 0.75) 1.1em -1.1em 0 0;
              box-shadow: rgba(0, 0, 0, 0.75) 1.5em 0 0 0, rgba(0, 0, 0, 0.75) 1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) 0 1.5em 0 0, rgba(0, 0, 0, 0.75) -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) -1.5em 0 0 0, rgba(0, 0, 0, 0.75) -1.1em -1.1em 0 0, rgba(0, 0, 0, 0.75) 0 -1.5em 0 0, rgba(0, 0, 0, 0.75) 1.1em -1.1em 0 0;
            }

            /* Animation */

            @-webkit-keyframes spinner {
              0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
              }
              100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
              }
            }
            @-moz-keyframes spinner {
              0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
              }
              100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
              }
            }
            @-o-keyframes spinner {
              0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
              }
              100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
              }
            }
            @keyframes spinner {
              0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
              }
              100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
              }
            }

        </style>
    </head>
    <body>

        <div class="append-product">
            <h2>sản phẩm không trùng model</h2>
            
        </div>
        <div>

            @foreach($define_page as $value)

            <a href="{{ route('table') }}?range={{ $value }}">bảng giá {{ $value  }}</a><br>

            @endforeach

            <h2>Bảng Giá {{ @$define_product[$id_page] }} {{ $page  }}</h2>

             <div>
                <a href="javascript:void(0)" onclick="checkproduct()">kiểm tra model sản phẩm</a>
            </div>
            

            <div>
                <a href="javascript:void(0)" onclick="updatePriceAll()">Đẩy giá sang  website</a>
            </div>

            <?php 

                
                $id_name = 1;

                if($id_page == 4){

                    if($page ==='Sanaky'){

                         $id_name =2;

                    }
                    elseif($page ==='kangaroo'){
                        $id_name =3;
                    }
                    else{
                        $id_name =4;
                    }

                }

               
            ?>
            
            <table class="table">
                <thead>
                    <tr>
                        <th>stt</th>

                        @foreach($values[$id_name] as $val)
                        <th>{{ $val }}</th>
                        @endforeach
                        
                        <th>Giá sp sẽ lấy </th>

                        <th>Giá  sp sau khi chạy</th>

                        <th>Kiểm tra</th>
                        
                    </tr>
                </thead>
                <tbody>

                    <?php 

                        $data =[];

                        $z = 0;
                    ?>

                    @for($k=2; $k<count($values); $k++)
                        @if(!empty($values[$k][$selected]))
                        <tr>
                            <?php 

                                $z++;
                            ?>

                            <td>{{ $z }}</td>
                            
                            @foreach($values[$k] as $vals)
                            
                            
                            <?php 

                                $data[$z]['model'] = @$values[$k][1];

                                $data[$z]['price'] = @$values[$k][$selected];


                            ?>

                            <td>{{ $vals }}</td>

                            @endforeach

                            <td><a href="javascript:void(0)" id="start_price_{{ $z }}">{{ @$values[$k][$selected]  }}</a></td>
                           
                            <td id="end_price_{{ $z }}"></td>

                            <td id="check_{{ $z }}"></td>
                            
                        </tr>
                        @endif
                    @endfor
                  
                </tbody>

            </table>

            <div class="loading">Loading&#8230;</div>

        </div>




        <div class="append"></div>

        <script type="text/javascript">

            $('.loading').hide();
            function formatMoney(number) {
              return number.toLocaleString('vi', { style: 'currency', currency: 'VND' });
            }
            
            function updatePriceAll() {

                data = '{!! json_encode($data) !!}';

                data = JSON.parse(data);


                var fals = [];

                $('.loading').show();
              
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            
                $.ajax({
                    type: 'POST',
                    url: "{{ route('update-price-all') }}",
                    data: {
                        data:data,
                           
                    },
                    success: function(result){

                       
                        const data = JSON.parse(result);

                        for (i = 1; i<= {{ count($data) }}; i++) {

                            if(data[i].price != null){

                                $('#end_price_'+i).text(formatMoney(data[i].price));

                                price_compare = parseInt($('#start_price_'+i).text().replaceAll('.', ''));

                                price_update  = parseInt(data[i].price);

                                if(data[i].price===''){

                                    price_update ='';
                                }

                                

                                if(price_compare === price_update){

                                    $('#check_'+i).html('<span style="color:green">Đ</span>');

                                }
                                else{
                                    if(price_update===''){

                                        $('#check_'+i).text('');

                                    }
                                    else{
                                        $('#check_'+i).html('<span style="color:red">S</span>');
                                    }
                                    
                                }

                            }
                            else{
                                fals.push(data[i].model);
                               
                            }
                            
                        }

                        if(fals.length>0){
                            alert(fals);
                        }

                        $('.loading').hide();
                        
                    }
                       
                });
                
            }

            function checkproduct() {

                $('.loading').show();

                data = '{!! json_encode($data) !!}';

                data = JSON.parse(data);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            
                $.ajax({
                    type: 'POST',
                    url: "{{ route('checkproduct') }}",
                    data: {
                        data:data,
                           
                    },
                    success: function(result){

                        $('.loading').hide();

                        $('.append-product').append(result);

                    }   
                       
                });
               
            }

        </script>

       
        
    </body>
</html>