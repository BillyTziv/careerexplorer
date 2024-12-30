<template>
    <div class="p-4">
        <FullCalendar
            v-if="options"
            :options="options"
        />

        <!-- Dialog for Editing or Adding Events -->
        <!-- <Dialog
            v-model:visible="showDialog"
            :breakpoints="{ '960px': '75vw', '640px': '90vw' }"
            :style="{ width: '36rem' }"
            modal
            closable
            @onHide="view = ''"
            >
            <template #header>
                <span class="text-900 font-semibold text-xl">{{ view === 'display' ? changedEvent.title : view === 'new' ? 'New Event' : 'Edit Event' }}</span>
            </template>

            <div v-if="view === 'display'">
                <span class="text-900 font-semibold block mb-2">Description</span>
                <span class="block mb-3">{{ changedEvent.description }}</span>
                <div class="grid">
                <div class="col-6">
                    <div class="text-900 font-semibold mb-2">Start</div>
                    <p class="flex align-items-center m-0">
                    <i class="pi pi-fw pi-clock text-700 mr-2"></i>
                    <span>{{ changedEvent.start.toISOString().slice(0, 10) }}</span>
                    </p>
                </div>
                <div class="col-6">
                    <div class="text-900 font-semibold mb-2">End</div>
                    <p class="flex align-items-center m-0">
                    <i class="pi pi-fw pi-clock text-700 mr-2"></i>
                    <span>{{ changedEvent.end.toISOString().slice(0, 10) }}</span>
                    </p>
                </div>
                </div>
            </div>

            <div v-if="view !== 'display'">
                <div class="grid p-fluid formgrid">
                <div class="col-12 md:col-6 field">
                    <label for="title" class="text-900 font-semibold">Title</label>
                    <InputText id="title" type="text" placeholder="Title" v-model="changedEvent.title" />
                </div>
                <div class="col-12 md:col-6 field">
                    <label for="location" class="text-900 font-semibold">Location</label>
                    <InputText id="location" type="text" placeholder="Location" v-model="changedEvent.location" />
                </div>
                <div class="col-12 field">
                    <label for="description" class="text-900 font-semibold">Event Description</label>
                    <Textarea id="description" type="text" :rows="5" v-model="changedEvent.description" style="resize: none"></Textarea>
                </div>
                <div class="col-12 md:col-6 field">
                    <label for="start" class="text-900 font-semibold">Start Date</label>
                    <Calendar dateFormat="mm-dd-yy" :max-date="changedEvent.end" showTime required v-model="changedEvent.start" />
                </div>
                <div class="col-12 md:col-6 field">
                    <label for="end" class="text-900 font-semibold">End Date</label>
                    <Calendar dateFormat="mm-dd-yy" :minDate="changedEvent.start" showTime required v-model="changedEvent.end" />
                </div>
                </div>
            </div>

            <template #footer>
                <Button v-if="view === 'display'" label="Edit" icon="pi pi-pencil" @click="onEditClick"></Button>
                <Button v-if="view === 'new' || view === 'edit'" label="Save" icon="pi pi-check" @click="handleSave()" :disabled="!changedEvent.start || !changedEvent.end"></Button>
            </template>
        </Dialog> -->
    </div>
</template>

<script setup>
  import { ref, onMounted, defineProps } from 'vue';
  import FullCalendar from '@fullcalendar/vue3'; // FullCalendar component
  import dayGridPlugin from '@fullcalendar/daygrid'; // Calendar grid view plugin
  import timeGridPlugin from '@fullcalendar/timegrid'; // Time grid plugin (if needed)
  import interactionPlugin from '@fullcalendar/interaction'; // Interaction plugin for drag-and-drop

  const emit = defineEmits([
    'action'
]);


  // Props for passing tasks to the calendar component
  const props = defineProps({
    tasks: {
      type: Array,
      required: true
    }
  });

  let clickedEvent = null;
  const view = ref('display');
  const showDialog = ref(false);
  const changedEvent = ref({
    title: '',
    start: '',
    end: '',
    allDay: false,
    location: '',
    borderColor: '',
    textColor: '',
    description: '',
    tag: {
      name: 'Company A',
      color: '#FFB6B6'
    }
  });

  // Calendar options
  const options = ref(null);

  // Map tasks to FullCalendar events
  const calendarEvents = ref([]);

  onMounted(() => {
    // Map tasks into the FullCalendar events format
    calendarEvents.value = props.tasks.map((task) => ({
      id: task.task_id,
      title: task.task_name,
      start: task.due_date,
      description: task.description
    }));

    options.value = {
        plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
        initialDate: new Date(),
        height: 720,
        headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        editable: true,
        selectable: true,
        selectMirror: true,
        dayMaxEvents: true,
        eventClick: (e) => onEventClick(e),
        select: (e) => onDateSelect(e),
        events: calendarEvents.value
    }
  });

  // Handle event click (view or edit the event)
  const onEventClick = (e) => {
    clickedEvent = e.event;
    console.log( clickedEvent.id )

    emit('action', { type: 'view', id: clickedEvent.id });

    // let plainEvent = e.event.toPlainObject({ collapseExtendedProps: true, collapseColor: true });
    // view.value = 'display';
    // showDialog.value = true;
    // changedEvent.value = { ...plainEvent, ...clickedEvent };
    // changedEvent.value.start = clickedEvent.start;
    // changedEvent.value.end = clickedEvent.end ? clickedEvent.end : clickedEvent.start;
  };

  // Handle event selection (new event)
  const onDateSelect = (e) => {
    view.value = 'new';
    showDialog.value = true;
    changedEvent.value = {
      ...e,
      title: '',
      location: '',
      description: '',
      tag: { name: 'Company A', color: '#FFB6B6' }
    };
  };

  // Save or update the event
//   const handleSave = () => {
//     const isValidDate = changedEvent.value.start && changedEvent.value.end;
//     if (!isValidDate) return;

//     showDialog.value = false;
//     clickedEvent = { ...changedEvent.value, backgroundColor: changedEvent.value.tag.color, borderColor: changedEvent.value.tag.color, textColor: '#212121' };

//     setEvents();
//   };

//   const setEvents = () => {
//     const clickedEventHasId = Object.keys(clickedEvent).some((key) => key === 'id');
//     if (clickedEventHasId) {
//       events.value = events.value.map((i) => (i.id.toString() === clickedEvent.id.toString() ? { ...i, ...clickedEvent } : i));
//     } else {
//       events.value.push({ ...clickedEvent, id: Math.floor(Math.random() * 10000) });
//     }
//   };
</script>

<style scoped lang="scss">
    :deep(.fc-header-toolbar) {
    .fc-button {
        line-height: 1;
        min-height: 2.07rem;
    }
    }
</style>
