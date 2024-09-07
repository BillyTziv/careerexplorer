import { useToast } from 'primevue/usetoast';

const DEFAULT_LIFE_DURATION = 5000;

export function useToastNotification() {
    const toast = useToast();

    const notify = (severity, summary, detail, life = DEFAULT_LIFE_DURATION) => {
        toast.add({
            severity, 
            summary, 
            detail, 
            life
        });
    };

    return { notify };
}