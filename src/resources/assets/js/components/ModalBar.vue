<template>
    <section class="modal-bar">
        <ui-modal ref="subscriber-modal" title="Manage follower">
            <subscriberbox></subscriberbox>

            <div slot="footer">
                <ui-button v-on:click="closeModal('subscriber-modal')">Close</ui-button>
            </div>
        </ui-modal>

        <ui-modal ref="share-modal" title="Manage follower">
            <p>Just copy the session link and send to a friend. No account is required.</p>

            <div v-if="showShareLink" style="border: 2px solid #333;padding:5px 0px; text-align:center; font-size: 10px;">
                http://shrizzer.com/session/{{this.sharedState.state.session.key}}
            </div>

            <div slot="footer">
                <ui-button v-on:click="closeModal('share-modal')">Close</ui-button>
            </div>
        </ui-modal>

        <ui-modal ref="setting-modal" title="Manage settings">
            <sh-settings context="modal" modalref="setting-modal"></sh-settings>

            <div slot="footer">
                <ui-button v-on:click="closeModal('setting-modal')">Close</ui-button>
            </div>
        </ui-modal>
    </section>
</template>

<script type="application/javascript">
    export default {

        data: function () {
            return {
                sharedState: vueSharedState,
                showFlasher: false,
                showShareLink: false
            }
        },

        created: function() {
            eventHub.$on('sharedData-set',  this.sharedDataSet);
            eventHub.$on('close-modal',  this.closeModalEvent);
            eventHub.$on('open-modal',  this.openModal);
        },

        methods: {
            sharedDataSet: function () {
                this.showShareLink = true;
            },
            closeModalEvent: function (ref) {
                this.$refs[ref].close();
            },
            openModal: function (ref) {
                this.$refs[ref].open();
            },
            closeModal: function (ref) {
                this.$refs[ref].close();
            }
        }
    }
</script>