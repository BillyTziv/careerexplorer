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
			page: 1
		}
	}),
	getters: {
		getTableHeaders() {
			return this.tableHeaders;
		},
		getTableFilters() {
			return this.filters;
		}
	},
	actions: {
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
		}
	},
});