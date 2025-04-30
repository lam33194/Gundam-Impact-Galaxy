import customizeAxios from './customize-axios';
export const addOrder = (data: any) => {
    return customizeAxios.post('/api/v1/orders', data, {
        headers: {
            'Content-Type': 'application/json',
        },
    });
}

export const getOrders = () => {
    return customizeAxios.get('api/v1/orders?include=orderItems.variant.product');
}

export const getOrderPayment = (orderId: number) => {
    return customizeAxios.get(`api/v1/orders/${orderId}/payment`);
}