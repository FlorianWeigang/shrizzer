<!doctype html>
<head>
</head>
<body>
    <table style="margin:0 auto; width:600px">
    <tr style="text-align:center">
        <td><img src="http://shrizzer.com/images/logo.png" style="width:300px"/></td>
    </tr>
    <tr style="line-height: 30px">
        <td>
            <p style="margin-top:20px;"><strong>Hey Fellow,</strong><br/>We got some notifications for you. The session (<a href="http://shrizzer.com/session/{{$session->key}}">{{$session->name}}</a>) which is followed by you had some new activities:</p>
        </td>
    </tr>

    @if(isset($notifications[\Shrizzer\Models\Notification::NOTIFICATION_GROUP_POSTS]))
        <tr>
            <td>
                <h3>New posts</h3>
            </td>
        </tr>
        @foreach ($notifications[\Shrizzer\Models\Notification::NOTIFICATION_GROUP_POSTS] as $notification)
            <tr>
                <td>
                    <table style="border-bottom: 1px solid #dedede;margin-top:10px;margin-bottom: 10px;padding-bottom:10px">
                        <tr>
                            <td rowspan="3" valign="middle"><a href="http://shrizzer.com/session/{{$session->key}}#url-id={{$notification->sessionUrl->id}}"><img src="{{$notification->url->image_url}}" style="width: 100px;margin-right: 20px"/></a></td>
                            <td valign="bottom"><a href="http://shrizzer.com/session/{{$session->key}}#url-id={{$notification->sessionUrl->id}}" style="color:black;font-size:16px;">{{$notification->url->title}}</a></td>
                        </tr>
                        <tr>
                            <td style="font-size:13px;line-height: 20px" valign="top">
                                <p style="margin-top: 5px;margin-bottom:5px">{{str_limit($notification->url->descriptions, $limit = 150, $end = '...')}} </p>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size:13px" valign="bottom">
                                <img src="{{$notification->url->favicon_url}}" style="height:10px"/> {{$notification->url->base_url}}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        @endforeach
    @endif

    @if(isset($notifications[\Shrizzer\Models\Notification::NOTIFICATION_GROUP_ACTIVITY]))
        <tr>
            <td>
                <h3>Activity on posts</h3>
            </td>
        </tr>
            <!-- Preview Loop-->
        @foreach ($notifications[\Shrizzer\Models\Notification::NOTIFICATION_GROUP_ACTIVITY] as $notification)
            <tr>
                <td>
                    @include("emails.components.notification", ['notification' => $notification, "session"=> $session])
                </td>
            </tr>
        @endforeach
    @endif

    @if(isset($notifications[\Shrizzer\Models\Notification::NOTIFICATION_GROUP_SESSION]))
        <tr>
            <td>
                <h3>Session updates</h3>
            </td>
        </tr>
        <!-- Preview Loop-->
        @foreach ($notifications[\Shrizzer\Models\Notification::NOTIFICATION_GROUP_SESSION] as $notification)
            <tr>
                <td>
                    @include("emails.components.notification", ['notification' => $notification, "session" => $session])
                </td>
            </tr>
        @endforeach
    @endif
    <tr>
        <td><p style="height:20px">&nbsp;</p></td>
    </tr>
    <tr>
        <td>If you want to unsubscribe from this session click <a href="#">here</a></td>
    </tr>
    <tr>
        <td><p style="height:20px">&nbsp;</p></td>
    </tr>
    </table>
</body>
</html>