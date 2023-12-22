@extends('user.subindex')
 @section('content')
 <div class="container">
    <div class="about-heading">
      <h2>{{__('messages.service')}}</h2>
      <p>{{__('messages.service_sugg')}}</p>
    </div>
    <div class="about-history-1">
      <div class="row">
        <div class="col-lg-8 col-md-8">
          <div class="about-content-1">
           <p>{{__('messages.the')}} <span>{{__('messages.history_ki')}}</span>{{__('messages.aboutdesc1')}}</p>
          </div>
        </div>
        <div class="col-lg-4 col-md-4">
          <img src="{{asset('burger/images/burger-1.png')}}" width="100%">
        </div>
      </div>
    </div>
 @stop