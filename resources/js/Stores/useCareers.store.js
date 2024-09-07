import { defineStore } from 'pinia';
import axios from 'axios';

export const useCareersStore = defineStore({
	id: 'careers',
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
			status: [],
			role: []
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
		getRoleDropdownOptions() {
			return this.dropdownOptions.role.map(role => ({
				id: role.id,
				label: role.name,
			}));
		}
	},
	actions: {
		setRoleDropdownOptions( roles ) {
			this.dropdownOptions.role = roles;
		},
		setStatusDropdownOptions( statuses ) {
			this.dropdownOptions.status = statuses;
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
		// async fetchStatusOptions() {
		// 	try {
		// 	  const response = await axios.get('/api/v1/volunteer-statuses');
		// 	  this.statusOptions = response.data;
		// 	} catch (error) {
		// 	  console.error('Failed to fetch status:', error);
		// 	}
		// },
		resetTableFilters() {
			this.filters.search = '';
			this.filters.status = null;
			this.filters.role = null;
			this.filters.page = 1;
		},
		// async fetchStatusOptions() {
		// 	try {
		// 		const response = await axios.get('/api/status-options');
		// 		this.dropdownOptions.status = response.data;
		// 	} catch (error) {
		// 		console.error(error);
		// 	}
		// },
		// async fetchRoleOptions() {
		// 	try {
		// 		const response = await axios.get('/api/role-options');
		// 		this.dropdownOptions.role = response.data;
		// 	} catch (error) {
		// 		console.error(error);
		// 	}
		// }
	},
});