'use client';

import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { home } from '@/routes';
import { store } from '@/routes/penilaian';
import type { Outsourcing } from '@/types/outsourcing';
import { Head, Link, useForm } from '@inertiajs/react';
import {
    AlertCircle,
    ArrowLeft,
    ArrowRight,
    BriefcaseBusiness,
    Building2,
    CheckCircle2,
    ClipboardCheck,
    FileText,
    Layers3,
    LoaderCircle,
    UserRound,
} from 'lucide-react';
import { FormEvent, useEffect, useMemo, useState } from 'react';

type BehavioralOption = {
    value: number;
    description: string;
};

type Indicator = {
    id: number;
    globalNumber: number;
    title: string;
    description: string;
    options: BehavioralOption[];
};

type Pillar = {
    id: number;
    title: string;
    weight: number;
    description: string;
    indicators: Indicator[];
};

type ScorePayload = {
    indicator_id: number;
    value: number;
};

type EvaluationFormData = {
    evaluator_id: number | null;
    outsourcing_id: number | null;
    scores: ScorePayload[];
    notes: string;
};

type Person = {
    id?: number;
    name?: string;
    jabatan?: string | { nama_jabatan?: string };
    jabatan_id?: number;
    biro?: { nama_biro?: string };
    [key: string]: unknown;
};

interface EvaluationFormProps {
    outsourcing: Outsourcing & {
        id?: number;
        biro?: { nama_biro?: string };
    };
    evaluator: Person;
    evaluationData: unknown;
    uuidPenugasanPeer: string;
    tipePenilai: string;
    overallNotes?: string | null;
}

const fallbackBars: BehavioralOption[] = [
    {
        value: 4,
        description: 'Fully meets criteria, no errors, ready to use',
    },
    {
        value: 3,
        description: 'Minor issues, still usable',
    },
    {
        value: 2,
        description: 'Needs revision',
    },
    {
        value: 1,
        description: 'Not usable / major issues',
    },
];

const pillarBlueprints = [
    {
        title: 'Task Performance',
        weight: 40,
        description: 'Quality, speed, and readiness of day-to-day work output.',
        indicators: [
            {
                title: 'Accuracy & Completeness',
                description:
                    'Work output is correct, complete, and ready for use without avoidable gaps.',
            },
            {
                title: 'Timeliness',
                description:
                    'Assignments are completed within agreed deadlines and operational priorities.',
            },
            {
                title: 'Information Availability',
                description:
                    'Required information, files, and status updates are available when needed.',
            },
        ],
    },
    {
        title: 'Work Behavior',
        weight: 40,
        description: 'Reliability, discipline, and coordination while working.',
        indicators: [
            {
                title: 'Priority Management',
                description:
                    'Able to distinguish urgent work, sequence tasks, and avoid preventable delays.',
            },
            {
                title: 'Stability Under Pressure',
                description:
                    'Maintains work quality, focus, and communication during busy or difficult periods.',
            },
            {
                title: 'Discipline',
                description:
                    'Consistently follows rules, attendance expectations, and agreed work procedures.',
            },
            {
                title: 'Coordination',
                description:
                    'Coordinates clearly with users, supervisors, and peers to keep work moving.',
            },
        ],
    },
    {
        title: 'Attitude & Service',
        weight: 20,
        description:
            'Service mindset, trustworthiness, and professional conduct.',
        indicators: [
            {
                title: 'Service Orientation',
                description:
                    'Responds helpfully, politely, and with attention to stakeholder needs.',
            },
            {
                title: 'Integrity & Confidentiality',
                description:
                    'Acts honestly and protects sensitive information, documents, and access.',
            },
        ],
    },
];

function readArray(value: unknown): any[] {
    return Array.isArray(value) ? value : [];
}

function readPillars(evaluationData: unknown): any[] {
    if (Array.isArray(evaluationData)) {
        return evaluationData;
    }

    if (evaluationData && typeof evaluationData === 'object') {
        return Object.values(evaluationData);
    }

    return [];
}

