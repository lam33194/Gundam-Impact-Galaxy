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

export const getTopRevenue = (): Promise<any> => {
    return customizeAxios.get(`/api/v1/getTopRevenueProducts`)
}

export const getTopSelling = (): Promise<any> => {
    return customizeAxios.get(`/api/v1/getTopSellingProducts`)
}

export const addCommentForProduct = (data: any, productSlug: any): Promise<any> => {
    return customizeAxios.post(`/api/v1/products/${productSlug}/comments`, data, {
        headers: {
            'Content-Type': 'multipart/form-data',
            'Accept': 'application/json'
        },
    });
}

export const getCommentForProduct = (productSlug: any): Promise<any> => {
    return customizeAxios.get(`/api/v1/products/${productSlug}/comments`);
}

export const deleteCommentOfProduct = (commentId: any): Promise<any> => {
    return customizeAxios.delete(`/api/v1/comments/${commentId}`);
}

