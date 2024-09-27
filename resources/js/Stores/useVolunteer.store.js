import { defineStore } from 'pinia';
import axios from 'axios';
import { get, set } from 'lodash';

export const useVolunteersStore = defineStore({
	id: 'volunteers',
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
		tableMetaData: {
			total: 0,
			per_page: 10,
			current_page: 1,
			last_page: 1,
		},
		filters: {
			search: '',
			status: null,
			role: null,
			page: 1,
			assigned_recruiter: null
		},
		dropdownOptions: {
			status: [],
			role: [],
			assigned_recruiter: []
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
		},
		getRecruiterDropdownOptions() {
			return this.dropdownOptions.assigned_recruiter.map(recruiter => ({
				id: recruiter.id,
				label: recruiter.firstname + ' ' + recruiter.lastname,
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
		setRecruiterDropdownOptions( recruiter ) {
			this.dropdownOptions.assigned_recruiter = recruiter;
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
			this.filters.status = null;
			this.filters.role = null;
			this.filters.page = 1;
			this.filters.assigned_recruiter = null;
		},
		// async fetchRecruiterOptions() {
		// 	try {
		// 		const response = await axios.get('/api/recruiter-options');
		// 		this.dropdownOptions.status = response.data;
		// 	} catch (error) {
		// 		console.error(error);
		// 	}
		// },
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