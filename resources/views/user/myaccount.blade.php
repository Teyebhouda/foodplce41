@extends('user.subindex')
@section('content')
<div class="container">
   <div class="account-page-head">
      <h1>{{__('messages.myaccount')}}</h1>
   </div>
</div>

<div class="container">
   <div class="account-pro-tab">
      <div class="row">
         <div class="col-lg-4 col-md-12">
            <div class="acc-pro1-box">
               <div class="acc-pro-box">
                  <div class="acc-proimg">
                     <?php $external_link = asset('public/upload/profile'.'/'.Session::get('user_photo'));
                        if (@GetImageSize($external_link)) {
                        	$image = $external_link;
                        } else {
                        	$image = asset('public/upload/profile'.'/'.'my-account-pro.png');
                        }?>
                     <img src="{{$image}}">
                  </div>
                  <div class="acc-pro-content">
                     <h4><?php echo Session::get("user_name");?></h4>
                     <p>{{Session::get("user_email")}}</p>
                  </div>
               </div>
               <ul class="nav nav-pills flex-column" id="myTab" role="tablist">
                  <li class="nav-item">
                     <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                        <span class="account-tabimg">
                        <img src="{{asset('burger/images/tab-1.png')}}" class="acc-tab-icon">
                        </span>
                        <p>{{__('messages.myorder')}}</p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">
                        <span class="account-tabimg">
                        <img src="{{asset('burger/images/tab-2.png')}}" class="acc-tab-icon">
                        </span>
                        <p>{{__('messages.per_detail')}}</p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">
                        <span class="account-tabimg">
                        <img src="{{asset('burger/images/tab-3.png')}}" class="acc-tab-icon">
                        </span>
                        <p>{{__('messages.change_password')}}</p>
                     </a>
                  </li>
                  <li class="nav-item log">
                     <a class="nav-link" data-toggle="modal" data-target="#myModal2" href="#logout" role="tab">
                        <span class="account-tabimg">
                        <img src="{{asset('burger/images/tab-4.png')}}" class="acc-tab-icon">
                        </span>
                        <p>{{__('messages.logout')}}</p>
                     </a>
                  </li>
               </ul>
            </div>
         </div>
         <div class="col-lg-8 col-md-12">
            <div class="acco-tab-content">
               <div class="tab-content" id="myTabContent">
                  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                     <h2>{{__('messages.myorder')}}</h2>
                     @if(count($myorder)==0)
                     {{__('messages.no_order')}}
                     @endif
                     @if(count($myorder)!=0)
                     @foreach($myorder as $order)
                     <div class="order-my">
                        <div class="order-main">
                           <div class="order-nu My-b">
                              <h1>{{__('messages.order_no')}}.: {{$order->id}}</h1>
                           </div>
                           <div class="order-add">
                              <p>{{$order->address}}</p>
                           </div>
                           <div class="order-price My-b">
                              <h1>{{Session::get("usercurrency")}}{{$order->total_price}}</h1>
                           </div>
                           <div class="order-date My-b">
                              <h1><?php 
                                 $date=date_create($order->order_placed_date);
                                 echo date_format($date,"d M Y");?>
                              </h1>
                           </div>
                        </div>
                        <div class="pending">
                           <div class="row">
                              <div class="col-md-6 col-sm-6 col-6 pending-btn">
                                 <?php 
                                    if($order->order_status==0){
                                    	$status=__('messages.Order_Placed');
                                    }
                                    if($order->order_status==1){
                                    	$status=__('messages.Order_Placed');
                                    }
                                    if($order->order_status==2){
                                    	$status=__('messages.reject');
                                    }
                                    if($order->order_status==3){
                                    	$status=__('messages.out_of_delivery');
                                    }
                                    if($order->order_status==4){
                                    	$status=__('messages.complete');
                                    }
                                    if($order->order_status==5){
                                    	$status=__('messages.in_pick');
                                    }
                                    ?>
                                 <a href="#">{{$status}}</a>
                              </div>
                              <div class="col-md-6 col-sm-6 col-6 order-v">
                                 <a href="{{url('viewdetails').'/'.$order->id}}">{{__('messages.view_dt')}}</a>
                              </div>
                           </div>
                        </div>
                     </div>
                     @endforeach
                     @endif
                  </div>
                  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                     <h2>{{__('messages.per_detail')}}</h2>
                     <div class="acco-cp-box">
                        <form action="{{url('updateuserprofile')}}" method="post" enctype="multipart/form-data">
                           {{csrf_field()}}
                           <p id="profile_msg_div"></p>
                           <div class="col-md-12">
                              <div class="row">
                                 <div class="col-md-6 col-12 pro-user">
                                    <?php $external_link = asset('public/upload/profile'.'/'.Session::get('user_photo'));
                                       if (@GetImageSize($external_link)) {
                                       	$image = $external_link;
                                       } else {
                                       	$image = asset('public/upload/profile'.'/'.'my-account-pro.png');
                                       }?>
                                    <img src="{{$image}}">
                                    <input type="file" name="image" id="acco-tab-fo">
                                 </div>
                                 <div class="col-md-6 col-12 user-d">
                                    <label>{{__('messages.name')}}<span>*</span></label><br>
                                    <input type="text" name="user_name" required value="{{Session::get('user_name')}}" id="acco-tab-fo" placeholder="{{__('messages.name')}}">
                                    <label>{{__('messages.phone_no')}}<span>*</span></label>
                                    <input type="text" name="user_phone" value="{{Session::get('user_phone')}}" readonly id="acco-tab-fo" placeholder="{{__('messages.phone_no')}}">
                                    <label>{{__('messages.email')}}<span>*</span></label>
                                    <input type="text" name="user_email" required value="{{Session::get('user_email')}}" id="acco-tab-fo" placeholder="{{__('messages.email')}}">
                                 </div>
                              </div>
                           </div>
                           <div class="acco-button">
                              <div class="acco-c-button acco-u-button col-md-12">
                                 <button type="submit">{{__('messages.update')}}</button>
                              </div>
                           </div>
                        </form>
                     </div>
                  </div>
                  <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                     <h2>{{__('messages.change_password')}}</h2>
                     <div class="acco-cp-box change">
                        <input type="hidden" id="error_cut_pwd" value="{{__('messages.error_cut_pwd')}}" />
                        <input type="hidden" id="pwdmatch" value="{{__('messages.pwdmatch')}}" />
                        <form>
                           <p id="msgres"></p>
                           <label>{{__('messages.current_pwd')}}<span>*</span></label><br>
                           <input type="password" required name="opwd" id="acco-tab-fo" placeholder="{{__('messages.current_pwd')}}" onchange="checkcurrentpwd(this.value)">
                           <label>{{__('messages.new_pwd')}}<span>*</span></label>
                           <input type="password" required name="npwd" id="acco-tab-fo" placeholder="{{__('messages.new_pwd')}}" >
                           <label>{{__('messages.confirm_pwd')}}<span>*</span></label>
                           <input type="password" required name="rpwd" id="acco-tab-fo" onchange="checkbothpwd(this.value)" placeholder="{{__('messages.confirm_pwd')}}">
                           <div class="acco-button">
                              <div class="acco-c-button">
                                 <a href="javascript:cancelpwd()">{{__('messages.cancel')}}</a>
                              </div>
                              <div class="acco-c-button acco-u-button">
                                 <a href="javascript:changepassword()">{{__('messages.change_password')}}</a>
                              </div>
                           </div>
                        </form>
                     </div>
                  </div>
                  <div class="tab-pane fade" id="logout" role="tabpanel" aria-labelledby="contact-tab">
                     <h2>{{__('messages.logout')}}</h2>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@stop