<template>
  <!--Center, below Control - hold the contents -->
  <div id="editor">
    <div id="contentChild">
      <div v-if="status != ''" class="status">{{ status }}</div>
      <table id="editor-table">
        <tr v-for="(bookmark, index) in bookmarks" :key="bookmark.id">
          <td :class="['title', 'level-' + bookmark.level]">
            <input type="text" v-model="bookmark.title" />
          </td>
          <td class="number">
            <input type="text" v-model="bookmark.page_number" />
          </td>
          <td class="actions">
            <button
              @click="bookmark.level--"
              v-if="bookmark.level > 1"
              class="promote"
            ></button>
            <button @click="bookmark.level++" class="demote"></button>
            <button
              @click="swap(index, index - 1)"
              v-if="index > 0"
              class="move-up"
            ></button>
            <button
              @click="swap(index, index + 1)"
              v-if="index < bookmarks.length - 1"
              class="move-down"
            ></button>
            <button @click="bookmarks.splice(index, 1)" class="delete"></button>
          </td>
        </tr>
      </table>

      <button
        @click="
          bookmarks.push({
            level: 1,
            title: '',
            id: null,
            page_number: null,
            parent_id: null,
          })
        "
      >
        Add Row
      </button>
      <button @click="save()">Save</button>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      metadata: null,
      bookmarks: null,
      status: "",
    };
  },

  mounted() {
    // This loads off an object set on the page from Laravel
    this.metadata = metadata;
    this.bookmarks = this.metadata.bookmarks;
  },

  methods: {
    swap: function (x, y) {
      if (x < 0 || y < 0) {
        // Keep us from swapping negative, which would cause index issues
        return;
      }

      if (x > this.bookmarks.length - 1 || y > this.bookmarks.length - 1) {
        // Keep us from swapping outside the array
        return;
      }

      var temp = this.bookmarks[x];
      this.bookmarks[x] = this.bookmarks[y];
      this.bookmarks[y] = temp;
    },

    save: function () {
      axios({
        method: "post",
        url: "#",
        data: {
          bookmarks: this.bookmarks,
        },
      }).then(
        (response) => {
            this.status = "Saved bookmarks!"
          console.log(response);
        },
        (error) => {
            this.status = "Save error!"
          console.log(error);
        }
      );
    },
  },
};
</script>