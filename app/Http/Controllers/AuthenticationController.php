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
use Hash;
use DateTimeZone;
use Artisan;
use DateTime;
class AuthenticationController extends Controller {
  
    
    public function showlogin(){
        Artisan::call('config:cache');
         $store=Setting::find(1);
        Session::put("is_web",$store->is_web);
        return view("admin.login");
    }

    public function editsetting($tab){
      $data=Setting::find(1);
      $timezone=$this->generate_timezone_list();
      return view("admin.setting")->with("data",$data)->with("timezone",$timezone)->with("tab",$tab);
    }
    public function generate_timezone_list(){
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
              $timezone_list[] = "(${pretty_offset}) $timezone";
            }
        
            return $timezone_list;
            ob_end_flush();
  }
    public function updatesetting(Request $request){
       $store=Setting::find(1);
       $store->facebook_id=$request->get("facebook_id");
       $store->twitter_id=$request->get("twitter_id");
       $store->linkedin_id=$request->get("linkedin_id");
       $store->linkedin_id=$request->get("linkedin_id");
       $store->whatsapp=$request->get("whatsapp");
       $store->address=$request->get("address");
       $store->email=$request->get("email");
       $store->phone=$request->get("phone_no");
       $store->delivery_charges=$request->get("delivery");
       $store->app_store_url=$request->get("appstore");
       $store->play_store_url=$request->get("playstore");
       $store->save();
       Session::flash('message', __('successerr.update_success')); 
       Session::flash('alert-class', 'alert-success');
       return redirect()->back();
    }

    public function savesoicalsetting(Request $request){
         $store=Setting::find(1);
         $store->facebook_id=$request->get("facebook_id");
         $store->twitter_id=$request->get("twitter_id");
         $store->linkedin_id=$request->get("linkedin_id");
         $store->google_plus_id=$request->get("google_plus_id");
         $store->whatsapp=$request->get("whatsapp");
         $store->app_store_url=$request->get("appstore");
         $store->play_store_url=$request->get("playstore");
         $store->have_playstore=$request->get("have_playstore")?$request->get("have_playstore"):0;
         $store->have_appstore=$request->get("have_appstore")?$request->get("have_appstore"):0;
         $store->save();
         Session::flash("message",__('successerr.data_save'));
         Session::flash('alert-class', 'alert-success');
         return redirect("setting/4");
    }

    public function saveresdetail(Request $request){
         $store=Setting::find(1);
         $store->address=$request->get("address");
         $store->email=$request->get("email");
         $store->phone=$request->get("phone_no");
         $store->delivery_charges=$request->get("delivery");
         $store->timezone=$request->get("timezone");
         $store->save();
         Session::flash("message",__('successerr.data_save'));
         Session::flash('alert-class', 'alert-success');
         return redirect("setting/2");
    }

    public function savepaymentdata(Request $request){
         $store1=Setting::find(1);
         $store1->stripe_key=$request->get("stripe_key");
         $store1->stripe_secret=$request->get("stripe_secret");
         $store1->paypal_client_id=$request->get("paypal_client_id");
         $store1->paypal_client_secret=$request->get("paypal_client_secret");
         $store1->paypal_mode=$request->get("paypal_mode")?$request->get("paypal_mode"):1;
         $store1->stripe_active=$request->get("stripe_active");
         $store1->paypal_active=$request->get("paypal_active");
         $store1->save();
         if($store1->is_web=='1'||$store1->is_web=='2'){
            Session::flash("message",__('successerr.data_save'));
            Session::flash('alert-class', 'alert-success');
            return redirect("setting/3");
         }
          Session::flash("message",__('successerr.data_save'));
          Session::flash('alert-class', 'alert-success');
         return redirect("setting/2");
    }

    public function savewebimage(Request $request){
        $store=Setting::find(1);
        
        if ($request->hasFile('logo')) 
        {
                 $logo_img=$store->logo;
                 $file = $request->file('logo');
                 $filename = $file->getClientOriginalName();
                 $extension = $file->getClientOriginalExtension() ?: 'png';
                 $folderName = '/upload/web';
                 $picture = str_random(10).time() . '.' . $extension;
                 $destinationPath = public_path() . $folderName;
                 $request->file('logo')->move($destinationPath, $picture);
                 $store->logo =$picture;
                 $image_path=public_path().'/upload/web/'.$logo_img;
                  if(file_exists($image_path)) {
                            try{
                                 unlink($image_path);
                            }
                            catch(\Exception $e)
                            {
                                
                            }
                            
                        }
        }
        if ($request->hasFile('main_banner')) 
        {
                 $img=$store->main_banner;
                 $file = $request->file('main_banner');
                 $filename = $file->getClientOriginalName();
                 $extension = $file->getClientOriginalExtension() ?: 'png';
                 $folderName = '/upload/web';
                 $picture = str_random(10).time() . '.' . $extension;
                 $destinationPath = public_path() . $folderName;
                 $request->file('main_banner')->move($destinationPath, $picture);
                 $store->main_banner =$picture;
                 $image_path=public_path().'/upload/web/'.$img;
                  if(file_exists($image_path)) {
                            try{
                                 unlink($image_path);
                            }
                            catch(\Exception $e)
                            {
                                
                            }
                            
                        }
        }
        if ($request->hasFile('second_sec_img')) 
        {
                 $img=$store->second_sec_img;
                 $file = $request->file('second_sec_img');
                 $filename = $file->getClientOriginalName();
                 $extension = $file->getClientOriginalExtension() ?: 'png';
                 $folderName = '/upload/web';
                 $picture = str_random(10).time() . '.' . $extension;
                 $destinationPath = public_path() . $folderName;
                 $request->file('second_sec_img')->move($destinationPath, $picture);
                 $store->second_sec_img =$picture;
                 $image_path=public_path().'/upload/web/'.$img;
                  if(file_exists($image_path)) {
                            try{
                                 unlink($image_path);
                            }
                            catch(\Exception $e)
                            {
                                
                            }
                            
                        }
        }
        if ($request->hasFile('secong_icon_img')) 
        {
                 $img=$store->secong_icon_img;
                 $file = $request->file('secong_icon_img');
                 $filename = $file->getClientOriginalName();
                 $extension = $file->getClientOriginalExtension() ?: 'png';
                 $folderName = '/upload/web';
                 $picture = str_random(10).time() . '.' . $extension;
                 $destinationPath = public_path() . $folderName;
                 $request->file('secong_icon_img')->move($destinationPath, $picture);
                 $store->secong_icon_img =$picture;
                 $image_path=public_path().'/upload/web/'.$img;
                  if(file_exists($image_path)) {
                            try{
                                 unlink($image_path);
                            }
                            catch(\Exception $e)
                            {
                                
                            }
                            
                        }
        }
       
        if ($request->hasFile('footer_up_img')) 
        {
                 $img=$store->footer_up_img;
                 $file = $request->file('footer_up_img');
                 $filename = $file->getClientOriginalName();
                 $extension = $file->getClientOriginalExtension() ?: 'png';
                 $folderName = '/upload/web';
                 $picture = str_random(10).time() . '.' . $extension;
                 $destinationPath = public_path() . $folderName;
                 $request->file('footer_up_img')->move($destinationPath, $picture);
                 $store->footer_up_img =$picture;
                 $image_path=public_path().'/upload/web/'.$img;
                  if(file_exists($image_path)) {
                            try{
                                 unlink($image_path);
                            }
                            catch(\Exception $e)
                            {
                                
                            }
                            
                        }
        }
        if ($request->hasFile('footer_img')) 
        {
                 $img=$store->footer_img;
                 $file = $request->file('footer_img');
                 $filename = $file->getClientOriginalName();
                 $extension = $file->getClientOriginalExtension() ?: 'png';
                 $folderName = '/upload/web';
                 $picture = str_random(10).time() . '.' . $extension;
                 $destinationPath = public_path() . $folderName;
                 $request->file('footer_img')->move($destinationPath, $picture);
                 $store->footer_img =$picture;
                 $image_path=public_path().'/upload/web/'.$img;
                  if(file_exists($image_path)) {
                            try{
                                 unlink($image_path);
                            }
                            catch(\Exception $e)
                            {
                                
                            }
                            
                        }
        }
        $store->save();
       Session::flash("message",__('successerr.data_save'));
       Session::flash('alert-class', 'alert-success');
       return redirect("setting/4");
    }
   
    public function postlogin(Request $request){ 
            $user=Sentinel::authenticate($request->all());
               if($user){
                   Session::put("profile_pic",asset("public/upload/images/profile/"."/".$user->profile_pic));
                   Session::put("currency",$user->currency);
                   Session::put("demo",$user->is_demo);
                   return  redirect("dashboard");    
               } 
               else{
                 
                Session::flash('message', __('successerr.login_error')); 
                Session::flash('alert-class', 'alert-danger');
                return redirect('admin');
               } 
    } 

    public function showdashboard(){
      $user=Sentinel::getUser();
      if($user){
        $today_order=Order::whereDate('created_at',date('y-m-d'))->get();
        $total_order=Order::all();
        $total_accept=Order::where("order_status","!=","0")->get();
        $delivery_boy=Delivery::where("is_deleted",'0')->where("attendance",'yes')->get();        
        return view("admin.dashboard")->with("today_order",count($today_order))->with("total_user",count($total_order))->with("total_accept",count($total_accept))->with("delivery",$delivery_boy);
      }
      return redirect("admin");
    }

    public function logout(){
      Sentinel::logout();
      return redirect("admin");
    }
   
     public function showuser(){
        return view("admin.user.default");
     }

     public function userdatatable(){
        $user =AppUser::orderBy('id','DESC')->where("is_deleted",'0')->get();

        return DataTables::of($user)
            ->editColumn('id', function ($user) {
                return $user->id;
            })
            ->editColumn('name', function ($user) {
                return $user->name;
            })
            ->editColumn('email', function ($user) {
                return $user->email;
            })
            ->editColumn('phone_no', function ($user) {
                return $user->mob_number;
            }) 
            ->editColumn('action', function ($user) {
               $delete= url('deleteuser',array('id'=>$user->id));
               $return = '<a onclick="delete_record(' . "'" . $delete . "'" . ')" rel="tooltip" title="" class="m-b-10 m-l-5" data-original-title="Remove"><i class="fa fa-trash f-s-25"></i></a>';
               return $return;               
            })
           
            ->make(true);
     }

     public function editprofile(){
       $user=Sentinel::getUser();
       return view("admin.updateprofile")->with("data",$user);
     }

     public function deleteuser($id){
        $store=AppUser::find($id);
        $store->is_deleted='1';
        $store->save();
        Session::flash('message',__('successerr.user_del')); 
        Session::flash('alert-class', 'alert-success');
        return redirect("users");
     }

     public function updateprofile(Request $request){
       
          $user=Sentinel::getUser();
           if ($request->hasFile('file')) 
              {
                 $file = $request->file('file');
                 $filename = $file->getClientOriginalName();
                 $extension = $file->getClientOriginalExtension() ?: 'png';
                 $folderName = '/upload/images/profile';
                 $picture = str_random(10).time() . '.' . $extension;
                 $destinationPath = public_path() . $folderName;
                 $request->file('file')->move($destinationPath, $picture);
                 $img_url =$picture;
             }else{
                 $img_url = $user->profile_pic;
             }
            $data=User::find($user->id);
            $data->name=$request->get("name");
            $data->mobile_number=$request->get("phone_no");
            $data->profile_pic=$img_url;
            $data->save();
            Session::put("profile_pic",asset("public/upload/images/profile/"."/".$img_url));
            Session::flash('message',__('successerr.pro_update_success')); 
            Session::flash('alert-class', 'alert-success');
            return redirect("editprofile");
     }

   public function changepassword(Request $request){
      return view("admin.changepassword");
   }  
   public  function check_password_same($pwd){
    $user=Sentinel::getUser();
     if (Hash::check($pwd, $user->password))
     {
        $data=1;
     }
    else{
        $data=0; 
     }
   return json_encode($data);
   }

   public function updatepassword(Request $request){
     $user=Sentinel::getUser();
       if (Hash::check($request->get('cpwd'), $user->password))
        {
            Sentinel::update($user, array('password' => $request->get('npwd')));
            Session::flash('message',__('successerr.pwd_update_success')); 
            Session::flash('alert-class', 'alert-success');
            return redirect("changepassword");
        }
       else{
          Session::flash('message',__('successerr.try_msg')); 
          Session::flash('alert-class', 'alert-danger');
          return redirect("changepassword");
       }
       
   }

   public function changecurreny($val){
     $store=User::find(1);
     $store->currency=$val;
     $store->save();
     Session::put("currency",$val);
     return redirect()->back();

   }

   public function changesettingorderstatus($status){
       $data=Setting::find(1);
       $data->order_status=$status;
       $data->save();
       return "done";

   }
  
}


