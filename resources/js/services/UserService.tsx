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

export const updateUser = (data: FormData): Promise<any> => {
    data.append('_method', 'PUT');
    return customizeAxios.post(`/api/v1/users`, data, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    });
};