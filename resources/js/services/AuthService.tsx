import customizeAxios from './customize-axios';

export const signup = (data: any): Promise<any> => {
    const formData = new FormData();
    formData.append('name', data.name);
    formData.append('phone', data.phone);
    formData.append('email', data.email);
    formData.append('password', data.password);
    formData.append('password_confirmation', data.password_confirmation);
    return customizeAxios.post('/api/v1/auth/register', formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    });
}

export const authenticate = (data: any): Promise<any> => {
    const formData = new FormData();
    formData.append('email', data.email);
    formData.append('password', data.password);
    return customizeAxios.post('/api/v1/auth/login', formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    });
}

export const changePassword = (data: any): Promise<any> => {
    const formData = new FormData();
    formData.append('current_password', data.current_password);
    formData.append('password', data.password);
    formData.append('password_confirmation', data.password_confirmation);

    return customizeAxios.post('/api/v1/auth/change-password', formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    });
};

export const forgotPassword = (email: string): Promise<any> => {
    const formData = new FormData();
    formData.append('email', email);
    return customizeAxios.post('/api/v1/auth/forgot-password', formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    });
};

