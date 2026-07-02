'use client';

import { Head, Link, useForm } from '@inertiajs/react';
import type { InertiaFormProps } from '@inertiajs/react';
import {
    AlertCircle,
    ArrowLeft,
    ArrowRight,
    BookOpenCheck,
    BriefcaseBusiness,
    Building2,
    CheckCircle2,
    ClipboardCheck,
    Eye,
    FileText,
    Layers3,
    ListChecks,
    LoaderCircle,
    Scale,
    ShieldCheck,
    UserRound,
} from 'lucide-react';
import { useEffect, useMemo, useState } from 'react';
import type { FormEvent } from 'react';

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
    development_area: string;
    observed_strengths: string;
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
    developmentArea?: string | null;
    observedStrengths?: string | null;
    overallNotes?: string | null;
}

const guidePillars = [
    {
        title: 'Task Performance',
        subtitle: 'Hasil kerja sesuai tugas dan tanggung jawab',
        meta: '3-4 indikator sesuai job family',
        icon: ClipboardCheck,
        className: 'border-sky-200 bg-sky-50 text-sky-900',
    },
    {
        title: 'Work Behavior',
        subtitle: 'Cara kerja dan proses kerja harian',
        meta: '4 indikator',
        icon: Layers3,
        className: 'border-emerald-200 bg-emerald-50 text-emerald-900',
    },
    {
        title: 'Attitude & Service',
        subtitle: 'Sikap, profesionalisme, integritas, dan pelayanan',
        meta: '2 indikator',
        icon: ShieldCheck,
        className: 'border-amber-200 bg-amber-50 text-amber-900',
    },
];

const fillingGuidelines = [
    'Bacalah setiap indikator dan pilihan perilaku BARS dengan saksama.',
    'Pilih satu pilihan yang paling menggambarkan kondisi nyata tenaga alih daya selama bekerja.',
    'Nilai berdasarkan pengamatan langsung dan pengalaman kerja, bukan asumsi, kedekatan pribadi, atau kesan umum.',
    'Pertimbangkan konsistensi perilaku selama periode penilaian, bukan hanya satu kejadian tertentu.',
    'Jika perilaku belum pernah diamati langsung, berikan penilaian secara hati-hati berdasarkan interaksi kerja yang paling relevan.',
];