function normalizeBehavioralOptions(options: unknown): BehavioralOption[] {
    const normalized = readArray(options)
        .map((option) => ({
            value: Number(option?.skor ?? option?.value),
            description: String(
                option?.behavioral ?? option?.description ?? '',
            ).trim(),
        }))
        .filter(
            (option) =>
                option.value >= 1 &&
                option.value <= 4 &&
                option.description.length > 0,
        )
        .sort((a, b) => b.value - a.value);

    return normalized.length >= 4 ? normalized.slice(0, 4) : fallbackBars;
}

function normalizePillars(evaluationData: unknown): Pillar[] {
    const sourcePillars = readPillars(evaluationData);
    let globalNumber = 1;

    return pillarBlueprints.map((blueprint, pillarIndex) => {
        const sourcePillar = sourcePillars[pillarIndex] ?? {};
        const sourceIndicators = readArray(
            sourcePillar.indikator ?? sourcePillar.kriteria,
        );

        return {
            id: Number(sourcePillar.id ?? pillarIndex + 1),
            title: blueprint.title,
            weight: Number(
                sourcePillar.bobot_skor?.bobot ??
                    sourcePillar.bobotSkor?.bobot ??
                    blueprint.weight,
            ),
            description: blueprint.description,
            indicators: blueprint.indicators.map(
                (indicator, indicatorIndex) => {
                    const sourceIndicator =
                        sourceIndicators[indicatorIndex] ?? {};

                    return {
                        id: Number(sourceIndicator.id ?? globalNumber),
                        globalNumber: globalNumber++,
                        title: indicator.title,
                        description:
                            String(
                                sourceIndicator.defenisi ??
                                    sourceIndicator.description ??
                                    '',
                            ).trim() || indicator.description,
                        options: normalizeBehavioralOptions(
                            sourceIndicator.behavioral,
                        ),
                    };
                },
            ),
        };
    });
}

function getJabatan(person?: Person | null): string {
    if (!person) {
        return '-';
    }

    if (typeof person.jabatan === 'string') {
        return person.jabatan;
    }

    return person.jabatan?.nama_jabatan ?? '-';
}

function getBiro(person?: Person | Outsourcing | null): string {
    const biro = person?.biro;

    return typeof biro === 'object' && biro && 'nama_biro' in biro
        ? String(biro.nama_biro ?? '-')
        : '-';
}

function formatEvaluatorType(value: string): string {
    return value
        .replace(/\d/g, '')
        .replace(/_/g, ' ')
        .replace(/\b\w/g, (letter) => letter.toUpperCase())
        .trim();
}

function PersonSummaryCard({
    label,
    icon: Icon,
    person,
}: {
    label: string;
    icon: typeof UserRound;
    person: Person | (Outsourcing & { biro?: { nama_biro?: string } });
}) {
    return (
        <Card className="gap-3">
            <CardHeader className="pb-2">
                <div className="flex items-center gap-3">
                    <div className="flex h-10 w-10 items-center justify-center rounded-md bg-sky-100 text-sky-700">
                        <Icon className="h-5 w-5" />
                    </div>
                    <div>
                        <Badge variant="secondary">{label}</Badge>
                        <CardTitle className="mt-2 text-xl">
                            {person?.name ?? '-'}
                        </CardTitle>
                    </div>
                </div>
            </CardHeader>
            <CardContent className="grid gap-3 text-sm sm:grid-cols-2">
                <div className="flex items-center gap-2">
                    <BriefcaseBusiness className="h-6 w-6 text-muted-foreground" />

                    <div className="flex flex-col">
                        <p className="text-muted-foreground">Jabatan</p>
                        <p className="font-medium">{getJabatan(person)}</p>
                    </div>
                </div>
                <div className="flex items-center gap-2">
                    <BriefcaseBusiness className="h-6 w-6 text-muted-foreground" />

                    <div className="flex flex-col">
                        <p className="text-muted-foreground">Biro</p>
                        <p className="font-medium">{getBiro(person)}</p>
                    </div>
                </div>
            </CardContent>
        </Card>
    );
}

