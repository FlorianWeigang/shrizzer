<template>

        <div class="row">
            <div class="col-sm-1 voting-container hidden-xs">
                <section class="voting-bar">
                    <div class="voting-button">
                        <a href="#" v-on:click="vote($event, '+1', item.hasUpvoted )"><i class="glyphicon glyphicon-chevron-up"  v-bind:class="{ active: item.hasUpvoted }"></i></a>
                    </div>
                    <strong>{{item.pivot.vote_count}}</strong>
                    <div class="voting-button">
                        <a href="" v-on:click="vote($event, '-1', item.hasDownvoted)"><i class="glyphicon glyphicon-chevron-down" v-bind:class="{ active: item.hasDownvoted }"></i></a>
                    </div>
                </section>
            </div>
            <div class="col-xs-12 col-sm-11">
                <section class="media-block img-rounded">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12">
                            <h5 v-if="item.pivot.title">{{item.pivot.title}}</h5>

                            <article class="media-item">
                                <div class="media-item-margin">
                                    <div v-if="item.embed">
                                        <div class="embed-responsive embed-responsive-16by9" v-html="item.embed"></div>
                                    </div>
                                    <div class="image-preview" v-else-if="item.image_url">
                                        <a :href="item.url" target="_blank"><img :src="item.image_url"  class="img-responsive"/></a>
                                    </div>

                                    <div class="meta">
                                        <h4>{{item.title}}</h4>
                                        <p v-if="item.descriptions">{{item.descriptions | truncate}}</p>
                                        <p><img :src="item.favicon_url"  /> <a :href="item.url" target="_blank">{{item.base_url}}</a></p>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-2 visible-xs">
                            <section class="voting-bar">
                                <div class="voting-button">
                                    <a href="#" v-on:click="vote($event, '+1', item.hasUpvoted)"><i class="glyphicon glyphicon-chevron-up" v-bind:class="{ active: item.hasUpvoted }"></i></a>
                                </div>
                                <strong>{{item.pivot.vote_count}}</strong>
                                <div class="voting-button">
                                    <a href="" v-on:click="vote($event, '-1', item.hasDownvoted)"><i class="glyphicon glyphicon-chevron-down" v-bind:class="{ active: item.hasDownvoted }"></i></a>
                                </div>
                            </section>
                        </div>
                        <div class="col-xs-10 col-sm-12">
                            <article class="comment-section">
                                <ul class="comment-list">
                                    <li class="comment" v-for="comment in item.comments">
                                        {{comment.comment}}
                                        <a href="#" class="pull-right" v-on:click="deleteComment($event, comment.id)"><i class="glyphicon glyphicon-remove"></i></a>
                                    </li>
                                </ul>
                            </article>

                            <form v-on:submit="saveComment">
                                <div class="form-group form-group-sm">
                                    <input type="text" class="form-control" v-model="newComment" placeholder="write a comment..">
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>
</template>
<script>
    export default {
        props: ['item'],
        data: function () {
            return {
                newComment: '',
                sharedState: vueSharedState
            }
        },
        filters: {
            truncate: function (string, value) {
                if (string.length > 103) {
                    return string.substring(0, 100) + '...';
                }

                return string;
            }
        },
        methods: {
            saveComment: function (event) {
                if (event) {
                    event.preventDefault();
                }

                if (this.newComment) {
                    var $this = this;

                    var promise = axios({
                        method: 'post',
                        url: '/sessions/' + this.sharedState.state.session.key + '/urls/' + this.item.id + '/comments',
                        data: {
                        comment: $this.newComment,
                        }
                    });

                    promise.then(function(response){
                        $this.newComment = '';
                        $this.fetchComments();
                    });
                }
            },
            fetchComments: function () {
                var $this = this;

                var promise = axios({
                    method: 'get',
                    url: '/sessions/' + this.sharedState.state.session.key + '/urls/' + this.item.id + '/comments'
                });

                promise.then(function(response){
                    $this.item.comments = response.data;
                });
            },
            deleteComment: function (event, id) {
                var $this = this;

                if (event) {
                    event.preventDefault();
                }

                var promise = axios({
                    method: 'delete',
                    url: '/sessions/' + this.sharedState.state.session.key + '/urls/' + this.item.id + '/comments/' + id
                });

                promise.then(function(response){
                    $this.fetchComments();
                });
            },
            vote: function(event, operation, alreadyVoted) {
                var $this = this;

                if (event) {
                    event.preventDefault();
                }

                if (alreadyVoted) {
                    return;
                }

                var promise = axios({
                    method: 'put',
                    url: '/sessions/' + this.sharedState.state.session.key + '/urls/' + this.item.id,
                    data: {
                        "vote": operation
                    }
                });

                promise.then(function(response){
                    $this.item.pivot.vote_count = response.data.newCount;

                    if (operation == '+1') {
                        $this.item.hasUpvoted = true;
                        $this.item.hasDownvoted = false;
                    } else {
                        $this.item.hasUpvoted = false;
                        $this.item.hasDownvoted = true;
                    }
                }).catch(function (error){
                    if (error.response.status == 422) {
                        eventHub.$emit('flashMessage-set', 'error-double-vote');
                    }

                    $this.subscriber = '';
                });
            }
        }
    }
</script>