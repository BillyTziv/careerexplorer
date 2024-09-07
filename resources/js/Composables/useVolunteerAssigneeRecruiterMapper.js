// src/composables/useRoleMapper.js
import { ref } from 'vue';

export function useVolunteerAssigneeRecruiterMapper(initialRoleMap) {
    const roleMap = ref(initialRoleMap);

    const getRecruiterName = (roleId) => {
        const role = roleMap.value.find(role => role.id === roleId);
        return role ? role.firstname + ' ' + role.lastname: '-';
    };

    return {
        getRecruiterName,
    };
}