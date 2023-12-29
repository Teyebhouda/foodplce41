@extends('admin.index')
@section('content')
<div class="breadcrumbs">
   <div class="col-sm-4">
      <div class="page-header float-left">
         <div class="page-title">
            <h1>{{__('messages.postal')}}</h1>
         </div>
      </div>
   </div>
   <div class="col-sm-8">
      <div class="page-header float-right">
         <div class="page-title">
            <ol class="breadcrumb text-right">
               <li class="active">{{__('messages.postal')}}</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="content mt-3">
   <div class="row">
      <div class="col-12">
         <div class="card">
            <div class="card-body">
               @if(Session::has('message'))
               <div class="col-sm-12">
                  <div class="alert  {{ Session::get('alert-class', 'alert-info') }} alert-dismissible fade show" role="alert">{{ Session::get('message') }}
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
               </div>
               @endif
               <button  class="btn btn-primary btn-flat m-b-30 m-t-30" data-toggle="modal" data-target="#myModal">{{__('messages.add')}}{{__('messages.postal')}}</button>
               
               <div class="table-responsive dtdiv">
                  <table id="menutb" class="table table-striped dttablewidth">
                     <thead>
                        <tr>
                           <th>{{__('messages.id')}}</th>
                           <th>{{__('messages.postal')}}</th>
                           <th>{{__('messages.city')}}</th>
                          
                           <th>{{__('messages.action')}}</th>
                           {{--  --}}
                        </tr>
                     </thead>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="modal fade" id="myModal" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title">{{__('messages.add')}}{{__('messages.postal')}}
               </h5>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
               <form name="menu_form_category" action="{{url('add_postal')}}" method="post" enctype="multipart/form-data">
                  {{csrf_field()}}
                  <div class="form-group">
                     <label>{{__('messages.sel_city')}}</label>
                     <select class="form-control" name="category" required>
                        <option value="">{{__('messages.sel_city')}}</option>
                        @foreach($city as $c)
                        <option value="{{$c->id}}">{{$c->city_name}}</option>
                        @endforeach
                     </select>
                  </div>
                  <div class="form-group">
                     <label>{{__('messages.postal')}}</label>
                     <input type="text" class="form-control" placeholder="{{__('messages.postal')}}" name="name" required>
                  </div>
                 
                  <div class="col-md-12">
                     <div class="col-md-6">
                          @if(Session::get("demo")==0)
                               <button id="payment-button" type="button" class="btn btn-primary btn-md form-control" onclick="disablebtn()">
                           {{__('messages.add')}}
                           </button>
                           @else
                             <button id="payment-button" type="submit" class="btn btn-primary btn-md form-control">
                           {{__('messages.add')}}
                           </button>
                           @endif
                     </div>
                     <div class="col-md-6">
                        <input type="button" class="btn btn-secondary btn-md form-control" data-dismiss="modal" value="{{__('messages.close')}}">
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="modal fade" id="edititem" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title">{{__('messages.edit')}}{{__('messages.postal')}}
               </h5>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
               <form name="menu_form_category" action="{{url('update_postal')}}" method="post" enctype="multipart/form-data">
                  {{csrf_field()}}
                  <input type="hidden" name="id" id="id">
                  <input type="hidden" name="real_image" id="real_image">
                  <div class="form-group">
                     <label>{{__('messages.select_cat')}}</label>
                     <select class="form-control" name="category" id="category" required>
                        <option value="">{{__('messages.select_city')}}</option>
                        @foreach($city as $c)
                        <option value="{{$c->id}}">{{$c->city_name}}</option>
                        @endforeach
                     </select>
                  </div>
                  <div class="form-group">
                     <label>{{__('messages.postal')}}</label>
                     <input type="text" class="form-control" placeholder="{{__('messages.item_name')}}" id="name" name="name" required>
                  </div>
                 
                  <div class="col-md-12">
                     <div class="col-md-6">
                          @if(Session::get("demo")==0)
                               <button id="payment-button" type="button" class="btn btn-primary btn-md form-control" onclick="disablebtn()">
                           {{__('messages.update')}}
                           </button>
                           @else
                             <button id="payment-button" type="submit" class="btn btn-primary btn-md form-control">
                           {{__('messages.update')}}
                           </button>
                           @endif
                     </div>
                     <div class="col-md-6">
                        <input type="button" class="btn btn-secondary btn-md form-control" data-dismiss="modal" value="{{__('messages.close')}}">
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
@stop