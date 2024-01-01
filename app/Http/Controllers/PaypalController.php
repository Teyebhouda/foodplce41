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
use GuzzleHttp\Client; // Import GuzzleHttp\Client

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
            ->setCurrency('EUR')
            ->setQuantity(1)
            ->setPrice($request->get('total_price_pal')); 

        $item_list = new ItemList();
        $item_list->setItems(array($item_1));

        $amount = new Amount();
        $amount->setCurrency('EUR')
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
                      $newUserData =  [
                        "Civilité" => 0,
                        "Nom" => $store->name,
                        "Prénom" => $store->name,
                        "Adresse" => $store->address,
                        "CodePostal" => "",
                        "Ville" => $store->city,
                        "Téléphone" => $store->phone_no,
                        "Mobile" => $store->phone_no,
                        "RIB" => "",
                        "Cin" => "",
                        "solde" => 0
                    ];
                    $client = new Client();
                    try {
                        // Make a POST request with the appropriate headers and JSON-encoded data
                        $apiLineResponse = $client->post("https://api.alaindata.com/foodplace41/Client", [
                            'headers' => [
                                'Content-Type' => 'application/json', // Set the Content-Type header
                            ],
                            'json' => $newUserData, // JSON-encode the data
                        ]);
                        $responseData = json_decode($apiLineResponse->getBody(), true);

                        // Access the 'IDCommande' from the decoded response data
                        $idClient = $responseData['IDClient'];
                    
                        function generateUniqueNumber() {
                            $min = 10000; // Minimum 5-digit number (inclusive)
                            $max = 99999; // Maximum 5-digit number (inclusive)
                            $randomNumber = mt_rand($min, $max);
                            return "W" . $randomNumber;
                        }
                        
                        function getCurrentDate() {
                            return date("Y-m-d"); // Returns current date in YYYY-MM-DD format
                        }
                       
                        $newCommandData = [
                            "IDClient" => $idClient,
                            "NuméroInterneCommande" => generateUniqueNumber(),
                            "DateCommande" => getCurrentDate(),
                            "TotalTTC" => $store->total_price,
                            // Other command data
                        ];
                        $client = new Client();
                        try {
                            // Make a POST request with the appropriate headers and JSON-encoded data
                            $apiLinecmd = $client->post("https://api.alaindata.com/foodplace41/Commande", [
                                'headers' => [
                                    'Content-Type' => 'application/json', // Set the Content-Type header
                                ],
                                'json' => $newCommandData, // JSON-encode the data
                            ]);
                            $responseData = json_decode($apiLinecmd->getBody(), true);

                            // Access the 'IDCommande' from the decoded response data
                            $IDCommande = $responseData['IDCommande'];
                        
                            $store->save();
                        
                        } catch (\GuzzleHttp\Exception\RequestException $e) {
                            // Handle exceptions, log errors, etc.
                            // Log an error if an exception occurs during the request
                            error_log("API request error: " . $e->getMessage());
                        }
                        
                    
                    } catch (\GuzzleHttp\Exception\RequestException $e) {
                        // Handle exceptions, log errors, etc.
                        // Log an error if an exception occurs during the request
                        error_log("API request error: " . $e->getMessage());
                    }
                    
                    
                    
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
                      
//api handle ///////


$ingredientString = '';
foreach ($ingredient as $ing) {
    $ingredientString .= $ing['item_name'] . ', '; // Adjust as per your required format
}
$ingredientString = rtrim($ingredientString, ', '); // Remove the trailing comma and space

// Now use $ingredientString in your $apiLineData
$apiLineData = [
    "IDCommande"   => $IDCommande,//here insert commande id
    "Référence"    => $getmenu->reference,
    "LibProd"      => $getmenu->menu_name . ' - Ingredients: ' . $ingredientString,
    "Quantité"     => $result["ItemQty"],
    "PrixVente"    => number_format($result["ItemTotalPrice"], 2, '.', ''),
];

//  dd( $apiLineData);



