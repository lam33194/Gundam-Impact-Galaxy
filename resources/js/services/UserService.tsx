import customizeAxios from './customize-axios';

export const getUserById = (id: number): Promise<any> => {
    return customizeAxios.get(`/api/v1/users/${id}`);
};