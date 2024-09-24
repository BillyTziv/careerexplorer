import { defineStore } from 'pinia';

export const useExploreCareerStore = defineStore({
	id: 'exploreCareer',
	state: () => ({
		filters: {
			search: '',
			page: 1
		}
	}),
	getters: {
		getFilters() {
			return this.filters;
		}
	},
	actions: {
		setTableFilters( filters ) {
			this.filters = filters;
		},
		
		resetTableFilters() {
			this.filters.search = '';
			this.filters.page = 1;
		}
	},
});