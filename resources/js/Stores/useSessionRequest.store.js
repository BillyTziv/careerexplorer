import { defineStore } from 'pinia';
import axios from 'axios';

export const useSessionRequestStore = defineStore({
	id: 'sessionRequests',
	state: () => ({
		tableHeaders: [
			{ id: 1, label: 'ΟΝΟΜΑ', data: 'firstname', type: 'string', excerptLength: 15 },
			{ id: 2, label: 'ΕΠΙΘΕΤΟ', data: 'lastname', type: 'string', excerptLength: 15 },
			{ id: 3, label: 'ΡΟΛΟΣ', data: 'volunteer_role', type: 'string', excerptLength: 15 },
			{ id: 4, label: 'ΚΑΤΑΣΤΑΣΗ', data: 'status', type: 'volunteer-status' },
			{ id: 5, label: 'ΤΗΛΕΦΩΝΟ', data: 'phone', type: 'string', excerptLength: 10 },
			{ id: 6, label: 'EMAIL', data: 'email', type: 'string', excerptLength: 30 },
			{ id: 7, label: 'Ημ/ΝΙΑ ΑΙΤΗΣΗΣ', data: 'created_at', type: 'date' },
		],
		filters: {
			search: '',
			status: null,
			role: null,
			page: 1
		},
		dropdownOptions: {
			status: [
				{ id: 1, name: 'Σε Αναμονή'},
				{ id: 2, name: 'Σε Επεξεργασία'},
				{ id: 3, name: 'Απορρίφθηκε'},
				{ id: 4, name: 'Ολοκληρωμένη'},
			]
		}
	}),
	getters: {
		getTableHeaders() {
			return this.tableHeaders;
		},
		getTableFilters() {
			return this.filters;
		},
		getStatusDropdownOptions() {
			return this.dropdownOptions.status.map(status => ({
				id: status.id,
				label: status.name,
			}));
		},
	},
	actions: {
		setTableFilterByKey( key, value ) {
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
			this.filters.status = null;
			this.filters.role = null;
			this.filters.page = 1;
		}
	},
});