function BehavioralScale({
    indicatorId,
    value,
    options,
    onChange,
}: {
    indicatorId: number;
    value?: number;
    options: BehavioralOption[];
    onChange: (value: number) => void;
}) {
    return (
        <fieldset className="grid gap-3">
            <legend className="sr-only">Behavioral Anchor Rating Scale</legend>
            {options.map((option) => {
                const optionId = `indicator-${indicatorId}-score-${option.value}`;
                const selected = value === option.value;

                return (
                    <Label
                        key={option.value}
                        htmlFor={optionId}
                        className={`flex cursor-pointer items-start gap-4 rounded-md border p-4 transition ${
                            selected
                                ? 'border-emerald-500 bg-emerald-50 text-emerald-950 shadow-sm'
                                : 'border-border bg-background hover:border-sky-300 hover:bg-sky-50/60'
                        }`}
                    >
                        <input
                            id={optionId}
                            type="radio"
                            name={`indicator-${indicatorId}`}
                            value={option.value}
                            checked={selected}
                            onChange={() => onChange(option.value)}
                            className="mt-1 h-4 w-4 accent-emerald-600"
                        />
                        <span className="grid gap-1">
                            <span className="font-semibold">
                                {option.value} - Score {option.value}
                            </span>
                            <span className="text-sm leading-6 text-muted-foreground">
                                {option.description}
                            </span>
                        </span>
                    </Label>
                );
            })}
        </fieldset>
    );
}

function IndicatorCard({
    indicator,
    value,
    onScoreChange,
}: {
    indicator: Indicator;
    value?: number;
    onScoreChange: (indicatorId: number, value: number) => void;
}) {
    return (
        <Card className="gap-4">
            <CardHeader>
                <div className="flex items-start gap-4">
                    <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-md bg-zinc-900 text-sm font-semibold text-white">
                        {indicator.globalNumber}
                    </div>
                    <div className="space-y-1">
                        <CardTitle className="text-lg">
                            {indicator.title}
                        </CardTitle>
                        <CardDescription className="leading-6">
                            {indicator.description}
                        </CardDescription>
                    </div>
                </div>
            </CardHeader>
            <CardContent>
                <BehavioralScale
                    indicatorId={indicator.id}
                    value={value}
                    options={indicator.options}
                    onChange={(score) => onScoreChange(indicator.id, score)}
                />
            </CardContent>
        </Card>
    );
}

function PilarSection({
    pillar,
    scores,
    onScoreChange,
}: {
    pillar: Pillar;
    scores: ScorePayload[];
    onScoreChange: (indicatorId: number, value: number) => void;
}) {
    const completedIndicators = pillar.indicators.filter((indicator) =>
        scores.some((score) => score.indicator_id === indicator.id),
    ).length;

    return (
        <section className="space-y-5">
            <div className="flex flex-col gap-4 rounded-md border bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between">
                <div className="flex items-start gap-4">
                    <div className="flex h-11 w-11 items-center justify-center rounded-md bg-sky-100 text-sky-700">
                        <Layers3 className="h-5 w-5" />
                    </div>
                    <div>
                        <div className="flex flex-wrap items-center gap-2">
                            <h2 className="text-2xl font-semibold">
                                {pillar.title}
                            </h2>
                            <Badge className="bg-zinc-900 text-white hover:bg-zinc-900">
                                {pillar.weight}%
                            </Badge>
                        </div>
                        <p className="mt-1 text-sm leading-6 text-muted-foreground">
                            {pillar.description}
                        </p>
                    </div>
                </div>
                <div className="rounded-md border bg-zinc-50 px-4 py-3 text-sm">
                    <span className="font-semibold">{completedIndicators}</span>{' '}
                    of {pillar.indicators.length} selected
                </div>
            </div>

            <div className="space-y-4">
                {pillar.indicators.map((indicator) => (
                    <IndicatorCard
                        key={indicator.id}
                        indicator={indicator}
                        value={
                            scores.find(
                                (score) => score.indicator_id === indicator.id,
                            )?.value
                        }
                        onScoreChange={onScoreChange}
                    />
                ))}
            </div>
        </section>
    );
}

