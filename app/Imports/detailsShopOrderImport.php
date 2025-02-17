<?php

namespace App\Imports;

use App\detailsShopOrder;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class detailsShopOrderImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        
        return new detailsShopOrder([
    
            'date_document'=>$row['ngay_chung_tu']??'',
            'number_document'=> $row['so_chung_tu'],
            'invoice_number'=> $row['so_hoa_don'],
            'broad_interpretation'=> $row['dien_giai_chung'],
            'explain'=> $row['dien_giai'],
            'client_id'=> $row['ma_khach_hang'],
            'name_client'=> $row['ten_khach_hang'],
            'address'=> $row['dia_chi'],
            'sku'=> $row['ma_hang'],
            'product_name'=> $row['ten_hang'],
            'unit'=> $row['dvt'],
            'quantity'=> $row['tong_so_luong_ban'],
            'price'=> $row['don_gia'],
            'tk_no'=> $row['tk_no'],
            'tk_co'=> $row['tk_co'],
            'revenue'=> $row['doanh_so_ban'],
            'return_revenue'=> $row['tong_so_luong_tra_lai'],
            'return_value'=> $row['gia_tri_tra_lai'],
            'number_of_entries'=> $row['so_phieu_nhapxuat'],
            'tk_gia_von'=> $row['tk_gia_von'],
            'tk_kho'=> $row['tk_kho'],
            'ma_kho'=> $row['ma_kho'],
            'ten_kho'=> $row['ten_kho'],
            'lazada_code'=> $row['ma_hang_lazada'],
            'cpdg'=> $row['cpdg'],
            'pdg_sauco'=> $row['cpdg_sau_combo'],
           
            
        ]);
    }
}
