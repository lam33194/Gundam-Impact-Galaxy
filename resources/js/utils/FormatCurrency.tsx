
export const FormatCurrency = (amount: any, locale = 'en-US') => {
    return new Intl.NumberFormat(locale, {
        style: 'decimal',
        minimumFractionDigits: 0,
    }).format(amount);
};
