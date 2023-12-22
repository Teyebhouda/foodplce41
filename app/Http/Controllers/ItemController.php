<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Sentinel;
use Session;
use DataTables;
use App\Category;
use App\Item;
use App\Ingredient;
use Hash;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client; // Import GuzzleHttp\Client

class ItemController extends Controller {
  
    public function delete($id){
      $ing=Ingredient::where('menu_id',$id)->update(["is_deleted"=>'1']);
      $store=Item::where('id',$id)->update(["is_deleted"=>'1']);
      Session::flash('message',__('successerr.menu_del_item')); 
      Session::flash('alert-class', 'alert-success');
      return redirect("menuitem");
    }
    public function index(){
        $category=Category::where('is_deleted','0')->get();
        return view("admin.item.default")->with("category",$category);
    }

    public function itemdatatable()
    {

       
        $item =Item::with('categoryitem')->orderBy('id','DESC')->where("is_deleted",'0')->get();

        return DataTables::of($item)
            ->editColumn('id', function ($item) {
                return $item->id;
            })
            ->editColumn('name', function ($item) {
                return $item->menu_name;
            })
            ->editColumn('reference', function ($item) {
                return $item->reference;
            })
              ->editColumn('category', function ($item) {
                  if($item->categoryitem){
                      return $item->categoryitem->cat_name;
                  }
                
            })
             ->editColumn('description', function ($item) {
                return $item->description;
            })
            ->editColumn('price', function ($item) {
                return $item->price;
            })           
            ->editColumn('image', function ($item) {
                return asset('public/upload/images/menu_item_icon/'.$item->menu_image);
            })
            ->editColumn('action', function ($item) {  
               $delete= url('deleteitem',array('id'=>$item->id));
               $return = '<a onclick="edititem('.$item->id.')"  rel="tooltip" title="" class="m-b-10 m-l-5" data-original-title="Remove" data-toggle="modal" data-target="#edititem"><i class="fa fa-edit f-s-25" style="margin-right: 10px;"></i></a><a onclick="delete_record(' . "'" . $delete . "'" . ')" rel="tooltip" title="" class="m-b-10 m-l-5" data-original-title="Remove"><i class="fa fa-trash f-s-25"></i></a>';    
               return $return;         
            })
           
            ->make(true);
    }

   public function add_menu_item(Request $request){
          if ($request->hasFile('image')) 
              {
                 $file = $request->file('image');
                 $filename = $file->getClientOriginalName();
                 $extension = $file->getClientOriginalExtension() ?: 'png';
                 $folderName = '/upload/images/menu_item_icon';
                 $picture = str_random(10).time() . '.' . $extension;
                 $destinationPath = public_path() . $folderName;
                 $request->file('image')->move($destinationPath, $picture);
                 $img_url =$picture;

             }else{
                 $img_url = '';
             }

           $store=new Item();
           $store->category=$request->get("category");
           $store->description=$request->get("description");
           $store->menu_name=$request->get("name");
           $store->price=number_format($request->get("price"),2,".",".");;
           $store->menu_image=$img_url;
           $store->reference=$request->get("reference");
           $store->save();  
           Session::flash('message',__('successerr.menu_add_item')); 
           Session::flash('alert-class', 'alert-success');
           return redirect("menuitem");

   }
   

   public function edititem($id){
     $data=Item::with('categoryitem')->find($id);
     return $data;
   }
   public function update_menu_item(Request $request){
         if ($request->hasFile('image')) 
              {
                 $file = $request->file('image');
                 $filename = $file->getClientOriginalName();
                 $extension = $file->getClientOriginalExtension() ?: 'png';
                 $folderName = '/upload/images/menu_item_icon';
                 $picture = str_random(10).time() . '.' . $extension;
                 $destinationPath = public_path() . $folderName;
                 $request->file('image')->move($destinationPath, $picture);
                 $img_url =$picture;

             }else{
                 $img_url = $request->get("real_image");
             }

           $store=Item::find($request->get("id"));
           $store->category=$request->get("category");
           $store->description=$request->get("description");
           $store->menu_name=$request->get("name");
           $store->reference=$request->get("reference");
           $store->price=number_format($request->get("price"),2,".",".");;
           $store->menu_image=$img_url;
           $store->save();  
           Session::flash('message',__('successerr.menu_update_item')); 
           Session::flash('alert-class', 'alert-success');
           return redirect("menuitem");
   }

