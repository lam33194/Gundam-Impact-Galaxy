import customizeAxios from './customize-axios';
export const getAllBlogs = (): Promise<any>=>{
    return customizeAxios.get('/api/v1/posts')
}

export const getBlogById = (id: any): Promise<any>=>{
    return customizeAxios.get(`/api/v1/posts/${id}`)
}

