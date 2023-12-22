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
use App\Item;
use App\Delivery;
use App\Setting;
use App\Resetpassword;
use Response;
use Cookie;
use App\FoodOrder;
use App\OrderResponse;
use App\Ingredient;
use Hash;
use Mail;
use DateTimeZone;
use DateTime;
use Cart;
class AppuserController extends Controller {
  
   private $_api_context;
   public function register(Request $request){
       $checkemail=AppUser::where("email",$request->get("email"))->first();
       if(isset($checkemail)){
           return 0;
       }
       else{
          $checkmobile=AppUser::where("mob_number",$request->get("phone"))->get();
           	if(count($checkmobile)==0){
           	   		$store=new AppUser();
                	$store->name=$request->get("name");
                	$store->mob_number=$request->get("phone");
               		$store->password=md5($request->get("password"));
               		$store->email=$request->get("email");
               		$store->create_at=date('Y-m-d');
               		$store->save();
               		return 1;
           	   }
           	   else{
           	   	   return 0;
           	   } 
       }
   	          
   } 
   public function updateuserprofile(Request $request){
      
       if(!Session::get("login_user")){
          return redirect("/");
       } 
       else{
           if ($request->hasFile('image')) 
              {
                 $file = $request->file('image');
                 $filename = $file->getClientOriginalName();
                 $extension = $file->getClientOriginalExtension() ?: 'png';
                 $folderName = '/upload/profile';
                 $picture = str_random(10).time() . '.' . $extension;
                 $destinationPath = public_path() . $folderName;
                 $request->file('image')->move($destinationPath, $picture);
                 $img_url =$picture;

             }else{
                 $img_url = Session::get('user_photo');
             }

             $user=AppUser::find(Session::get("login_user"));
             $name=$request->get("user_name");
             $email=$request->get("user_email");
             $user->name=$name;
             $user->email=$email;
             $user->profile_pic=$img_url;
             $user->save();
             Session::put("user_name",$name);
             Session::put("user_email",$email);
             Session::put("user_photo",$img_url);
             return redirect()->back();
       }     
      
   }
   public function login(Request $request){

        $checkdetails=AppUser::where("mob_number",$request->get("phone"))->where("password",md5($request->get("password")))->where("is_deleted","0")->get();
        if(count($checkdetails)!=0){
          if($request->get("rem_me")==1){
            setcookie('phone', $request->get("phone"), time() + (86400 * 30), "/");
            setcookie('password',$request->get("password"), time() + (86400 * 30), "/");
            setcookie('rem_me',1, time() + (86400 * 30), "/");
          }
          $getdata=AppUser::find($checkdetails[0]->id);
        	Session::put("login_user",$getdata->id);
          Session::put("user_phone",$request->get("phone"));
          Session::put("user_name",$getdata->name);
          Session::put("user_email",$getdata->email);
          Session::put("user_photo",$getdata->profile_pic);
        	return 1;
        }
        else{
        	return 0;
        }
   }

   public function logout(){
   	  Session::forget("login_user");
      Session::forget("user_name");
      Session::forget("user_email");
      Session::forget("user_photo");
      Session::forget("user_phone");
   	  return redirect("/");
   }

   public function forgotpassword(Request $request){
         $checkmobile=AppUser::orwhere("mob_number",$request->get("phone"))->orwhere("email",$request->get("phone"))->get();
         if(count($checkmobile)!=0){
             $code=mt_rand(100000, 999999);
             $store=array();
             if(!isset($checkmobile[0]->email)){
                 return 0;
             }
             $store['email']=$checkmobile[0]->email;
             $store['name']=$checkmobile[0]->name;
             $store['code']=$code;
             $add=new Resetpassword();
             $add->user_id=$checkmobile[0]->id;
             $add->code=$code;
             $add->save();
              try {
                     Mail::send('email.forgotpassword', ['user' => $store], function($message) use ($store){
                      $message->to($store['email'],$store['name'])->subject(__("messages.site_name"));
                    });
              } catch (\Exception $e) {
              }
            
            return 1;
         }
         else{
            return 0;
         }
   }

   public function resetpwd($code){
       $data=Resetpassword::where("code",$code)->get();
       if(count($data)!=0){
           return view('user.resetpwd')->with("id",$data[0]->user_id)->with("code",$code);
       }
       else{
          return view('user.resetpwd')->with("msg",__('messages.code_ex'));
       }
   }
   
   public function resetpassword(Request $request){
      if($request->get('id')==""){
          return view('user.resetpwd')->with("msg",__('messages.pwd_success'));
      }
      else{
        $user=AppUser::find($request->get('id'));
       $user->password=md5($request->get('npwd'));
       $user->save();
       $codedel=Resetpassword::where('user_id',$request->get("id"))->delete();
       return view('user.resetpwd')->with("msg",__('messages.pwd_success'));
      }
       
   }
   function get_lat_long($address){

    $address = str_replace(" ", "+", $address);
    $url = "https://maps.googleapis.com/maps/api/geocode/json?address=$address&key=AIzaSyBiVfFZRtrGy8AmV5UH7WZEou_3Hpbc_xg"; 
    $ch = curl_init();  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch, CURLOPT_URL, $url); 
    $result = curl_exec($ch); 
    
    $json = json_decode($result);
   // return $json;
    $lat="";
    $long="";
    if(isset($json->{'results'}[0])){
        $lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
        $long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
    }
    
