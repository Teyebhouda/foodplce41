<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Sentinel;
use Session;
use DataTables;
use App\User;
use App\Order;
use App\AppUser;
use App\Delivery;
use App\Setting;
use Cart;
use App\Category;
use App\Item;
use App\Ingredient;
use Hash;
class Cartcontroller extends Controller {
  
    public function addcart($id){
       $item_data=Item::where("menu_name",$id)->first();   
       $id=rand(10,100);
       Cart::add($id, $item_data->menu_name,$item_data->price,1, array());
       return redirect("/");
    }
    public function addcartitem(Request $request){
    	$totalint=array();
    	if($request->get("totalint")!=0){
    		$totalint=explode(",",$request->get("totalint")); 
        foreach ($totalint as $k) {
            $dt=Ingredient::find($k);
            $total[]=$dt->price;
        }
    	}	
    	
    	$id=rand(10,100);
    	$item_data=Item::where("menu_name",$request->get("id"))->first();
    	$qty=$request->get("qty");
      $price=$item_data->price+array_sum($total);
    	Cart::add($id, $item_data->menu_name,$price,$qty, array($totalint));    	
    	$cartCollection = Cart::getContent();
      Session::flash('message', __('messages.item_success')); 
      Session::flash('alert-class', 'alert-success');
   	 	return $cartCollection->count();
    }

    public function managecart(){
      $data=array();
      $finalresult=array();
      $result=array();
      $cartCollection = Cart::getContent();
      foreach ($cartCollection as $ke) {
           $getmenu=Item::where("menu_name",$ke->name)->first();
           $result['ItemId']=(string)isset($getmenu->id)?$getmenu->id:0;
           $result['ItemName']=(string)$ke->name;
           $result['ItemQty']=(string)$ke->quantity;
           $result['ItemAmt']=(string)$ke->price;
           $totalamount=(float)$ke->quantity*(float)$ke->price;
           $result['ItemTotalPrice']=(string)round($totalamount,2);
           $ingredient=array();
          foreach ($ke->attributes[0] as $val) {
               $ls=array();
                 $inter=Ingredient::find($val);
                 $ls['id']=(string)$inter->id;
                 $ls['category']=(string)$inter->category;
                 $ls['item_name']=(string)$inter->item_name;
                 $ls['type']=(string)$inter->type;
                 $ls['price']=(string)$inter->price;
                 $ls['menu_id']=(string)$inter->menu_id;
                 $ingredient[]=$ls;
          }
          $result['Ingredients']=$ingredient;
          $finalresult[]=$result;
      }
      $data=array("Order"=>$finalresult);
      print_r(json_encode($data));
    }

    public function deletecart($id){
       Cart::remove($id);
       $cartCollection = Cart::getContent();
       if(count($cartCollection)!=0){
           return redirect()->back();
       }
       else{
          return redirect("/");
       }
    }

    public function cartupdate($id,$qty,$op){
        $setting=Setting::find(1);
        if($op==1){//add qty
           Cart::update($id, array('quantity' =>1));
        }
        if($op==0){//minus qty
          Cart::update($id, array('quantity' => -1));
        }
        $cartCollection = Cart::getContent();
                      $totalamountarr=array();
                     foreach ($cartCollection as $car) {
                       $totalamount="";
                       $totalamount=(float)$car->quantity*(float)$car->price;
                       $totalamountarr[]=round($totalamount,2);
        }
        $subtotal=array_sum($totalamountarr);
        $finalresult=(float)array_sum($totalamountarr);
        $data=array("subtotal"=>Session::get("usercurrency").number_format($subtotal, 2, '.', ''),"finaltotal"=>Session::get("usercurrency").number_format($finalresult, 2, '.', ''));
        return $data;
        
    }
  
}


