@extends('layouts.admin.admin-app')

@section('title')
    Messages - {{ Config::get('app.name') }}
@endsection
@section('page-css')
<link rel="stylesheet" href="{{ asset('css/chat.css') }}">
<style>
.select2-container.select2-container--default.select2-container--open{width:100%;height:auto;min-height:100%}
.active-click-user{background-color:#efefef}
.select2-container{width:100%!important}
.chat-heading{font-size:13px!important;padding-top:6px}
.groupChat{padding:.5rem!important;font-size:15px}
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/line-awesome/css/line-awesome.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simplebar/5.3.5/simplebar.css" />
<link rel="stylesheet" href="{{ asset('css/chat.css') }}">
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Messaging Center</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Messaging Center</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content chat-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-3 col-lg-4 responsive-chat-list">
                        <div class="card user-card-list">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-soft-secondary font-12 rounded-circle receiever-name">
                                            {!! getFirstLetterString(Auth::user()->username) !!}
                                        </span>
                                    </div>
                                    <div class="pl-2 avatar-user-name">
                                        <h5 class="mt-0 mb-0 font-15">
                                            <a href="javascript: void(0)" class="text-reset">{{Auth::user()->username}}</a>
                                        </h5>
                                        <p class="online-circle">
                                            <small class="fas fa-circle text-success"></small> Online
                                        </p>
                                    </div>
                                </div>
                                <div class="position-relative">
                                    {{ Form::select('user_id', $users, '', array('class'=>'form-control select2 mb-2', 'placeholder'=>'Select User', 'id'=> 'user_id'))  }}
                                </div>
                                <!--  Group chat    -->
                                <!-- Only for Admin or Cashier  -->
                                @if((Auth::user()->getRoleNames()->first()=="Admin" || Auth::user()->getRoleNames()->first() == "Cashier") && ($user_count > 0 || $cashier_count > 0))
                                    <h6 class="text-muted text-uppercase mt-3 chat-heading">Group Chats</h6>
                                    @if( $user_count > 0 )
                                    <a href="javascript:void(0);" class="receiver-user-div text-body receiver customer_user_change_message" id="customer">
                                        <div class="media p-1 user_message_div_customer">
                                            <div class="avatar-sm">
                                                <span class="avatar-title bg-soft-secondary text-secondary font-12 rounded-circle receiever-name">C</span>
                                            </div>
                                            <div class="media-body m-1">
                                                <h5 class="mt-0 mb-0 font-14">
                                                    <span class="float-right text-muted font-weight-normal font-12"></span>
                                                    Customers
                                                </h5>
                                                <p class="mb-0 text-muted font-14">
                                                    <span class="w-75 customer_user_last_message"></span>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                    @endif
                                    @if(Auth::user()->getRoleNames()->first()=="Admin" && $cashier_count > 0)

                                    <a href="javascript:void(0);" class="receiver-user-div text-body receiver cashier_user_change_message" id="cashier">
                                        <div class="media p-1 user_message_div_cashier">
                                            <div class="avatar-sm">
                                                <span class="avatar-title bg-soft-secondary text-secondary font-12 rounded-circle receiever-name">H</span>
                                            </div>
                                            <div class="media-body m-1">
                                                <h5 class="mt-0 mb-0 font-14">
                                                    <span class="float-right text-muted font-weight-normal font-12"></span>
                                                    Hosts
                                                </h5>
                                                <p class="mb-0 text-muted font-14">
                                                    <span class="w-75 cashier_user_last_message"></span>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                    @endif
                                @endif
                                <!--  End Group chat   -->
                                <h6 class="font-13 text-muted text-uppercase my-3">Contacts</h6>
                                <div class="row">
                                    <div class="col">
                                        <div class="user-chat-list" data-simplebar style="max-height: 375px">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-9 col-lg-8 responsive-chat-body">
                        <div class="card top-user-status">
                            <div class="card-body py-2 px-3 border-bottom border-light">
                                <div class="media">
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-soft-secondary text-secondary font-10 rounded-circle current-reciever-logo-text d-none"></span>
                                        <small class="fas fa-circle text-danger d-none current-reciever-status"></small>
                                    </div>
                                    <div class="media-body m-1 responsive-back">
                                        <h5 class="mt-0 mb-0 font-15">
                                            <a href="#" class="text-reset current-reciever"></a>
                                        </h5>
                                        <a id="back" href="javascript: void(0)">back</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body chat-body-main">

                                <h3 class="text-center" id="default_text">No Message Yet!
                                    <p class="text-center">looks like you haven't initiated a conversation with any of your connections.</p>
                                </h3>

                                <div class="load-chat-spinner d-none">
                                    <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <ul class="conversation-list" id="conversation-list-scroll" data-simplebar-auto-hide="false" data-simplebar >

                                </ul>
                                <input type="hidden" id="page_start" value="1"/>
                                <div class="row d-none w-100" id="chat-section">
                                    <div class="col">
                                        <div class="mt-2 bg-light p-1 rounded">
                                            <div class="row">
                                                <div class="col mb-2 mb-sm-0">
                                                    <input type="text" class="form-control border-0" id="message_text" placeholder="Enter your text" required="">
                                                    <div class="invalid-feedback">
                                                        Please enter your messsage
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="btn-group">
                                                        <button type="button" id="send_message" onclick="sendMessage()" class="btn btn-success chat-send btn-block" data-style="slide-down"><i class='fas fa-paper-plane'></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection
@section('page-scripts')
<script src="https://js.pusher.com/6.0/pusher.min.js"></script>
<script src="js/app.js"></script>
<script>

    let screenSize = window.screen.availWidth;


    var didScroll = false;
    var receiver_id = '';
    var my_id = "{{ Auth::id() }}";
    function userChatList()
    {
        $.ajax({
            type: 'POST',
            url: "{{route('chat.list')}}",
            dataType: "json",
            success: function(resultData) {
                if(resultData.success)
                {
                    $('.user-chat-list').html(resultData.html);
                }
                else if(!resultData.success)
                {
                    toastr.error(resultData.error);
                }
            },
            error: function (jqXHR, exception) {
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                }
                else if (jqXHR.status == 401) {
                    window.location.reload();
                }
                else if (jqXHR.status == 404) {
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
                toastr.error(msg);
            }
        });
    }
    function getMessage(user_id=Null,list=NULL,page=Null)
    {
        $('.active-click-user').removeClass('active-click-user');
        if(user_id!=='') {
            var my_id = "{{ Auth::id() }}";
            $('.user_message_div_'+user_id).addClass('active-click-user');
            $.ajax({
                type: 'get',
                url: "{{route('chat.fetchMessages')}}",
                data: {page:page,user_id:user_id},
                dataType: "json",
                beforeSend:function(e)
                {
                    if(list==2)
                    {
                        $('.load-chat-spinner').removeClass('d-none');
                    }
                },
                success: function(resultData) {
                    if(resultData.success)
                    {
                        $('#default_text').addClass('d-none');
                        $('#chat-section').addClass('d-none');
                        if(resultData.send_message)
                        {
                            $('#chat-section').removeClass('d-none');
                        }
                        if(list==1)
                        {
                            if(resultData.max_count==0)
                            {
                                $('.current-reciever-logo-text').addClass('d-none');
                            }
                            else {
                                $('.current-reciever-logo-text').removeClass('d-none');
                            }
                            $('.current-reciever').html(resultData.reciever_name);
                            $('.current-reciever-logo-text').html(resultData.reciever_logo_text);
                            $('.current-reciever-status').removeClass('d-none');
                            $('.current-reciever-status').attr('id',user_id+'_current-reciever-status');
                            if($('#is_online_'+user_id).hasClass('text-success'))
                            {
                                $('#'+user_id+'_current-reciever-status').addClass('text-success');
                                $('#'+user_id+'_current-reciever-status').removeClass('text-danger');
                            }
                            else{
                                $('#'+user_id+'_current-reciever-status').addClass('text-danger');
                            }
                        }
                        var user_message_count = parseInt($('#'+user_id+'_pending').text());
                        var current_message = parseInt($('#'+my_id+'_current_pending').text());
                        $('#'+user_id+'_pending').text('0');

                        var pending_message = current_message-user_message_count;
                        if(pending_message>0) {
                           $('#'+my_id+'_current_pending').text(pending_message);
                        }
                        else {
                            $('#'+my_id+'_current_pending').text('0');
                        }

                        if(list==2) {
                            $('.load-chat-spinner').addClass('d-none');
                            $('.conversation-list .simplebar-content').prepend(resultData.html);
                        }
                        else {
                            $('.conversation-list .simplebar-content').html(resultData.html);

                        }
                        var container = document.querySelector('#conversation-list-scroll .simplebar-content-wrapper');
                        container.scrollTo({ top: 2500, behavior: "smooth" });
                    }
                    else if(!resultData.success)
                    {
                        toastr.error(resultData.error);
                    }
                },
                error: function (jqXHR, exception) {
                    var msg = '';
                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    }
                    else if (jqXHR.status == 401) {
                        window.location.reload();
                    }
                    else if (jqXHR.status == 404) {
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
                    toastr.error(msg);
                }
            });
        }
    }

    function getGroupMessage(user,list=NULL,page=Null)
    {
        $('.active-click-user').removeClass('active-click-user');
        if(user!=='') {
            var my_id = "{{ Auth::id() }}";
            $('.user_message_div_'+user).addClass('active-click-user');
            $.ajax({
                type: 'get',
                url: "{{route('chat.fetchGroupMessages')}}",
                data: {page:page,userRequest:user},
                dataType: "json",
                beforeSend:function(e)
                {
                    if(list==2)
                    {
                        $('.load-chat-spinner').removeClass('d-none');
                    }
                },
                success: function(resultData) {
                    if(resultData.success)
                    {
                        $('#default_text').addClass('d-none');
                        $('#chat-section').addClass('d-none');
                        if(resultData.send_message)
                        {
                            $('#chat-section').removeClass('d-none');
                        }
                        if(list==1)
                        {
                            if(resultData.max_count==0)
                            {
                                $('.current-reciever-logo-text').removeClass('d-none');
                            }
                            else {
                                $('.current-reciever-logo-text').removeClass('d-none');
                            }
                            $('.current-reciever').html(resultData.reciever_name);
                            $('.current-reciever-logo-text').html(resultData.reciever_logo_text);
                            $('.current-reciever-status').addClass('d-none');
                            // $('.current-reciever-status').attr('id',user_id+'_current-reciever-status');
                            // if($('#is_online_'+user_id).hasClass('text-success'))
                            // {

                            //     $('#'+user_id+'_current-reciever-status').removeClass('text-danger');
                            // }
                            // else{
                            //     $('#'+user_id+'_current-reciever-status').addClass('text-danger');
                            // }
                        }

                        if(list==2) {
                            $('.load-chat-spinner').addClass('d-none');
                            $('.conversation-list .simplebar-content').prepend(resultData.html);
                        }
                        else {
                            $('.conversation-list .simplebar-content').html(resultData.html);

                        }
                        var container = document.querySelector('#conversation-list-scroll .simplebar-content-wrapper');
                        container.scrollTo({ top: 2500, behavior: "smooth" });
                    }
                    else if(!resultData.success)
                    {
                        toastr.error(resultData.error);
                    }
                },
                error: function (jqXHR, exception) {
                    var msg = '';
                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    }
                    else if (jqXHR.status == 401) {
                        window.location.reload();
                    }
                    else if (jqXHR.status == 404) {
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
                    toastr.error(msg);
                }
            });
        }
    }

    function pageCountUpdate(){
        var page = parseInt($('#page_start').val());
        var max_page = parseInt($('#max_count').val());

        if( (page*30) < max_page){
            $('#page_start').val(page+1);
            var user_id = $('#selected_recevier').val();
            var list = 2;
            var next_page = $('#page_start').val();
            var selected_group = $('#selected_group').val();
            if(selected_group)
            {
                getGroupMessage(selected_group,list,page);
            }
            else {
                getMessage(user_id,list,next_page);
            }
        }
        else
        {
            return false;
        }
    }

    function sendMessage()
    {
        var message_text = $('#message_text').val();
        $('#message_text').val('');
        receiver_id =  $('#selected_recevier').val();
        if (typeof receiver_id !== 'undefined' && $.trim(message_text) !='')
        {
            $.ajax({
                type: 'POST',
                url: "{{route('chat.sendMessage')}}",
                data:  {user_id:receiver_id,message_text:message_text},
                dataType: "json",
                beforeSend: function() {
                    l = Ladda.create( document.querySelector('.chat-send') );
                    l.start();
                },
                success: function(resultData) {
                    l.stop();
                    if(resultData.success)
                    {

                    }
                    else if(!resultData.success)
                    {
                        toastr.error(resultData.error);
                    }
                },
                error: function (jqXHR, exception) {
                    var msg = '';
                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    }
                    else if (jqXHR.status == 401) {
                        window.location.reload();
                    }
                    else if (jqXHR.status == 404) {
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
        }
    }

    function updateLastMsgUserList(user_receiver=Null,message=Null,change_position=Null)
    {
        if(typeof user_receiver === 'number')
        {
            $('.'+user_receiver+'_user_last_message').text(message);
            if(change_position)
            {
                $('.'+user_receiver+'_user_change_message').clone().hide().prependTo('.user-chat-list').slideDown();
                $('.'+user_receiver+'_user_change_message:last').remove();
            }
        }
        else if(typeof user_receiver === 'string'){
            user_receiver.split(",").forEach((val)=>{
                $('.'+val+'_user_last_message').text(message);
                if(change_position)
                {
                    $('.'+val+'_user_change_message').clone().hide().prependTo('.user-chat-list').slideDown();
                    $('.'+val+'_user_change_message:last').remove();
                }
            })
        }
    }

    function appendLastMessageStatic(data=Null)
    {
        var my_id = "{{ Auth::id() }}";
        var last_message = '';
        if(my_id==data.from)
        {
            last_message+='<li class="clearfix odd" id="'+data.message_id+'_message">'
        }
        else
        {
            last_message+='<li class="clearfix" id="'+data.message_id+'_message">'
        }
        last_message+='<div class="conversation-text"><div class="ctext-wrap">'
            +'<p class="sent-text" id="'+data.message_id+'_currentMessage">'+data.last_message
          +'<i class="text-muted"><small>'+data.chat_date+'</small></i></p></div></div>'
        last_message+='</li>'

        $('.conversation-list .simplebar-content').append(last_message);
        $('#conversation-list-scroll .simplebar-content-wrapper').scrollTop($('#conversation-list-scroll .simplebar-content-wrapper').find('.simplebar-content').height());
        var user_message_count = parseInt($('#'+data.to+'_pending').text());
        var current_message_count = parseInt($('#'+my_id+'_current_pending').text());
        if(data.to.indexOf(',') == -1)
        {
            $('#'+data.to+'_pending').text('0');
        }

        var user_receiver;
        if(data.from==my_id)
        {
            user_receiver = data.to;
        }
        else
        {
            user_receiver = data.from;
        }
        updateLastMsgUserList(user_receiver,data.last_short_message,change_position=false);
        var pending_message = current_message_count-user_message_count;
        if(pending_message>0)
        {
            $('#'+my_id+'_current_pending').text(pending_message);
        }
        else
        {
            $('#'+my_id+'_current_pending').text('0');
        }
    }

    $('body').on('click', '#back', function(e) {
        let screenSize = window.screen.availWidth;
        if(screenSize <= 991){
            $('.responsive-chat-body').css({"display": "none"});
            $('.responsive-chat-list').css({"display": "block"});
        }
    });

    $('body').on('click', '.receiver', function(e) {
        let screenSize = window.screen.availWidth;
        if(screenSize <= 991){
            $('.responsive-chat-body').css({"display": "block"});
            $('.responsive-chat-list').css({"display": "none"});
        }

        var user_id = $(this).attr('id');
        var list = 1;
        var page = 1;
        $('#page_start').val(1);
        if(user_id == 'customer' || user_id == 'cashier') {
            getGroupMessage(user_id,list,page);
        }
        else{
            getMessage(user_id,list,page);
        }
    });

    $('.select2').select2();

    
    function reportWindowSize() {
        console.log('screenSize',window.screen.availWidth)
        window.screen.availWidth >= 991 ? $('.responsive-chat-list').css({"display": "block"}) : "";
    }

    $(document).ready(function(){

        

        userChatList();

        $('#user_id').change(function() {

            if(screenSize <= 991){
                $('.responsive-chat-body').css({"display": "block"});
                $('.responsive-chat-list').css({"display": "none"});
            }
            var user_id = $(this).val();
            var name = $("#user_id option:selected").text();
            var matches = name.match(/\b(\w)/g);
            var logo_text = matches.join('');

            $('.current-reciever-logo-text').removeClass('d-none');
            $('.current-reciever').html(name);
            $('.current-reciever-logo-text').html(logo_text);
            $('.current-reciever-status').removeClass('d-none');
            $('.current-reciever-status').attr('id',user_id+'_current-reciever-status');
            var list = 0;
            var page =1;
            $('#page_start').val(1);
            getMessage(user_id,list,page);
        });
        $('#conversation-list-scroll .simplebar-content-wrapper').on('scroll',function(e){
            didScroll = true;
        });
        setInterval(function() {
            if (didScroll){
               didScroll = false;
               var scroll_position = $('#conversation-list-scroll .simplebar-content-wrapper').scrollTop();
               if(scroll_position <= 10)
               {
                   pageCountUpdate();
               }
            }
        }, 2000);

        Pusher.logToConsole = true;
        var pusher = new Pusher("{{config('services.pusher.PUSHER_APP_KEY')}}", {
            cluster: "{{config('services.pusher.PUSHER_APP_CLUSTER')}}",
            forceTLS: true,
            encrypted: true ,
            authEndpoint:"{{route('pusher.auth')}}",
            auth: {
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                }
            },
        });
        var channel = pusher.subscribe('send_message_channel');
        channel.bind('send_message_event', function (data) {
            //userChatList();
            if (my_id == data.from) {
                var me_click = $('#selected_recevier').val();
                if(me_click==data.to)
                {
                    appendLastMessageStatic(data);
                }
            }
            else if (my_id == data.to) {
                var me_click = $('#selected_recevier').val();
                if (me_click == data.from)
                {
                    appendLastMessageStatic(data);
                }
                else
                {
                    var pending = parseInt($('#'+data.from+'_pending').text());
                    if ($('#'+data.from+'_pending').length) {
                        $('#'+data.from+'_pending').text(pending + 1);
                    }
                    else{
                        $('#'+data.from+'_pending').text('0');
                    }
                    updateLastMsgUserList(data.from,data.last_short_message,change_position=true);
                }
            }
            else {
                var me_click = $('#selected_recevier').val();
                if (typeof me_click !== 'undefined' && me_click == data.from)
                {
                    appendLastMessageStatic(data);
                }
                else {
                    if(data.to.indexOf(',') != -1)
                    {
                        data.to.split(",").forEach((val)=>{
                            data.to = val;
                            if(my_id == data.to)
                            {
                                updateLastMsgUserList(data.from,data.last_short_message,change_position=true);
                            }
                        })
                    }
                }
            }
        });


        var presence = pusher.subscribe('presence-user');
        presence.bind('pusher:subscription_succeeded', function(members) {
            members.each(function(member) {
                $('#is_online_'+member.id).removeClass('text-danger');
                $('#is_online_'+member.id).addClass('text-success');

                $('#'+member.id+'_current-reciever-status').removeClass('text-danger');
                $('#'+member.id+'_current-reciever-status').addClass('text-success');
            });
        });

        presence.bind('pusher:member_added', function(member) {
            $('#is_online_'+member.id).removeClass('text-danger');
            $('#is_online_'+member.id).addClass('text-success');

            $('#'+member.id+'_current-reciever-status').removeClass('text-danger');
            $('#'+member.id+'_current-reciever-status').addClass('text-success');
        });

        presence.bind('pusher:member_removed', function(member) {
            $('#is_online_'+member.id).addClass('text-danger');
            $('#is_online_'+member.id).removeClass('text-success');

            $('#'+member.id+'_current-reciever-status').removeClass('text-danger');
            $('#'+member.id+'_current-reciever-status').addClass('text-success');
        });

        presence.bind('pusher:subscription_error', function(data) {
            // console.log("pus",data);
        });

        $('#action_menu_btn').click(function(){
            $('.action_menu').toggle();
        });
    });

    window.onresize = reportWindowSize;
    //execue send message
    var message = document.getElementById("message_text");

    message.addEventListener("keydown", function (e) {
        if (e.keyCode === 13) {  //checks whether the pressed key is "Enter"
            $('#send_message').click();
        }
    });
</script>
@endsection
