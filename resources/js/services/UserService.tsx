import customizeAxios from './customize-axios';

export const getUserById = (id: number, params?: Record<string, any>): Promise<any> => {
    let url = `/api/v1/users/${id}`;
    if (params && Object.keys(params).length > 0) {
        const queryString = Object.entries(params)
            .map(([key, value]) => `${key}=${value}`)
            .join('&');
        url += `?${queryString}`;
    }
    return customizeAxios.get(url);
};

export const updateUser = (data: any): Promise<any> => {
    const formData = new FormData();
    formData.append('name', data.name);
    formData.append('phone', data.phone);
    formData.append('address', data.address);
    formData.append('ward', data.ward);
    formData.append('district', data.district);
    formData.append('city', data.city);
    formData.append('_method', "PUT");

    return customizeAxios.post(`/api/v1/users`, formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    });
};