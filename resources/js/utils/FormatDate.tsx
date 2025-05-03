
export const FormatDate = (date: any) => {
    try {
        const [datePart, timePart] = date.split('T');
        const [year, month, day] = datePart.split('-');
        const [hour, minute] = timePart.split(':');

        return `${hour}:${minute} ${day}-${month}-${year}`;
    } catch (error) {
        console.error("Error formatting date:", error);
        return date;
    }
};
