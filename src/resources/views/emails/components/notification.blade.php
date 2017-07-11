<table style="border-bottom: 1px solid #dedede;margin-top:10px;margin-bottom: 10px;padding-bottom:10px;width: 100%;padding-left: 5px;">
    <tr style="font-size:14px;">
        @if($notification->type == 'invitation_accepted')
            <td>&checkmark; Someone accepted the request to this session.</td>
        @elseif($notification->type == 'session_invited')
            <td>&commat; A new user was added to the session.</td>
        @elseif($notification->type == 'post_liked')
            <td>The Post "<a href="http://shrizzer.com/session/{{$session->key}}#url-id={{$notification->sessionUrl->id}}">{{$notification->url->title}}</a>" was <strong>upvoted</strong>.</td>
        @elseif($notification->type == 'post_disliked')
            <td>The Post "<a href="http://shrizzer.com/session/{{$session->key}}#url-id={{$notification->sessionUrl->id}}">{{$notification->url->title}}</a>" was <strong>downvoted</strong></td>
        @elseif($notification->type == 'post_commented')
            <td>The Post "<a href="http://shrizzer.com/session/{{$session->key}}#url-id={{$notification->sessionUrl->id}}">{{$notification->url->title}}</a>" was <strong>commented</strong></td>
        @elseif($notification->type == 'post_created')
            <td>A new post was created "<a href="http://shrizzer.com/session/{{$session->key}}#url-id={{$notification->sessionUrl->id}}">{{ $notification->url->title }}</a>"</td>
        @endif
    </tr>
</table>
