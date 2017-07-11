<header class="big-header visible-xs">
    <div class="header-middle">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <nav class="navbar">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menu" aria-expanded="false">
                                <i class="glyphicon glyphicon-menu-hamburger"></i>
                            </button>
                            <a class="navbar-brand" href="/"><img src="/images/logo.png" class="logo"/></a>
                        </div>
                        <div id="menu" class="main-menu collapse navbar-collapse pull-left">

                            <ul class="nav navbar-nav">

                                <li class="menu-item" v-if="page != 'activity'">
                                    <a href="" v-on:click="switchPage($event, 'activity')">Show activity log</a>
                                </li>
                                <li class="menu-item" v-else>
                                    <a href="" v-on:click="switchPage($event, 'timeline')">Show Timeline</a>
                                </li>



                                <li class="menu-item top-bar">
                                    <a href="">Order by votes</a>
                                </li>
                                <li class="menu-item">
                                    <a href="">Upvoted by me</a>
                                </li>
                                <li class="menu-item">
                                    <a href="">Commented by me</a>
                                </li>

                                <li class="menu-item menu-item-has-children top-bar" v-bind:class="{open: lastVisited}">
                                    <a href="#" v-on:click="toggleMenu($event, 'lastVisited')">Last visited sessions</a>
                                    <ul class="sub-menu children">
                                        @foreach($lastSessions as $last)
                                            <li><a href="/session/{{$last->key}}">{{$last->name}}</a></li>
                                        @endforeach
                                    </ul>
                                </li>

                                <li class="menu-item">
                                    <a href="/contact">Contact</a>
                                </li>

                                <li class="menu-item">
                                    <a href="/about">Imprint</a>
                                </li>
                            </ul>
                        </div><!-- /.navbar-collapse -->
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>