<template>
  <span class="tagTitle">{{ tag.title }}</span>
</template>

<script lang="ts">
    import { Component, Vue, Prop } from 'nuxt-property-decorator';
    import { Tag } from '../interfaces/app'
    import { TagDefault } from '../defaults/object'

    @Component
    export default class AppTag extends Vue {
        @Prop({ default: TagDefault }) tag!: Tag
    }
</script>

<style>
  .tagTitle {
      color: #FFFFFF;
      background: #A1AF2F;
      border-radius: 10px;
      padding: 0 6px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 10px;
  }
</style>
