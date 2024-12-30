<template>
    <div class="p-4">
        <div class="grid">
            <!-- Quadrant I: Urgent and Important -->
            <EisenhowerMatrixQuadrant
                :title="'Επείγον και Σημαντικό'"
                :tasks="quadrant1"
            />

            <!-- Quadrant II: Not Urgent but Important -->
            <EisenhowerMatrixQuadrant
                :title="'Μη Επείγον αλλά Σημαντικό'"
                :tasks="quadrant2"
            />

            <!-- Quadrant III: Urgent but Not Important -->
            <EisenhowerMatrixQuadrant
                :title="'Επείγον αλλά Μη Σημαντικό'"
                :tasks="quadrant3"
            />

            <!-- Quadrant IV: Not Urgent & Not Important -->
            <EisenhowerMatrixQuadrant
                :title="'Μη Επείγον και Μη Σημαντικό'"
                :tasks="quadrant4"
            />
        </div>

        <BaseInfoText type="info">
            <b>Επείγον</b> είναι μια εργασία που έχει deadline <b>εντός 5 ημερών</b>.
        </BaseInfoText>

        <BaseInfoText type="info">
           <b>Σημαντική</b> είναι μια εργασία με <b>μεσαία</b> και <b>υψηλή</b> προτεραιότητα.
        </BaseInfoText>

    </div>
  </template>

  <script setup>
  import { reactive, onMounted, defineProps } from 'vue';
  import EisenhowerMatrixQuadrant from './EisenhowerMatrixQuadrant.vue';
import BaseInfoText from '../../BaseInfoText.vue';

  // Accepting tasks as a prop from the parent
  const props = defineProps({
    tasks: {
      type: Array,
      required: true
    }
  });

  // Quadrants initialization
  const quadrant1 = reactive([]);
  const quadrant2 = reactive([]);
  const quadrant3 = reactive([]);
  const quadrant4 = reactive([]);

  // Function to classify tasks into quadrants
  const transformForMatrix = (tasks) => {
    return tasks.reduce((acc, task) => {
      // Determine if the task is urgent
      const isUrgentTask = isUrgent(task);

      // Determine if the task is important
      const isImportantTask = isImportant(task);

      // Get the quadrant for the task
      const quadrant = getQuadrant(isUrgentTask, isImportantTask);

      // Assign the task to the appropriate quadrant
      acc[quadrant] = acc[quadrant] || [];
      acc[quadrant].push(task);

      return acc;
    }, {
      quadrant1: [], // Urgent & Important
      quadrant2: [], // Not Urgent but Important
      quadrant3: [], // Urgent but Not Important
      quadrant4: []  // Not Urgent & Not Important
    });
  };

  // urgent is a task that has due_date within 5 days
  const isUrgent = (task) => {
    const now = new Date();
    const createdAt = new Date(task.created_at);
    const timeDiff = now - createdAt;

    return timeDiff < 5 * 24 * 60 * 60 * 1000;
  };

  // important is a task that has a priority is high (2)
  const isImportant = (task) => {
    console.log( task.priority )
    return task.priority >= 1;
  };

  // Helper function to determine the quadrant based on urgency and importance
  const getQuadrant = (isUrgent, isImportant) => {
    if (isUrgent && isImportant) {
      return 'quadrant1'; // Urgent & Important
    } else if (!isUrgent && isImportant) {
      return 'quadrant2'; // Not Urgent but Important
    } else if (isUrgent && !isImportant) {
      return 'quadrant3'; // Urgent but Not Important
    } else {
      return 'quadrant4'; // Not Urgent & Not Important
    }
  };

  // Populate the quadrants based on tasks data
  onMounted(() => {
    const groupedTasks = transformForMatrix(props.tasks);
    quadrant1.splice(0, quadrant1.length, ...groupedTasks.quadrant1);
    quadrant2.splice(0, quadrant2.length, ...groupedTasks.quadrant2);
    quadrant3.splice(0, quadrant3.length, ...groupedTasks.quadrant3);
    quadrant4.splice(0, quadrant4.length, ...groupedTasks.quadrant4);
  });
  </script>

  <style scoped>
  .eisenhower-matrix {
    padding: 2rem;
  }

  .p-grid {
    display: flex;
    justify-content: space-between;
  }

  .p-col-5 {
    width: 45%;
  }
  </style>
