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

export interface UserData {
    id: number;
    name: string;
    email: string;
    email_verified_at: string | null;
    avatar: string | null;
    phone: string | null;
    is_active: number;
    role: string;
    created_at: string;
    updated_at: string;
    addresses?: Address[];
}