import customizeAxios from './customize-axios';
export const getAll = (params?: any): Promise<any> => {
    return customizeAxios.get('/api/v1/products', { params });
};

export const getAllByCategory = (category_slug: any): Promise<any> => {
    return customizeAxios.get(`/api/v1/categories/${category_slug}/products`);
};


export const getDetail = (slug: any): Promise<any> => {
    return customizeAxios.get(`/api/v1/products/${slug}?include=category,variants.size,variants.color`);
}
