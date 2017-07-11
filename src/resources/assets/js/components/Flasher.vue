<template>
    <div class="row flasher" v-if="showFlasher">
        <div class="col-md-12 pre-br">
            <ui-alert @dismiss="showFlasher = false" :type="type" v-show="showFlasher">
                {{text}}
            </ui-alert>
        </div>
    </div>
</template>

<script type="application/javascript">
    export default {

        data: function () {
            return {
                showFlasher: false,
                type: 'success',
                text: 'fsd',
                sharedState: vueSharedState,
                defined: {
                    'name-changed': {
                        type: 'success',
                        text: 'The name / description has been changed.'
                    },
                    'session-create-not-allowed': {
                        type: 'error',
                        text: 'the password you used is not correct'
                    },
                    'error-subscribed-to-session': {
                        type: 'error',
                        text: 'the subscription could not be confirmed'
                    },
                    'subscribed-to-session': {
                        type: 'success',
                        text: 'Your email was confirmed to receive updates for this session.'
                    },
                    'error-add-invalid-url-to-session': {
                        type: 'error',
                        text: 'The url you entered was not valid.'
                    },
                    'error-add-url-to-session': {
                        type: 'error',
                        text: 'The url could not be added to this session.'
                    },
                    'error-add-email-to-session': {
                        type: 'error',
                        text: 'The email could not be added to this session.'
                    },
                    'error-add-invalid-email-to-session': {
                        type: 'error',
                        text: 'The email you entered was not valid.'
                    },
                    'error-double-vote': {
                        type: 'error',
                        text: 'You can not vote twice for the same url'
                    }
                }
            }
        },

        created: function() {
            eventHub.$on('sharedData-set',  this.sharedDataSet);
            eventHub.$on('flashMessage-set',  this.handleFlashMessage);
        },

        methods: {
            /**
             *
             */
            sharedDataSet: function() {

            },

            /**
             *
             */
            handleFlashMessage: function (type) {

                if (type && this.defined[type]) {
                    this.type = this.defined[type].type;
                    this.text = this.defined[type].text;
                    this.showFlasher = true;
                }
            }
        }
    }
</script>