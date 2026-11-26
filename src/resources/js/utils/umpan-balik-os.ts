import { CheckCircle2, MessageSquareText, Target } from 'lucide-react';

export type FeedbackTone = 'strength' | 'development' | 'note';

export const feedbackStyles: Record<
    FeedbackTone,
    {
        icon: typeof CheckCircle2;
        label: string;
        wrapper: string;
        iconWrapper: string;
        iconClass: string;
        titleClass: string;
    }
> = {
    strength: {
        icon: CheckCircle2,
        label: 'Kekuatan Teramati',
        wrapper:
            'border-emerald-200/70 bg-emerald-50/70 dark:border-emerald-900/60 dark:bg-emerald-950/20',
        iconWrapper: 'bg-emerald-100 dark:bg-emerald-900/50',
        iconClass: 'text-emerald-700 dark:text-emerald-300',
        titleClass: 'text-emerald-800 dark:text-emerald-200',
    },
    development: {
        icon: Target,
        label: 'Area Pengembangan',
        wrapper:
            'border-amber-200/70 bg-amber-50/70 dark:border-amber-900/60 dark:bg-amber-950/20',
        iconWrapper: 'bg-amber-100 dark:bg-amber-900/50',
        iconClass: 'text-amber-700 dark:text-amber-300',
        titleClass: 'text-amber-800 dark:text-amber-200',
    },
    note: {
        icon: MessageSquareText,
        label: 'Catatan',
        wrapper:
            'border-sky-200/70 bg-sky-50/70 dark:border-sky-900/60 dark:bg-sky-950/20',
        iconWrapper: 'bg-sky-100 dark:bg-sky-900/50',
        iconClass: 'text-sky-700 dark:text-sky-300',
        titleClass: 'text-sky-800 dark:text-sky-200',
    },
};
