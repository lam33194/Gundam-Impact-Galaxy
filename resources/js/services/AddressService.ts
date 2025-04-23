import customizeAxios from './customize-axios';

export interface Address {
    id: number;
    user_id: number;
    address: string;
    ward: string;
    district: string;
    city: string;
    is_primary: number;
    created_at: string;
    updated_at: string;
}

export interface AddressFormData {
    address: string;
    ward: string;
    district: string;
    city: string;
    is_primary: boolean;
}

export const getAddresses = (): Promise<any> => {
    return customizeAxios.get('/api/v1/addresses');
};

export const createAddress = (data: AddressFormData): Promise<any> => {
    return customizeAxios.post('/api/v1/addresses', data);
};

export const updateAddress = (id: number, data: AddressFormData): Promise<any> => {
    return customizeAxios.put(`/api/v1/addresses/${id}`, data);
};

export const deleteAddress = (id: number): Promise<any> => {
    return customizeAxios.delete(`/api/v1/addresses/${id}`);
};

export const setPrimaryAddress = (id: number): Promise<any> => {
    return customizeAxios.put(`/api/v1/addresses/${id}/primary`);
};