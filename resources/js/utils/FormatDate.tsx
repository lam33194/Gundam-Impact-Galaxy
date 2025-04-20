
export const FormatDate = (date: any) => {
    const [datePart, timePart] = date.split('T');
    const [year, month, day] = datePart.split('-');
    const [hour, minute] = timePart.split(':');
    
    return `${hour}:${minute} ${day}-${month}-${year}`;
};
