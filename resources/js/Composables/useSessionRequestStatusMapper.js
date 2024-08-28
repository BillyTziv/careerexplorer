import { ref } from 'vue';

export function useSessionRequestStatusMapper() {
    const statusMap = ref([
        { id: 1, name: 'Σε Αναμονή', hex_color: 'FFD700' },
        { id: 2, name: 'Σε Επεξεργασία', hex_color: '008000' },
        { id: 3, name: 'Απορρίφθηκε', hex_color: 'FF0000' },
        { id: 4, name: 'Ολοκληρώθηκε', hex_color: '0000FF' },
    ]);

    const getStatusById = (statusId) => {
        return statusMap.value.find(status => status.id === statusId);
    };

    const getStatusName = ( statusId ) => {
        const selectedStatus = getStatusById(statusId);
        return selectedStatus ? selectedStatus.name : 'Unknown';
    };

    return {
        getStatusName
    };
}