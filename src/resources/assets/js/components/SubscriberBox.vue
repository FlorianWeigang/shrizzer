<template>
    <div class="subscriber-box">
        <h4>Email notifications</h4>
        <p>Get notifications about updates every hour.</p>
        <ul v-if="subscribers.length == 0">
            <li>No one is following this session</li>
        </ul>
        <ul v-else>
            <li v-for="subscriber in subscribers">
                {{subscriber.pivot.nickname}}
                <i v-if="subscriber.pivot.status == 'confirmed'" class="glyphicon glyphicon-ok"></i>
                <i v-else class="glyphicon glyphicon-time"></i>
                <a href="#" v-on:click="removeSubscriber(subscriber.id)" class="pull-right"><i class="glyphicon glyphicon-remove-circle"></i></a>
            </li>
        </ul>

        <div class="">
            <form class="form-inline">
                <div>
                    <div class="form-group form-group-sm"">
                        <input class="form-control" id="subscriber-input" v-model="subscriber" style="width:240px" placeholder="Add your email or from a friend"/>
                    </div>
                    <button class="btn btn-sm btn-default" v-on:click="addSubscriber">add</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script type="application/javascript">
    export default {
        data: function () {
            return {
                subscriber: '',
                subscribers: [],
                sharedState: vueSharedState
            }
        },
        created: function() {
            eventHub.$on('sharedData-set',  this.sharedDataSet);
        },
        methods: {
            sharedDataSet: function() {
                this.getSubscribers();
            },
            addSubscriber: function (event) {
                if (event) {
                    event.preventDefault();
                }

                var $this = this;

                var promise = axios({
                    method: 'post',
                    url: '/sessions/' + this.sharedState.state.session.key + '/users',
                    data: {
                        email: this.subscriber
                    }
                });

                promise.then(function(response){
                    $this.subscriber = '';
                    $this.getSubscribers();
                }).catch(function (error, code){
                    if (error.response.status == 422) {
                        eventHub.$emit('flashMessage-set', 'error-add-invalid-email-to-session');
                    } else {
                        eventHub.$emit('flashMessage-set', 'error-add-email-to-session');
                    }

                    $this.subscriber = '';
                });

            },
            removeSubscriber: function (id, event) {
                if (event) {
                    event.preventDefault();
                }

                var $this = this;

                var promise = axios({
                    method: 'delete',
                    url: '/sessions/' + this.sharedState.state.session.key + '/users/' + id
                });

                promise.then(function(response){
                    $this.getSubscribers();
                });
            },
            getSubscribers: function () {
                var $this = this;

                var promise = axios({
                    method: 'get',
                    url: '/sessions/' + this.sharedState.state.session.key + '/users'
                });

                promise.then(function(response){
                    $this.subscribers = response.data;
                });
            }
        }
    }
</script>