<?php 
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Validator;
use URL;
use Session;
use Redirect;
use Input;
use Cart;
use App\User;
use App\Order;
use App\AppUser;
use App\Delivery;
use App\Setting;
use App\Resetpassword;
use DateTimeZone;
use App\Item as itemli;
use DateTime;
use Response;
use Cookie;
use App\FoodOrder;
use App\OrderResponse;
use App\Ingredient;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;

class PaypalController extends Controller
{
    private $_api_context;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $setting=Setting::find(1);
        $paypal_conf = \Config::get('paypal');
        if($setting->paypal_mode==0){
           $mode="sandbox";
        }
        else{
          $mode="live";
        }
        $this->_api_context = new ApiContext(new OAuthTokenCredential($setting->paypal_client_id,$setting->paypal_client_secret));
        $this->_api_context->setConfig(array('mode' =>$mode,'http.ConnectionTimeOut' => 1000,'log.LogEnabled' => true,'log.FileName' => storage_path() . '/logs/paypal.log','log.LogLevel' => 'FINE'));
    }

    /**
     * Show the application paywith paypalpage.
     *
     * @return \Illuminate\Http\Response
     */
    public function payWithPaypal()
    {
        return view('paywithpaypal');
    }

    /**
     * Store a details of payment with paypal.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
    public function postPaymentWithpaypal(Request $request)
    {
      
        $cartCollection = Cart::getContent();
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item_1 = new Item();

        $item_1->setName(__('messages.site_name')) 
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($request->get('total_price_pal')); 

        $item_list = new ItemList();
        $item_list->setItems(array($item_1));

        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($request->get('total_price_pal'));

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('Your transaction description');

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::route('status')) 
            ->setCancelUrl(URL::route('status'));

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
            if (\Config::get('app.debug')) {
                \Session::put('error',__('successerr.connection_timeout'));
                return Redirect::route('paywithpaypal');
              
            } else {
                \Session::put('error',__('successerr.error1'));
                return Redirect::route('paywithpaypal');
                
            }
        }

        foreach($payment->getLinks() as $link) {
            if($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                $data=array();
                      $finalresult=array();
                      $result=array();
                       $input = $request->input();
                      $cartCollection = Cart::getContent();
                      $setting=Setting::find(1);
                      $gettimezone=$this->gettimezonename($setting->timezone);
                      date_default_timezone_set($gettimezone);
                      $date = date('d-m-Y H:i');
                      $getuser=AppUser::find(Session::get('login_user'));
                      $store=new Order();
                      $store->user_id=$getuser->id;

                      $store->total_price=number_format($request->get("total_price_pal"), 2, '.', '');
                      $store->order_placed_date=$date;
                      $store->order_status=0;

                      $store->latlong= strip_tags(preg_replace('#<script(.*?)>(.*?)</script>#is', '',$request->get("lat_long_or")));
                      $store->name=$getuser->name;

                      $store->address=strip_tags(preg_replace('#<script(.*?)>(.*?)</script>#is', '',$request->get("address_pal")));
                      $store->email=$getuser->email;

                      $store->payment_type= strip_tags(preg_replace('#<script(.*?)>(.*?)</script>#is', '',$request->get("payment_type_pal")));

                      $store->notes=strip_tags(preg_replace('#<script(.*?)>(.*?)</script>#is', '',$request->get("note_or")));

                      $store->city= strip_tags(preg_replace('#<script(.*?)>(.*?)</script>#is', '',$request->get("city_or")));

                      $store->shipping_type= strip_tags(preg_replace('#<script(.*?)>(.*?)</script>#is', '',$request->get("shipping_type_pal")));

                      $store->subtotal=number_format($request->get("subtotal_pal"), 2, '.', '');

                      $store->delivery_charges=number_format($request->get("charage_pal"), 2, '.', '');

                      $store->phone_no= strip_tags(preg_replace('#<script(.*?)>(.*?)</script>#is', '',$request->get("phone_pal")));
                      $store->pay_pal_paymentId=$payment->getId();
                      $store->delivery_mode=$store->shipping_type;
                      $store->notify=1;
                      $store->save();
                      foreach ($cartCollection as $ke) {
                            $getmenu=itemli::where("menu_name",$ke->name)->first();
                           $result['ItemId']=(string)isset($getmenu->id)?$getmenu->id:0;
                           $result['ItemName']=(string)$ke->name;
                           $result['ItemQty']=(string)$ke->quantity;
                           $result['ItemAmt']=number_format($ke->price, 2, '.', '');
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
                      
                break;
            }
        }

    
        Session::put('paypal_payment_id', $payment->getId());

        if(isset($redirect_url)) {
     
            return Redirect::away($redirect_url);
        }
         $order=Order::where("pay_pal_paymentId",$payment_id)->first();
         if(count($order)!=0){
            $order->delete();
         }
         Session::flash('message',__('successerr.payment_fail')); 
             Session::flash('alert-class', 'alert-danger');
            return redirect('checkout');
    }

    public function getPaymentStatus(Request $request)
    {


      
        $payment_id = Session::get('paypal_payment_id');
      
        if (empty($request->get('PayerID')) || empty($request->get('token'))) {
            \Session::put('error','Payment failed');
             $order=Order::where("pay_pal_paymentId",$payment_id)->first();
             if(count($order)!=0){
                $order->delete();
             }
             Session::flash('message',__('successerr.payment_fail')); 
             Session::flash('alert-class', 'alert-danger');
            return redirect('checkout');
        }
        $payment = Payment::get($payment_id, $this->_api_context);
       
        $execution = new PaymentExecution();
        $execution->setPayerId($request->get('PayerID'));
       
        $result = $payment->execute($execution, $this->_api_context);
        if ($result->getState() == 'approved') { 
             $order=Order::where("pay_pal_paymentId",$payment_id)->first();
             $order->pay_pal_token=$request->get('token');
             $order->pay_pal_PayerID=$request->get('PayerID');
             $order->save();
             Cart::clear();
             Session::flash('message', __('messages.order_success')); 
             Session::flash('alert-class', 'alert-success');
             return redirect("viewdetails/".$order->id);
        }
         $order=Order::where("pay_pal_paymentId",$payment_id)->first();
         if(count($order)!=0){
            $order->delete();
         }
         Session::flash('message',__('successerr.payment_fail')); 
             Session::flash('alert-class', 'alert-danger');
            return redirect('checkout');
    }

}
