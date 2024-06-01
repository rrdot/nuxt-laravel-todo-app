<template>
  <div class="task-item" @dragstart="dragStart($event, task.id)" @drop="taskDrop($event, `${task.id}-${task.position}`)">
      <h3>{{ task.title }}</h3>
      <div class="selected-tag-list">
          <AppTag v-for="tag in task.tags" :tag="tag" :key="`tags-${tag.title}`"/>
      </div>
  </div>
</template>

<script lang="ts">
    import { Component, Vue, Prop } from 'nuxt-property-decorator';
    import { Task } from '../interfaces/app'
    import { TaskDefault } from '../defaults/object'

    @Component
    export default class AppTask extends Vue {
        @Prop({ default: TaskDefault }) task!: any

        dragStart(event:any, taskTitle:number):void {
          this.$emit('drag-start', {
            event,
            taskTitle
          })
        }

        taskDrop(event:any, pos_title:string):void {
          this.$emit('task-drop', {
            event,
            pos_title
          })
        }
    }
</script>
