import { ref } from 'vue';

export function useVolunteerStatusMapper(initialStatusMap) {
    const statusMap = ref(initialStatusMap);

    const getStatusById = (statusId) => {

        console.log( statusId, statusMap.value )

        return statusMap.value.find(status => status.id === statusId);
    };

    const getStatusName = (statusId) => {
        const selectedStatus = getStatusById(statusId);
        return selectedStatus ? selectedStatus.name : 'Unknown';
    };

    const getStatusDecoration = (statusId) => {
        const selectedStatus = getStatusById(statusId);
        if (!selectedStatus) return 'rgba(0, 0, 0, 0.1)';
    
        const colorHex = '#'+selectedStatus.hex_color;
        const baseColor = colorHex.substring(1);
        
        const r = parseInt(baseColor.slice(0, 2), 16);
        const g = parseInt(baseColor.slice(2, 4), 16);
        const b = parseInt(baseColor.slice(4, 6), 16);
        
        // Set the opacity to 0.1 for a light background
        const opacity = 0.9;
    
        return `rgba(${r}, ${g}, ${b}, ${opacity})`;
    };

    // Function to adjust color opacity
    const adjustOpacity = (statusId, opacity) => {
        const selectedStatus = getStatusById(statusId);
        if (!selectedStatus) return 'rgba(0, 0, 0, 0.25)';

        const colorHex = '#'+selectedStatus.hex_color;


        // Extract the base color without the hash
        const baseColor = colorHex.substring(1);

        // Convert hex to RGB
        const r = parseInt(baseColor.slice(0, 2), 16);
        const g = parseInt(baseColor.slice(2, 4), 16);
        const b = parseInt(baseColor.slice(4, 6), 16);
        
        // Increase RGB values towards 255 (white)
        const lightR = Math.min(255, r + (255 - r) * opacity);
        const lightG = Math.min(255, g + (255 - g) * opacity);
        const lightB = Math.min(255, b + (255 - b) * opacity);

        // Return the RGBA color
        return `rgba(${lightR}, ${lightG}, ${lightB}, ${opacity})`;
    };

    // Function to determine text color based on background color brightness
    const determineTextColor = (statusId) => {
        const selectedStatus = getStatusById(statusId);
        if (!selectedStatus) return 'rgba(0, 0, 0, 0.8)';

        const colorHex = '#'+selectedStatus.hex_color;

        // Simple algorithm to determine if the color is light or dark
        const baseColor = colorHex.substring(1);
        const r = parseInt(baseColor.slice(0, 2), 16);
        const g = parseInt(baseColor.slice(2, 4), 16);
        const b = parseInt(baseColor.slice(4, 6), 16);
        const brightness = (r * 299 + g * 587 + b * 114) / 1000;

        return brightness > 125 ? 'text-black' : 'text-white';
    };

    return {
        getStatusName,
        getStatusDecoration,
        adjustOpacity,
        determineTextColor
    };
}