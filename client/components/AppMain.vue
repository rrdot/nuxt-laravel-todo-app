<template>
    <div class="main">
        <AppFilter :tags="tags" @filter-tasks="filterTasks"/>
        <div class="board-container">
            <AppBoard title="未対応" :tasks="taskProp" :position="first_pos" :filterLength="filterLength" @reorder-tasks="reOrderTask">
                <template v-slot:modal>
                    <AppModal value="課題の追加..." :tags="tags" @create-tag="createTag" @create-task="createTask" :position="first_pos" :tasks="tasks"/>
                </template>
            </AppBoard>
            <AppBoard title="処理中" :tasks="taskProp" :position="second_pos" :filterLength="filterLength" @reorder-tasks="reOrderTask">
                <template v-slot:modal>
                    <AppModal value="課題の追加..." :tags="tags" @create-tag="createTag" @create-task="createTask" :position="second_pos" :tasks="tasks"/>
                </template>
            </AppBoard>
            <AppBoard title="レビュー中" :tasks="taskProp"  :position="third_pos" :filterLength="filterLength" @reorder-tasks="reOrderTask">
                <template v-slot:modal>
                    <AppModal value="課題の追加..." :tags="tags" @create-tag="createTag" @create-task="createTask" :position="third_pos" :tasks="tasks"/>
                </template>
            </AppBoard>
            <AppBoard title="完了" :tasks="taskProp" :position="fourth_pos" :filterLength="filterLength" @reorder-tasks="reOrderTask">
                <template v-slot:modal>
                    <AppModal value="課題の追加..." :tags="tags" @create-tag="createTag" @create-task="createTask" :position="fourth_pos" :tasks="tasks"/>
                </template>
            </AppBoard>
        </div>
    </div>
</template>

<script lang="ts">
    import { Component, Vue, Watch } from 'nuxt-property-decorator'
    import { Tag, Task } from '../interfaces/app'

    @Component
    export default class AppMain extends Vue {
        tags:Tag[] = []
        tasks:any = []
        filteredTask:Task[] = []
        filterLength:number = 0
        positions:number[] = [1, 2, 3, 4]
        first_pos = this.positions[0]
        second_pos = this.positions[1]
        third_pos = this.positions[2]
        fourth_pos = this.positions[3]

        @Watch('tags')
        onTagsChanged(newVal:Tag[]):void {
            localStorage.setItem('tags', JSON.stringify(newVal))
        }

        @Watch('tasks')
        onTasksChanged(newVal:Task[]):void {
            localStorage.setItem('tasks', JSON.stringify(newVal))
        }

        @Watch('filteredTask')
        onFilteredTaskChanged(newVal:Task[]):void {
            localStorage.setItem('filteredTask', JSON.stringify(newVal))
        }

        filterTasks(filters:Tag[]):void {
            this.filterLength = filters.length
            this.filteredTask = []
            let trueCount:number[] = []
            for (let i = 0; i < this.tasks.length; i++) {
                trueCount[i] = 0
            }
            filters.forEach((filter) => {
                for(let i = 0; i < this.tasks.length; i++) {
                    for(let j = 0; j < this.tasks[i].tags.length; j++) {
                        if(filter.title == this.tasks[i].tags[j].title) {
                            trueCount[i]++
                        }
                    }
                }
            })

            trueCount.forEach((value, index) => {
                if(value == filters.length) {
                    this.filteredTask.push(this.tasks[index])
                }
            })
        }

        async createTag(params:string):Promise<void> {
            // this.tags.push({
            //     title : params
            // })
            await this.$axios.$post('http://127.0.0.1:8000/api/tag', {
              title: params
            });
            this.fetchTags();
        }

        async createTask(params:Task):Promise<void> {
            // this.tasks.push({
            //     title: params.title,
            //     tags: params.tags,
            //     position: params.position
            // })
            await this.$axios.$post('http://127.0.0.1:8000/api/task', {
              title: params.title,
              position: params.position,
              tags: params.tags
            });
            this.fetchTasks();
        }

        // reOrderTask(params:Task[]):void {
        //     // this.tasks = [];
        //     // params.forEach(task => {
        //     //     this.tasks.push(task)
        //     // })
        //     console.log(params)
        // }

        reOrderTask():void {
            // this.tasks = [];
            // params.forEach(task => {
            //     this.tasks.push(task)
            // })
            this.fetchTasks()
        }

        get taskProp():Task[] {
            if(this.filterLength > 0) {
                return this.filteredTask
            }
            return this.tasks
        }

        async fetchTags():Promise<void> {
          try {
            this.tags = await this.$axios.$get('http://127.0.0.1:8000/api/tags');
          } catch (error) {
            console.log(error)
          }
        }

        async fetchTasks():Promise<void> {
          try {
            const data = await this.$axios.$get('http://127.0.0.1:8000/api/tasks');
            data.map((item:any) => item.tags = JSON.parse(item.tags))
            this.tasks = data
          } catch (error) {
            console.log(error)
          }
        }

        async mounted() {
            this.fetchTasks();
            this.fetchTags();
            this.tasks = JSON.parse(localStorage.getItem('tasks') || '[]')
            this.tags = JSON.parse(localStorage.getItem('tags') || '[]')
            this.filteredTask = JSON.parse(localStorage.getItem('filteredTask') || '[]')
        }
    }
</script>

<style>
    .main > * {
        margin: 0 58px;
    }

    .board-container {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 40px;
        margin-top: 40px;
    }

    .board-container > .board {
        min-width: 250px;
        width: 100%;
        margin-bottom: 10px;
    }
</style>
