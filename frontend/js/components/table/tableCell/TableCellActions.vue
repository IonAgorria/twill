<template>
  <a17-dropdown
    ref="rowSetupDropdown"
    position="bottom-right"
    :fixed="true">
    <a17-button
      variant="icon"
      @click="$refs.rowSetupDropdown.toggle()">
      <span v-svg symbol="more-dots"></span>
    </a17-button>
    <div slot="dropdown__content">
      <a v-if="row.hasOwnProperty('permalink')"
         :href="row['permalink']"
         target="_blank">{{permalinkLabel}}</a>
      <a v-if="row.hasOwnProperty('edit') && !row.hasOwnProperty('deleted') && row['edit']"
         :href="editUrl"
         @click="preventEditInPlace($event)">{{editLabel}}</a>
      <a v-if="row.hasOwnProperty('published') && !row.hasOwnProperty('deleted')"
         href="#"
         @click.prevent="update('published')"
      >{{ row['published'] ? unpublishLabel : publishLabel }}</a>
      <a v-if="row.hasOwnProperty('featured') && !row.hasOwnProperty('deleted')"
         href="#"
         @click.prevent="update('featured')">{{ row['featured'] ? unfeatureLabel : featureLabel }}</a>
      <a v-if="row.hasOwnProperty('deleted')"
         href="#"
         @click.prevent="restoreRow">{{restoreLabel}}</a>
      <a v-else-if="row.delete"
         href="#"
         @click.prevent="deleteRow">{{deleteLabel}}</a>
    </div>
  </a17-dropdown>
</template>

<script>
  import { TableCellMixin } from '@/mixins'

  export default {
    name: 'TableCellActions',
    mixins: [TableCellMixin],
    props: {
      permalinkLabel: {
        type: String,
        default: 'View permalink'
      },
      editLabel: {
        type: String,
        default: 'Edit'
      },
      publishLabel: {
        type: String,
        default: 'Publish'
      },
      unpublishLabel: {
        type: String,
        default: 'Unpublish'
      },
      featureLabel: {
        type: String,
        default: 'Feature'
      },
      unfeatureLabel: {
        type: String,
        default: 'Unfeature'
      },
      restoreLabel: {
        type: String,
        default: 'Restore'
      },
      deleteLabel: {
        type: String,
        default: 'Delete'
      }
    },
    methods: {
      update: function (colName) {
        this.$emit('update', {row: this.row, col: colName})
      }
    }
  }
</script>

<style scoped>

</style>
