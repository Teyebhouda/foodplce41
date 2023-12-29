<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;
use Session;
use Cart;
use App\User;
use App\Order;
use App\AppUser;
use App\Delivery;
use App\Setting;
use App\Resetpassword;
use App\Item;
use Response;
use Cookie;
use App\FoodOrder;
use App\OrderResponse;
use DateTimeZone;
use DateTime;
use App\Ingredient;
use GuzzleHttp\Client; // Import GuzzleHttp\Client

class PaymentController extends Controller
{
	public function stripe(){
		 return view("stripe");
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
    public function pay(Request $request)
    {
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
      $store->total_price= number_format($request->get("total_price_or"), 2, '.', '');
      $store->order_placed_date=$date;
      $store->order_status=0;

      $store->latlong= strip_tags(preg_replace('#<script(.*?)>(.*?)</script>#is', '',$request->get("lat_long_or")));
      $store->name=$getuser->name;

      $store->address=strip_tags(preg_replace('#<script(.*?)>(.*?)</script>#is', '',$request->get("address_or")));
      $store->email=$getuser->email;

      $store->payment_type= strip_tags(preg_replace('#<script(.*?)>(.*?)</script>#is', '',$request->get("payment_type_or")));

      $store->notes= strip_tags(preg_replace('#<script(.*?)>(.*?)</script>#is', '',$request->get("note_or")));

      $store->city=strip_tags(preg_replace('#<script(.*?)>(.*?)</script>#is', '',$request->get("city_or")));

      $store->shipping_type= strip_tags(preg_replace('#<script(.*?)>(.*?)</script>#is', '',$request->get("shipping_type_or")));

     $store->subtotal=number_format($request->get("subtotal_or"), 2, '.', '');

      $store->delivery_charges=number_format($request->get("charage_or"), 2, '.', '');
     
      $store->phone_no=  strip_tags(preg_replace('#<script(.*?)>(.*?)</script>#is', '',$request->get("phone_or")));
      $store->delivery_mode=$store->shipping_type;
      $store->notify=1;
    
// api handle 
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
           $getmenu=Item::where("menu_name",$ke->name)->first();
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

//  handle api action 





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
       


      }
      if($store->shipping_type == 1){$shippingtype = "pickup"; }else{$shippingtype = "a domicile";}
      $apiLineData = [
        "IDCommande"   => $IDCommande,//here insert commande id
        "Référence"    => $getmenu->reference,
        "LibProd" => "Transport Marchandise :"  . $shippingtype,
        "Quantité"     => 1,
        "PrixVente"    => 1,
    ];

  
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

      $data=array("Order"=>$finalresult);
      $addresponse=new FoodOrder();
      $addresponse->order_id=$store->id;
      $addresponse->desc=json_encode($data);
      $addresponse->save();
      \Stripe\Stripe::setApiKey(Session::get("stripe_secret"));
            
                $unique_id = uniqid(); 
                $charge = \Stripe\Charge::create(array(
                    'description' => "Amount: ".$input['total_price_or'].' - '. $unique_id,
                    'source' => $request->stripeToken,                    
                    'amount' => (int)($input['total_price_or'] * 100), 
                    'currency' => 'EUR'
                ));
        $data=Order::find($store->id);
        $data->charges_id=$charge->id;
        if ($charge->status === 'succeeded') {
            $apiLineData = [
                "IDCommande"   => $IDCommande,//here insert commande id
                "Référence"    => $getmenu->reference,
                "LibProd" => "Moy Paiement  " . $store->payment_type . "\n" . "Payé" ,
                "Quantité"     => 1,
                "PrixVente"    => 1,
            ];
    
          
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
            echo "Payment successful!";
        } else {
            $apiLineData = [
                "IDCommande"   => $IDCommande,//here insert commande id
                "Référence"    => $getmenu->reference,
                "LibProd" => "Moy Paiement  " . $store->payment_type . "\n" . "NonPayé" ,
                "Quantité"     => 1,
                "PrixVente"    => 1,
            ];
    
          
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
            echo "Payment failed or pending.";
        }
        $data->save();
      Cart::clear();
      Session::flash('message', __('messages.order_success')); 
      Session::flash('alert-class', 'alert-success');
      return redirect("viewdetails/".$store->id);
       
       
    }
   
}