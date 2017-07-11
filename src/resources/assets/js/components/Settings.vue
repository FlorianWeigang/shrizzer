<template>
    <section>
        <h4>Settings</h4>
        <div class="basic-settings">
            <h5>Name</h5>
            <ui-textbox v-model="name" @blur="saveChanges"></ui-textbox>
        </div>
        <div>
            <h5>Description</h5>
            <ui-textbox placeholder="Introduce the session in a few words" :multi-line="true" v-model="description" @blur="saveChanges"></ui-textbox>
        </div>
    </section>
</template>

<script type="application/javascript">
    export default {
        props: ['context', 'modalref'],
        data: function () {
            return {
                name: '',
                nameOld: '',
                description: '',
                descriptionOld: '',
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
                this.description = this.sharedState.state.session.description;
                this.descriptionOld = this.sharedState.state.session.description;
            },

            /**
             *
             */
            saveChanges: function() {
                if (this.name != this.nameOld || this.description != this.descriptionOld) {
                    var $this = this;

                    var promise = axios({
                        method: 'put',
                        url: '/sessions/' + this.sharedState.state.session.key,
                        data: {
                            name: $this.name,
                            description: $this.description
                        }
                    });

                    promise.then(function(response){
                        eventHub.$emit('flashMessage-set', 'name-changed');

                        $this.nameOld = $this.name;

                        if ($this.context == 'modal') {
                            eventHub.$emit('close-modal', $this.modalref);
                        }
                    });
                }
            }
        }
    }
</script>