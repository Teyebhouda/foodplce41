<?php 
   function readMoreHelper($story_desc, $chars = 75) {
       $story_desc = substr($story_desc,0,$chars);  
       $story_desc = substr($story_desc,0,strrpos($story_desc,' '));  
       $story_desc = $story_desc."...";  
       return $story_desc;  
   }
   function headreadMoreHelper($story_desc, $chars =75) {
       $story_desc = substr($story_desc,0,$chars);  
       $story_desc = substr($story_desc,0,strrpos($story_desc,' '));  
       $story_desc = $story_desc;  
       return $story_desc;  
   } 
   ?>
<!DOCTYPE html>
<html>
   <head>
       
       <title>{{__('messages.site_name')}}</title>
       
       <meta charset="UTF-8">
      <meta property="og:url" content="{{__('messages.site_name')}}"/>
       <meta property="og:title" content="{{__('messages.site_name')}}"/>
       <meta property="og:image" content="{{asset('public/favicon.png')}}"/>
       <meta property="og:image:width" content="250px"/>
       <meta property="og:image:height" content="250px"/>
       <meta property="og:site_name" content="{{__('messages.site_name')}}"/>
       <meta property="og:description" content="{{__('messages.metadescweb')}}"/>
       <meta property="og:keyword" content="{{__('messages.metakeyboard')}}"/>
       <meta name="viewport" content="width=device-width, initial-scale=1">
       
     <link rel="shortcut icon" href="{{asset('public/favicon.png')}}">
        @if(__('messages.rtl')=='0')
      
         <link rel="stylesheet" type="text/css" href="{{asset('burger/css/style.css')}}">
         @else
           <link rel="stylesheet" type="text/css" href="{{asset('burger/css/rtl.css')}}">
         @endif
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.7.1/slick.css'>
       <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
      <script type="text/javascript" src="{{asset('burger/js/bootstrap.min.js')}}"></script>
      <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
      <link rel="stylesheet" type="text/css" href="{{asset('burger/css/app.css')}}"/>
      <link rel="stylesheet" href="{{asset('burger/css/font.css')}}">
      


      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
     

      <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

      <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
       <link rel="stylesheet" type="text/css" href="{{asset('burger/css/bootstrap.min.css')}}">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
     
     
     
      <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
      <script type="text/javascript" src="{{asset('burger/js/dropdown.js')}}"></script>
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.5/slick.min.js"></script>
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/lightslider/1.1.6/js/lightslider.js"></script>
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.js"></script>
      <script type="text/javascript" src=""></script>
      <script type="text/javascript" src="{{asset('burger/js/jquery.mixitup.min.js')}}"></script>
      <script type="text/javascript" src='https://maps.google.com/maps/api/js?key=AIzaSyC1JUHjsnQZtKx5eBOpG42E_CLoJ1s39AU&sensor=false&libraries=places'></script>
      <script src="{{url('public/js/locationpicker.js')}}"></script>
     
   </head>
   
   <body>
   @if (!($Ok_Status))
      @include('user.popup_client')
      @endif
      @include('cookieConsent::index')
      @include('user.cssclass')
      <div class="login-re-modal">
         <div class="modal" id="myModal1">
            <div class="modal-dialog">
               <div class="modal-content">
                    <div id="overlaychk">
                        <div class="cv-spinner">
                           <span class="spinner"></span>
                        </div>
                     </div>
                  <div class="modal-header">
                     <div class="login-modal-head">
                        <button type="button" class="close" data-dismiss="modal">
                        &times;
                        </button>
                     </div>
                  </div>
                  <div class="modal-body">
                      
                     <div id="forgotbody" class="forgotbody">
                        <span class="for_success_msg" id="for_success_msg">  
                        {{__('messages.forgot_email_success')}}
                        </span>
                        <span class="for_error_msg" id="for_error_msg">
                        {{__('messages.forgot_error')}}
                        </span>
                        <form>
                           <label class="for_label">
                           {{__('messages.forgot_text')}}
                           <span class="requiredfield">*</span>
                           </label>
                           <input type="text" required name="phone_for" id="modal-text" placeholder="{{__('messages.forgot_placeholder')}}" class="reuiredtextfields"/>
                        </form>
                        <div class="for_btn_div">
                           <div class="modal-login-button for_btn_div_model" >
                              <button type="button" onclick="forgotaccount()"class="for_button">
                                 <h6 class="for_button_value">   
                                    {{__('messages.forgot_pwd')}}
                                 </h6>
                              </button>
                           </div>
                        </div>
                        <div class="modal-forgot">
                           <a href="javascript:loginmodel()">
                           {{__('messages.login')}}
                           </a>
                        </div>
                     </div>
                     <div class="login-modal" id="loginmodel">
                        <ul class="nav nav-tabs" role="tablist">
                           <li class="nav-item">
                              <a href="#tab1" class="nav-link active" data-toggle="tab">
                              {{__('messages.login')}}
                              </a>
                           </li>
                           <li class="nav-item">
                              <a href="#tab2" class="nav-link" data-toggle="tab">
                              {{__('messages.register')}}
                              </a>
                           </li>
                        </ul>
                        <div class="tab-content">
                           <div id="tab1" class="tab-pane show active">
                              <span id="login_error_msg" class="for_error_msg">  
                              {{__('messages.login_error')}}
                              </span>
                              <label class="for_button_value">
                              {{__('messages.phone_no')}} 
                              <span class="requiredfield">*</span>
                              </label>
                              <input type="text" required name="phone" id="modal-text" placeholder="{{__('messages.phone_no')}}" value="{{isset($_COOKIE['phone'])?$_COOKIE['phone']:''}}">
                              <label class="for_button_value">
                              {{__('messages.password')}}
                              <span class="requiredfield">*</span>
                              </label>
                              <input type="password" required name="password" id="modal-text" placeholder="{{__('messages.password')}}" value="{{isset($_COOKIE['password'])?$_COOKIE['password']:''}}">
                              <div class="modal-re">
                                 <input type="checkbox" name="rem_me" value="1" <?php echo isset($_COOKIE[ 'rem_me'])? "checked": ''?>>
                                 <p>{{__('messages.rem_me')}}</p>
                              </div>
                              <span class="modal-forgot">
                              <a href="javascript:forgotmodel()" >{{__('messages.forgot_u')}}</a>
                              </span>
                              <div class="modal-login-button ">
                                 <button type="button" onclick="loginaccount()" class="for_button">
                                    <h6 class="for_button_value">{{__('messages.login')}}</h6>
                                 </button>
                              </div>
                           </div>
                           <div id="tab2" class="tab-pane ">
                              <span class="for_success_msg" id="reg_success_msg">
                              {{__('messages.register_suceess')}}
                              </span>
                              <span class="for_error_msg" id="reg_error_msg">
                              {{__('messages.reg_error')}}
                              </span>
                              <form action="{{url('userregister')}}" method="post">
                                 {{csrf_field()}}
                                 <label class="for_button_value">        
                                 {{__('messages.name')}}
                                 <span class="requiredfield">*</span>
                                 </label>
                                 <input type="text" required name="name" id="modal-text" placeholder="{{__('messages.name')}}">
                                 <label class="for_button_value">
                                 {{__('messages.email')}}
                                 <span class="requiredfield">*</span>
                                 </label>
                                 <input type="text" required name="email" id="modal-text" placeholder="{{__('messages.email')}}">
                                 <label class="for_button_value">
                                 {{__('messages.phone_no')}}
                                 <span class="requiredfield">*</span>
                                 </label>
                                 <input type="text" required name="phone_reg" id="modal-text" placeholder="{{__('messages.phone_no')}}">
                                 <label>{{__('messages.address')}} <span>*</span></label>
                              <input type="text" required name="address" id="modal-text" placeholder="{{__('messages.address')}}">
                             
                                 <label class="for_button_value">
                                 {{__('messages.password')}}
                                 <span class="requiredfield">*</span>
                                 </label>
                                 <input type="password" required name="password_reg" id="modal-text" placeholder="{{__('messages.password')}}">
                                 <label class="for_button_value">
                                 {{__('messages.confirm_pwd')}}
                                 <span sclass="requiredfield">*</span>
                                 </label>
                                 <input type="password" required name="con_password_reg" id="modal-text" placeholder="{{__('messages.confirm_pwd')}}" onchange="checkboth(this.value)">
                                 <div class="modal-rg-content">
                                    <p>{{__('messages.reg_fixed')}}</p>
                                 </div>
                                 <div class="modal-login-button">
                                    <button type="button" onclick="registeruser()" class="for_button">
                                       <h6>{{__('messages.register')}}</h6>
                                    </button>
                                 </div>
                              </form>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="modal model1my" id="myModal" >
         <div class="modal-dialog modal-1">
            <?php $cartCollection = Cart::getContent(); 
               if($cartCollection->count()==0){?>
            <div class="empty-box">
               <div class="modal-header cart-h">
                  <button type="button" class="close" data-dismiss="modal">
                  <img src='{{asset("burger/images/close.png")}}'>
                  </button>
               </div>
               <div class="container">
                  <div class="cart-modal-empty col-md-8">
                     <img src='{{asset("burger/images/empty-cart.png")}}'>
                     <h1>{{__('messages.nocart')}}</h1>
                  </div>
               </div>
            </div>
            <?php }else{ ?>
            <div class="modal-content">
               <div class="modal-header cart-h">
                  <button type="button" class="close" data-dismiss="modal">
                  <img src="{{asset('burger/images/close.png')}}">
                  </button>
               </div>
               <div class="container">
                  <div class="cart-modal col-md-8">
                     <div class="modal-body main-modal ">
                        <?php $cartCollection = Cart::getContent();$i=0;?>
                        @foreach($cartCollection as $item)
                        <div class="portfolio por-1 col-md-12 row">
                           <div class="por-img">
                              <div class="b-img">
                                 @foreach($allmenu as $ai)  
                                 @if($item->name==$ai->menu_name)
                                 <img src="{{asset('public/upload/images/menu_item_icon/'.$ai->menu_image)}}" class="cartth"> 
                                 @endif
                                 @endforeach
                              </div>
                           </div>
                           <div class="b-text">
                              <div class="box-spa">
                                 <h1>{{$item->name}}</h1>
                                 <h2>
                                    @foreach($item->attributes[0] as $cartinter) 
                                    @foreach($ingredient as $me)    
                                    @if($cartinter==$me->id)
                                    <span>{{$me->item_name}},</span>
                                    @endif
                                    @endforeach 
                                    @endforeach
                                 </h2>
                                 <p>{{$item->quantity}}
                                    <img src="{{asset('burger/images/cross.png')}}">
                                    {{Session::get("usercurrency").number_format($item->price, 2, '.', '')}}
                                 </p>
                              </div>
                              <span class="price">
                                 <?php $totalamount=(float)$item->quantity*(float)$item->price;?>
                                 <a href="{{url('deletecartitem/'.$item->id)}}">
                                 <i class="fa fa-trash-o" aria-hidden="true">
                                 </i>
                                 </a>
                                 <h1>
                                    {{Session::get("usercurrency")}}{{number_format($totalamount, 2, '.', '')}}
                                 </h1>
                              </span>
                           </div>
                        </div>
                        @endforeach
                     </div>
                     <div class="total">
                        <h1>{{__('messages.subtotal')}} :</h1>
                        <span>
                        {{Session::get("usercurrency").number_format(Cart::getTotal(), 2, '.', '')}} 
                        </span>
                     </div>
                     <?php if(Session::get("orderstatus")==1){ ?>
                     <div class="viewcart">
                        <h1>
                           <a href="{{url('cartdetails')}}" class="viewcarta">
                           {{__('messages.view_cart')}}
                           </a>
                        </h1>
                     </div>
                     <?php }?>
                     <?php if(Session::get("orderstatus")==0){ ?>
                     <div class="last-box">
                        <div class="Delivery">
                           <img src="{{asset('burger/images/delivery.png')}}" style="width:50px">
                        </div>
                        <div class="last-text">
                           <h1>{{__('messages.offline_order')}}</h1>
                           <p>{{__('messages.off_time')}}</p>
                        </div>
                     </div>
                     <?php }?>
                  </div>
               </div>
            </div>
            <?php }?>
         </div>
      </div>
      <div class="first-section">
         <div class="img">
         </div>
         <div class="container kb-nav">
            <div class="row">
               <div class="kb-nav-logo">
                  <a href="{{url('/')}}">
                  <img src="{{Session::get('logo')}}" class="img-fluid">
                  </a>
               </div>
               <div class="kb-menu">
                  <ul class="p-0">
                     <li>
                        <a href="{{url('/')}}" class="for_button_value">
                        {{ __('messages.home') }}
                        </a>
                     </li>
                     <li>
                        <a href="{{url('aboutus')}}" class="for_button_value">
                        {{__('messages.aboutus')}}
                        </a>
                     </li>
                     {{-- <li>
                        <a href="{{url('service')}}" class="for_button_value">
                        {{__('messages.service')}}
                        </a>
                     </li> --}}
                     <li>
                        <a href="{{url('contactus')}}" class="for_button_value">
                        {{__('messages.contact')}}
                        </a>
                     </li>
                  </ul>
               </div>
               <div class="kd-social">
                  <div class="login">
                     <?php if(empty(Session::get('login_user'))){?>
                     <a href="#" data-toggle="modal" data-target="#myModal1">
                     <i class="fa fa-user" aria-hidden="true"></i>
                     </a>
                     <?php }else{?>
                     <a href="{{url('myaccount')}}">
                     <i class="fa fa-user" aria-hidden="true"></i>
                     </a>
                     <?php }?>
                     <a href="#" data-toggle="modal" data-target="#myModal">
                     <i class="fa fa-shopping-cart fa-3x" aria-hidden="true">
                     <span id="totalcart">
                     <?php $cartCollection = Cart::getContent();
                        $carttotal=0;
                         if($cartCollection->count()!=0)
                             {
                                 $carttotal=$cartCollection->count();
                                 echo '<div class="cric">'.$cartCollection->count().'</div>';
                             }
                        ?> 
                     </span>
                     <input type="hidden" id="carttotal" value="{{$carttotal}}">
                     </i></a>
                  </div>
                  <a href="{{ Session::get('facebook')}}"  target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                  <a href="{{ Session::get('twitter')}}"  target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                  <a href="{{ Session::get('whatsapp')}}"  target="_blank"><i class="fa fa-whatsapp" aria-hidden="true"></i></a>
               </div>
               <div class="tm">
                  <div id="toggle" onclick="changetab()">
                     <div class="one"></div>
                     <div class="two"></div>
                     <div class="three"></div>
                  </div>
               </div>
               <div class="col-12">
                  <div id="menu">
                     <ul class="p-0">
                        <li><a href="{{url('/')}}">{{ __('messages.home') }}</a></li>
                        <li><a href="{{url('aboutus')}}">{{__('messages.aboutus')}}</a></li>
                        {{-- <li><a href="{{url('service')}}">{{__('messages.service')}}</a></li> --}}
                        <li><a href="{{url('contactus')}}">{{__('messages.contact')}}</a></li>
                     </ul>
                  </div>
               </div>
               <div class="kb-text-box">
                  <h1>{{__('messages.silder1h')}}</h1>
                  <h2>{{__('messages.silderh2')}}</h2>
                  <p>{{__('messages.silderp')}}.</p>
               </div>
            </div>
         </div>
      </div>
      <div class="secound-section">
         <div class="container">
            <div class="col-md-12">
               <div class="row">
                  <div class="col-md-6 about-img">
                     <img src="{{Session::get('second_sec_img')}}" class="img-fluid">
                  </div>
                  <div class="col-md-6 about-text">
                     <img src="{{Session::get('secong_icon_img')}}" class="img-fluid">
                     <h5>{{__('messages.silder23')}}
                     </h5>
                     <p>
                        {{__('messages.psilder23')}}
                     </p>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="heading">

         <h1>
            
         {{__('messages.menu_title')}}</h1>
         
      </div>
    

         <div class="main-pizza-sb">
            <div class="container">
               <div class="carousel">
                  <?php $i=0;?>
                  @foreach($category as $ca)
                  @if($i==0)
                  <div class="box King_script_active" id="box{{$i}}" onclick="changecategory('{{$ca->id}}','{{$i}}')">
                     @else
                     <div class="box" id="box{{$i}}" onclick="changecategory('{{$ca->id}}','{{$i}}')">
                     @endif
                     <button class="pizza-category-menu">
                        <?php $img=asset('public/upload/images/menu_cat_icon/').'/'.$ca->cat_icon; ?>
                        <div class="pizza-content-box">
                           <div class="pizza-content-imgb">
                              <img src="{{asset($img)}}" alt="img">
                           </div>
                           <div class="pizza-content-textb">
                              <h3>{{$ca->cat_name}}</h3>
                           </div>
                        </div>
                     </button>
                  </div>
                  <?php $i++;?>
                  @endforeach                  
               </div>
            </div>
         </div>
         <input type="hidden" id="totalcategory" value="{{$i}}">
      </div>
      <div class="container">
         <div class="portfolist_sb_b" id="portfoliolist">
            <div class="row">
               <?php $i=0;?>
               @foreach($category as $ca)
                  @if($i==0)
                    <div id="category{{$i}}" class="mtcat" >
                  @else
                     <div id="category{{$i}}" style="display: none" class="mtcat">
                  @endif
               @foreach($items as $it)
                 @if($it->category==$ca->id)
               <div class="portfolio {{$it->categoryitem->id}} col-md-6 burger w3-container  w3-animate-zoom portfoliocat" data-cat="{{$it->categoryitem->id}}" data-bound>
                  <div class="items">
                     <div class="b-img">
                        <a href="{{url('detailitem/'.$it->id)}}"><img src="{{asset('public/upload/images/menu_item_icon/'.$it->menu_image)}}"></a>
                     </div>
                     <div class="bor">
                        <div class="b-text">
                           <h1><a href="{{url('detailitem/'.$it->id)}}">{{$it->menu_name}}</a></h1>
                           <p>
                              {{substr($it->description,0,75)}}
                           </p>
                        </div>
                        <div class="price">
                           <h1>{{Session::get("usercurrency")}} {{$it->price}}</h1>
                           <div class="cart">
                              <a href="{{url('detailitem/'.$it->id)}}">{{__('messages.addcart')}}</a>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               @endif
               @endforeach
               <?php $i++;?>
            </div>
               @endforeach
            </div>
         </div>
      </div>
      {{-- <div class="play">
         <div class="container">
            <div class="row">
               <div class="col-md-6">
                  <div class="play-logo">
                     <img src="{{Session::get('logo')}}">
                  </div>
                  <div class="play-b">
                     <img src="{{Session::get('footer_up_img')}}">
                  </div>
               </div>
               <div class="col-md-6 text-box">
                  <div class="head">
                     <h1>{{__('messages.bannerh')}}</h1>
                     <p>{{__('messages.bannerp')}}</p>
                  </div>
                  <h2>{{__('messages.bennerh2')}}</h2>
                  <ul>
                     <li>
                        <span>
                           <div class="crl"></div>
                        </span>
                        {{__('messages.b1')}}
                     </li>
                     <li>
                        <span>
                           <div class="crl"></div>
                        </span>
                        {{__('messages.b2')}}
                     </li>
                     <li>
                        <span>
                           <div class="crl"></div>
                        </span>
                        {{__('messages.b3')}}
                     </li>
                     <li>
                        <span>
                           <div class="crl"></div>
                        </span>
                        {{__('messages.b4')}}
                     </li>
                  </ul>
                  <div class="row">
                     @if($setting->have_playstore=='1')
                     <div class="col-md-5 col-sm-5 col-5 google">
                        <a href="{{ Session::get('playstore')}}" target="_blank"><img src="{{asset('burger/images/Google-Play-icon.png')}}" class="img-fluid"></a>
                     </div>
                     @endif
                     @if($setting->have_appstore=='1')
                     <div class="col-md-5 col-sm-5 col-5 apple">
                        <a href="{{ Session::get('appstore')}}" target="_blank"><img src="{{asset('burger/images/App-Store-icon.png')}}" class="img-fluid"></a>
                     </div>
                     @endif
                  </div>

               </div>
            </div>
         </div>
      </div> --}}
      <div class="footer-section">
         <div class="footer">
            <div class="container kb-footer">
               <div class="row">
                  <div class="col-md-3 about">
                     <img src="{{Session::get('logo')}}">
                     <p>{{__('messages.footer_text')}}</p>
                     <div class="footer-social">
                        <a href="{{ Session::get('facebook')}}" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                        <a href="{{ Session::get('twitter')}}"  target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                        <a href="{{ Session::get('linkedin')}}" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                        <a href="{{ Session::get('google_plus_id')}}" target="_blank"><i class="fa fa-google-plus" aria-hidden="true"></i></a>
                        <a href="{{ Session::get('whatsapp')}}" target="_blank"><i class="fa fa-whatsapp" aria-hidden="true"></i></a>
                     </div>
                  </div>
                  <div class="col-md-3 info">
                     <div class="fo-text">
                        <h1>{{__('messages.information')}}</h1>
                     </div>
                     <ul class="textdata">
                        <li><a href="{{url('/')}}">{{__('messages.home')}}</a></li>
                        <li><a href="{{url('aboutus')}}">{{__('messages.aboutus')}}</a></li>
                        {{-- <li><a href="{{url('service')}}">{{__('messages.service')}}</a></li> --}}
                        <li><a href="{{url('contactus')}}">{{__('messages.contact')}}</a></li>
                     </ul>
                  </div>
                  <div class="col-md-3 info">
                     <div class="fo-text">
                        <h1>{{__('messages.myaccount')}}</h1>
                     </div>
                     <ul class="textdata">
                        @if(empty(Session::get('login_user')))
                        <li><a href="#" data-toggle="modal" data-target="#myModal1">{{__('messages.myorder')}}</a></li>
                        @endif
                        @if(!empty(Session::get('login_user')))
                        <li><a href="{{url('myaccount')}}">{{__('messages.myorder')}}</a></li>
                        @endif
                        <?php $cartCollection = Cart::getContent();?>
                        @if(count($cartCollection)!=0)
                        <li><a href="{{url('cartdetails')}}">{{__('messages.checkout')}}</a></li>
                        @endif
                        @if(count($cartCollection)==0)
                        <li><a href="#" data-toggle="modal" data-target="#myModal">{{__('messages.checkout')}}</a></li>
                        @endif
                        <li><a  href="#" data-toggle="modal" data-target="#myModal">{{__('messages.cart')}}</a></li>
                        <li><a href="{{url('termofuse')}}">{{__('messages.terms')}}</a></li>
                     </ul>
                  </div>
                  <div class="col-md-3 info">
                     <div class="fo-text">
                        <h1>{{__('messages.contact_us')}}</h1>
                     </div>
                     <div class="f-location">
                        <img src="{{asset('burger/images/location.png')}}">
                     </div>
                     <span>
                     {{Session::get('address')}}
                     </span>
                     <div class="f-location">
                        <img src="{{asset('burger/images/phone.png')}}">
                     </div>
                     <span>
                     {{Session::get('phone')}}
                     </span>
                     <div class="f-location">
                        <img src="{{asset('burger/images/email.png')}}">
                     </div>
                     <span>
                     {{Session::get('email')}}
                     </span>
                  </div>
               </div>
            </div>
            <div class="right">
               <div class="container">
                  <div class="row">
                     <div class="col-md-6">
                        <h1>{{__('messages.copyright')}} © {{Session::get('current_year')}} {{__('messages.site_name')}}. {{__('messages.f1')}}.</h1>
                     </div>
                     <div class="col-md-6 v-box">
                        <div class="v-img">
                           <a href="#"><img src="{{asset('burger/images/v-1.png')}}"></a>
                        </div>
                        <div class="v-img">
                           <a href="#"><img src="{{asset('burger/images/v-2.png')}}"></a>
                        </div>
                        <div class="v-img">
                           <a href="#"><img src="{{asset('burger/images/v-3.png')}}"></a>
                        </div>
                        <div class="v-img">
                           <a href="#"><img src="{{asset('burger/images/v-4.png')}}"></a>
                        </div>
                        <div class="v-img">
                           <a href="#"><img src="{{asset('burger/images/v-5.png')}}"></a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="img-aa"></div>
   </body>
   <div class="modal modal-2" id="myModal2">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header logout">
               <h4 class="modal-title">{{__('messages.register')}}</h4>
               <p>{{Session::get('user_phone')}}</p>
               <h1>{{__('messages.logout_msg')}}</h1>
               <div class="logout-but">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">{{__('messages.cancel')}}</button>
                  <button type="button" class="btn-1" data-dismiss="modal" onclick="logout()"><i class="fa fa-sign-out" aria-hidden="true"></i>{{__('messages.logout')}}
                  </button>
               </div>
            </div>
         </div>
      </div>
   </div>
   <input type="hidden" id="is_rtl" value="{{__('messages.rtl')}}"/>
   <input type="hidden" id="currency" value='{{Session::get("usercurrency")}}' />
   <input type="hidden" id="addcart" value='{{__("messages.addcart")}}' />
   <input type="hidden" id="path_site" value="{{url('/')}}" />
   <input type="hidden" id="forgot_error" value="{{__('messages.forgot_error')}}"/>
   <input type="hidden" id="reg_error" value="{{__('messages.reg_error')}}"/>
   <input type="hidden" id="pwdmatch" value="{{__('messages.pwdmatch')}}" />
   <input type="hidden" id="login_error" value="{{__('messages.login_error')}}" />
   <input type="hidden" id="required_field" value="{{__('messages.required_field')}}" />
   <input type="hidden" id="login_error" value="{{__('messages.login_error')}}" />
   <input type="hidden" id="forgot_error_2" value="{{__('messages.forgot_error_2')}}">
  
   <script type="text/javascript" src="{{ URL::to('public/js/code.js').'?version=965326' }}"></script>


   <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
   <script type="text/javascript" src="{{ URL::to('public/demo/button.js')}}"></script>
   <script src="{{ URL::to('public/demo/script.js')}}"></script>
   <script type="text/javascript">
      function changecategory(cat_id,id){
           var total=$("#totalcategory").val();
           console.log(id);
           for(var i=0;i<total;i++){
               document.getElementById("category"+i).style.display="none";
               $("#box"+i).removeClass('King_script_active');
           }
           document.getElementById("category"+id).style.display="block";
           $("#box"+id).addClass('King_script_active');
      }
   </script>
</html>