import axios from 'axios';
import { LocationResponse, Province, District, Ward } from '../interfaces/Location';

const BASE_URL = 'https://open.oapi.vn/location';

export const getProvinces = async (): Promise<LocationResponse<Province>> => {
    const response = await axios.get(`${BASE_URL}/provinces?size=63`);
    return response.data;
};

export const getDistricts = async (provinceId: string): Promise<LocationResponse<District>> => {
    const response = await axios.get(`${BASE_URL}/districts/${provinceId}?page=0&size=99`);
    return response.data;
};

export const getWards = async (districtId: string): Promise<LocationResponse<Ward>> => {
    const response = await axios.get(`${BASE_URL}/wards/${districtId}?page=0&size=99`);
    return response.data;
};