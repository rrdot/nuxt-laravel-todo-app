
<template>
    <div>
        <button
            class="btn-modal"
            @click.prevent="showModal"
            >
                {{ value }}
        </button>
        <a-modal
            v-model:visible="visible"
            title="タスクの追加"
            cancelText="キャンセル"
            okText="追加"
            centered
            v-if="visible"
        >
            <form class="form" ref="form">
                <input type="text" placeholder="タスクを入力..." class="task-title" v-model="titleName" autocomplete="off"><br />
                <div class="display-tag-btn">
                    <span>タグ</span>
                    <input type="checkbox" id="tagShow" :checked="tagListShow">
                    <label class="add" @click="toggleTagListShow"></label>
                </div>

                <div class="selectedTagList">
                    <AppTag v-for="tag in selectedTags" :tag="tag" :key="`selectedTags-${tag.title}`"/>
                </div>

                <div class="container" v-show="tagListShow">
                    <div class="tags-container">
                        <h3 class="label">タグ一覧</h3>
                        <div v-for="tag in tags" class="tag-item" :key="`tags-${tag.title}`">
                            <input type="checkbox" :id="`tag-${tag.title}`" class="tag-choices" :value="tag.title"><label :for="`tag-${tag.title}`" @click="selected">{{ tag.title }}</label>
                        </div>
                    </div>
                    <div class="tag-create-container">
                        <form @submit.prevent="createTag">
                            <input type="text" placeholder="新しいタグを入力" v-model="tagName" name="tagTitle" autocomplete="off">
                            <input type="submit" value="作成" :disabled="isDisabledTag">
                        </form>
                    </div>
                </div>

                <div class="footer">
                    <button class="ant-btn" type="button" ant-click-animating-without-extra-node="false" @click="closeModal"><!----><span>キャンセル</span></button>
                    <button class="ant-btn ant-btn-primary" type="button" @click="createTask" :disabled="isDisabledTask"><!----><span>追 加</span></button>
                </div>

            </form>
        </a-modal>
    </div>
</template>

<script lang="ts">
    import { Component, Vue, Prop, Watch } from 'nuxt-property-decorator';
    import { Tag, Task } from '../interfaces/app'
    import { TaskDefault, TagDefault } from '../defaults/object'

    @Component
    export default class AppModal extends Vue {
        @Prop({default:'Button Value'}) value!: string
        @Prop({default: [TagDefault]}) tags!: Tag[]
        @Prop({default: 1}) position!: number
        @Prop({default: [TaskDefault]}) tasks!: Task[]

        visible:boolean = false
        selectedTags:Tag[] = []
        tagListShow:boolean = false
        tagName:string = ""
        titleName:string = ""

        showModal():void {
            this.visible = true
        }

        closeModal():void {
            this.visible = false
        }

        toggleTagListShow():void {
            this.tagListShow = !(this.tagListShow)
        }

        createTag():void {
            this.$emit('create-tag', this.tagName)
            this.tagName = '';
        }

        selected(event:any):void {
            const checkbox = event.target.previousElementSibling
            if(checkbox.checked) {
                this.selectedTags = this.selectedTags.filter(tag => {
                    return tag.title != event.target.textContent
                })
                return
            }
            checkbox.checked
            const selectedTag:Tag = {
              title: event.target.textContent
            }
            this.selectedTags.push(selectedTag)
        }

        createTask():void {
            this.$emit('create-task', {
                title: this.titleName,
                tags: this.selectedTags,
                position: this.position
            })
            this.visible = false
        }

        @Watch('selectedTags')
        onSelectedTagsChanged(newVal:any):void {
            localStorage.setItem('selectedTags', JSON.stringify(newVal))
        }

        @Watch('visible')
        onVisibleChanged(newVal:boolean):void {
            this.tagName = '';
            this.selectedTags = []
            this.tagListShow = false
            this.titleName = ''
            if(newVal == false) {
                document.querySelectorAll<HTMLInputElement>('.tag-choices').forEach(item => {item.checked = false;})
            }
        }

        @Watch('tagListShow')
        onTagListShowChanged(newVal:boolean):void {
            if(newVal == true) {
                this.selectedTags.forEach(item => {
                    document.querySelectorAll<HTMLInputElement>('.tag-choices').forEach(element => {
                        if(element.value == item.title) {
                            element.checked = true
                        }
                    })
                })
            }
        }

        mounted():void {
            this.selectedTags = JSON.parse(localStorage.getItem('selectedTags') || '[]');
            // this.selectedTags
        }

        get isDisabledTag():boolean {
            if(this.tagName.trim() == "" || this.tagName == null) {
                return true
            } else {
                let trueCount = 0
                this.tags.forEach(tag => {
                    if(tag.title == this.tagName) {
                        trueCount++
                    }
                })
                if(trueCount > 0) {
                    return true
                }
                return false
            }
        }

        get isDisabledTask():boolean {
            if(this.titleName.trim() == "" || this.titleName == null || this.selectedTags.length == 0) {
                return true
            } else {
                let trueCount = 0
                this.tasks.forEach(task => {
                    if(task.title == this.titleName) {
                        trueCount++
                    }
                })
                if(trueCount > 0) {
                    return true
                } else {
                    return false
                }
            }
        }
    }
