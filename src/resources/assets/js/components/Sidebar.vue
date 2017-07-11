<template>
    <div>
        <div class="box img-rounded">
            <subscriberbox></subscriberbox>
        </div>

        <div class="box img-rounded">
            <h4>Share this session</h4>
            <p>No account is required.</p>

            <div v-if="show" style="border: 2px solid #333;padding:5px 0px; text-align:center">
                http://shrizzer.com/session/{{this.sharedState.state.session.key}}
            </div>
        </div>

        <div class="box img-rounded">
            <sh-settings></sh-settings>
        </div>
    </div>
</template>

<script type="application/javascript">
    export default {

        data: function () {
            return {
                name: '',
                show: false,
                sharedState: vueSharedState
            }
        },

        created: function() {
            eventHub.$on('sharedData-set',  this.sharedDataSet);
        },

        methods: {
            /**
             *
             */
            sharedDataSet: function() {
                this.name = this.sharedState.state.session.name;
                this.nameOld = this.sharedState.state.session.name;
                this.show = true;
            },

        }
    }
</script>