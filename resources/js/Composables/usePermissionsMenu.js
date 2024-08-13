import { ref, onMounted } from 'vue';
import axios from 'axios';

export default function usePermissions() {
  const permissions = ref([]);

  // Fetch permissions from the backend
  const fetchPermissions = async () => {
    try {
      const response = await axios.get('/user/permissions');
      permissions.value = response.data;
    } catch (error) {
      console.error('Error fetching permissions:', error);
    }
  };

  // Check if the user has a specific permission
  const hasPermission = (menuItem) => {
    const permissionCodes = permissions.value.map((permission) => permission.code);

    console.log( permissionCodes )
    if( permissionCodes.includes(menuItem.permission) ) return true;

    if (menuItem.items.length > 0) {
      return menuItem.items.some((perm) => permissionCodes.includes(perm.permission));
    }

    return false;
};

  // Fetch permissions when the composable is used
  onMounted(fetchPermissions);

  return {
    permissions,
    hasPermission,
  };
}