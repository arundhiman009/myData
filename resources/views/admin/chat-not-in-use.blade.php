@extends('layouts.admin.admin-app')

@section('title')
    Messages - {{ Config::get('app.name') }}
@endsection
@section('page-css')
<style>
    body,html{
            height: 100%;
            margin: 0;
           
        }
       .chat-not{
            color: white;
            font-size: 24px;
            margin-left: 24px;
       }
       .seen_chat{
        position: absolute;
        margin: 33px;
        color: white;
    }
        .chat{
            margin-top: 10px;
            margin-bottom: auto;
        }
        .card{
            height: 500px;
            border-radius: 15px !important;
            background-color: #222222 !important;
        }
        .contacts_body{
            padding:  0.75rem 0 !important;
            overflow-y: auto;
            white-space: nowrap;
        }
        .msg_card_body{
            overflow-y: auto;
        }
        .card-header{
            border-radius: 15px 15px 0 0 !important;
            border-bottom: 0 !important;
        }
     .card-footer{
        border-radius: 0 0 15px 15px !important;
            border-top: 0 !important;
    }
        .container{
            align-content: center;
        }
        .search{
            border-radius: 15px 0 0 15px !important;
            background-color: rgba(0,0,0,0.3) !important;
            border:0 !important;
            color:white !important;
        }
        .search:focus{
             box-shadow:none !important;
           outline:0px !important;
        }
        .type_msg{
            background-color: rgba(0,0,0,0.3) !important;
            border:0 !important;
            color:white !important;
            height: 60px !important;
            overflow-y: auto;
        }
            .type_msg:focus{
             box-shadow:none !important;
             outline:0px !important;
        }
        .attach_btn{
            border-radius: 15px 0 0 15px !important;
            background-color: rgba(0,0,0,0.3) !important;
            border:0 !important;
            color: white !important;
            cursor: pointer;
        }
        .send_btn{
            border-radius: 0 15px 15px 0 !important;
            background-color: rgba(0,0,0,0.3) !important;
            border:0 !important;
            color: white !important;
            cursor: pointer;
        }
        .search_btn{
            border-radius: 0 15px 15px 0 !important;
            background-color: rgba(0,0,0,0.3) !important;
            border:0 !important;
            color: white !important;
            cursor: pointer;
        }
        .contacts{
            list-style: none;
            padding: 0;
        }
        .contacts li{
            width: 100% !important;
            padding: 5px 10px;
            margin-bottom: 15px !important;
        }
    .active{
            background-color: rgba(0,0,0,0.3);
    }
        .user_img{
            height: 70px;
            width: 70px;
            border:1.5px solid #f5f6fa;
        
        }
        .user_img_msg{
            height: 40px;
            width: 40px;
            border:1.5px solid #f5f6fa;
        
        }
    .img_cont{
            position: relative;
            height: 70px;
            width: 70px;
    }
    .img_cont_msg{
            height: 40px;
            width: 40px;
    }
    .online_icon{
        position: absolute;
        height: 15px;
        width:15px;
        background-color: #4cd137;
        border-radius: 50%;
        bottom: 0.2em;
        right: 0.4em;
        border:1.5px solid white;
    }
    .offline_icon{
        position: absolute;
        height: 15px;
        width:15px;
        background-color: #c23616;
        border-radius: 50%;
        bottom: 0.2em;
        right: 0.4em;
        border:1.5px solid white;
    }
    .offline{
        background-color: #c23616 !important;
    }
    .user_info{
        margin-top: auto;
        margin-bottom: auto;
        margin-left: 15px;
    }
    .user_info span{
        font-size: 20px;
        color: white;
    }
    .user_info p{
    font-size: 10px;
    color: rgba(255,255,255,0.6);
    }
    .video_cam{
        margin-left: 50px;
        margin-top: 5px;
    }
    .video_cam span{
        color: white;
        font-size: 20px;
        cursor: pointer;
        margin-right: 20px;
    }
    .msg_cotainer{
        margin-top: auto;
        margin-bottom: auto;
        margin-left: 10px;
        border-radius: 25px;
        background-color: #82ccdd;
        padding: 10px;
        position: relative;
    }
    .msg-cnt {
    margin: auto;
   background-color: #78e08f;
    position: relative;
    left: 20px;
    border-radius: 50%;
    border: 1px solid #fff;
    height: fit-content;
    width: fit-content;
    padding: 5px;
    font-size: 12px;
}
    .msg_cotainer_send{
        margin-top: auto;
        margin-bottom: auto;
        margin-right: 10px;
        border-radius: 25px;
        background-color: #78e08f;
        padding: 10px;
        position: relative;
    }
    .msg_time{
        position: absolute;
        left: 0;
        bottom: -15px;
        color: rgba(255,255,255,0.5);
        font-size: 10px;
        width: max-content;
    }
    .msg_time_send{
        position: absolute;
        right:0;
        bottom: -15px;
        color: rgba(255,255,255,0.5);
        font-size: 10px;
       width: max-content
    }
    .mesage_count{
        letter-spacing: 3px;
    }
    .msg_head{
        position: relative;
    }
    #action_menu_btn{
        position: absolute;
        right: 10px;
        top: 10px;
        color: white;
        cursor: pointer;
        font-size: 20px;
    }
    .action_menu{
        z-index: 1;
        position: absolute;
        padding: 15px 0;
        background-color: rgba(0,0,0,0.5);
        color: white;
        border-radius: 15px;
        top: 30px;
        right: 15px;
        display: none;
    }
    .action_menu ul{
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .action_menu ul li{
        width: 100%;
        padding: 10px 15px;
        margin-bottom: 5px;
    }
    .action_menu ul li i{
        padding-right: 10px;
    
    }
    .action_menu ul li:hover{
        cursor: pointer;
        background-color: rgba(0,0,0,0.2);
    }
    @media(max-width: 576px){
    .contacts_card{
        margin-bottom: 15px !important;
    }
    }
</style>
@endsection
@section('content')
<script src="{{asset('js/app.js')}}"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/line-awesome/css/line-awesome.min.css" />
<div class="container-fluid h-100">
            <div class="row justify-content-center h-100" style="margin-left: 7%;">
                <div class="col-md-4 col-xl-3 chat"><div class="card mb-sm-3 mb-md-0 contacts_card">
                    <div class="card-header">
                        <div class="input-group">
                            <input type="text" placeholder="Search..." name="" class="form-control search user-search">
                            <div class="input-group-prepend">
                                <span class="input-group-text search_btn"><i class="fas fa-search"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body contacts_body users-list">
                       @if(count($users) > 0)
                        @foreach($users as $user)

                        <ui class="contacts">
                           
                        <li class="active">
                            <div class="d-flex bd-highlight user-chat" id="{{$user->id}}">
                                <div class="img_cont">
                                    <input type="hidden" value="{{$user->id}}" class="userId first_user"/>
                                    <img src="{{$user->profile_photo_path ?? asset('assets/dist/img/user2-160x160.jpg') }}" class="rounded-circle user_img">
                                       @if(Cache::has('user-is-online-' . $user->id))<span class="online_icon"></span> @else <span class="offline_icon"></span> @endif
                                </div>
                                <div class="user_info">
                                    <span>{{$user->name}}</span>
                                    <p>{{$user->name}} is @if(Cache::has('user-is-online-' . $user->id)) 
                                       Online
                                                        @else
                                                            Offline
                                                        @endif</p>
                                </div>
                                
                            </div>
                        </li>
                        
                        @endforeach
                   @else
                         <li>
                           <div class="user_info ml-3">
                                    <span>No Users......</span>
                                  
                                </div>
                        </li>
                      @endif
                       
                      
                       
                        </ui>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
            <div class="col-md-8 col-xl-6 chat" >
                 <form  method="post" id="sendMessage">
                    <div class="card">
                        <div class="card-header msg_head">
                            <div class="d-flex bd-highlight user-detail">
                               
                            </div>
                            <span id="action_menu_btn"><i class="fas fa-ellipsis-v"></i></span>
                            <div class="action_menu">
                                <ul>
                                    <li><i class="fas fa-user-circle"></i> View profile</li>
                                    <li><i class="fas fa-users"></i> Add to close friends</li>
                                    <li><i class="fas fa-plus"></i> Add to group</li>
                                    <li><i class="fas fa-ban"></i> Block</li>
                                </ul>
                            </div>
                        </div>
                          <input type="hidden" class="receiver_id"  name="receiver_id"/>
                        <input type="hidden" class="sender_id" value ="{{Auth::user()->id}}" name="sender_id"/>
                        <div class="card-body msg_card_body">
                                                     
                         
                           
                        </div>
                        <div class="card-footer">
                            <div class="input-group">
                                <div class="input-group-append">
                                    <span class="input-group-text attach_btn"><i class="fas fa-paperclip"></i></span>
                                </div>
                               <textarea name="message" id="type_msg" class="form-control type_msg" placeholder="Type your message..." ></textarea>
                                <div class="input-group-append">
                                     <BUTTON class="input-group-text send_btn btn-submit" type="submit"><i class="fas fa-location-arrow"></i></BUTTON>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
            </form>   
            </div>
        </div>




  @endsection

@section('page-scripts')
<script>   
    $(document).ready(function(){
$('#action_menu_btn').click(function(){
    $('.action_menu').toggle();
});
    });
var firstUser=$('.first_user').val();

if(firstUser == undefined){
 $('.card-footer').css('display','none');  

}

 messageNotify();
 $(".receiver_id").val(firstUser);
   var fUser = {
                'userid': firstUser,
                
            };
$.ajax({
         url: "{{route('admin.userDetail')}}",
                        type: "post",
                        data: fUser,
                        success: function(d) {
                         $('.user-detail').html(d);
                         messageCount();
                           }
       });

var formVal = {
                'sender_id': $('.sender_id').val(),
                'receiver_id':firstUser
            };
       $.ajax({
                url: "{{route('admin.chatList')}}",
                type: "post",
                data: formVal,
                success: function(d) {
                   $('.msg_card_body').html(d); 
                }
            });

$('#sendMessage').on('submit',function(e){
        e.preventDefault();
      $('#sendMessage').validate({
      
        errorClass: 'is-invalid', 
        submitHandler: function(form) {
                l = Ladda.create( document.querySelector('#sendMessage .btn-submit') );                
                l.start();
                $.ajax({
                    url: "{{route('send.message')}}",
                    method: "POST",                   
                    data: $("#sendMessage").serialize(),
                    success: function (resultData) {
                        
                        l.stop();       
                         var msg = resultData.message;
                         if(resultData.success)
                        {  
                        document.getElementById("type_msg").value = "";     
                        reloadData();                        
                           
                                                     
                        }
                        else if(!resultData.success)
                        {
                            if(resultData.type=="validation-error")
                            {                                                   
                                $.each( resultData.error, function( key, value ) {
                                    $('label.is-invalid').remove();
                                    $('#'+key).after('<label id="'+key+'-error" class="is-invalid" for="'+key+'">'+value+'</label>');
                                    $('#'+key).addClass('is-invalid');           
                                    });
                            }
                            else {
                                toastr.error(msg);
                            }
                        }
                    },
                    error: function (jqXHR, exception) {
                        var msg = '';
                        if (jqXHR.status === 0) {
                            msg = 'Not connect.\n Verify Network.';
                        } else if (jqXHR.status == 401) {
                                window.location.reload();
                        } else if (jqXHR.status == 404) {
                            msg = 'Requested page not found. [404]';
                        } else if (jqXHR.status == 500) {
                            msg = 'Internal Server Error [500].';
                        } else if (exception === 'parsererror') {
                            msg = 'Requested JSON parse failed.';
                        } else if (exception === 'timeout') {
                            msg = 'Time out error.';
                        } else if (exception === 'abort') {
                            msg = 'Ajax request aborted.';
                        } else {
                            msg = 'Uncaught Error.\n' + jqXHR.responseText;
                        }
                        l.stop();
                        toastr.error(msg);
                    }
                });
                return false;
            }
    });
    }); 

function messageCount(){
    $.ajax({
    url: "{{route('admin.messgaeCount')}}",
                type: "post",
                data: formVal,
                success: function(d) {
                 $('.mesage_count').html(d);
                   }
        });
        }
function reloadData(){
 var formdata = {
                'sender_id': $('.sender_id').val(),
                'receiver_id':$('.receiver_id').val()
            };
         $.ajax({
                url: "{{route('admin.chatList')}}",
                type: "post",
                data: formdata,
                success: function(d) {
                   $('.msg_card_body').html(d);
                    
                }
            });
}

function messageNotify(){
 var formdata = {
                'sender_id': $('.sender_id').val(),
                'receiver_id':$('.receiver_id').val()
            };

     $.ajax({
                url: "{{route('admin.notification')}}",
                type: "post",
                data: formdata,
                success: function(d) {
                   $('.user-chat').append(d); 
                }
            });        

}






Echo.private('chat')
    .listen('SendMessage', (e) => {
     
      

    if($('#'+e.data.sender_id).children().length == 2) {
             reloadData(); 
           $('#'+e.data.sender_id).append('<div class="msg-cnt" ></span></div>');
            
    }
    else{
        reloadData();  
        

     
    }

    });


$('.user-chat').click(function(){

$('.chat').css('display','block');

var id = $(this).attr('id');
 $(this).children(".msg-cnt").remove();
var userId = {
    'userid':id
};

 $(".receiver_id").val(id);
 
$.ajax({
         url: "{{route('admin.userDetail')}}",
                        type: "post",
                        data: userId,
                        success: function(d) {
                         $('.user-detail').html(d);
                           }
       });

 var formdata = {
                'sender_id': $('.sender_id').val(),
                'receiver_id':id
            };
       $.ajax({
                url: "{{route('admin.chatList')}}",
                type: "post",
                data: formdata,
                success: function(d) {
                   $('.msg_card_body').html(d); 
                }
            });

messageCount();

function messageCount(){
$.ajax({
      url: "{{route('admin.messgaeCount')}}",
                        type: "post",
                        data: formdata,
                        success: function(d) {
                         $('.mesage_count').html(d);
                           }
});
}

Echo.private('chat')
    .listen('SendMessage', (e) => {

      if($('#'+e.data.sender_id).children().length == 2) {
             reloadData(); 
           $('#'+e.data.sender_id).append('<div class="msg-cnt" ></span></div>');
            
    }
    else{
        reloadData();  
         
    }
    });

function reloadData(){
 var formdata = {
                'sender_id': $('.sender_id').val(),
                'receiver_id':$('.receiver_id').val()
            };
       $.ajax({
                url: "{{route('admin.chatList')}}",
                type: "post",
                data: formdata,
                success: function(d) {
                   $('.msg_card_body').html(d); 
                }
            });
  }

       $('#sendMessage').on('submit',function(e){
        e.preventDefault();
      $('#sendMessage').validate({
      
        errorClass: 'is-invalid', 
        submitHandler: function(form) {
                l = Ladda.create( document.querySelector('#sendMessage .btn-submit') );                
                l.start();
                $.ajax({
                    url: "{{route('send.message')}}",
                    method: "POST",                   
                    data: $("#sendMessage").serialize(),
                    success: function (resultData) {
                        
                        l.stop();       
                         var msg = resultData.message;
                         if(resultData.success)
                        {  
                        document.getElementById("type_msg").value = "";     
                        reloadData();                        
                           
                                                     
                        }
                        else if(!resultData.success)
                        {
                            if(resultData.type=="validation-error")
                            {                                                   
                                $.each( resultData.error, function( key, value ) {
                                    $('label.is-invalid').remove();
                                    $('#'+key).after('<label id="'+key+'-error" class="is-invalid" for="'+key+'">'+value+'</label>');
                                    $('#'+key).addClass('is-invalid');           
                                    });
                            }
                            else {
                                toastr.error(msg);
                            }
                        }
                    },
                    error: function (jqXHR, exception) {
                        var msg = '';
                        if (jqXHR.status === 0) {
                            msg = 'Not connect.\n Verify Network.';
                        } else if (jqXHR.status == 401) {
                                window.location.reload();
                        } else if (jqXHR.status == 404) {
                            msg = 'Requested page not found. [404]';
                        } else if (jqXHR.status == 500) {
                            msg = 'Internal Server Error [500].';
                        } else if (exception === 'parsererror') {
                            msg = 'Requested JSON parse failed.';
                        } else if (exception === 'timeout') {
                            msg = 'Time out error.';
                        } else if (exception === 'abort') {
                            msg = 'Ajax request aborted.';
                        } else {
                            msg = 'Uncaught Error.\n' + jqXHR.responseText;
                        }
                        l.stop();
                        toastr.error(msg);
                    }
                });
                return false;
            }
    });
    });    
});
  
</script>
    @endsection