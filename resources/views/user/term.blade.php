@extends('user.subindex')
@section('content')
<div class="container a-con">
  <div class="pizzaro-about-features">
   <div class="feature-head">
    <h2 class="section-title">{{__('messages.termsandcon')}}</h2>
    <p> {{__('messages.aboutush')}}</p>
  </div>
</div>
</div>
<div class="container">
 <div class="terms-head">
   <h4>{{__('messages.term_h1')}}</h4>
   <ol>
     <li class="termls">{{__('messages.t1')}}</li>
     <li class="termls">{{__('messages.t2')}}</li>
     <li class="termls">{{__('messages.t3')}}</li>
     <li class="termls">{{__('messages.t4')}}</li>
     <li class="termls">{{__('messages.t5')}}</li>
     <li class="termls">{{__('messages.t6')}}</li>
 </ol>
</div>
</div>
@stop