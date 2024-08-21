export function useFormatBoolean() {
    const formatBoolean = (value) => {
        return value === 1 ? 'Ναι' : 'Όχι';
    };

    return {
        formatBoolean,
    };
}