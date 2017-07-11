<template>
    <article class="notification">
        <p v-if="data.type == 'invitation_accepted'">
            <i class="glyphicon glyphicon-user"></i> Someone accepted the request to this session.
        </p>
        <p v-if="data.type == 'session_invited'">
            <i class="glyphicon glyphicon-envelope"></i> A new user was added to the session
        </p>
        <p v-if="data.type == 'post_liked'">
            <i class="glyphicon glyphicon-thumbs-up"></i> The Post "<a href="">{{data.sessionUrl.data.url.data.title}}</a>" was upvoted
        </p>
        <p v-if="data.type == 'post_disliked'">
            <i class="glyphicon glyphicon-thumbs-down"></i> The Post "<a href="">{{data.sessionUrl.data.url.data.title}}</a>" was downvoted
        </p>
        <p v-if="data.type == 'post_commented'">
            <i class="glyphicon glyphicon-comment"></i> The Post "<a href="">{{data.sessionUrl.data.url.data.title}}</a>" was commented
        </p>
        <p v-if="data.type == 'post_created'">
            <i class="glyphicon glyphicon-bullhorn"></i> A new post was created "<a href="">{{data.sessionUrl.data.url.data.title}}</a>"
        </p>
        <div>{{data.createdAt}}</div>
    </article>
</template>

<script type="text/javascript">
    export default {
        props: ['data']
    }
</script>