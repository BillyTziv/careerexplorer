import { ref, onMounted } from 'vue';
import { useUserStore } from '@/Stores/useUser.store';

export default function usePermissions() {
  const userPermissions = ref([]);

  const userStore = useUserStore();

  // Check if the user has a specific permission
  const hasPermission = (menuItem) => {
    // if (menuItem.items.length > 0) {
    //   console.log("Has submenu")
    //     return menuItem.items.some((perm) => permissionCodes.includes(perm));
    // }

    // return permissionCodes.includes(menuItem.permission);
    return true;
  };

  // Fetch permissions when the composable is used
  onMounted( () => {
    // userPermissions = userStore.getUser.permissions.map((permission) => permission.code)
  });

  return {
    userPermissions,
    hasPermission,
  };
}