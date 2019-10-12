<template>
    <div class="container">
        <!-- Comments List -->

        <div class="col-md-12" v-if="comments" v-for="(comment,index) in commentsData">
            <!-- Comment -->
            <div class="comment">
                <!-- Comment Box -->
                <div class="comment-box ">
                    <div class="comment-text">{{comment.comment }}</div>
                    <div class="comment-footer">
                        <div class="comment-info">
                       <span class="comment-author">
                               <em>{{ comment.name}}</em>
                           </span>
                            <span class="comment-date">{{ comment.date}}</span>

                            <span v-if="comment.level<3">
                                <a v-on:click="openComment(index,comment.commentid)">Reply</a>
                            </span>
                        </div>
                    </div>

                </div>
                <div v-if="comment.reply">
                    <replies :data="comment.commentid"></replies>
                </div>
                <!-- From -->
                <div class="comment-form comment-v" v-if="commentBoxs[index]">

                    <form class="form" name="form">

                        <div class="form-group">

                            <textarea class="input" placeholder="Add reply..." required
                                      v-model="message_reply"></textarea>

                            <span class="input" v-if="errorReply" style="color:red">{{errorReply}}</span>

                        </div>


                        <div class="form-group">

                            <input class="input" placeholder="Name" type="text" v-model="reply_name">

                        </div>


                        <div class="form-group">

                            <input type="button" class="btn btn-success"
                                   v-on:click="replyComment(comment.commentid,index)" value="Add Reply">

                        </div>

                    </form>

                </div>

            </div>

        </div>
    </div>
</template>

<script>
    export default {
        props: {
            data: Number,

        },
        data() {

            return {

                comments: [],
                commentreplies: [],
                comments: 0,
                commentBoxs: [],
                message: null,
                message_reply: null,
                reply_name: null,
                name: null,
                parrentID: null,
                replyCommentBoxs: [],
                commentsData: [],
                viewcomment: [],
                parent_id: 0,
                show: [],
                errorComment: null,

                errorReply: null,

                user: 1

            }

        },
        mounted() {
            console.log(this.data)
            console.log('Component mounted.')
            this.fetchComments();
        },

        methods: {
            replyComment(commentId, index) {

                if (this.message_reply != null && this.message_reply != ' ') {

                    this.errorReply = null;

                    axios.post('save-comment', {

                        comment: this.message_reply,

                        name: this.reply_name,

                        parent_id: this.parent_id

                    }).then(res => {


                        if (res.data.status) {

                            if (!this.commentsData[index].reply) {

                                this.commentsData[index].replies.push({
                                    "commentid": res.data.commentId,
                                    "name": this.user.name,
                                    "comment": this.message,
                                    "votes": 0
                                });

                                this.commentsData[index].reply = 1;

                                Vue.set(this.replyCommentBoxs, index, 0);

                                Vue.set(this.commentBoxs, index, 0);

                            } else {

                                this.commentsData[index].replies.push({
                                    "commentid": res.data.commentId,
                                    "name": this.user.name,
                                    "comment": this.message,
                                    "votes": 0
                                });

                                Vue.set(this.replyCommentBoxs, index, 0);

                                Vue.set(this.commentBoxs, index, 0);

                            }

                            this.message = null;

                        }


                    });

                } else {

                    this.errorReply = "Please enter a comment to save";

                }

            },
            fetchComments() {

                axios.get('comments/' + this.data).then(res => {


                    this.commentData = res.data;

                    this.commentsData = _.orderBy(res.data, ['votes'], ['desc']);

                    this.comments = 1;

                });


            },
            openComment(index, id) {
                //Open Comment box
                if (this.user) {
                    this.parent_id = id;
                    if (this.commentBoxs[index]) {

                        Vue.set(this.commentBoxs, index, 0);

                    } else {

                        Vue.set(this.commentBoxs, index, 1);

                    }

                }

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

        }
    }
</script>
