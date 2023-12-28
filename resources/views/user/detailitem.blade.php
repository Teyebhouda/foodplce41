@extends('user.subindex') @section('content')
<?php 
   function readMoreHelper1($story_desc, $chars = 75) {
    $story_desc = substr($story_desc,0,$chars);  
    $story_desc = substr($story_desc,0,strrpos($story_desc,' '));  
    $story_desc = $story_desc."...";  
    return $story_desc;  
   }
   function headreadMoreHelper1($story_desc, $chars =75) {
    $story_desc = substr($story_desc,0,$chars);  
    $story_desc = substr($story_desc,0,strrpos($story_desc,' '));  
    $story_desc = $story_desc;  
    return $story_desc;  
   }  
   
   ?>
<div class="container detail-section-2">
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
      <div class="col-lg-5 col-md-5 col-sm-6 col-12">
         <img src="{{asset('public/upload/images/menu_item_icon/'.$itemdetails->menu_image)}}" class="img-fluid detail-product-img" alt="{{__('messages.res_image')}}">
      </div>
      <input type="hidden" name="item_id" id="item_id" value="{{$itemdetails->id}}" />
      <div class="col-lg-7 col-md-7 col-sm-6 col-12">
         <div class="detail-product-box">
            <div class="detail-descri">
               <div class="detail-product-head">
                  <h4>{{$itemdetails->menu_name}}</h4>
                  <input type="hidden" name="menu_name" id="menu_name" value="{{$itemdetails->menu_name}}"/>
                  <p>{{Session::get("usercurrency")}}<span id="price">{{$itemdetails->price}}</span></p>
                  <input type="hidden" id="origin_price" name="origin_price" value="{{$itemdetails->price}}" />
               </div>
               <div class="detail-product-content">
                  <p>{{$itemdetails->description}}</p>
               </div>
               <div class="detail-share-buttons">
                  <div class="detail-facebook">
                     <a href="javascript:shareonsoical(1,'{{$itemdetails->id}}')">
                     <i class="fa fa-facebook-square" aria-hidden="true"></i>
                     <span>{{__('messages.share')}}</span>
                     <span id="facebook_share_id">{{$itemdetails->facebook_share}}</span>
                     </a>
                  </div>
                  <div class="detail-tweet">
                     <a href="javascript:shareonsoical(2,'{{$itemdetails->id}}')">
                     <i class="fa fa-twitter" aria-hidden="true"></i>
                     <span>{{__('messages.tweet')}}</span>
                     <span id="twitter_share_id">{{$itemdetails->twitter_share}}</span>
                     </a>
                  </div>
               </div>
            </div>
            <div class="detail-ingredients">
               <div class="detail-ingredients-heading">
                  <h2>{{__('messages.ingredients')}}</h2>
               </div>
              
                 
                 
                     <div class="detail-ingredients-head ">
                         <!-- <h3>{{__('messages.FI')}}</h3> -->
                         <form name="form" id="form">
                             <?php $i = 0; ?>
                             <?php $currentFamilles = collect(); ?>
                             @foreach($menu_interdient1 as $mi)
                                 @if($mi->familleoption)
                                     <?php $currentFamilles->push($mi->familleoption->id); ?>
                                 @endif
                             @endforeach
                            
                             @foreach($currentFamilles->unique() as $currentFamilleId)
                                 <?php $currentFamille = null; ?>
                                 <?php $familyCounter = 0; ?>
                                 @foreach($menu_interdient1 as $mi)
                                 
                                     @if($mi->type == 0 && $mi->familleoption->type == "simple" && $mi->familleoption->id == $currentFamilleId)
                                         @if($currentFamille != $mi->familleoption)
                                             @php
                                                 $currentFamille = $mi->familleoption;
                                             @endphp
                                          @if($currentFamille->name == "SUUPLIMENTS PIZZA")
                                          <h4>SUPLIMENTS</h4>
                                      @else
                                          <h4>{{$currentFamille->name}}</h4>
                                      @endif
                                         @endif
             
                                          <p>
                                             <input type="radio" id="radio-{{$i}}" class="checkbox-custom" name="interdient{{$currentFamilleId}}" value="{{$mi->id}}" onchange="addprice('{{$mi->price}}','{{$i}}')"  {{ $currentFamille->name !== 'BOISSONS' && $familyCounter === 0 ? 'checked' : '' }}>
                                             <label for="radio-{{$i}}" class="checkbox-custom-label">
                                                 {{$mi->item_name}} ({{$mi->price}} €)
                                             </label>
                                         </p> 
                                         {{-- <p>
                                <input type="checkbox" id="checkbox-{{$i}}" class="checkbox-custom" name="interdient" value="{{$mi->id}}" >
                                <label for="checkbox-{{$i}}" class="checkbox-custom-label">
                                    {{$mi->item_name}} 
                                </label>
                            </p> --}}

                                         <?php $i++; $familyCounter++; ?>
                                     @endif
                                 @endforeach
                             @endforeach
                         </form>
                     
                
           
            <form>
                <?php $currentFamilles = collect(); ?>
                @foreach($menu_interdient1 as $mi)
                    @if($mi->type == 1 && $mi->familleoption)
                        <?php $currentFamilles->push($mi->familleoption->id); ?>
                    @endif
                @endforeach

                @foreach($currentFamilles->unique() as $currentFamilleId)
                    <?php $currentFamille = null; ?>
                    @foreach($menu_interdient1 as $mi)
                        @if($mi->type == 1 && $mi->familleoption && $mi->familleoption->id == $currentFamilleId)
                            @if($currentFamille != $mi->familleoption)
                                @php
                                    $currentFamille = $mi->familleoption;
                                @endphp
                                <h4>{{$currentFamille->name}}</h4>
                            @endif

                            <p>
                                <input type="checkbox" id="checkbox-{{$i}}" class="checkbox-custom" name="interdient" value="{{$mi->id}}" onclick="addprice('{{$mi->price}}','{{$i}}')">
                                <label for="checkbox-{{$i}}" class="checkbox-custom-label">
                                    {{$mi->item_name}} ({{$mi->price}} €)
                                </label>
                            </p>
                            <?php $i++; ?>
                        @endif
                    @endforeach
                @endforeach
            </form>
        </div>
        </div>
  
            <div class="detail-plus-button min-add-button">
               <div class="input-group">
                  <a data-decrease>
                  <i class="fa fa-minus" aria-hidden="true" onclick="decreaseValue()"></i>
                  </a>
                  <input type="text" id="number" name="qty" value="{{__('messages.qty_pl')}}" />
                  <a data-increase>
                  <i class="fa fa-plus" aria-hidden="true" onclick="increaseValue()"></i>
                  </a>
               </div>
            </div>
            <a href="javascript:addtocart()">
               <div class="detail-plus-add-cart">
                  <span>{{__('messages.addcart')}}</span>
               </div>
            </a>
         </div>
      </div>
   </div>
