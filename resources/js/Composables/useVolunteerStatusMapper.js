import { ref } from 'vue';

export function useVolunteerStatusMapper(initialStatusMap) {
    const statusMap = ref(initialStatusMap);

    console.log(statusMap)
    const getStatusName = (statusId) => {
        const status = statusMap.value.find(status => status.id === statusId);
        return status ? status.name : 'Unknown';
    };

    const getStatusDecoration = (statusId) => {
        const status = statusMap.value.find(status => status.id === statusId);
        const color = status.hex_color;

        const baseColor = colorHex.substring(1);
        const r = parseInt(baseColor.slice(0, 2), 16);
        const g = parseInt(baseColor.slice(2, 4), 16);
        const b = parseInt(baseColor.slice(4, 6), 16);
        const brightness = (r * 299 + g * 587 + b * 114) / 1000;
        
        return brightness > 125 ? 'dark:text-slate-800' : 'dark:text-slate-300';
    };

    return {
        getStatusName,
        getStatusDecoration
    };
}