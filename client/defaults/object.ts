import { Task, Tag } from '../interfaces/app'

const TagDefault: Tag = {
  title: 'Tag Title',
}

const TaskDefault: Task = {
  title: 'Task Title',
  position: 1,
  tags: [TagDefault]
}

export { TagDefault, TaskDefault }
