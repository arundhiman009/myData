@if($contact_list->count() > 0) 
    @foreach($contact_list as $key=>$value)
        <a href="javascript:void(0);" class="receiver-user-div text-body receiver {{$value->sender_id == Auth::user()->id ? $value->toMessage->id : $value->fromMessage->id}}_user_change_message" id="{{$value->sender_id == Auth::user()->id ? $value->toMessage->id : $value->fromMessage->id}}">
            <div class="media p-1 user_message_div_{{$value->sender_id == Auth::user()->id ? $value->toMessage->id : $value->fromMessage->id}}">
                <div class="avatar-sm">
                    <span class="avatar-title bg-soft-secondary text-secondary font-12 rounded-circle receiever-name">
                        {{ $value->sender_id == Auth::user()->id ? getFirstLetterString($value->toMessage->username) : getFirstLetterString($value->fromMessage->username)}}
                    </span>
                    <small class="fas fa-circle text-danger" id="is_online_{{$value->sender_id == Auth::user()->id ? $value->toMessage->id : $value->fromMessage->id}}"></small>
                </div>  
                <div class="media-body m-1">
                    <h5 class="mt-0 mb-0 font-14">
                        <span class="float-right text-muted font-weight-normal font-12">{{setChatDate($value->created_at)}}</span>
                        {{ $value->sender_id == Auth::user()->id ? $value->toMessage->username : $value->fromMessage->username }}
                    </h5>
                    <p class="mb-0 text-muted font-14">
                        <span class="w-25 float-right text-right"><span class="badge badge-soft-danger pending" id="{{$value->sender_id == Auth::user()->id ? $value->toMessage->id : $value->fromMessage->id}}_pending">{{getUnreadMessageCount( $value->sender_id == Auth::user()->id ? $value->toMessage->id : $value->fromMessage->id )}}</span></span>
                        <span class="w-75 {{$value->sender_id == Auth::user()->id ? $value->toMessage->id : $value->fromMessage->id}}_user_last_message">{{ limitString($value->message, 10) }}</span>
                    </p>
                </div>
            </div>
        </a>
    @endforeach 
    @else
      <h6 class="text-center">No converstation till yet!</h6>
@endif