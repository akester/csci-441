<template>
    <!--Center, below Control - hold the contents -->
    <div id="editor">
        <div id="contentChild">
            <table id="editor-table">
                <tr v-for="(bookmark,index) in bookmarks" :key="bookmark.id">
                    <td :class="['title', 'level-' + bookmark.level]"><input type="text" v-model="bookmark.title" /></td>
                    <td>
                        <button @click="bookmark.level--" class="promote"></button>
                        <button @click="bookmark.level++" class="demote"></button>
                        <button @click="bookmarks.splice(index, 1)" class="delete"></button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                metadata: null,
                bookmarks: null,
            }
        },

        mounted() {
            // This loads off an object set on the page from Laravel
            this.metadata = metadata;
            this.bookmarks = this.metadata.bookmarks;
        }
    }
</script>