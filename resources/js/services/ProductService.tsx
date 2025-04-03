import customizeAxios from './customize-axios';
export const getAll = (page?: any, size?: any): Promise<any> =>{
    return customizeAxios.get('/api/v1/products');
}

export const getDetail = (slug: any): Promise<any> =>{
    return customizeAxios.get(`/api/v1/products/${slug}?include=category`);
}
