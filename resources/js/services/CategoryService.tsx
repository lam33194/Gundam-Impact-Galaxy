import customizeAxios from './customize-axios';
export const fetchAll = () =>{
    return customizeAxios.get('/api/v1/categories');
}