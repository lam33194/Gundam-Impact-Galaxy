import customizeAxios from './customize-axios';

export const addToCart = (data: any): Promise<any> => {
    const formData = new FormData();
    formData.append('product_variant_id', data.product_variant_id);
    formData.append('quantity', data.quantity);
    return customizeAxios.post('/api/v1/carts', formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    });
}

export const updateCart = (quantity: any, cartItemId: any): Promise<any> => {
    const formData = new FormData();
    formData.append('quantity', quantity);
    formData.append('_method', 'PUT');
    return customizeAxios.post(`/api/v1/carts/${cartItemId}`, formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    });
}

export const getCart = (): Promise<any> => {
    return customizeAxios.get('api/v1/carts?include=variant.product,variant.size,variant.color');
}