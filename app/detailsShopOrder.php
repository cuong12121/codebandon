<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class detailsShopOrder extends Model
{
    public $table = 'details_shop_order';


    public $fillable = [
        'date_document','number_document','invoice_number','broad_interpretation','explain','client_id','name_client','address','sku','product_name','unit', 'quantity','price','tk_no','tk_co','revenue','return_revenue','return_value','number_of_entries','tk_gia_von','tk_kho','ma_kho','ten_kho','lazada_code','cpdg','cpdg_sauco'
        
    ];    
}
