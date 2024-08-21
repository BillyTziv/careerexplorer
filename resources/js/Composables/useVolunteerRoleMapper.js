// src/composables/useRoleMapper.js
import { ref } from 'vue';

export function useVolunteerRoleMapper(initialRoleMap) {
    const roleMap = ref(initialRoleMap);

    const getRoleName = (roleId) => {
        const role = roleMap.value.find(role => role.id === roleId);
        return role ? role.name : 'Unknown';
    };

    return {
        getRoleName,
    };
}