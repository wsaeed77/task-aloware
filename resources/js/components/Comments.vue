<template>

    <div class="comments-app container">

        <h1>Comments</h1>
        <!-- From -->
        <div class="comment-form">
            <div class="row">
                <div class="col-8">
                    <form class="form" name="form">
                        <div class="form-group">
                            <textarea class="form-control" placeholder="Add comment..." required
                                      v-model="message"></textarea>
                            <span class="input" v-if="errorComment" style="color:red">{{errorComment}}</span>
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="Name" type="text" v-model="name">
                        </div>
                        <div class="form-group">
                            <input type="button" class="btn btn-success" @click="saveComment" value="Add Comment">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <replies :data="0" ref="foo"></replies>

    </div>
</template>

<script>

    var _ = require('lodash');
    import  Replies from './Replies';
    export default {

        props: ['commentUrl'],

        data() {

            return {

                comments: [],
                commentreplies: [],
                comments: 0,
                commentBoxs: [],
                message: null,
                message_reply:null,
                reply_name:null,
                name: null,
                parrentID: null,
                replyCommentBoxs: [],
                commentsData: [],
                viewcomment: [],
                show: [],
                errorComment: null,
                errorReply: null,
            }

        },

        http: {

            headers: {

                'X-CSRF-TOKEN': window.csrf

            }

        },

        methods: {

            fetchComments() {

                axios.get('comments').then(res => {


                    this.commentData = res.data;

                    this.commentsData = _.orderBy(res.data, ['votes'], ['desc']);

                    this.comments = 1;

                });


            },

            showComments(index) {

                if (!this.viewcomment[index]) {

                    Vue.set(this.show, index, "hide");

                    Vue.set(this.viewcomment, index, 1);

                } else {

                    Vue.set(this.show, index, "view");

                    Vue.set(this.viewcomment, index, 0);

                }

            },

            saveComment() {

                if (this.message != null && this.message != ' ') {
                    this.errorComment = null;

                    axios.post('save-comment', {
                        comment: this.message,
                        name: this.name,
                        parent_id:0,

                    }).then(res => {
                        if (res.data.status) {
                            this.commentsData.push({
                                "commentid": res.data.commentId,
                                "name": this.name,
                                "comment_text": this.message,
                                "replies": []
                            });
                            this.message = null;
                            this.name = null;
                        }

                        this.$refs.foo.fetchComments();
                    });

                } else {

                    this.errorComment = "Please enter a comment to save";

                }

            },


        },

        mounted() {

        }


    }

</script>
