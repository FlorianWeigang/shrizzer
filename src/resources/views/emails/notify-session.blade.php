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
            <p style="margin-top:20px;"><strong>Hey Dude,</strong><br/>We got some updates for you. The Session (<a href="http://shrizzer.com/session/{{$session->key}}">{{$session->key}}</a>) which is followed by you was updated
            with fresh content:</p>
        </td>
    </tr>

    <!-- Preview Loop-->
    @foreach ($urls as $url)
        <tr>
            <td>
                <table style="border-bottom: 1px solid #dedede;margin-top:10px;margin-bottom: 10px;padding-bottom:10px">
                    <tr>
                        <td rowspan="3" valign="middle"><a href="{{$url->url}}"><img src="{{$url->image_url}}" style="width: 100px;margin-right: 20px"/></a></td>
                        <td valign="bottom"><a href="{{$url->url}}" style="color:black;font-size:16px;">{{$url->title}}</a></td>
                    </tr>
                    <tr>
                        <td style="font-size:13px;line-height: 20px" valign="top">
                            <p style="margin-top: 5px;margin-bottom:5px">{{str_limit($url->descriptions, $limit = 150, $end = '...')}} </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:13px" valign="bottom">
                            <img src="{{$url->favicon_url}}" style="height:10px"/> {{$url->base_url}}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    @endforeach
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