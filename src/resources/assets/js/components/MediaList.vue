<template>
    <div>
        <div v-for="item in items">
            <mediaitem :item="item"></mediaitem>
        </div>
    </div>
</template>

<script type="text/javascript">
    export default {
        props: ['sessionkey'],
        data() {
            return {
                items: []
            }
        },
        created: function() {
            this.fetchData();

            eventHub.$on('url-added',  this.fetchData);
        },
        methods: {
            fetchData: function() {
                eventHub.$emit('start-loading-links');

                var $this = this;
                var promise = axios({
                    method: 'get',
                    url: '/sessions/' + this.sessionkey + '/urls'
                });

                promise.then(function(response){
                    $this.items = response.data;

                    eventHub.$emit('finished-loading-links');
                });
            }
        }
    }
</script>
