export interface Tag {
    id?: number,
    title: string
}

export interface Task {
    id?: number,
    title: string,
    position: number,
    tags: Tag[]
}