    return $lat.','.$long;
}
  static public function generate_timezone_list(){
          static $regions = array(
                     DateTimeZone::AFRICA,
                     DateTimeZone::AMERICA,
                     DateTimeZone::ANTARCTICA,
                     DateTimeZone::ASIA,
                     DateTimeZone::ATLANTIC,
                     DateTimeZone::AUSTRALIA,
                     DateTimeZone::EUROPE,
                     DateTimeZone::INDIAN,
                     DateTimeZone::PACIFIC,
                 );
                  $timezones = array();
                  foreach($regions as $region) {
                            $timezones = array_merge($timezones, DateTimeZone::listIdentifiers($region));
                  }

                  $timezone_offsets = array();
                  foreach($timezones as $timezone) {
                       $tz = new DateTimeZone($timezone);
                       $timezone_offsets[$timezone] = $tz->getOffset(new DateTime);
                  }
                 asort($timezone_offsets);
                 $timezone_list = array();
    
                 foreach($timezone_offsets as $timezone=>$offset){
                          $offset_prefix = $offset < 0 ? '-' : '+';
                          $offset_formatted = gmdate('H:i', abs($offset));
                          $pretty_offset = "UTC${offset_prefix}${offset_formatted}";
                          $timezone_list[] = "$timezone";
                 }

                 return $timezone_list;
                ob_end_flush();
       }

       public function gettimezonename($timezone_id){
              $getall=$this->generate_timezone_list();
              foreach ($getall as $k=>$val) {
                 if($k==$timezone_id){
                     return $val;
                 }
              }
       }
   public function placeorder(Request $request){
      $data=array();
      $finalresult=array();
      $result=array();
      $cartCollection = Cart::getContent();
      $setting=Setting::find(1);
      $gettimezone=$this->gettimezonename($setting->timezone);
      date_default_timezone_set($gettimezone);
      $date = date('d-m-Y H:i');
      $getuser=AppUser::find(Session::get('login_user'));
      $store=new Order();
      $store->user_id=$getuser->id;
      $store->total_price=number_format($request->get("totalprice"), 2, '.', '');
      $store->order_placed_date=$date;
      $store->order_status=0;
      $store->latlong= strip_tags(preg_replace('#<script(.*?)>(.*?)</script>#is', '',$request->get("latlong")));
      $store->name=$getuser->name;
      $store->address=strip_tags(preg_replace('#<script(.*?)>(.*?)</script>#is', '',$request->get("address")));
      $store->email=$getuser->email;
      $store->payment_type= strip_tags(preg_replace('#<script(.*?)>(.*?)</script>#is', '',$request->get("payment_type")));
      $store->notes= strip_tags(preg_replace('#<script(.*?)>(.*?)</script>#is', '',$request->get("note")));
      $store->city= strip_tags(preg_replace('#<script(.*?)>(.*?)</script>#is', '',$request->get("city")));
      $store->shipping_type= strip_tags(preg_replace('#<script(.*?)>(.*?)</script>#is', '',$request->get("shipping_type")));
      $store->subtotal=number_format($request->get("subtotal"), 2, '.', '');
      $store->delivery_charges=number_format($request->get("charge"), 2, '.', '');
      $store->phone_no=strip_tags(preg_replace('#<script(.*?)>(.*?)</script>#is', '',$request->get("phone")));
      $store->delivery_mode=strip_tags(preg_replace('#<script(.*?)>(.*?)</script>#is', '',$request->get("shipping_type")));
      $store->notify=1;
      $store->save();
      foreach ($cartCollection as $ke) {
           $getmenu=Item::where("menu_name",$ke->name)->first();
           $result['ItemId']=(string)isset($getmenu->id)?$getmenu->id:0;
           $result['ItemName']=(string)$ke->name;
           $result['ItemQty']=(string)$ke->quantity;
           $result['ItemAmt']=(string)$ke->price;
           $totalamount=(float)$ke->quantity*(float)$ke->price;
           $result['ItemTotalPrice']=number_format($totalamount, 2, '.', '');
           $ingredient=array();
           $inter_ids=array();
           foreach ($ke->attributes[0] as $val) {
                     $ls=array();
                     $inter=Ingredient::find($val);
                     $ls['id']=(string)$inter->id;
                     $inter_ids[]=$inter->id;
                     $ls['category']=(string)$inter->category;
                     $ls['item_name']=(string)$inter->item_name;
                     $ls['type']=(string)$inter->type;
                     $ls['price']=(string)$inter->price;
                     $ls['menu_id']=(string)$inter->menu_id;
                     $ingredient[]=$ls;
             }

        $result['Ingredients']=$ingredient;
        $finalresult[]=$result;
        $adddesc=new OrderResponse();
        $adddesc->set_order_id=$store->id;
        $adddesc->item_id=$result["ItemId"];
        $adddesc->item_qty=$result["ItemQty"];
        $adddesc->ItemTotalPrice=number_format($result["ItemTotalPrice"], 2, '.', '');
        $adddesc->item_amt=$result["ItemAmt"];
        $adddesc->ingredients_id=implode(",",$inter_ids);
        $adddesc->save();
      }
      $data=array("Order"=>$finalresult);
      $addresponse=new FoodOrder();
      $addresponse->order_id=$store->id;
      $addresponse->desc=json_encode($data);
      $addresponse->save();
      Cart::clear();
      Session::flash('message', __('messages.order_success')); 
      Session::flash('alert-class', 'alert-success');
      return $store->id;
   }
   public function checkuserpassword($cpwd){
      $user=AppUser::find(Session::get("login_user"));
      if($user->password==md5($cpwd)){

          return 1;
      }
      else{
          return 0;
      }
   }

   public function changeuserpwd(Request $request){
       if(!Session::get("login_user")){
          return redirect("/");
       } 
       else{
           $user=AppUser::find(Session::get("login_user"));
           $user->password=md5($request->get("npwd"));
           $user->save();
           Session::flash('message', __("messages.pwd_chd")); 
           Session::flash('alert-class', 'alert-success');
           return 1;
       }
   } 
}


