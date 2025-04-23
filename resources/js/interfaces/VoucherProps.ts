export interface VoucherProps {
    voucher: {
        id: number;
        code: string;
        title: string;
        description: string | null;
        start_date_time: string;
        end_date_time: string;
        discount: string;
        is_active: number;
        min_order_amount: string | null;
        used_count: number;
        max_usage: number;
        created_at: string;
        updated_at: string;
    }
}