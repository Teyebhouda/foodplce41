@extends('user.subindex')
@section('content')
<div class="container">
   <div class="detail-main">
      <div class="order-d">
         <h1>{{__('messages.view_order_details')}}</h1>
      </div>
      <div div class="order-status">
         <ul>
            <li class="process-1">
               <div class="pro-rou">
                  <div class="round-d">
                     <img src="{{asset('burger/images/ture.png')}}">
                  </div>
               </div>
               <div class="order-d-text">
                  <h1>{{__('messages.Order_Placed')}}</h1>
                  <p>{{$order->order_placed_date}}</p>
               </div>
            </li>
            <?php
               if($order->preparing_date_time!=""){
               	 $class2="process-1";
               	 $divclass2="d";
               }
               else{
               	 $class2="process-2 no-process-1";
               	 $divclass2="d-1";
               }
               ?>
            <li class="{{$class2}}">
               <div class="pro-rou">
                  <div class="round-{{$divclass2}}">
                     <?php if($order->preparing_date_time!=""){?>
                     <img src="{{asset('burger/images/ture.png')}}">
                     <?php }else{?>
                     <span>{{__('messages.two')}}</span>
                     <?php }?>
                  </div>
               </div>
               <div class="order-d-text">
                  <h1>{{__('messages.preparing')}}</h1>
                  <p>
                     @if($order->preparing_date_time!="")
                     {{$order->preparing_date_time}}
                     @endif
                  </p>
               </div>
            </li>
            <?php
               if($order->dispatched_date_time!=""){
               	 $class3="process-1";
               	 $divclass3="d";
               }
               else{
               	 $class3="process-2 no-process-1";
               	 $divclass3="d-1";
               }
               
               ?>
            <li class="{{$class3}}">
               <div class="pro-rou">
                  <div class="round-{{$divclass3}}">
                     <?php if($order->dispatched_date_time!=""){?>
                     <img src="{{asset('burger/images/ture.png')}}">
                     <?php }else{?>
                     <span>{{__('messages.three')}}</span>
                     <?php }?>
                  </div>
               </div>
               <div class="order-d-text">
                   @if($order->shipping_type==1)
                       <h1>{{__('messages.wait_pick')}}</h1>
                   @else
                       <h1>{{__('messages.dispatching')}}</h1>
                   @endif
                 
                  <p>
                     @if($order->dispatched_date_time!="")
                     {{$order->dispatched_date_time}}
                     @endif
                  </p>
               </div>
            </li>
            <?php
               if($order->delivered_date_time!=""){
               	 $divclass4="d";
               }
               else{
               	 $divclass4="d-1";
               }
               
               ?>
            <li class="">
               <div class="pro-rou">
                  <div class="round-{{$divclass4}}">
                     <?php if($order->delivered_date_time!=""){?>
                     <img src="{{asset('burger/images/ture.png')}}">
                     <?php }else{?>
                     <span> {{__('messages.four')}}</span>
                     <?php }?>
                  </div>
               </div>
               <div class="order-d-text">
                     @if($order->shipping_type==1)
                       <h1>{{__('messages.order_pickup')}}</h1>
                   @else
                      <h1>{{__('messages.delivered')}}</h1>
                   @endif
                  
                  <p>
                     @if($order->delivered_date_time!="")
                     {{$order->delivered_date_time}}
                     @endif
                  </p>
               </div>
            </li>
         </ul>
      </div>
      <div class="item-main">
         <div class="row">
            @if(Session::has('message'))
            <div class="col-sm-12">
               <div class="alert  {{ Session::get('alert-class', 'alert-info') }} alert-dismissible fade show" role="alert">{{ Session::get('message') }}
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
            </div>
            @endif
            <div class="col-md-12 col-sm-12 col-12 col-lg-8">
               <div class="order-item">
                  <div class="main-order-detail">
                     <h1>{{$order->order_placed_date}}</h1>
                     <span>{{Session::get("usercurrency")}} {{$order->total_price}}</span>
                     <p>{{count($itemlist)}} {{__('messages.item_order')}}</p>
                     <p>{{$order->address}}</p>
                     <p>{{$order->phone_no}}</p>
                  </div>
                  <div class="sub-order">
                     @foreach($itemlist as $item)
                     <div class="sub-order-1">
                        <div class="img">
                           @foreach($allmenu as $as)
                             @if($as->id==$item->item_id)
                           <img src="{{asset('public/upload/images/menu_item_icon/'.$as->menu_image)}}">
                             @endif
                           @endforeach
                        </div>
                        <div class="sub-order-text">
                           <div class="sub-text-1">
                              <h1>{{$item->itemdata->menu_name}}</h1>
                              <p><?php $intgr=explode(",",$item->ingredients_id);  ?>
                                 @foreach($menu_interdient as $menu)
                                 @if(in_array($menu->id,$intgr))
                                 {{$menu->item_name}},
                                 @endif
                                 @endforeach
                              </p>
                           </div>
                           <span>
                              {{Session::get("usercurrency")}}{{$item->ItemTotalPrice}}
                              <h1>{{Session::get("usercurrency")}}   	         		{{$item->item_amt}}<img src="{{asset('burger/images/cross-1.png')}}">{{$item->item_qty}}</h1>
                           </span>
                        </div>
                     </div>
                     @endforeach
                  </div>
               </div>
            </div>
            <div class="col-md-12 col-sm-12 col-12 col-lg-4">
               <div class="all-data-order">
                  <div class="per-data">
                     <h1>{{__('messages.per_detail')}}</h1>
                  </div>
                  <ul>
                     <li class="pro">
                        <h1>{{__('messages.name')}} :</h1>
                        <span>{{$order->name}}</span>
                     </li>
                     <li class="pro">
                        <h1>{{__('messages.city')}} :</h1>
                        <span>{{$order->city}}</span>
                     </li>
                     <li class="pro">
                        <h1>{{__('messages.pay_type')}} :</h1>
                        <span>{{$order->payment_type}}</span>
                     </li>
                     <li class="pro">
                        <h1>{{__('messages.shipping_type')}}:</h1>
                        <span> @if($order->shipping_type==0)
                        {{__('messages.HD')}}
                        @endif
                        @if($order->shipping_type==1)
                        {{__('messages.LP')}}	
                        @endif</span>
                     </li>
                  </ul>
               </div>
               <div class="all-data-order-1">
                  <div class="per-data">
                     <h1>{{__('messages.billing_detail')}}</h1>
                  </div>
                  <div class="order-sub-total">
                     <div class="sub-total-start">
                        <h1>{{__('messages.subtotal')}}:</h1>
                        <span>{{Session::get("usercurrency")}} {{$order->subtotal}}</span>
                     </div>
                     <?php if($order->delivery_mode==0){?>
                     <div class="sub-total-start">
                        <h1>{{__('messages.DC')}} :</h1>
                        <span>{{Session::get("usercurrency")}} {{$order->delivery_charges}}</span>
                     </div>
                     <?php }?>
                     <div class="final-total">
                        <h1>{{__('messages.total')}} :</h1>
                        <span>{{Session::get("usercurrency")}} {{$order->total_price}}</span>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@stop