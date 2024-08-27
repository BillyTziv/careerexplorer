import { defineStore } from 'pinia';

export const usePermissionsStore = defineStore({
	id: 'permissions',
	state: () => ({
		tableHeaders: [
			{ id: 1, label: 'ΟΝΟΜΑ', data: 'name', type: 'string', excerptLength: 15 },
			{ id: 2, label: 'ΚΩΔΙΚΟΣ', data: 'code', type: 'string', excerptLength: 15 },
			{ id: 2, label: 'ΟΝΤΟΤΗΤΑ', data: 'entity', type: 'string', excerptLength: 15 }
		],
		filters: {
			search: '',
			status: '',
			page: 1,
			category: null
		},
		categoryDropdownOptions: []
	}),
	getters: {
		getTableHeaders() {
			return this.tableHeaders;
		},
		getTableFilters() {
			return this.filters;
		},
		getCategoryDropdownOptions() {		
			return this.categoryDropdownOptions.map(category => ({
				id: category?.entity,
				label: category?.entity?.toLowerCase()
				.split('_')
				.map(word => word.charAt(0).toUpperCase() + word.slice(1))
				.join(' ')
			}));
		},
	},
	actions: {
		setCategoryDropdownOptions( options ) {
			this.categoryDropdownOptions = options;
		},
		setTableFilterByKey( key, value ) {
			// Used to reset page if a filter is changed.
			if( key !== 'page') {
				this.filters.page = 1;
			}
			
			this.filters[key] = value;
		},
		setTableFilters( filters ) {
			this.filters = filters;
		},
		resetTableFilters() {
			this.filters.search = '';
			this.filters.status = '';
			this.filters.page = 1;
			this.filters.category = '';
		}
	},
});