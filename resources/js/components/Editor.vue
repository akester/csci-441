<template>
    <!--Center, below Control - hold the contents -->
    <div id="editor">
        <div id="contentChild">
            <table id="editor-table">
                <tr v-for="(bookmark,index) in bookmarks" :key="bookmark.id">
                    <td :class="['title', 'level-' + bookmark.level]"><input type="text" v-model="bookmark.title" /></td>
                    <td class="actions">
                        <button @click="bookmark.level--" v-if="bookmark.level > 1" class="promote"></button>
                        <button @click="bookmark.level++" class="demote"></button>
                        <button @click="swap(index, index-1)" v-if="index > 0" class="move-up"></button>
                        <button @click="swap(index, index+1)" v-if="index < bookmarks.length - 1" class="move-down"></button>
                        <button @click="bookmarks.splice(index, 1)" class="delete"></button>
                    </td>
                </tr>
            </table>

            <button @click="bookmarks.push({level: 1, title: ''})">Add Row</button>
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
        },

        methods: {
            swap: function(x, y) {
                if (x < 0 || y < 0) {
                    // Keep us from swapping negative, which would cause index issues
                    return;
                }

                if (x > this.bookmarks.length - 1 || y > this.bookmarks.length - 1) {
                    // Keep us from swapping outside the array
                    return;
                }

                var temp = this.bookmarks[x]
                this.bookmarks[x] = this.bookmarks[y]
                this.bookmarks[y] = temp
            }
        },
    }
</script>