</div>
<div class="detail-related-box">
   <div class="container">
      <div class="detail-related-head">
         <h3>{{__('messages.realted_pro')}}</h3>
      </div>
      <?php for($i=0;$i<count($related_item);$i++) { ?>
       <div class="row">
         @if(!empty($related_item[$i]))
        
         <div class="col-lg-6 col-md-6">
            <div class="bor detail-related-tab">
               <div class="items">
                  <div class="b-img">
                     <a href="{{url('detailitem/'.$related_item[$i]->id)}}">
                     <img src="{{asset('public/upload/images/menu_item_icon/'.$related_item[$i]->menu_image)}}" class="img-fluid">
                     </a>
                  </div>
                  <div class="bor">
                     <div class="b-text">
                        <a href="{{url('detailitem/'.$related_item[$i]->id)}}">
                           <h1>{{$related_item[$i]->menu_name}}</h1>
                        </a>
                        <p>{{$related_item[$i]->description}}</p>
                     </div>
                     <div class="price">
                        <h1>{{Session::get("usercurrency")}}{{$related_item[$i]->price}}</h1>
                        <div class="cart">
                           <a href="{{url('detailitem/'.$related_item[$i]->id)}}">{{__('messages.addcart')}}</a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
          <?php $i++;?>
         @endif
         @if(!empty($related_item[$i]))
         <div class="col-lg-6 col-md-6">
            <div class="bor detail-related-tab">
               <div class="items">
                  <div class="b-img">
                     <a href="{{url('detailitem/'.$related_item[$i]->id)}}">
                     <img src="{{asset('public/upload/images/menu_item_icon/'.$related_item[$i]->menu_image)}}" class="img-fluid">
                     </a>
                  </div>
                  <div class="bor">
                     <div class="b-text">
                        <a href="{{url('detailitem/'.$related_item[$i]->id)}}">
                           <h1>{{$related_item[$i]->menu_name}}</h1>
                        </a>
                        <p>{{$related_item[$i]->description}}</p>
                     </div>
                     <div class="price">
                        <h1>{{Session::get("usercurrency")}}{{$related_item[$i]->price}}</h1>
                        <div class="cart">
                           <a href="{{url('detailitem/'.$related_item[$i]->id)}}">{{__('messages.addcart')}}</a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         @endif
         </div>
     <?php } ?>
  
   </div>
</div>
</div>
@stop