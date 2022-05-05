<template>
    <!--Center, below Control - hold the contents -->
    <div id="editor">
        <div id="contentChild">
            <table id="editor-table">
                <tr v-for="bookmark in bookmarks" :key="bookmark.id">
                    <td>{{ bookmark.level }}</td>
                    <td>{{ bookmark.title }}</td>
                    <td>Actions</td>
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