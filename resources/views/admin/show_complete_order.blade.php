<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>

<?php 
    $mien = (Session()->get('admin')[0])==='adminmn'?'Miền Nam':'Miền Bắc'; 
?>
<body>
	<body>
    <a href="{{ route('dong don') }}"><h3>Đóng đơn</h3></a>    
    <div class="modal-menu-full-screen"></div>
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
            <!--end: .form_head-->
            <!-- Latest compiled and minified CSS -->
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
            <!-- jQuery library -->
            <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
            <!-- Popper JS -->
            <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
            <!-- Latest compiled JavaScript -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
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
                .code{
                font-size: 130px;
                }
            </style>


            <h1>Hệ thống tạm thời bảo trì, xin quay lại sau!</h1>
            <?php 

                $dem =0;
            ?>

            @if(!empty($info_data))
           
            <table style="display:none">
                <tbody>
                    <tr>
                        <th>STT</th>
                        <th>Tracking code </th>
                    </tr>

                    @foreach($info_data as $key => $value)
                    <?php

                        $dem++;
                    ?>
                    <tr>
                        <td>{{ $dem }}</td>
                        <td><span class="code">{{ @$value->tracking_code }}</span></td>
                    </tr>
                    @endforeach
                    
                </tbody>
            </table>
            @endif 
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item"><a class="page-link" href="?page=1">1</a></li>
                    <li class="page-item"><a class="page-link" href="?page=2">2</a></li>
                    <li class="page-item"><a class="page-link" href="?page=3">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="?page=4" aria-label="Next">
                        <span aria-hidden="true">»</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <script>
                $(document).ready(function(){
                    // Function to load realtime data
                    function loadRealtimeData(){
                        $.ajax({
                            url: '{{ route("auto_packed_load") }}', // Đường dẫn tới tập tin PHP xử lý dữ liệu
                            type: 'GET',
                            data: {
                                kho: {{ (Session()->get('admin')[0])==='adminmn'?2:1 }},
                                
                                   
                            },
                            success: function(response){
                
                                if(response==1){
                
                                    window.location.reload()
                                }
                               
                               
                               
                            }
                        });
                    }
                
                    // Load realtime data initially
                    loadRealtimeData();
                
                    // Load realtime data every 5 seconds
                    setInterval(function(){
                        loadRealtimeData();
                    }, 2000);
                });
            </script>            
        </div>
    <div class="clearfix"></div>
    <!-- /#wrapper -->
    <div class="popup-notification">
        Thành công
    </div>
    <div class="go-top scrollToTop" style="display: none;">
        <i class="fa fa-arrow-circle-up"></i>
    </div>
    <!-- jQuery -->
    <script type="text/javascript" src="https://dienmayai.com/admin/templates/default/js/jquery-confirm.min.js"></script>
    <script type="text/javascript" src="https://dienmayai.com/admin/templates/default/js/select2.min.js"></script>
    <script type="text/javascript" src="https://dienmayai.com/admin/templates/default/js/helper.js?t=1734938637"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="https://dienmayai.com/admin/templates/default/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Metis Menu Plugin JavaScript -->
    <script src="https://dienmayai.com/admin/templates/default/bower_components/metisMenu/dist/metisMenu.min.js"></script>
    <!-- Morris Charts JavaScript -->
    <script src="https://dienmayai.com/admin/templates/default/bower_components/raphael/raphael-min.js"></script>
    <!-- DataTables JavaScript -->
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
</body>
</html>