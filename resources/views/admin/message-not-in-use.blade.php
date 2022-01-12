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
        display: block ruby;
        width: max-content;
    }
    .msg_time_send{
        position: absolute;
        right:0;
        bottom: -15px;
        color: rgba(255,255,255,0.5);
        font-size: 10px;
        display: block ruby;
        width: max-content;
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
        <div class="container-fluid h-100" style="margin-left:19%">
            <form  method="post" id="sendMessage">
               
            <div class="row  h-100">
               
                <div class="col-md-8 col-xl-6 chat">
                    <div class="card">
                        <div class="card-header msg_head">
                            <div class="d-flex bd-highlight">
                                <div class="img_cont">
                                   @if($users->profile_photo_path)
                                     <img src="{{asset('assets/dist/images/'.$users->profile_photo_path)}}">
                                    @else
                                    <img src="{{asset('assets/dist/img/user2-160x160.jpg')}}" class="rounded-circle user_img">
                                    @endif
                                    <span class="online_icon"></span>
                                </div>
                                <div class="user_info">
                                     <span>Chat with {{$users->name}}</span>
                                    <p>{{count($chats)}} Messages</p>
                                </div>
                                <div class="video_cam">
                                    <span><i class="fas fa-video"></i></span>
                                    <span><i class="fas fa-phone"></i></span>
                                </div>
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
                        <input type="hidden" class="receiver_id" value ="{{$users->id}}" name="receiver_id"/>
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
    </body>
</html>


  @endsection
@section('page-scripts')
<script>   
    $(document).ready(function(){
$('#action_menu_btn').click(function(){
    $('.action_menu').toggle();
});
    });

 
$(document).ready(function(){
   


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

/*
setInterval(function(){ 
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
    
}, 100);
*/
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
    });    
   
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
    })
});



</script>
    @endsection