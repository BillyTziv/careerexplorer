// src/composables/useFormatDate.js

export function useFormatDate() {
    const formatDate = (dateString, includeTime = false) => {
        const date = new Date(dateString);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
        const year = date.getFullYear();
        
        let formattedDate = `${day}/${month}/${year}`;

        if (includeTime) {
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            const seconds = String(date.getSeconds()).padStart(2, '0');
            formattedDate += ` ${hours}:${minutes}`;
        }

       return formattedDate;
    };

    return {
        formatDate,
    };
}