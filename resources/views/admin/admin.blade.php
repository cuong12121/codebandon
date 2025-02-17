@extends('admin.layout')
<div id="main-content">
    <div id="header">
        <div class="header-left float-left">
            <i id="toggle-left-menu" class="ion-android-menu"></i>
        </div>
        <div class="header-right float-right">
            <i class="ion-ios-people"></i>
        </div>
    </div>

    <div id="page-container">
        
        <div class="card-body">
            
            
            
            <?php 
                $date_time = DB::table('details_shop_order')->select('date_document')->orderBy('id', 'desc')->first();
                
                
            ?>
            
            <h3>Thêm bảng danh sách chi tiết, dữ liệu cập nhật mới nhất đang lấy ngày {{  \Carbon\Carbon::parse($date_time->date_document)->format('d/m/Y')   }}</h3>
            
            <br>
            
            <form action="{{ route('detail-Shop-Order.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
  
                @if (count($errors) > 0)
                <div class="row">
                    <div class="col-md-8 col-md-offset-1">
                      <div class="alert alert-danger alert-dismissible">
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                          <h4><i class="icon fa fa-ban"></i> Error!</h4>
                          @foreach($errors->all() as $error)
                          {{ $error }} <br>
                          @endforeach      
                      </div>
                    </div>
                </div>
                @endif
  
                @if (Session::has('success'))
                    <div class="row">
                      <div class="col-md-8 col-md-offset-1">
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5>{!! Session::get('success') !!}</h5>   
                        </div>
                      </div>
                    </div>
                @endif
  
                <input type="file" name="file" class="form-control">
                <br>
                <button class="btn btn-success">Submit</button>
            </form>
        </div>
        
        <div>
            
            <div class="card-body">
                
                <h3>Thêm bảng giá thấp nhất</h3>
                 
                <form action="{{ route('insert-container-cost') }}" method="POST" enctype="multipart/form-data">
                    @csrf
      
                    @if (count($errors) > 0)
                    <div class="row">
                        <div class="col-md-8 col-md-offset-1">
                          <div class="alert alert-danger alert-dismissible">
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                              <h4><i class="icon fa fa-ban"></i> Error!</h4>
                              @foreach($errors->all() as $error)
                              {{ $error }} <br>
                              @endforeach      
                          </div>
                        </div>
                    </div>
                    @endif
      
                    @if (Session::has('success'))
                        <div class="row">
                          <div class="col-md-8 col-md-offset-1">
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h5>{!! Session::get('success') !!}</h5>   
                            </div>
                          </div>
                        </div>
                    @endif
      
                    <input type="file" name="file" class="form-control">
                    <br>
                    <button class="btn btn-success">Submit</button>
                </form>
            </div>
        </div>
        
    </div>
</div>

    