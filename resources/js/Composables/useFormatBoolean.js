export function useFormatBoolean() {
    const formatBoolean = (value) => {
        console.log( value )
        return value === 1 ? 'Ναι' : 'Όχι';
    };

    return {
        formatBoolean,
    };
}