   public function getitem($id){
       $data=Item::where("category",$id)->where("is_deleted",'0')->get();
       return $data;
   }
  


   public function SynchronizeProducts()
   {
       // Fetch all products from the API
       $apiUrl = env('API_foodplace_URL');
      // dd($apiUrl);
       $client = new Client();
       $response = $client->get('https://api.alaindata.com/foodplace41/ProduitsSortie');
      // $response2 = $client->get('https://api.alaindata.com/foodplace41/sousfamille');
       // Check if the request was successful (status code 200)
       if ($response->getStatusCode() === 200) {
           $produitsApi = json_decode($response->getBody(), true);
   
           $barcodes = collect($produitsApi)->pluck('Référence')->toArray();
           $existingProducts = Item::whereIn('reference', $barcodes)
               ->with('categoryitem')
               ->orderBy('id', 'DESC')
               ->where("is_deleted", '0')
               ->get()
               ->keyBy('reference');
               $categories = Category::all(); // Replace Category with your actual model name for categories
           foreach ($produitsApi as $produitApi) {
               $apiname = $produitApi['Libellé'];
               $apireference = $produitApi['Référence'];
               $apiPrice = $produitApi['PrixHT'];
               $apiPhoto = $produitApi['Photo'];
               $apiFamille = $produitApi['Famille'];

               
               // dd($categories);
   //dd($apiPhoto);
               // Find products with matching barcode
               if (!(isset($existingProducts[$apireference]))) {
                   // Update prices for matching products
                     
                   if ($apiPhoto != null) 
                   {
                     /* $file = $request->file('image');
                      $filename = $file->getClientOriginalName();
                      $extension = $file->getClientOriginalExtension() ?: 'png';
                      $folderName = '/upload/images/menu_item_icon';
                      $picture = str_random(10).time() . '.' . $extension;
                      $destinationPath = public_path() . $folderName;
                      $request->file('image')->move($destinationPath, $picture);
                      $img_url =$picture;*/
                      $img_url = '';
                  }else{
                      $img_url = '';
                  }
         foreach($categories as $category){
           // dd($apiFamille);
if($category->cat_name == $apiFamille )

$productcategory=$category->id;

//dd($productcategory);

         }
                $store=new Item();
                $store->category=$productcategory;
                //$store->description=$request->get("description");
                $store->menu_name=$apiname;
                $store->price=number_format($apiPrice,2,".",".");;
                $store->menu_image=$img_url;
                $store->reference=$apireference;
                $store->save();
               } else {
                   // Product exists, update if needed
                   $matchingProduct = $existingProducts[$apireference];
              //  dd($matchingProduct);
                   if ($matchingProduct->menu_name != $apiname) {
                       $matchingProduct->menu_name = $apiname;
                   }
   
                   if ($matchingProduct->price != $apiPrice) {
                       $matchingProduct->price = $apiPrice;
                   }
                   foreach ($categories as $category) {
                  //  dd($categories);
                    if ($category->cat_name == $apiFamille) {
                      //  dd($apiFamille);
                        $productcategory = $category->id;
                        $matchingProduct->category = $productcategory;
                    }
                }
                
                   $matchingProduct->save();
               }
           }
   
           // Optionally, you might return a success message or any other response here
           //return ['message' => 'Products synchronized successfully'];
           Session::flash('message',__('menu_synchronize_item')); 
           Session::flash('alert-class', 'alert-success');
           return redirect("menuitem");
       } else {
           // Handle unsuccessful API request
           return response('Failed to fetch data from API', 500);
       }
   }



}





