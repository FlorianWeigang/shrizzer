@extends('layouts.app', ['sharedData' => $sharedData])

@section('title', 'share things you love')

@section('content')

    @include('components.header-big', ['lastSessions' => $lastSessions])

    <section class="sub-navigation">
        <div class="container">
            <div class="row">
                <div class="col-md-2 hidden-xs">
                    <a href="/"><img src="/images/logo.png" class="logo" style="max-height: 30px;" /></a>
                </div>
                <div class="col-xs-12 col-md-6">
                    <MediaForm></MediaForm>
                </div>
                <div class="col-md-4 hidden-xs">

                </div>
            </div>
            <div class="row visible-xs">
                <div class="col-xs-12">
                    <div class="btn-group  btn-group-justified" role="group" style="margin-top:10px;">
                        <div class="btn-group btn-group-sm" role="group">
                            <button v-on:click="openModal('subscriber-modal')" type="button" class="btn btn-default">Follower</button>
                        </div>
                        <div class="btn-group btn-group-sm" role="group">
                            <button v-on:click="openModal('share-modal')" type="button" class="btn btn-default">Share</button>
                        </div>
                        <div class="btn-group btn-group-sm" role="group">
                            <button v-on:click="openModal('setting-modal')"  type="button" class="btn btn-default">Settings</button>
                        </div>
                    </div>
                </div>
            </div>

            <flasher></flasher>

        </div>
    </section>



    <div class="container" >
        <div class="row">
            <div class="col-md-2 hidden-xs">

                <ul class="side-navigation">
                    <li class="group" v-if="page != 'activity'">
                        <a href="" v-on:click="switchPage($event, 'activity')">Show activity log</a>
                    </li>
                    <li class="group" v-else>
                        <a href="" v-on:click="switchPage($event, 'timeline')">Show Timeline</a>
                    </li>
                    <li class="group">
                        <a href="">Order by votes</a>
                    </li>
                    <li>
                        <a href="">Upvoted by me</a>
                    </li>
                    <li>
                        <a href="">Commented by me</a>
                    </li>
                    <li class="group">
                        <h5>Last visited sessions</h5>
                        <ul>
                            @foreach($lastSessions as $last)
                                <li><a href="/session/{{$last->key}}">{{$last->name}}</a></li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="col-xs-12 col-md-6" v-bind:class="{hidden: !showPreLoader}">
                <section class="preloader" v-if="showPreLoader">
                    <ui-preloader :show="showPreLoader"></ui-preloader>
                    <p>Fetching data.</p>
                </section>
            </div>
            <div class="col-xs-12 col-md-6" v-bind:class="{hidden: (showPreLoader || page != 'timeline')}">
                <MediaList sessionkey="{{$session->key}}"></MediaList>
            </div>
            <div class="col-xs-12 col-md-6" v-bind:class="{hidden: (showPreLoader || page != 'activity')}">
                <ActivityLog sessionkey="{{$session->key}}"></ActivityLog>
            </div>
            <div class="col-md-4 sidebar hidden-xs" id="sidebar">
                <sh-sidebar></sh-sidebar>

                <p style="text-align: center">
                    <a href="/contact">Contact</a> | <a href="/about">Imprint</a>
                </p>
            </div>
        </div>
    </div>


    <sh-modalbar></sh-modalbar>

@endsection