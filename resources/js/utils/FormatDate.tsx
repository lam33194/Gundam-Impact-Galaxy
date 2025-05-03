
export const FormatDate = (date: any) => {
    const [datePart, timePart] = date.split('T');
    const [year, month, day] = datePart.split('-');
    const [hour, minute] = timePart.split(':');
    if (date === null){
        return null;
    }
    return `${hour}:${minute} ${day}-${month}-${year}`;
};
