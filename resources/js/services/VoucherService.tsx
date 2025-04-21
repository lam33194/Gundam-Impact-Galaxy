import customizeAxios from './customize-axios';
import { VoucherProps } from '../interfaces/VoucherProps';

type Voucher = VoucherProps['voucher'];

const isVoucherUsable = (voucher: Voucher): boolean => {
    const now = new Date();
    const endDate = new Date(voucher.end_date_time);
    const isExpired = endDate < now;
    const isFullyUsed = voucher.used_count >= voucher.max_usage;
    return !isExpired && !isFullyUsed;
};

const compareVouchers = (a: Voucher, b: Voucher): number => {
    const aUsable = isVoucherUsable(a);
    const bUsable = isVoucherUsable(b);
    if (aUsable !== bUsable) {
        return bUsable ? 1 : -1;
    }

    if (aUsable && bUsable) {
        const aDiscount = parseInt(a.discount);
        const bDiscount = parseInt(b.discount);
        if (aDiscount !== bDiscount) {
            return bDiscount - aDiscount;
        }

        const aMinAmount = parseInt(a.min_order_amount || '0');
        const bMinAmount = parseInt(b.min_order_amount || '0');
        if (aMinAmount !== bMinAmount) {
            return aMinAmount - bMinAmount;
        }

        const aRemaining = a.max_usage - a.used_count;
        const bRemaining = b.max_usage - b.used_count;
        if (aRemaining !== bRemaining) {
            return aRemaining - bRemaining;
        }

        const aEndDate = new Date(a.end_date_time).getTime();
        const bEndDate = new Date(b.end_date_time).getTime();
        return aEndDate - bEndDate;
    }

    return 0;
};

export const getVouchers = async (): Promise<any> => {
    const response = await customizeAxios.get('/api/v1/vouchers');
    if (response.data && response.data.data) {
        response.data.data.sort(compareVouchers);
    }
    return response;
};