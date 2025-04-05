export interface Province {
    id: string;
    name: string;
    type: number;
    typeText: string;
    slug: string;
}

export interface District {
    id: string;
    name: string;
    provinceId: string;
    type: number;
    typeText: string;
}

export interface Ward {
    id: string;
    name: string;
    districtId: string;
    type: number;
    typeText: string;
}

export interface LocationResponse<T> {
    total: number;
    data: T[];
}