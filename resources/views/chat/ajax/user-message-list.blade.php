@if($notifyMessage->count()>0)
    @foreach($notifyMessage as $message)
      @if($message->sender_id==Auth::user()->id)
          <li class="clearfix odd" id="{{$message->id}}_message">
        @else
          <li class="clearfix" id="{{$message->id}}_message">
        @endif
          <div class="conversation-text"><div class="ctext-wrap"> 
            <p id="{{$message->id}}_currentMessage" class="sent-text" >{{$message->message}}
          <i class="text-muted"><small>{{setChatDate($message->created_at)}}</small></i></p></div></div>
        @if($message->sender_id==Auth::user()->id)
          <div class="conversation-actions dropdown">
              <button class="btn btn-sm btn-link" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical font-16"></i></button>
              {{--
              <div class="dropdown-menu">
                <a class="dropdown-item" href="javascript:void(0);" onclick="actionModel(this,{{$message->id}},1)">Edit</a>
                <a class="dropdown-item" href="javascript:void(0)" onclick="actionMessage(this,{{$message->id}},0)">Delete</a>
              </div>
              --}}
            </div>
        @endif
      </li>
    @endforeach
  @else
   
  @endif
  <input type="hidden" id="selected_group" value="{{$selected_group}}"/>
  <input type="hidden" id="selected_recevier" value="{{$user_id}}"/> 
  <input type="hidden" id="max_count" value="{{$max_count}}"/>