// LigneDocument API request
$client = new Client();
try {
    // Make a POST request with the appropriate headers and JSON-encoded data
    $apiclientResponse = $client->post("https://api.alaindata.com/foodplace41/LigneCommande", [
        'headers' => [
            'Content-Type' => 'application/json', // Set the Content-Type header
        ],
        'json' => $apiLineData, // JSON-encode the data
    ]);

    // Handle the response here
} catch (\GuzzleHttp\Exception\RequestException $e) {
    // Handle exceptions, log errors, etc.
    // Log an error if an exception occurs during the request
    error_log("API request error: " . $e->getMessage());
}




                break;
            }
        }
        if($store->shipping_type == 0){$shippingtype = "a domicile"; }else{$shippingtype = "pickup";}
        $apiLineData = [
          "IDCommande"   => $IDCommande,//here insert commande id
          "Référence"    =>"",
          "LibProd" => "Transport Marchandise :"  . $shippingtype,
          "Quantité"     => 1,
          "PrixVente"    => 1,
      ];
  
    
    
     
          // Make a POST request with the appropriate headers and JSON-encoded data
          $apiclientResponse = $client->post("https://api.alaindata.com/foodplace41/LigneCommande", [
              'headers' => [
                  'Content-Type' => 'application/json', // Set the Content-Type header
              ],
              'json' => $apiLineData, // JSON-encode the data
          ]);
      
          // Handle the response here
          $apiLineData = [
            "IDCommande"   => $IDCommande,//here insert commande id
            "Référence"    => "",
            "LibProd" => "Moy Paiement  : Paypal" . "\n" . "NonPayé" ,
            "Quantité"     => 1,
            "PrixVente"    => now()->format('Y-m-d H:i:s'),
        ];

      
        
        try {
            // Make a POST request with the appropriate headers and JSON-encoded data
            $apiclientResponse = $client->post("https://api.alaindata.com/foodplace41/LigneCommande", [
                'headers' => [
                    'Content-Type' => 'application/json', // Set the Content-Type header
                ],
                'json' => $apiLineData, // JSON-encode the data
            ]);
        
            // Handle the response here
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Handle exceptions, log errors, etc.
            // Log an error if an exception occurs during the request
            error_log("API request error: " . $e->getMessage());
        }



        Session::put('paypal_payment_id', $payment->getId());
        Session::put('IdCommande', $IDCommande);

        if(isset($redirect_url)) {
     
            return Redirect::away($redirect_url);
        }
         $order=Order::where("pay_pal_paymentId",$payment->getId())->first();
         if(count($order)!=0){
            $order->delete();
         }
         Session::flash('message',__('successerr.payment_fail')); 
             Session::flash('alert-class', 'alert-danger');
            return redirect('checkout');
    }





    public function getPaymentStatus(Request $request)
    {


        $client = new Client();
        $payment_id = Session::get('paypal_payment_id');
      $commandeid = Session::get('IdCommande');
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
             $apiLineData = [
                "IDCommande"   => $commandeid,//here insert commande id
                "Référence"    => "123",
                "LibProd" => "Moy Paiement  : Paypal" . "\n" . "Payé" ,
                "Quantité"     => 1,
                "PrixVente"    => now()->format('Y-m-d H:i:s'),
            ];
    
          
            
            try {
                // Make a POST request with the appropriate headers and JSON-encoded data
                $apiclientResponse = $client->post("https://api.alaindata.com/foodplace41/LigneCommande", [
                    'headers' => [
                        'Content-Type' => 'application/json', // Set the Content-Type header
                    ],
                    'json' => $apiLineData, // JSON-encode the data
                ]);
                
            
                // Handle the response here
            } catch (\GuzzleHttp\Exception\RequestException $e) {
                // Handle exceptions, log errors, etc.
                // Log an error if an exception occurs during the request
                error_log("API request error: " . $e->getMessage());
            }
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
    function generateUniqueNumber() {
        $min = 10000; // Minimum 5-digit number (inclusive)
        $max = 99999; // Maximum 5-digit number (inclusive)
        $randomNumber = mt_rand($min, $max);
        return "W" . $randomNumber;
    }
    
    function getCurrentDate() {
        return date("Y-m-d"); // Returns current date in YYYY-MM-DD format
    }
}
