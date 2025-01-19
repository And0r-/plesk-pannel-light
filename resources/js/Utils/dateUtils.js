export function formatDate(date, locale = 'de-CH') {
    if (!date) return '-';
    try {
        const options = { year: 'numeric', month: '2-digit', day: '2-digit' };
        return new Intl.DateTimeFormat(locale, options).format(new Date(date));
    } catch (error) {
        console.error('Invalid date:', date);
        return '-';
    }
}