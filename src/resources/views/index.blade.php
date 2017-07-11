@extends('layouts.app')

@section('title', 'Share things you love')


@section('content')

    @include('components.header-small')

    <section class="intro">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <p>
                        <span class="label label-primary">Beta</span> The free, registration less, anonymously link timeline you ever waited for.
                    </p>

                    <MediaForm :isnew="1"></MediaForm>

                    <flasher></flasher>
                </div>
            </div>
        </div>
    </section>


    @if (isset($lastSessions) && count($lastSessions) > 0)
        <section class="session-list">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Last visited sessions</h2>
                    </div>
                </div>
                <div class="row">
                    @foreach($lastSessions as $session)
                        <div class="col-xs-6 col-md-3">
                            <div class="panel panel-default preview-box">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><a href="/session/{{$session->key}}">{{$session->name}}</a></h3>
                                </div>
                                <div class="panel-body">
                                    <a href="/session/{{$session->key}}"><img class="img-responsive" src="{{($session->getLastUrl()) ? $session->getLastUrl()->image_url:null}}" /></a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif


    <section class="description">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2>What is shrizzer.com</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Free & Share</h3>
                        </div>
                        <div class="panel-body">
                            <img class="img-responsive" src="/images/instant-free.png" />
                        </div>
                        <div class="panel-footer">
                            Just post a link in the top bar and you create instant and free a new session. Every session can be shared without registration via email
                            or link with all your friends, family members or colleagues.
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Interact & Collect</h3>
                        </div>
                        <div class="panel-body">
                            <img class="img-responsive" src="/images/interact-and-share.png" />
                        </div>
                        <div class="panel-footer">
                            Every link which gets shared generate a post on a session. This posts can be liked and commented. Every email which is connected to this session gets notified about updates.
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Grouped & Centralized</h3>
                        </div>
                        <div class="panel-body">
                            <img class="img-responsive" src="/images/grouped-2.png" />
                        </div>
                        <div class="panel-footer">
                            Group all your links inside a session and collect them on shrizzer. Never loose a link in a messenger history or email conversations again. They are all collected and shareable online.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <a href="/contact">Contact</a> | <a href="/about">Imprint</a>
        </div>
    </footer>

@endsection