const scoringAnchors = [
    {
        score: 4,
        label: 'Sangat Sesuai',
        description: 'Perilaku kerja konsisten memenuhi kriteria.',
    },
    {
        score: 3,
        label: 'Sesuai',
        description: 'Perilaku kerja umumnya baik dengan isu minor.',
    },
    {
        score: 2,
        label: 'Perlu Perbaikan',
        description: 'Perilaku kerja masih membutuhkan revisi atau arahan.',
    },
    {
        score: 1,
        label: 'Belum Sesuai',
        description: 'Perilaku kerja belum memenuhi kriteria utama.',
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
    return readArray(options)
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
}

function normalizePillars(evaluationData: unknown): Pillar[] {
    const sourcePillars = readPillars(evaluationData);
    let globalNumber = 1;

    return sourcePillars.map((sourcePilar: any) => {
        const sourceIndicators = readArray(
            sourcePilar.indikator ?? sourcePilar.kriteria,
        );

        return {
            id: Number(sourcePilar.id ?? 0),
            title: String(sourcePilar.title ?? 'Untitled Pillar'),
            weight: Number(
                sourcePilar.bobot_skor?.bobot ??
                    sourcePilar.bobotSkor?.bobot ??
                    0,
            ),
            indicators: sourceIndicators.map((sourceIndicator: any) => ({
                id: Number(sourceIndicator.id ?? globalNumber),
                globalNumber: globalNumber++,
                title: String(sourceIndicator.title ?? 'Untitled Indicator'),
                description: String(
                    sourceIndicator.defenisi ??
                        sourceIndicator.description ??
                        '',
                ).trim(),
                options: normalizeBehavioralOptions(sourceIndicator.behavioral),
            })),
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
        <Card className="gap-3 border-slate-200 bg-white shadow-sm transition-colors hover:shadow-md">
            <CardHeader className="pb-2">
                <div className="flex items-center gap-3">
                    <div className="flex h-10 w-10 items-center justify-center rounded-md bg-gradient-to-br from-sky-100 to-sky-200 text-sky-700 shadow-sm ring-1 ring-sky-100">
                        <Icon className="h-5 w-5" />
                    </div>

                    <div>
                        <Badge className="bg-slate-200 text-slate-800 hover:bg-slate-300">
                            {label}
                        </Badge>

                        <CardTitle className="mt-2 text-lg font-semibold tracking-tight text-slate-900">
                            {person?.name ?? '-'}
                        </CardTitle>
                    </div>
                </div>
            </CardHeader>

            <CardContent className="ml-1 grid gap-3 text-sm sm:grid-cols-2">
                <div className="flex items-center gap-2">
                    <BriefcaseBusiness className="h-6 w-6 text-slate-400" />

                    <div className="flex flex-col">
                        <p className="text-slate-500">Jabatan</p>
                        <p className="font-medium text-slate-900">
                            {getJabatan(person)}
                        </p>
                    </div>
                </div>

                <div className="flex items-center gap-2">
                    <Building2 className="h-6 w-6 text-slate-400" />

                    <div className="flex flex-col">
                        <p className="text-slate-500">Biro</p>
                        <p className="font-medium text-slate-900">
                            {getBiro(person)}
                        </p>
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
                                Skor {option.value}
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

function NotesTextareaCard({
    title,
    description,
    value,
    placeholder,
    error,
    onChange,
}: {
    title: string;
    description: string;
    value: string;
    placeholder: string;
    error?: string;
    onChange: (value: string) => void;
}) {
    return (
        <Card className="gap-3">
            <CardHeader>
                <div className="flex items-center gap-3">
                    <div className="flex h-10 w-10 items-center justify-center rounded-md bg-amber-100 text-amber-700">
                        <FileText className="h-5 w-5" />
                    </div>
                    <div>
                        <CardTitle>{title}</CardTitle>
                        <CardDescription className="text-xs text-muted-foreground">
                            {description}
                        </CardDescription>
                    </div>
                </div>
            </CardHeader>
            <CardContent>
                <Textarea
                    value={value}
                    onChange={(event) => onChange(event.target.value)}
                    placeholder={placeholder}
                    className="min-h-32 resize-y bg-white"
                />
                {error && (
                    <p className="mt-2 text-sm text-destructive">{error}</p>
                )}
            </CardContent>
        </Card>
    );
}

function OtherNotesSection({
    form,
}: {
    form: InertiaFormProps<EvaluationFormData>;
}) {
    return (
        <section className="space-y-5">
            <div className="flex flex-col gap-4 rounded-md border bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between">
                <div className="flex items-center gap-3">
                    <div className="flex h-11 w-11 items-center justify-center rounded-md bg-amber-100 text-amber-700">
                        <FileText className="h-5 w-5" />
                    </div>
                    <div>
                        <h2 className="text-2xl font-semibold">
                            Catatan Tambahan
                        </h2>
                        <p className="mt-1 text-sm text-muted-foreground">
                            Tambahkan konteks kualitatif untuk melengkapi skor
                            BARS.
                        </p>
                    </div>
                </div>
                <Badge variant="outline" className="w-fit bg-white">
                    Optional
                </Badge>
            </div>

            <div className="grid gap-4">
                <NotesTextareaCard
                    title="Area yang perlu dikembangkan"
                    description="Optional"
                    value={form.data.development_area}
                    onChange={(value) =>
                        form.setData('development_area', value)
                    }
                    placeholder="Tuliskan area atau kompetensi yang masih perlu dikembangkan..."
                    error={form.errors.development_area}
                />

                <NotesTextareaCard
                    title="Kekuatan yang teramati"
                    description="Optional"
                    value={form.data.observed_strengths}
                    onChange={(value) =>
                        form.setData('observed_strengths', value)
                    }
                    placeholder="Tuliskan kekuatan atau perilaku positif yang paling terlihat..."
                    error={form.errors.observed_strengths}
                />

                <NotesTextareaCard
                    title="Catatan Lainnya"
                    description="Optional"
                    value={form.data.notes}
                    onChange={(value) => form.setData('notes', value)}
                    placeholder="Tambahkan catatan untuk OS yang dinilai..."
                    error={form.errors.notes}
                />
            </div>

            <Button
                type="submit"
                disabled={form.processing}
                className="gap-2 bg-emerald-600 hover:bg-emerald-700"
            >
                {form.processing ? (
                    <LoaderCircle className="h-4 w-4 animate-spin" />
                ) : (
                    <CheckCircle2 className="h-4 w-4" />
                )}
                Kirim Penilaian
            </Button>
        </section>
    );
}

function AssessmentGuideSection() {
    return (
        <section className="space-y-5">
            <Card className="overflow-hidden border-slate-200 bg-white pt-0 shadow-sm">
                <div className="border-b bg-linear-to-r from-sky-50 via-white to-emerald-50 p-6">
                    <div className="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                        <div className="flex gap-4">
                            <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-md bg-sky-600 text-white shadow-sm">
                                <BookOpenCheck className="h-6 w-6" />
                            </div>

                            <div className="space-y-2">
                                <Badge className="bg-sky-100 text-sky-800 hover:bg-sky-100">
                                    Panduan Penilaian
                                </Badge>
                                <div>
                                    <h2 className="text-2xl font-semibold tracking-tight text-slate-950">
                                        Pedoman sebelum memberikan nilai
                                    </h2>
                                    <p className="mt-2 max-w-3xl text-sm leading-6 text-slate-600">
                                        Yth. Bapak/Ibu, terima kasih atas
                                        kesediaan Bapak/Ibu untuk berpartisipasi
                                        dalam Penilaian Kinerja Tenaga Alih Daya
                                        (Outsourcing) di lingkungan Sekretariat
                                        Wakil Presiden.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div className="grid gap-2 sm:grid-cols-3 lg:w-[28rem]">
                            <div className="rounded-md border bg-white p-3 text-center shadow-sm">
                                <p className="text-2xl font-bold text-sky-700">
                                    3
                                </p>
                                <p className="text-xs text-slate-500">Pilar</p>
                            </div>
                            <div className="rounded-md border bg-white p-3 text-center shadow-sm">
                                <p className="text-2xl font-bold text-emerald-700">
                                    1-4
                                </p>
                                <p className="text-xs text-slate-500">
                                    Skala BARS
                                </p>
                            </div>
                            <div className="rounded-md border bg-white p-3 text-center shadow-sm">
                                <p className="text-2xl font-bold text-amber-700">
                                    100%
                                </p>
                                <p className="text-xs text-slate-500">
                                    Objektif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <CardContent className="space-y-6 p-6">
                    <div className="grid gap-4 lg:grid-cols-[1.1fr_0.9fr]">
                        <div className="rounded-md border border-slate-200 bg-slate-50 p-5">
                            <div className="flex items-center gap-3">
                                <div className="flex h-10 w-10 items-center justify-center rounded-md bg-white text-sky-700 shadow-sm">
                                    <Scale className="h-5 w-5" />
                                </div>
                                <div>
                                    <h3 className="font-semibold text-slate-950">
                                        Tujuan penilaian
                                    </h3>
                                    <p className="text-xs text-slate-500">
                                        Dasar evaluasi dan pengembangan layanan
                                    </p>
                                </div>
                            </div>

                            <p className="mt-4 text-sm leading-7 text-slate-700">
                                Penilaian ini bertujuan untuk memperoleh
                                gambaran yang objektif mengenai kinerja tenaga
                                alih daya sebagai dasar peningkatan kualitas
                                layanan, pengembangan kompetensi, serta
                                pengambilan keputusan dalam pengelolaan tenaga
                                alih daya.
                            </p>
                        </div>

                        <div className="rounded-md border border-emerald-200 bg-emerald-50 p-5">
                            <div className="flex items-center gap-3">
                                <div className="flex h-10 w-10 items-center justify-center rounded-md bg-white text-emerald-700 shadow-sm">
                                    <Eye className="h-5 w-5" />
                                </div>
                                <div>
                                    <h3 className="font-semibold text-emerald-950">
                                        Apa yang dinilai?
                                    </h3>
                                    <p className="text-xs text-emerald-700">
                                        Perilaku kerja yang benar-benar
                                        ditunjukkan
                                    </p>
                                </div>
                            </div>

                            <p className="mt-4 text-sm leading-7 text-emerald-950">
                                Instrumen ini menilai perilaku kerja nyata
                                selama tenaga alih daya menjalankan
                                pekerjaannya. Jumlah indikator dapat berbeda
                                antar job family karena disusun berdasarkan
                                karakteristik dan tuntutan masing-masing
                                pekerjaan.
                            </p>
                        </div>
                    </div>

                    <div className="grid gap-3 md:grid-cols-3">
                        {guidePillars.map((pillar) => {
                            const Icon = pillar.icon;

                            return (
                                <div
                                    key={pillar.title}
                                    className={`rounded-md border p-4 ${pillar.className}`}
                                >
                                    <div className="flex items-start gap-3">
                                        <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-md bg-white/80 shadow-sm">
                                            <Icon className="h-5 w-5" />
                                        </div>
                                        <div>
                                            <h3 className="font-semibold">
                                                {pillar.title}
                                            </h3>
                                            <p className="mt-1 text-sm leading-6">
                                                {pillar.subtitle}
                                            </p>
                                            <Badge
                                                variant="outline"
                                                className="mt-3 bg-white/60"
                                            >
                                                {pillar.meta}
                                            </Badge>
                                        </div>
                                    </div>
                                </div>
                            );
                        })}
                    </div>

                    <div className="grid gap-4 lg:grid-cols-[0.95fr_1.05fr]">
                        <Card className="gap-3 border-slate-200 bg-gray-50 shadow-none">
                            <CardHeader>
                                <div className="flex items-center gap-3">
                                    <div className="flex h-10 w-10 items-center justify-center rounded-md bg-violet-100 text-violet-700">
                                        <ListChecks className="h-5 w-5" />
                                    </div>
                                    <div>
                                        <CardTitle className="text-lg">
                                            Skala BARS
                                        </CardTitle>
                                        <CardDescription>
                                            Pilih satu perilaku paling sesuai
                                        </CardDescription>
                                    </div>
                                </div>
                            </CardHeader>
                            <CardContent className="grid gap-2">
                                {scoringAnchors.map((anchor) => (
                                    <div
                                        key={anchor.score}
                                        className="flex items-start gap-3 rounded-md border bg-white p-3"
                                    >
                                        <div className="flex h-8 w-8 shrink-0 items-center justify-center rounded-md bg-slate-900 text-sm font-semibold text-white">
                                            {anchor.score}
                                        </div>
                                        <div>
                                            <p className="text-sm font-semibold text-slate-950">
                                                {anchor.label}
                                            </p>
                                            <p className="text-xs leading-5 text-slate-600">
                                                {anchor.description}
                                            </p>
                                        </div>
                                    </div>
                                ))}
                            </CardContent>
                        </Card>

                        <Card className="gap-3 border-slate-200 bg-gray-50 shadow-none">
                            <CardHeader>
                                <div className="flex items-center gap-3">
                                    <div className="flex h-10 w-10 items-center justify-center rounded-md bg-amber-100 text-amber-700">
                                        <ShieldCheck className="h-5 w-5" />
                                    </div>
                                    <div>
                                        <CardTitle className="text-lg">
                                            Petunjuk pengisian
                                        </CardTitle>
                                        <CardDescription>
                                            Gunakan pengamatan yang relevan dan
                                            konsisten
                                        </CardDescription>
                                    </div>
                                </div>
                            </CardHeader>
                            <CardContent>
                                <ul className="grid gap-3">
                                    {fillingGuidelines.map(
                                        (guideline, index) => (
                                            <li
                                                key={guideline}
                                                className="flex gap-3 rounded-md border bg-white p-3 text-sm leading-6 text-slate-700"
                                            >
                                                <span className="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-amber-100 text-xs font-semibold text-amber-800">
                                                    {index + 1}
                                                </span>
                                                <span>{guideline}</span>
                                            </li>
                                        ),
                                    )}
                                </ul>
                            </CardContent>
                        </Card>
                    </div>

                    <div className="rounded-md border border-slate-200 bg-slate-950 p-5 text-white">
                        <div className="flex flex-col gap-3 sm:flex-row sm:items-start">
                            <ShieldCheck className="h-6 w-6 shrink-0 text-emerald-300" />
                            <div className="space-y-2">
                                <h3 className="font-semibold">
                                    Kerahasiaan dan kontribusi
                                </h3>
                                <p className="text-sm leading-7 text-slate-200">
                                    Seluruh informasi yang diberikan akan dijaga
                                    kerahasiaannya dan hanya digunakan untuk
                                    kepentingan pengelolaan, evaluasi, serta
                                    pengembangan kinerja tenaga alih daya di
                                    lingkungan Sekretariat Wakil Presiden.
                                    Partisipasi dan penilaian objektif Bapak/Ibu
                                    merupakan kontribusi berharga dalam
                                    mendukung peningkatan kualitas layanan.
                                </p>
                                <p className="text-sm font-medium text-emerald-200">
                                    Terima kasih atas waktu, perhatian, dan
                                    partisipasi Bapak/Ibu.
                                </p>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </section>
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
                <div className="flex items-center gap-3">
                    <div className="flex h-11 w-11 items-center justify-center rounded-md bg-sky-100 text-sky-700">
                        <Layers3 className="h-5 w-5" />
                    </div>
                    <div className="flex items-center gap-2">
                        <h2 className="text-2xl font-semibold">
                            {pillar.title}
                        </h2>

                        <Badge className="bg-zinc-900 text-white hover:bg-zinc-900">
                            {pillar.weight}%
                        </Badge>
                    </div>
                </div>
                <div className="rounded-md border bg-zinc-50 px-4 py-3 text-sm">
                    <span className="font-semibold">{completedIndicators}</span>{' '}
                    dari {pillar.indicators.length} terpilih
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
    developmentArea = '',
    observedStrengths = '',
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
        development_area: developmentArea ?? '',
        observed_strengths: observedStrengths ?? '',
        notes: overallNotes ?? '',
    });

    const noteStep = pillars.length + 1;
    const totalSteps = noteStep + 1;
    const isGuideStep = currentStep === 0;
    const isNoteStep = currentStep === noteStep;
    const currentPillar = pillars[currentStep - 1];
    const progress = ((currentStep + 1) / totalSteps) * 100;

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

    const isPillarComplete = (pillar: Pillar) =>
        pillar.indicators.every((indicator) =>
            selectedScoreMap.has(indicator.id),
        );

    const currentStepComplete =
        isGuideStep ||
        (currentPillar ? isPillarComplete(currentPillar) : false);

    const allIndicatorsComplete = pillars.every((pillar) =>
        isPillarComplete(pillar),
    );

    const canOpenStep = (targetStep: number) =>
        targetStep === 0 ||
        pillars.slice(0, targetStep - 1).every((pillar) => {
            return isPillarComplete(pillar);
        });

    useEffect(() => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
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
        setStepMessage('');
        setCurrentStep((step) => Math.max(step - 1, 0));
    };

    const goToNextStep = () => {
        if (isGuideStep) {
            setStepMessage('');
            setCurrentStep((step) => Math.min(step + 1, totalSteps - 1));

            return;
        }

        if (!currentStepComplete) {
            setStepMessage(
                'Pilih skor BARS untuk setiap indikator pada pilar ini sebelum melanjutkan.',
            );

            return;
        }

        setStepMessage('');
        setCurrentStep((step) => Math.min(step + 1, totalSteps - 1));
    };

    const selectStep = (targetStep: number) => {
        if (targetStep <= currentStep || canOpenStep(targetStep)) {
            setStepMessage('');
            setCurrentStep(targetStep);

            return;
        }

        setStepMessage(
            'Selesaikan pilar sebelumnya terlebih dahulu sebelum membuka sesi ini.',
        );
    };

    const submit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        if (!allIndicatorsComplete) {
            const firstIncompleteStep = pillars.findIndex((pillar) =>
                pillar.indicators.some(
                    (indicator) => !selectedScoreMap.has(indicator.id),
                ),
            );

            setCurrentStep(Math.max(firstIncompleteStep + 1, 1));
            setStepMessage(
                'Lengkapi seluruh skor indikator yang wajib diisi sebelum mengirim penilaian.',
            );

            return;
        }

        form.post(store.url(uuidPenugasanPeer), {
            preserveScroll: true,
        });
    };

    const isLastStep = currentStep === noteStep;
    return (
        <>
            <Head title="Evaluation Form" />

            <div className="min-h-screen bg-zinc-50">
                <header className="sticky top-0 z-10 border-b bg-white/95 backdrop-blur">
                    <div className="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                        <Link href={home.url()}>
                            <Button variant="ghost" className="gap-2">
                                <ArrowLeft className="h-4 w-4" />
                                Kembali
                            </Button>
                        </Link>
                        <Badge
                            variant="outline"
                            className="border-orange-300 px-3 py-1"
                        >
                            Penilai sebagai {formatEvaluatorType(tipePenilai)}
                        </Badge>
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
                        <div className="grid gap-2 sm:grid-cols-2 lg:grid-cols-5">
                            <button
                                type="button"
                                onClick={() => selectStep(0)}
                                className={`rounded-md border px-4 py-3 text-left text-sm transition ${
                                    isGuideStep
                                        ? 'border-sky-500 bg-sky-50 text-sky-950'
                                        : 'border-border bg-white hover:border-sky-300'
                                }`}
                            >
                                <div className="flex items-center justify-between gap-3">
                                    <span className="font-medium">
                                        Panduan Penilaian
                                    </span>
                                    {currentStep > 0 && (
                                        <CheckCircle2 className="h-4 w-4 text-emerald-600" />
                                    )}
                                </div>
                                <span className="mt-1 block text-xs text-muted-foreground">
                                    Baca sebelum menilai
                                </span>
                            </button>

                            {pillars.map((pillar, index) => {
                                const stepNumber = index + 1;
                                const active = stepNumber === currentStep;
                                const complete = isPillarComplete(pillar);
                                const unlocked = canOpenStep(stepNumber);

                                return (
                                    <button
                                        key={pillar.id}
                                        type="button"
                                        onClick={() => selectStep(stepNumber)}
                                        disabled={!unlocked}
                                        className={`rounded-md border px-4 py-3 text-left text-sm transition disabled:cursor-not-allowed disabled:opacity-50 ${
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
                                            Bobot {pillar.weight}%
                                        </span>
                                    </button>
                                );
                            })}

                            <button
                                type="button"
                                onClick={() => selectStep(noteStep)}
                                className={`rounded-md border px-4 py-3 text-left text-sm transition ${
                                    isNoteStep
                                        ? 'border-sky-500 bg-sky-50 text-sky-950'
                                        : 'border-border bg-white hover:border-sky-300'
                                }`}
                            >
                                <div className="flex items-center justify-between gap-3">
                                    <span className="font-medium">
                                        Catatan Tambahan
                                    </span>
                                    {currentStep > 0 && (
                                        <CheckCircle2 className="h-4 w-4 text-emerald-600" />
                                    )}
                                </div>
                                <span className="mt-1 block text-xs text-muted-foreground">
                                    Berikan catatan jika ada
                                </span>
                            </button>
                        </div>
                    </div>

                    <form onSubmit={submit} className="space-y-5">
                        {stepMessage && (
                            <Alert variant="destructive">
                                <AlertCircle className="h-4 w-4" />
                                <AlertTitle>Lengkapi sesi ini</AlertTitle>
                                <AlertDescription>
                                    {stepMessage}
                                </AlertDescription>
                            </Alert>
                        )}

                        {isGuideStep ? (
                            <AssessmentGuideSection />
                        ) : isNoteStep ? (
                            <OtherNotesSection form={form} />
                        ) : (
                            currentPillar && (
                                <PilarSection
                                    pillar={currentPillar}
                                    scores={form.data.scores}
                                    onScoreChange={setIndicatorScore}
                                />
                            )
                        )}

                        {Object.keys(form.errors).length > 0 && (
                            <Alert variant="destructive">
                                <AlertCircle className="h-4 w-4" />
                                <AlertTitle>
                                    Penilaian belum dapat dikirim
                                </AlertTitle>
                                <AlertDescription>
                                    Periksa kembali skor wajib pada setiap
                                    indikator.
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
                                Sebelumnya
                            </Button>

                            {(isGuideStep || currentStep != 4) && (
                                <Button
                                    type="button"
                                    onClick={goToNextStep}
                                    disabled={
                                        form.processing || !currentStepComplete
                                    }
                                    className="gap-2"
                                >
                                    {isGuideStep
                                        ? 'Mulai Penilaian'
                                        : 'Selanjutnya'}
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
