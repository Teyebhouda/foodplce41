<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Sentinel;
use Session;
use DataTables;
use App\Ingredient;
use App\Famille; 
use App\Category;
use App\Item;
use Hash;
use GuzzleHttp\Client; 

class IngredientsController extends Controller {
  
    
    public function index(){
         $category=Category::where('is_deleted','0')->get();
        return view("admin.ingredient.default")->with("category",$category);
    }
    
    public function ingredientsdatatable(){
      $item =Ingredient::with('categoryitem','itemname')->orderBy('id','DESC')->where("is_deleted",'0')->get();

        return DataTables::of($item)
            ->editColumn('id', function ($item) {
                return $item->id;
            })
            ->editColumn('name', function ($item) {
                return $item->item_name;
            })
              ->editColumn('category', function ($item) {
                return $item->categoryitem->cat_name;
            })
             ->editColumn('item', function ($item) {
                return $item->itemname->menu_name;
            })
              ->editColumn('type', function ($item) {
                if($item->type==1){
                  $type="Paid";
                }
                else{
                  $type="Free";
                }

                return $type;
            })
            ->editColumn('price', function ($item) {
                return $item->price;
            })
            ->editColumn('action', function ($item) {              
              $edit=url('editinge',array('id'=>$item->id));
               $delete= url('deleteinge',array('id'=>$item->id));
               $return = '<a onclick="editing('.$item->id.')"  rel="tooltip" title="" class="m-b-10 m-l-5" data-original-title="Remove" data-toggle="modal" data-target="#editing"><i class="fa fa-edit f-s-25" style="margin-right: 10px;" ></i></a><a onclick="delete_record(' . "'" . $delete . "'" . ')" rel="tooltip" title="" class="m-b-10 m-l-5" data-original-title="Remove"><i class="fa fa-trash f-s-25"></i></a>';    
               return $return;        
            })
           
            ->make(true);
    }

    public function add_menu_ingre(Request $request){
       $store=new Ingredient();
       $store->category=$request->get("category");
       $store->item_name=$request->get("name");
       $store->menu_id=$request->get("item");
       $store->type=$request->get("type");
       if($store->type==0){
          $store->price=0.00;
       }
       else{
          $store->price=number_format($request->get("price"),2,".",".");
       }       
       $store->save();
       Session::flash('message', __('successerr.menu_inter_add_success')); 
       Session::flash('alert-class', 'alert-success');
       return redirect("menuingredients");

    }

    public function delete($id){
       $ing=Ingredient::where('id',$id)->update(["is_deleted"=>'1']);
       Session::flash('message',__('successerr.menu_inter_del_success')); 
       Session::flash('alert-class', 'alert-success');
       return redirect("menuingredients");
    }

    public function editing($id){
      $data=Ingredient::find($id);
      $category=Category::all();
      $item=Item::where("category",$data->category)->get();
      $main=array("data"=>$data,"category"=>$category,"Item"=>$item);
      return $main;
    }

    public function update_menu_ingre(Request $request){       
       $store=Ingredient::find($request->get("id"));
       $store->category=$request->get("editcategory");
       $store->item_name=$request->get("name");
       $store->menu_id=$request->get("item");
       $store->type=$request->get("edittype");
       if($store->type==0){
          $store->price=0.00;
       }
       else{
          $store->price=number_format($request->get("price"),2,".",".");;
       } 
       $store->save();
       Session::flash('message',__('successerr.menu_inter_update_success')); 
       Session::flash('alert-class', 'alert-success');
       return redirect("menuingredients");
    }

    public function SynchronizeIngredients()
{
    $apiUrl = env('API_foodplace_URL');
    $client = new Client();
    $response = $client->get('https://api.alaindata.com/foodplace41/LigneFamilleOptions');

    if ($response->getStatusCode() === 200) {
        $optionsApi = json_decode($response->getBody(), true);

        $categoriesToCheck = [
            'BURGERS' => ['SUUPLIMENTS PIZZA','SUPPLIMENTS VIANDES PLATS','SAUCES', 'BOISSONS', 'LEGUMES'],
            'PANINIS' => ['SUUPLIMENTS PIZZA','SUPPLIMENTS VIANDES PLATS','SAUCES', 'BOISSONS', 'LEGUMES'],
            'SANDWICHS' => ['SUUPLIMENTS PIZZA','SUPPLIMENTS VIANDES PLATS','SAUCES', 'BOISSONS', 'LEGUMES'],
            'TACOS' => ['SUUPLIMENTS PIZZA','SUPPLIMENTS VIANDES PLATS','SAUCES', 'VIANDES', 'BOISSONS'],
            'SALADES' => ['LEGUMES', 'BOISSONS'],
            'PLATS' => [ 'BOISSONS'],
            'BARQUETTES' => [ 'BOISSONS'],
            'DESSERTS' => [ 'BOISSONS'],
            'SIGNATURE' => [ 'BOISSONS'],
            'TEX MEX' => [ 'BOISSONS'],
            'MENU ENFANT' => ['LEGUMES', 'BOISSONS'],
            '1ER PIZZA' => ['SUUPLIMENTS PIZZA','SUPPLIMENTS VIANDES PLATS', 'BOISSONS', 'PROMOTION PIZZA']
        ];

        $items = Item::where('is_deleted', '0')->get();

        foreach ($items as $item) {
            if (isset($item->categoryitem) && is_object($item->categoryitem)) {
                $catName = $item->categoryitem->cat_name;
                // Process $catName or perform actions here
           

            if (isset($categoriesToCheck[$catName])) {
                $desiredFamilles = $categoriesToCheck[$catName];

                foreach ($optionsApi as $option) {
                    $apiname = $option['designation'];
                    $apiPrixUni = $option['PrixUni'];

                    $familleOptions = Famille::whereIn('name', $desiredFamilles)->get();

                    foreach ($familleOptions as $familleOption) {
                        if ($option['IDFamilleOptions'] == $familleOption->id) {
                            $existingIngredient = Ingredient::where('item_name', $apiname)
                            
                                ->where('category', $item->categoryitem->id)
                                ->where('famille', $familleOption->id)
                                ->first();

                            if (!$existingIngredient) {
                                $store = new Ingredient();
                                $store->item_name = $apiname;
                                $store->menu_id = $item->id;
                                $store->category = $item->categoryitem->id;
                                $store->famille = $familleOption->id;
                                $store->type = $familleOption->type === 'simple' ? 0 : 1;
                                $store->price =  number_format($apiPrixUni, 2, ".", ".");
                                $store->save();
                            }
                        }
                    }
                }
            }
        }
        }

        Session::flash('alert-class', 'alert-success');
        return redirect("menuingredients");
    }
    return redirect("menuingredients");
}
}