</script>

<style>
    .btn-modal {
        border: none;
        color: #02836B;
        font-size: 12px;
        background: none;
        cursor: pointer;
        display: flex;
        align-items: center;
    }

    .btn-modal::before {
        content: url(../assets/images/add-sign.png);
        margin-right: 4px;

    }

    .ant-modal-content {
        width: 450px;
        padding: 30px 0;
    }

    .ant-modal-content > * {
        margin: 0 70px;
    }
    .ant-modal-header, .ant-modal-footer {
        border: none;
    }

    .ant-modal-title {
        text-align: center;
        font-size: 20px;
    }

    .ant-modal-close {
        display: none;
    }

    input{
        border: 1px solid #A5A4A4;
        outline: none;
        padding: 6px;
        font-size: 20px;
    }

    input[type="text"]::placeholder {
        color: #A5A4A4;
    }

    .display-tag-btn {
        font-size: 14px;
        font-weight: bold;
        display: flex;
        align-items: center;
        gap: 6px;
        margin: 10px 0;
    }

    #tagShow {
        display: none;
    }

    .display-tag-btn .add {
        background: url(../assets/images/add-sign.png) center no-repeat,#D9D9D9;
        border-radius: 10px;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        width: 29px;
        height: 18px;
        cursor: pointer;
    }

    .display-tag-btn #tagShow:checked + .add {
        background: url(../assets/images/dash.png) center no-repeat,#D9D9D9;
    }

    .selectedTagList {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        margin-bottom: 5px;
    }

    .container {
        width: 170px;
    }

    .tags-container {

        height: 220px;
        border: 1px solid #C1C1C1;
        overflow-y: auto;
        margin-bottom: 6px;
    }

    .tags-container .label {
        background: #393939;
        text-align: center;
        color: #FFFFFF;
        height: 26px;
        line-height: 26px;
        font-size: 14px;
        overflow-x: auto;
    }

    .tag-item {
        display: flex;
        align-items: center;
    }

    .tag-item label {
        padding: 6px 10px;
        cursor: pointer;
        position: relative;
    }

    .tag-item input[type="checkbox"],
    .tag-item input[type="checkbox"]:checked {
        display: none;
    }

    .tag-item input[type="checkbox"]:checked + label::after {
        content: "";
        display: inline-block;
        background: url(../assets/images/check.png);
        width: 14px;
        height: 15px;
        position: absolute;
        top: 0;
        bottom: 0;
        margin: auto;
        margin-left: 6px;
    }

    .tag-create-container form{
        display: flex;
        justify-content: space-between;
    }

    .tag-create-container input[type="text"] {
        height: 25px;
        width: 115px;
        display: flex;
        align-items: center;
        font-size: 11px;
        color: #000000;
    }

    .tag-create-container input[type="submit"] {
        width: 45px;
        height: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #393939;
        color: #FFFFFF;
        line-height: 14px;
        font-size: 14px;
        cursor: pointer;
    }

    .tag-create-container input[type="submit"]:disabled {
        cursor: no-drop;
    }

    .ant-modal-footer {
        text-align: left;
        padding: 0;
        margin: 0 92px;
        display: flex;
    }

    .ant-btn {
        color: #000000;
        font-size: 14px;
        background: #EAEAEA;
        border: 1px solid #C1C1C1;
        height: 25px;
        display: flex;
        align-items: center;
    }

    .ant-btn-primary {
        background: #393939!important;
        color: #FFFFFF!important;
    }

    .ant-modal-footer {
        display: none;
    }

    .footer {
        text-align: left;
        padding: 0;
        /* margin: 0 92px; */
        margin-top: 20px;
        display: flex;
    }

    .footer button:first-of-type {
        margin-right: 10px;
    }


</style>
