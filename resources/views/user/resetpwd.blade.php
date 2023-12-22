<!DOCTYPE html>
<html lang="en">
   <head>
      <title>{{__('messages.site_name')}}</title>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
   </head>
   <body>
      <div class="container">
         @if(!isset($msg))
         <h2>{{__('messages.reset_pwd')}}</h2>
         <form action="{{url('resetnewpwd')}}" method="post">
            {{csrf_field()}}
            <input type="hidden" name="code" value="{{$code}}" />
            <input type="hidden" name="id" value="{{$id}}" />
            <div class="form-group">
               <label for="email">{{__('messages.new_pwd')}}:</label>
               <input type="password" required class="form-control" id="npwd" placeholder="{{__('messages.en_new_pwd')}}" name="npwd">
            </div>
            <div class="form-group">
               <label for="pwd">{{__('messages.r_ent_pwd')}}:</label>
               <input type="password" required class="form-control" id="rpwd" placeholder="{{__('messages.e_r_pwd')}}" name="rpwd" onchange="checkbothpwd(this.value)">
            </div>
            <button type="submit" class="btn btn-default">{{__('messages.reset')}}</button>
         </form>
         @endif @if(isset($msg))
         <h3>{{$msg}}</h3>
         @endif
      </div>
   </body>
</html>