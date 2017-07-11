<template>
    <section class="activity-list">
        <h4>Activity log for this session</h4>
        <notification v-for="item in items" :data="item"></notification>
    </section>
</template>

<script type="text/javascript">
    export default {
        props: ['sessionkey'],

        data: function() {
            return {
                items: []
            }
        },

        created: function() {
            eventHub.$on('activity-load-content',  this.fetchData);
        },

        methods: {
            fetchData: function() {
                eventHub.$emit('start-loading-links');

                var $this = this;
                var promise = axios({
                    method: 'get',
                    url: '/sessions/' + this.sessionkey + '/notifications'
                });

                promise.then(function(response){
                    $this.items = response.data.data;

                    eventHub.$emit('finished-loading-links');
                });
            }
        }
    }
</script>