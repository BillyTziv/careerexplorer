import { defineStore } from 'pinia';

export const useCareerStore = defineStore({
	id: 'career',
	state: () => ({
		filters: null,
	}),
	getters: {
		getSearchFilters() {
			return this.filters;
		}
	},
	actions: {
		setSearchFilters( filters ) {
			this.filters = filters;
		}
	},
});