export default function EvaluationForm({
    outsourcing,
    evaluator,
    evaluationData,
    uuidPenugasanPeer,
    tipePenilai,
    overallNotes = '',
}: EvaluationFormProps) {
    const pillars = useMemo(
        () => normalizePillars(evaluationData),
        [evaluationData],
    );
    const [currentStep, setCurrentStep] = useState(0);
    const [stepMessage, setStepMessage] = useState('');

    const form = useForm<EvaluationFormData>({
        evaluator_id: evaluator?.id ?? null,
        outsourcing_id: outsourcing?.id ?? null,
        scores: [],
        notes: overallNotes ?? '',
    });

    const currentPillar = pillars[currentStep];
    const progress = ((currentStep + 1) / pillars.length) * 100;
    const isLastStep = currentStep === pillars.length - 1;
    const selectedScoreMap = useMemo(
        () =>
            new Map(
                form.data.scores.map((score) => [
                    score.indicator_id,
                    score.value,
                ]),
            ),
        [form.data.scores],
    );

    const currentStepComplete = currentPillar.indicators.every((indicator) =>
        selectedScoreMap.has(indicator.id),
    );

    const allIndicatorsComplete = pillars.every((pillar) =>
        pillar.indicators.every((indicator) =>
            selectedScoreMap.has(indicator.id),
        ),
    );

    useEffect(() => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
        setStepMessage('');
    }, [currentStep]);

    const setIndicatorScore = (indicatorId: number, value: number) => {
        const nextScores = form.data.scores.filter(
            (score) => score.indicator_id !== indicatorId,
        );

        form.setData('scores', [
            ...nextScores,
            { indicator_id: indicatorId, value },
        ]);
        setStepMessage('');
    };

    const goToPreviousStep = () => {
        setCurrentStep((step) => Math.max(step - 1, 0));
    };

    const goToNextStep = () => {
        if (!currentStepComplete) {
            setStepMessage(
                'Please select a BARS score for every indicator in this pillar.',
            );

            return;
        }

        setCurrentStep((step) => Math.min(step + 1, pillars.length - 1));
    };

    const submit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        if (!allIndicatorsComplete) {
            const firstIncompleteStep = pillars.findIndex((pillar) =>
                pillar.indicators.some(
                    (indicator) => !selectedScoreMap.has(indicator.id),
                ),
            );

            setCurrentStep(Math.max(firstIncompleteStep, 0));
            setStepMessage(
                'Please complete all required indicator scores before submitting.',
            );

            return;
        }

        form.post(store.url(uuidPenugasanPeer), {
            preserveScroll: true,
        });
    };

    return (
        <>
            <Head title="Evaluation Form" />

            <div className="min-h-screen bg-zinc-50">
                <header className="sticky top-0 z-10 border-b bg-white/95 backdrop-blur">
                    <div className="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                        <Link href={home.url()}>
                            <Button variant="ghost" className="gap-2">
                                <ArrowLeft className="h-4 w-4" />
                                Back
                            </Button>
                        </Link>
                        {/* <Badge variant="outline" className="px-3 py-1">
                            {formatEvaluatorType(tipePenilai)}
                        </Badge> */}
                    </div>
                </header>

                <main className="mx-auto max-w-7xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">
                    <div className="space-y-2">
                        <div className="flex items-center gap-2">
                            <ClipboardCheck className="h-9 w-9 text-sky-700" />

                            <div className="flex flex-col">
                                <div className="text font-medium text-sky-700">
                                    Evaluasi Kinerja Tenaga Outsourcing
                                </div>
                                <div className="text-xs text-gray-600">
                                    Semester I Tahun 2026
                                </div>
                            </div>
                        </div>
                        <div className="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
                            <p className="mt-2 max-w-3xl text-sm leading-6 text-muted-foreground">
                                Beri nilai setiap indikator dari 1 hingga 4
                                menggunakan deskripsi perilaku. Semua indikator
                                wajib diisi; catatan bersifat opsional.
                            </p>

                            <div className="rounded-md border bg-white px-4 py-3 text-sm shadow-sm">
                                Step {currentStep + 1} of {pillars.length}
                            </div>
                        </div>
                    </div>

                    <div className="grid gap-4 lg:grid-cols-2">
                        <PersonSummaryCard
                            label="Evaluator"
                            icon={UserRound}
                            person={evaluator}
                        />
                        <PersonSummaryCard
                            label="Outsourcing"
                            icon={ClipboardCheck}
                            person={outsourcing}
                        />
                    </div>

                    <div className="space-y-3">
                        <div className="h-2 overflow-hidden rounded-full bg-zinc-200">
                            <div
                                className="h-full rounded-full bg-emerald-500 transition-all"
                                style={{ width: `${progress}%` }}
                            />
                        </div>
                        <div className="grid gap-2 sm:grid-cols-3">
                            {pillars.map((pillar, index) => {
                                const active = index === currentStep;
                                const complete = pillar.indicators.every(
                                    (indicator) =>
                                        selectedScoreMap.has(indicator.id),
                                );

                                return (
                                    <button
                                        key={pillar.id}
                                        type="button"
                                        onClick={() => setCurrentStep(index)}
                                        className={`rounded-md border px-4 py-3 text-left text-sm transition ${
                                            active
                                                ? 'border-sky-500 bg-sky-50 text-sky-950'
                                                : 'border-border bg-white hover:border-sky-300'
                                        }`}
                                    >
                                        <div className="flex items-center justify-between gap-3">
                                            <span className="font-medium">
                                                {pillar.title}
                                            </span>
                                            {complete && (
                                                <CheckCircle2 className="h-4 w-4 text-emerald-600" />
                                            )}
                                        </div>
                                        <span className="mt-1 block text-xs text-muted-foreground">
                                            {pillar.weight}% weight
                                        </span>
                                    </button>
                                );
                            })}
                        </div>
                    </div>

                    <form onSubmit={submit} className="space-y-5">
                        {stepMessage && (
                            <Alert variant="destructive">
                                <AlertCircle className="h-4 w-4" />
                                <AlertTitle>Complete this section</AlertTitle>
                                <AlertDescription>
                                    {stepMessage}
                                </AlertDescription>
                            </Alert>
                        )}

                        <PilarSection
                            pillar={currentPillar}
                            scores={form.data.scores}
                            onScoreChange={setIndicatorScore}
                        />

                        {isLastStep && (
                            <Card className="gap-3">
                                <CardHeader>
                                    <div className="flex items-center gap-3">
                                        <div className="flex h-10 w-10 items-center justify-center rounded-md bg-amber-100 text-amber-700">
                                            <FileText className="h-5 w-5" />
                                        </div>
                                        <div>
                                            <CardTitle>Global Notes</CardTitle>
                                            <CardDescription>
                                                Optional summary, context, or
                                                improvement suggestion.
                                            </CardDescription>
                                        </div>
                                    </div>
                                </CardHeader>
                                <CardContent>
                                    <Textarea
                                        value={form.data.notes}
                                        onChange={(event) =>
                                            form.setData(
                                                'notes',
                                                event.target.value,
                                            )
                                        }
                                        placeholder="Add notes for this evaluation..."
                                        className="min-h-32 resize-y bg-white"
                                    />
                                    {form.errors.notes && (
                                        <p className="mt-2 text-sm text-destructive">
                                            {form.errors.notes}
                                        </p>
                                    )}
                                </CardContent>
                            </Card>
                        )}

                        {Object.keys(form.errors).length > 0 && (
                            <Alert variant="destructive">
                                <AlertCircle className="h-4 w-4" />
                                <AlertTitle>Unable to submit</AlertTitle>
                                <AlertDescription>
                                    Please review the required scores and try
                                    again.
                                </AlertDescription>
                            </Alert>
                        )}

                        <div className="flex flex-col-reverse gap-3 rounded-md border bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">
                            <Button
                                type="button"
                                variant="outline"
                                onClick={goToPreviousStep}
                                disabled={currentStep === 0 || form.processing}
                                className="gap-2"
                            >
                                <ArrowLeft className="h-4 w-4" />
                                Previous
                            </Button>

                            {isLastStep ? (
                                <Button
                                    type="submit"
                                    disabled={
                                        form.processing ||
                                        !allIndicatorsComplete
                                    }
                                    className="gap-2 bg-emerald-600 hover:bg-emerald-700"
                                >
                                    {form.processing ? (
                                        <LoaderCircle className="h-4 w-4 animate-spin" />
                                    ) : (
                                        <CheckCircle2 className="h-4 w-4" />
                                    )}
                                    Submit Evaluation
                                </Button>
                            ) : (
                                <Button
                                    type="button"
                                    onClick={goToNextStep}
                                    disabled={
                                        form.processing || !currentStepComplete
                                    }
                                    className="gap-2"
                                >
                                    Next
                                    <ArrowRight className="h-4 w-4" />
                                </Button>
                            )}
                        </div>
                    </form>
                </main>
            </div>
        </>
    );
}
