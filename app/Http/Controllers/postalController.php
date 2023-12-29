<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Sentinel;
use Session;
use DataTables;
use App\City;
use App\Postal;
use App\Ingredient;
use Hash;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client; // Import GuzzleHttp\Client

class postalController extends Controller {
  
    public function delete($id){
     
      $store=Postal::where('id',$id)->update(["is_deleted"=>'1']);
      Session::flash('message',__('successerr.menu_del_item')); 
      Session::flash('alert-class', 'alert-success');
      return redirect("postal");
    }
    public function index(){
        $city=City::where('is_deleted','0')->get();
        return view("admin.postal.default")->with("city",$city);
    }

    public function itemdatatable()
    {

       
        $item =Postal::with('citypostal')->orderBy('id','DESC')->where("is_deleted",'0')->get();

        return DataTables::of($item)
            ->editColumn('id', function ($item) {
                return $item->id;
            })
            ->editColumn('name', function ($item) {
                return $item->postal_name;
            })
           
              ->editColumn('city', function ($item) {
                  if($item->citypostal){
                      return $item->citypostal->city_name;
                  }
                
            })
            
            ->editColumn('action', function ($item) {  
               $delete= url('deleteitem',array('id'=>$item->id));
               $return = '<a onclick="edititem('.$item->id.')"  rel="tooltip" title="" class="m-b-10 m-l-5" data-original-title="Remove" data-toggle="modal" data-target="#edititem"><i class="fa fa-edit f-s-25" style="margin-right: 10px;"></i></a><a onclick="delete_record(' . "'" . $delete . "'" . ')" rel="tooltip" title="" class="m-b-10 m-l-5" data-original-title="Remove"><i class="fa fa-trash f-s-25"></i></a>';    
               return $return;         
            })
           
            ->make(true);
    }

   public function add_menu_item(Request $request){
         

           $store=new Postal();
           $store->city=$request->get("city");
         
           $store->postal_name=$request->get("name");
          
           $store->save();  
           Session::flash('message',__('successerr.menu_add_item')); 
           Session::flash('alert-class', 'alert-success');
           return redirect("postal");

   }
   

   public function edititem($id){
     $data=Postal::with('citypostal')->find($id);
     return $data;
   }
   public function update_menu_item(Request $request){
        

           $store=Postal::find($request->get("id"));
           $store=new Postal();
           $store->city=$request->get("city");
         
           $store->postal_name=$request->get("name");
           $store->save();  
           Session::flash('message',__('successerr.menu_update_item')); 
           Session::flash('alert-class', 'alert-success');
           return redirect("postal");
   }

   public function getitem($id){
       $data=Postal::where("city",$id)->where("is_deleted",'0')->get();
       return $data;
   }
  


  

}





