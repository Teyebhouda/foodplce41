@extends('user.subindex')
@section('content')
<div class="container">
   <div class="contact-h">
      <h1>{{__('messages.contact_us')}}</h1>
      <p>{{__('messages.service_sugg')}}</p>
   </div>
   <div class="contact-add">
      <div class="row">
         <div class="col-md-8">
            <div class="contact-head">
               <h1>{{__('messages.leave_msg')}}</h1>
               <p>{{__('messages.contact_sugg')}}</p>
               @if(Session::has('message'))
               <div class="col-sm-12">
                  <div class="alert  {{ Session::get('alert-class', 'alert-info') }} alert-dismissible fade show" role="alert">{{ Session::get('message') }}
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
               </div>
               @endif
               <form action="{{url('savecontact')}}" method="post">
                  {{csrf_field()}}
                  <br>
                  <input type="text" name="name" id="acco-tab-fo" required placeholder="{{__('messages.name')}}" />
                  <br>
                  <input type="email" name="email" id="acco-tab-fo" required placeholder="{{__('messages.email')}}"/>
                  <br>
                  <input type="text" name="phone" id="acco-tab-fo" required placeholder="{{__('messages.phone_no')}}"/>
                  <br>
                  <textarea name="message" required placeholder="{{__('messages.msg')}}"></textarea>
                  <button class="submit" type="submit">{{__('messages.submit')}}</button>
               </form>
            </div>
         </div>
         <div class="col-md-4">
            <div class="contact-head-1">
               <h1>{{__('messages.our_add')}}</h1>
               <p> 
               <div class="f-location col-md-12 p-0">
                  <div class="col-md-1 col-1 icons"> 
                     <i class="fa fa-address-book" aria-hidden="true"></i>
                  </div>
                  <div class="col-md-10 col-10 co-add">
                     <p>{{Session::get('address')}}</p>
                  </div>
               </div>
               <div class="f-location col-md-12 p-0">
                  <div class="col-md-1 col-1 icons"> 
                     <i class="fa fa-envelope" aria-hidden="true"></i>
                  </div>
                  <div class="col-md-10 col-10 co-add">
                     <p>{{Session::get('phone')}}</p>
                  </div>
               </div>
               <div class="f-location col-md-12 p-0">
                  <div class="col-md-1 col-1 icons"> 
                     <i class="fa fa-phone" aria-hidden="true"></i>
                  </div>
                  <div class="col-md-10 col-10 co-add">
                     <p>{{Session::get('email')}}</p>
                  </div>
               </div>
               </p>
            </div>
         </div>
      </div>
   </div>
</div>
@stop