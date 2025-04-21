import customizeAxios from './customize-axios';

export const getVouchers = (): Promise<any> => {
    return customizeAxios.get('/api/v1/vouchers');
}