import { defineStore } from 'pinia';

export const useUserStore = defineStore({
	id: 'user',
	state: () => ({
		user: null,
		permissions: [],
	}),
	getters: {
		getUser() {
			return this.user;
		},
		getPermissionCodes() {
			return this.permissions.map((permission) => permission.code);
		}
	},
	actions: {
		setUser( user ) {
			this.user = user;
		},
		async fetchPermissions() {
			try {
				const response = await axios.get('/user/permissions');
				console.log( response );
				console.log( response.data );
				console.log( typeof response.data );

				this.permissions = response.data;
			} catch (error) {
				console.error('Failed to fetch permissions:', error);
			}
		},
	},
});