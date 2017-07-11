<template>
    <article class="media-form">
        <form class="form-inline">
            <div class="form-group">
                <div class="visible-xs">
                    <div class="input-group">
                        <input v-if="loading" disabled class="form-control" v-model="url" placeholder="Enter url to music, products or just things you love"/>
                        <input v-else class="form-control" v-model="url" placeholder="Enter url to music, products or just things you love"/>
                        <div class="input-group-addon"><span class="glyphicon glyphicon-plus" aria-hidden="true" v-on:click="buttonClick"></span></div>
                    </div>
                </div>
                <div class="hidden-xs desktop-input">
                    <input v-if="loading" disabled class="form-control" v-model="url" placeholder="Enter url to music, products or just things you love"/>
                    <input v-else class="form-control" v-model="url" placeholder="Enter url to music, products or just things you love"/>
                </div>
            </div>
            <div class="hidden-xs button-bar">
                <ui-button v-if="isnew" :loading="loading" v-on:click="buttonClick">create</ui-button>
                <ui-button v-else :loading="loading" v-on:click="buttonClick">Post it</ui-button>
            </div>
        </form>

        <ui-confirm autofocus="none" confirm-button-text="Create" deny-button-text="Close" ref="create-session-pw" title="Enter password" @confirm="createSession" @deny="closePasswordModal">
            <form>

                <div class="form-group">
                    <input type="password" class="form-control" v-model="password" placeholder="Password">
                </div>
            </form>
        </ui-confirm>
    </article>
</template>

<script type="application/javascript">
    export default {
        props: ['isnew'],
        data: function () {
            return {
                url: '',
                loading: false,
                sharedState: vueSharedState,
                password: ''
            }
        },

        methods: {
            /**
             *
             */
            buttonClick: function(event) {
                if (event) {
                    event.preventDefault();
                }

                if (this.isnew == '1') {
                    //this.openPasswordModal();
                    this.createSession();
                } else {
                    this.addUrl();
                }
            },

            openPasswordModal: function () {
                this.$refs['create-session-pw'].open();
            },

            closePasswordModal: function () {
                this.$refs['create-session-pw'].close();
            },

            createSession: function (event) {
                var $this = this;

                var promise = axios({
                    method: 'post',
                    url: '/sessions',
                    data: {
                        name: 'Untitled Session',
                        url: this.url
                    }
                });

                $this.loading = true;

                promise.then(function(response){
                    location.href = '/session/' + response.data.key;

                    $this.loading = false;
                }).catch(function (error, code){
                    if (error.response.status == 422) {
                        eventHub.$emit('flashMessage-set', 'error-add-invalid-url-to-session');
                    } else {
                        eventHub.$emit('flashMessage-set', 'session-create-not-allowed');
                    }

                    $this.password = '';
                    $this.loading = false;
                });
            },
            addUrl: function() {
                var $this = this;

                var promise = axios({
                    method: 'post',
                    url: '/sessions/' + this.sharedState.state.session.key + '/urls',
                    data: {
                        url: this.url
                    }
                });

                $this.loading = true

                promise.then(function(response){
                    eventHub.$emit('url-added');
                    $this.url = '';

                    $this.loading = false;
                }).catch(function (error, code) {
                    if (error.response.status == 422) {
                        eventHub.$emit('flashMessage-set', 'error-add-invalid-url-to-session');
                    } else {
                        eventHub.$emit('flashMessage-set', 'error-add-url-to-session');
                    }

                    $this.url = '';
                    $this.loading = false;
                });
            },
        }
    }
</script>