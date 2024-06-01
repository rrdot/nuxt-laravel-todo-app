<template>
    <div class="board" @drop="onBoardDrop($event, position)" @dragenter.prevent @dragover.prevent>
        <h3 class="title">{{ title }} <span class="task-count">{{ newTasks.length }}</span></h3>
        <div class="card-group">
            <slot name="modal"></slot>

            <div class="task-list">
                <AppTask v-for="task in newTasks" :task="task" :draggable="isDraggable" :key="task.id" @drag-start="startDrag" @task-drop="onTaskDrop" :class="isDraggable? 'draggable' : ''"/>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
    import { Component, Vue, Prop } from 'nuxt-property-decorator';
    import { Task } from '../interfaces/app'
    import { TaskDefault } from '../defaults/object'

    @Component
    export default class AppBoard extends Vue {
        @Prop({default: 'Board Title'}) title!: string
        @Prop({default: [TaskDefault]}) tasks!: Task[]
        @Prop({default: 1}) position!: number
        @Prop({default: 0}) filterLength!: number

        get newTasks():Task[] {
            const newTasks = this.tasks.filter((task) => {
                return task.position == this.position
            })
            return newTasks
        }

        get isDraggable():Boolean {
            if(this.filterLength > 0) {
                return false
            }
            return true
        }

        startDrag(params:any):void {
            params.event.dataTransfer.dropEffect = 'move'
            params.event.dataTransfer.effectAllowed = 'move'
            params.event.dataTransfer.setData('taskTitle', params.taskTitle)
        }

        async onBoardDrop(event:any, dropPos:number):Promise<void> {
            const taskTitle = event.dataTransfer.getData('taskTitle')
            // this.tasks.forEach((task, index) => {
            //     if(task.title == taskTitle) {
            //         this.tasks[index].position = dropPos
            //         this.tasks.push(this.tasks.splice(index, 1)[0])
            //     }
            // })
            await this.$axios.$patch(`http://127.0.0.1:8000/api/task/${taskTitle}`, {
              position: dropPos
            })
            this.$emit('reorder-tasks')
        }

        async onTaskDrop(params:any):Promise<void> {
            params.event.stopPropagation();
            const taskTitle = params.event.dataTransfer.getData('taskTitle')
            // console.log(taskTitle)
            // console.log(params.pos_title.split("-")[1])
            await this.$axios.$patch(`http://127.0.0.1:8000/api/task/${taskTitle}`, {
              position: Number(params.pos_title.split("-")[1])
            })
            this.$emit('reorder-tasks')
            // this.tasks.forEach((task, index) => {
            //     if(task.title == taskTitle) {
            //         this.tasks[index].position = Number(params.pos_title.split("-")[0])

            //         const dropTask = this.tasks.filter(task => {
            //             return task.title == params.pos_title.split("-")[1]
            //         })
            //         const dropTaskIndex = this.tasks.indexOf(dropTask[0])
            //         const extractedDraggedTask = this.tasks.filter(task => {
            //             return task.title != taskTitle
            //         })
            //         const reOrderTasks = [
            //         ...extractedDraggedTask.slice(0, dropTaskIndex),
            //         this.tasks[index],
            //         ...extractedDraggedTask.slice(dropTaskIndex)
            //         ]
            //         this.$emit('reorder-tasks', reOrderTasks)
            //     }
            // })
        }
    }
</script>

<style>
    .title {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .title::before {
        content: "";
        width: 16px;
        height: 16px;
        border-radius: 50%;
        margin-right: 8px;
    }

    .board:nth-of-type(1) .title::before {
        background: #ED8077;
    }

    .board:nth-of-type(2) .title::before {
        background: #4487C5;
    }

    .board:nth-of-type(3) .title::before {
        background: #5EB5A6;
    }

    .board:nth-of-type(4) .title::before {
        background: #A1AF2F;
    }

    .card-group {
        height: 574px;
        width: 100%;
        background: #FFFFFF;
        border-radius: 3px;
        overflow: auto;
    }

    .card-group > * {
        margin: 6px 10px;
    }

    .task-item {
        border: 1px solid #C1C1C1;
        margin-bottom: 6px;
        min-height: 70px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        border-radius: 2px;
    }

    .task-item > * {
        margin: 7px 11px;
    }

    .task-item h5 {
        font-size: 12px;
    }

    .selected-tag-list {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
    }

    .task-count {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 21px;
        background: #D9D9D9;
        border-radius: 15px;
        margin-left: 4px;
    }

    .draggable {
      cursor: -webkit-grab!important;
    }

</style>
