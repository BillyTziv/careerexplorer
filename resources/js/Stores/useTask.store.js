import { defineStore } from 'pinia';

const initialFilters = {
    status: null,
    volunteer: null,
    tag: null,
    category: null,
    priority: null
};

export const useTaskStore = defineStore({
	id: 'tasks',
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
		tableOptions: {
			search: '',
			status: null,
			volunteer: null,
			page: 1,
		},
        filters: { ...initialFilters },
		dropdownOptions: {
			status: [],
			volunteer: [],
            tag: [],
            category: [],
            priority: []
		}
	}),
	getters: {
		getTableHeaders() {
			return this.tableHeaders;
		},
		getTableOptions() {
			return this.tableOptions;
		},
        getFilters() {
            return this.filters;
        },
		getStatusDropdownOptions() {
			return this.dropdownOptions.status.map(status => ({
				id: status.id,
				label: status.name,
			}));
		},
        getPriorityDropdownOptions() {
            return this.dropdownOptions.priority.map(priority => ({
				id: priority.id,
				label: priority.name,
			}));
        },
        getTagDropdownOptions() {
            return this.dropdownOptions.tag.map(tag => ({
                id: tag.id,
                label: tag.name,
            }));
        },
        getCategoryDropdownOptions() {
            return this.dropdownOptions.category.map(category => ({
                id: category.id,
                label: category.name,
            }));
        },
		getVolunteerDropdownOptions() {
			return this.dropdownOptions.volunteer.map(volunteer => ({
				id: volunteer.id,
				label: volunteer.name,
			}));
		}
	},
	actions: {
		setDropdownOptions( filterKey, options ) {
			this.dropdownOptions[filterKey] = options;
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
            this.filters = { ...initialFilters };
		}
	},
});
