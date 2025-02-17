<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<?php 
    $mien = (Session()->get('admin')[0])==='adminmn'?'Miền Nam':'Miền Bắc'; 

    $kt= $_GET['kt']??'';

    if(!empty($kt)){
        echo "Tồn tại bảo trì";
    }
?>
<body>
	
    <!-- <a href="{{ route('tracking') }}"><h3>tracking</h3></a>   -->

    <a href="{{ route('logout') }}">đăng xuất</a>   

    <h1>Hệ thống tạm thời bảo trì, xin quay lại sau</h1>
    <div class="modal-menu-full-screen"></div>
    <div id="wrapper" @if(empty($kt)) style="display: none;" @endif>
       
        <div class="clear"></div>
        
        <div id="page-wrapper" class="page-wrapper-small" style="min-height: 777px;">
            <div class="form_head">
                <div id="wrap-toolbar" class="wrap-toolbar">
                    <div class="fl">
                        <h1 class="page-header">Hệ thống quản lý nội dung (CMS) {{ $mien }}</h1>
                        <!--end: .page-header -->
                        <!-- /.row -->    
                    </div>
                    <div class="clearfix"></div>
                </div>
                <!--end: .wrap-toolbar-->
            </div>
            
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
           
            <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
           
            <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
           
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
            <style type="text/css">
                table, th, td{
                    border:1px solid #868585;
                }
                table{
                    border-collapse:collapse;
                    width:100%;
                }
                th, td{
                    text-align:left;
                    padding:10px;
                }
                table tr:nth-child(odd){
                    background-color:#eee;
                }
                table tr:nth-child(even){
                    background-color:white;
                }
                table tr:nth-child(1){
                    background-color:skyblue;
                }
                .form-search{
                    display: flex;
                }
                #tags{
                    margin-right: 15px;
                    height: 30px;
                    font-size: 20px;
                }
                .return a{
                    color: red !important;
                }
                @media only screen and (min-width: 601px) {
                    .mobile{
                        display: none;
                    }
                }        
                @media only screen and (max-width: 600px) {
                    #tags {
                        margin-right: 15px;
                        height: 50px;
                        font-size: 26px;
                    }
                }
            </style>

            
            <div class="form-search">
                <form class="header__search" method="get" action="{{ route('post order') }}" style="display: flex; margin-bottom: 15px;">
                    <input type="text" class="input-search ui-autocomplete-input" id="tags" name="search" autocomplete="off" maxlength="100" required="" wfd-id="id0" autofocus=""> 
                    <input type="hidden" name="active" value="1">    
                    <button type="submit">Bắn đơn </button> 
                </form>
            </div>
            <br>
         

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <div class="form-search">
                <form class="header__search" method="get" action="{{ route('user.packaging')  }}" style="display: flex; margin-bottom: 15px;">
                    <select name="name">
                        <option value="0">Tên người dùng</option>
                        <option value="252">PHUONGDGMB</option>
                        <option value="253">LANDGMB</option>
                        <option value="254">LOANDGMB</option>
                        <option value="255">CANHDGMB</option>
                        <option value="256">TRANGDGMB</option>
                        <option value="9">admin</option>
                        <option value="257">HAIDGMB</option>
                        <option value="258">ANHDGMN</option>
                        <option value="259">THOADGMN</option>
                        <option value="260">THUDGMN</option>
                    </select>
                    <label>từ</label>
                    <input type="date" class="input-search ui-autocomplete-input" name="date1" autocomplete="off" maxlength="100" required=""> 
                    <label>đến</label>
                    <input type="date" class="input-search ui-autocomplete-input" name="date2" autocomplete="off" maxlength="100" required=""> 
                    <input type="checkbox" name="options" value="1"> xuất file excel<br>
                    <button type="submit">Tìm kiếm </button> 
                </form>
                &nbsp;
                <a href="{{ route('dong don') }}"><button>reset</button></a>
            </div>
            <h2>Danh sách đơn đã đóng mới nhất của  admin</h2>
            <table class="table-responsive">
                <tbody>
                    <tr>
                        <th>STT</th>
                        <th>Tracking code</th>
                        <th>Tên sản phẩm </th>
                        <th>Tên shop</th>
                        <th>Mã shop</th>
                        <th>Số lượng</th>
                        <th>Id đơn hàng</th>
                        <th>Người đánh đơn</th>
                        <th>Ngày đánh đơn</th>
                        <th>Thời gian đóng đơn hàng</th>
                        <th>Thành tiền</th>
                        <th>Hoàn đơn</th>
                    </tr>

                    <?php 
                        date_default_timezone_set('Asia/Ho_Chi_Minh');
                        $now = date("d/m/Y");
                        $dem =0;

                        $info_user["252"]='PHUONGDGMB';
                        $info_user["253"]='LANDGMB';
                        $info_user["254"]='LOANDGMB';
                        $info_user["255"]='CANHDGMB';
                        $info_user["256"]='TRANGDGMB';
                        $info_user["9"]='admin';
                        $info_user["257"]='HAIDGMB';
                        $info_user["258"]='ANHDGMN';
                        $info_user["259"]='THOADGMN';
                        $info_user["260"]='THUDGMN';

                      
                    ?>
                    @if(!empty($info_data))
                    @foreach($info_data as $key => $value)
                    <?php

                        $dem++;
                    ?>
                    <tr>
                        <td>
                            {{ $dem }}              
                            <div class="mobile">
                                <a href="{{ route('post order') }}?active=0&id={{ $value->id }}">Hoàn đơn</a>
                            </div>
                        </td>
                       <td><?= $value->tracking_code  ?></td>
                        <td><?= $value->product_name  ?></td>
                        <td><?= $value->shop_name  ?></td>
                        <td><?= $value->shop_code  ?></td>
                        <td><?= $value->count  ?></td>
                        <td><?= $value->record_id  ?></td>
                        <td>{{ !empty($value->user_package_id)?$info_user[$value->user_package_id]:'chưa đóng đơn' }}</td>
                        <?php  
                            $date_time_package = date("d/m/Y,H:i:s", strtotime($value->date_package)); 
                            $date_package = date("d/m/Y", strtotime($value->date_package));
                        ?>
                        <td><?= date("d/m/Y", strtotime($value->date));  ?></td>
                        <td> {{ @$date_time_package  }}</td>
                        <td><?=  number_format((float)$value->total_price, 0, ',', '.') ?>đ</td>
                        <td class="return">
                            
                            @if($now=== $date_package)
                                
                            <a href="{{ route('post order') }}?active=0&id={{ $value->id }}">Hoàn đơn</a>

                            
                            @endif
                           
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>

                {{ $info_data->links() }}
            </table>
            
        </div>
        <!-- /#page-wrapper -->
    </div>
    <div class="clearfix"></div>
   
    <div class="go-top scrollToTop" style="display: none;">
        <i class="fa fa-arrow-circle-up"></i>
    </div>
   
    <script type="text/javascript" src="https://dienmayai.com/admin/templates/default/js/jquery-confirm.min.js"></script>
    <script type="text/javascript" src="https://dienmayai.com/admin/templates/default/js/select2.min.js"></script>
    <script type="text/javascript" src="https://dienmayai.com/admin/templates/default/js/helper.js?t=1734938637"></script>
   
    <script src="https://dienmayai.com/admin/templates/default/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
   
    <script src="https://dienmayai.com/admin/templates/default/bower_components/metisMenu/dist/metisMenu.min.js"></script>
   
    <script src="https://dienmayai.com/admin/templates/default/bower_components/raphael/raphael-min.js"></script>
   
    <script src="https://dienmayai.com/admin/templates/default/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="https://dienmayai.com/admin/templates/default/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
    <script src="https://dienmayai.com/admin/templates/default/bower_components/datatables-responsive/js/dataTables.responsive.js"></script>
    <script>
        $(document).ready(function() {
            $('#dataTables-example1').DataTable({
                responsive: true,
                "language": {
                    "lengthMenu": "Hiển thị _MENU_ trên mỗi trang",
                    "zeroRecords": "Không tìm thấy gì - xin lỗi",
                    "info": "Đang ở trang _PAGE_ của _PAGES_",
                    "infoEmpty": "Không có dữ liệu có sẵn",
                    "infoFiltered": "(lọc từ tổng số hồ sơ _MAX_)",
                    "search": "Tìm kiếm nhanh:",
                    paginate: {
                        first:    '«',
                        previous: '‹',
                        next:     '›',
                        last:     '»'
                    },
                },
                select: {
                    style: 'multi'
                },
                "lengthMenu": [ 10 ,20, 30, 40 , 50],
                "columnDefs": [
                { "orderable": false, "targets": 1 }
                ]
            });
        
        });
    </script>
    <!-- Custom Theme JavaScript -->
    <script src="https://dienmayai.com/admin/templates/default/dist/js/sb-admin-2.js"></script>
    <script src="https://dienmayai.com/admin/templates/default/dist/js/jquery.cookie.js"></script>
    <!-- Custom select chosen.jquery.js -->
    <script src="https://dienmayai.com/admin/templates/default/js/chosen.jquery.js" type="text/javascript"></script>
    <script type="text/javascript">
        var config = {
          '.chosen-select'           : {no_results_text: "Không tìm thấy "},
          '.chosen-select-deselect'  : {allow_single_deselect:true},
          '.chosen-select-no-single' : {disable_search_threshold:10},
          '.chosen-select-no-results': {no_results_text:"Không tìm thấy "},
          '.chosen-select-width'     : {width:"95%"}
        }
        for (var selector in config) {
          $(selector).chosen(config[selector]);
        }
    </script>
    <script>
        // popover demo
        $("[data-toggle=popover]").popover()
    </script>
</body>

</html>