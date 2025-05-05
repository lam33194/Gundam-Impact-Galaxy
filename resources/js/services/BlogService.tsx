import customizeAxios from './customize-axios';
export const getAllBlogs = (): Promise<any>=>{
    return customizeAxios.get('/api/v1/posts')
}

export const getThreeBlogs = (): Promise<any>=>{
    return customizeAxios.get('/api/v1/posts?limit=3')
}

export const getBlogById = (id: any): Promise<any>=>{
    return customizeAxios.get(`/api/v1/posts/${id}`)
}

