'use client';

import {
    BriefcaseBusiness,
    CheckCircle2,
    FileSpreadsheet,
    Loader2,
    MessageSquareText,
    Sparkles,
    Target,
    UserRoundCheck,
    UsersRound,
} from 'lucide-react';
import { useEffect, useMemo, useState } from 'react';
import * as XLSX from 'xlsx';
import { router } from '@inertiajs/react';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { saranEvaluator } from '@/routes/os';

type FeedbackValue = string | null | undefined;

type Penugasan = {
    nama?: string | null;
    image?: string | null;
    tipe_penilai?: string | null;
    catatan?: FeedbackValue;
    area_pengembangan?: FeedbackValue;
    kekuatan_teramati?: FeedbackValue;
};

type Evaluator = {
    id?: string | number;
    name?: string | null;
    image?: string | null;
    penugasan?: Penugasan[];
};

type OutsourcingGroup = {
    jabatan?: string | null;
    jabatan_id?: string | number | null;
    evaluators?: Evaluator[];
};

type Jabatan = {
    id: string | number;
    nama_jabatan: string;
};

type SaranPerbaikanProps = {
    Outsourcings?: OutsourcingGroup[];
    allJabatan?: Jabatan[];
    selectedJabatanId?: string;
};

type PersonEntry = {
    id: string;
    name: string;
    image?: string | null;
    jabatan: string;
    penugasan: Penugasan[];
};

type FeedbackTone = 'strength' | 'development' | 'note';

const slugify = (value: string) =>
    value
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/(^-|-$)/g, '');

const normalizeFeedback = (value: FeedbackValue) => {
    if (!value) return null;

    const normalized = value.trim();

    if (!normalized || normalized === '-') return null;

    return normalized;
};

const getInitials = (name?: string | null) => {
    if (!name) return 'U';

    return name
        .trim()
        .split(/\s+/)
        .filter(Boolean)
        .map((part) => part.charAt(0))
        .join('')
        .slice(0, 2)
        .toUpperCase();
};

const getImageSrc = (image?: string | null) => {
    if (!image) return undefined;

    return `/storage/${image}`;
};

const formatEvaluatorType = (value?: string | null) => {
    if (!value) return 'Penilai';

    return value
        .replace(/_/g, ' ')
        .replace(/([a-zA-Z])(\d+)/g, '$1 $2')
        .replace(/\b\w/g, (char) => char.toUpperCase());
};

const hasAnyFeedback = (feedback: Penugasan) =>
    Boolean(
        normalizeFeedback(feedback.kekuatan_teramati) ||
        normalizeFeedback(feedback.area_pengembangan) ||
        normalizeFeedback(feedback.catatan),
    );

const exportFeedbackToExcel = (entries: PersonEntry[]) => {
    const workbook = XLSX.utils.book_new();
    const entriesByPosition = new Map<string, PersonEntry[]>();
    const usedSheetNames = new Set<string>();

    entries.forEach((person) => {
        const positionEntries = entriesByPosition.get(person.jabatan) || [];

        positionEntries.push(person);
        entriesByPosition.set(person.jabatan, positionEntries);
    });

    entriesByPosition.forEach((positionEntries, position) => {
        const rows = positionEntries
            .flatMap((person) =>
                person.penugasan.map((reviewer) => ({
                    'Nama OS': person.name,
                    'Jabatan OS': person.jabatan,
                    'Nama Evaluator': reviewer.nama?.trim() || '-',
                    'Type Evaluator': formatEvaluatorType(
                        reviewer.tipe_penilai,
                    ),
                    'Kekuatan Teramati':
                        normalizeFeedback(reviewer.kekuatan_teramati) || '-',
                    'Area Pengembangan':
                        normalizeFeedback(reviewer.area_pengembangan) || '-',
                    Catatan: normalizeFeedback(reviewer.catatan) || '-',
                })),
            )
            .map((row, index) => ({ No: index + 1, ...row }));

        const worksheet = XLSX.utils.json_to_sheet(rows, {
            header: [
                'No',
                'Nama OS',
                'Jabatan OS',
                'Nama Evaluator',
                'Type Evaluator',
                'Kekuatan Teramati',
                'Area Pengembangan',
                'Catatan',
            ],
        });
        const baseSheetName =
            position.replace(/[\\/?*:[\]]/g, '-').trim() || 'Tanpa Jabatan';
        let sheetName = baseSheetName.slice(0, 31);
        let suffix = 2;

        while (usedSheetNames.has(sheetName)) {
            const suffixText = ` (${suffix})`;
            sheetName = `${baseSheetName.slice(0, 31 - suffixText.length)}${suffixText}`;
            suffix += 1;
        }

        usedSheetNames.add(sheetName);
        XLSX.utils.book_append_sheet(workbook, worksheet, sheetName);
    });

    XLSX.writeFile(workbook, 'umpan-balik-outsourcing.xlsx');
};

const feedbackStyles: Record<
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

function FeedbackBlock({
    tone,
    value,
}: {
    tone: FeedbackTone;
    value: FeedbackValue;
}) {
    const style = feedbackStyles[tone];
    const Icon = style.icon;
    const content = normalizeFeedback(value);

    return (
        <div
            className={`rounded-2xl border p-4 transition-colors ${style.wrapper}`}
        >
            <div className="flex items-start gap-3">
                <div
                    className={`mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-xl ${style.iconWrapper}`}
                >
                    <Icon className={`h-4 w-4 ${style.iconClass}`} />
                </div>

                <div className="min-w-0 flex-1">
                    <p
                        className={`text-[11px] font-bold tracking-[0.08em] uppercase ${style.titleClass}`}
                    >
                        {style.label}
                    </p>

                    {content ? (
                        <p className="mt-2 text-sm leading-6 whitespace-pre-line text-slate-700 dark:text-slate-300">
                            {content}
                        </p>
                    ) : (
                        <p className="mt-1.5 text-sm text-slate-400 italic dark:text-slate-500">
                            Belum ada masukan.
                        </p>
                    )}
                </div>
            </div>
        </div>
    );
}

function ReviewerCard({ reviewer }: { reviewer: Penugasan }) {
    const reviewerHasFeedback = hasAnyFeedback(reviewer);

    return (
        <div className="rounded-2xl border border-slate-200/80 bg-slate-50/70 p-4 transition-all duration-200 hover:border-slate-300 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900/40 dark:hover:border-slate-700">
            <div className="flex items-center gap-3">
                <Avatar className="h-11 w-11 shrink-0 border-2 border-white shadow-sm dark:border-slate-800">
                    <AvatarImage
                        src={getImageSrc(reviewer.image)}
                        alt={reviewer.nama || 'Foto penilai'}
                    />
                    <AvatarFallback className="bg-slate-200 text-xs font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                        {getInitials(reviewer.nama)}
                    </AvatarFallback>
                </Avatar>

                <div className="min-w-0 flex-1">
                    <p className="truncate text-sm font-semibold text-slate-900 dark:text-slate-100">
                        {reviewer.nama || 'Penilai'}
                    </p>

                    <div className="mt-1 flex flex-wrap items-center gap-2">
                        <span className="inline-flex items-center rounded-full border border-slate-200 bg-white px-2.5 py-1 text-[11px] font-medium text-slate-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">
                            {formatEvaluatorType(reviewer.tipe_penilai)}
                        </span>

                        {reviewerHasFeedback && (
                            <span className="inline-flex items-center gap-1 text-[11px] font-medium text-emerald-700 dark:text-emerald-400">
                                <UserRoundCheck className="h-3.5 w-3.5" />
                                Masukan tersedia
                            </span>
                        )}
                    </div>
                </div>
            </div>

            {reviewerHasFeedback ? (
                <div className="mt-4 grid gap-3">
                    <FeedbackBlock
                        tone="strength"
                        value={reviewer.kekuatan_teramati}
                    />
                    <FeedbackBlock
                        tone="development"
                        value={reviewer.area_pengembangan}
                    />
                    <FeedbackBlock tone="note" value={reviewer.catatan} />
                </div>
            ) : (
                <div className="mt-4 flex items-start gap-2 rounded-xl border border-dashed border-slate-200 bg-white/70 px-3.5 py-3 dark:border-slate-800 dark:bg-slate-950/40">
                    <MessageSquareText className="mt-0.5 h-4 w-4 shrink-0 text-slate-400" />
                    <p className="text-sm leading-5 text-slate-500 dark:text-slate-400">
                        Belum memberikan masukan tertulis.
                    </p>
                </div>
            )}
        </div>
    );
}

function PersonCard({ person }: { person: PersonEntry }) {
    const reviewerCount = person.penugasan.length;
    const feedbackCount = person.penugasan.filter(hasAnyFeedback).length;

    return (
        <Card className="gap-0 overflow-hidden rounded-3xl border-slate-200/80 bg-white p-0 shadow-[0_18px_50px_-32px_rgba(15,23,42,0.35)] transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_24px_60px_-34px_rgba(15,23,42,0.42)] dark:border-slate-800 dark:bg-slate-950">
            <div className="border-b border-slate-100 bg-gradient-to-br from-white via-white to-sky-50/70 p-5 dark:border-slate-800 dark:from-slate-950 dark:via-slate-950 dark:to-sky-950/20">
                <div className="flex items-start gap-4">
                    <Avatar className="h-14 w-14 shrink-0 border-2 border-white shadow-md ring-1 ring-slate-200/80 dark:border-slate-900 dark:ring-slate-700">
                        <AvatarImage
                            src={getImageSrc(person.image)}
                            alt={person.name}
                        />
                        <AvatarFallback className="bg-gradient-to-br from-sky-100 to-indigo-100 text-sm font-bold text-slate-700 dark:from-sky-950 dark:to-indigo-950 dark:text-slate-200">
                            {getInitials(person.name)}
                        </AvatarFallback>
                    </Avatar>

                    <div className="min-w-0 flex-1">
                        <h3 className="text-base leading-6 font-bold text-slate-950 dark:text-white">
                            {person.name}
                        </h3>

                        <div className="mt-1 flex items-center gap-1.5 text-sm text-slate-500 dark:text-slate-400">
                            <BriefcaseBusiness className="h-3.5 w-3.5 shrink-0" />
                            <span>{person.jabatan}</span>
                        </div>
                    </div>

                    <div className="hidden shrink-0 sm:block">
                        <div className="rounded-2xl border border-slate-200/80 bg-white/90 px-3 py-2 text-center shadow-sm dark:border-slate-800 dark:bg-slate-900/80">
                            <p className="text-lg leading-5 font-bold text-slate-900 dark:text-white">
                                {reviewerCount}
                            </p>
                            <p className="mt-0.5 text-[10px] font-medium tracking-wide text-slate-500 uppercase dark:text-slate-400">
                                Penilai
                            </p>
                        </div>
                    </div>
                </div>

                <div className="mt-4 flex flex-wrap items-center gap-2">
                    <span className="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600 dark:bg-slate-900 dark:text-slate-300">
                        <UsersRound className="h-3.5 w-3.5" />
                        {reviewerCount} penilai
                    </span>

                    <span
                        className={`inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium ${
                            feedbackCount > 0
                                ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400'
                                : 'bg-slate-100 text-slate-500 dark:bg-slate-900 dark:text-slate-400'
                        }`}
                    >
                        <MessageSquareText className="h-3.5 w-3.5" />
                        {feedbackCount} masukan
                    </span>
                </div>
            </div>

            <div className="space-y-3 p-4 sm:p-5">
                {reviewerCount > 0 ? (
                    person.penugasan.map((reviewer, index) => (
                        <ReviewerCard
                            key={`${person.id}-${reviewer.nama || 'reviewer'}-${index}`}
                            reviewer={reviewer}
                        />
                    ))
                ) : (
                    <div className="flex min-h-28 flex-col items-center justify-center rounded-2xl border border-dashed border-slate-200 bg-slate-50/60 px-5 py-6 text-center dark:border-slate-800 dark:bg-slate-900/30">
                        <MessageSquareText className="h-5 w-5 text-slate-400" />
                        <p className="mt-2 text-sm font-medium text-slate-600 dark:text-slate-300">
                            Belum ada data penilaian
                        </p>
                        <p className="mt-1 text-xs text-slate-400 dark:text-slate-500">
                            Masukan dari penilai akan tampil di sini.
                        </p>
                    </div>
                )}
            </div>
        </Card>
    );
}

export default function SaranPerbaikan({
    Outsourcings = [],
    allJabatan = [],
    selectedJabatanId: initialSelectedJabatanId,
}: SaranPerbaikanProps) {
    const [isLoading, setIsLoading] = useState(false);

    const positions = useMemo(() => {
        if (Array.isArray(allJabatan) && allJabatan.length > 0) {
            return allJabatan.map((jabatan) => ({
                value: String(jabatan.id),
                label: jabatan.nama_jabatan,
            }));
        }

        // Fallback: extract from current data
        if (!Array.isArray(Outsourcings)) return [];

        const uniquePositions = new Map<string, string>();

        Outsourcings.forEach((group) => {
            const jabatan = group?.jabatan?.trim();

            if (!jabatan) return;

            uniquePositions.set(slugify(jabatan), jabatan);
        });

        return Array.from(uniquePositions, ([value, label]) => ({
            value,
            label,
        }));
    }, [allJabatan, Outsourcings]);

    const [selectedJabatanId, setSelectedJabatanId] = useState(
        initialSelectedJabatanId ?? positions?.[0]?.value ?? '',
    );

    const entries = useMemo<PersonEntry[]>(() => {
        if (!Array.isArray(Outsourcings)) return [];

        return Outsourcings.flatMap((group) => {
            const jabatan = group?.jabatan?.trim() || 'Tanpa Jabatan';
            const evaluators = Array.isArray(group?.evaluators)
                ? group.evaluators
                : [];

            return evaluators.map((evaluator, index) => ({
                id:
                    evaluator.id?.toString() ||
                    `${slugify(jabatan)}-${slugify(evaluator.name || '')}-${index}`,
                name: evaluator.name?.trim() || 'Tanpa Nama',
                image: evaluator.image,
                jabatan,
                penugasan: Array.isArray(evaluator.penugasan)
                    ? evaluator.penugasan
                    : [],
            }));
        });
    }, [Outsourcings]);

    const selectedJabatanLabel =
        positions.find((position) => position.value === selectedJabatanId)
            ?.label || 'Semua Jabatan';

    const handleJabatanChange = (jabatanId: string) => {
        setSelectedJabatanId(jabatanId);
        setIsLoading(true);

        // Fetch data dengan jabatan_id
        router.visit(saranEvaluator.url() + `?jabatan_id=${jabatanId}`, {
            onFinish: () => {
                setIsLoading(false);
            },
        });
    };

    const totalReviewers = entries.reduce(
        (total, person) => total + person.penugasan.length,
        0,
    );

    return (
        <div className="space-y-5">
            <section className="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-gradient-to-br from-white via-sky-50/60 to-indigo-50/70 p-6 shadow-[0_18px_60px_-38px_rgba(37,99,235,0.45)] sm:p-7 dark:border-slate-800 dark:from-slate-950 dark:via-slate-950 dark:to-indigo-950/30">
                <div className="pointer-events-none absolute -top-24 -right-20 h-56 w-56 rounded-full bg-sky-200/30 blur-3xl dark:bg-sky-700/10" />
                <div className="pointer-events-none absolute -bottom-28 left-1/3 h-52 w-52 rounded-full bg-indigo-200/30 blur-3xl dark:bg-indigo-700/10" />

                <div className="relative flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                    {/* Kiri */}
                    <div className="max-w-2xl">
                        <div className="mb-3 inline-flex items-center gap-2 rounded-full border border-sky-200/70 bg-white/80 px-3 py-1.5 text-xs font-semibold text-sky-700 shadow-sm backdrop-blur dark:border-sky-900 dark:bg-slate-900/70 dark:text-sky-300">
                            <Sparkles className="h-3.5 w-3.5" />
                            Evaluasi & Pengembangan
                        </div>

                        <h1 className="text-2xl font-bold tracking-tight text-slate-950 sm:text-3xl dark:text-white">
                            Umpan Balik Outsourcing
                        </h1>

                        <p className="mt-2 max-w-xl text-sm leading-6 text-slate-600 sm:text-[15px] dark:text-slate-400">
                            Lihat kekuatan yang teramati, area pengembangan, dan
                            catatan dari setiap penilai dalam satu tampilan yang
                            lebih ringkas dan mudah dibaca.
                        </p>
                    </div>

                    {/* Kanan */}
                    <div className="flex shrink-0 flex-col items-end gap-6">
                        <Button
                            type="button"
                            onClick={() => exportFeedbackToExcel(entries)}
                            className="gap-2 rounded-xl bg-[#217346] px-4 py-2.5 font-semibold text-white shadow-sm transition-all hover:bg-[#185c37] hover:shadow-md"
                        >
                            <FileSpreadsheet className="h-4 w-4" />
                            Export
                        </Button>

                        <div className="flex gap-2">
                            <div className="min-w-28 rounded-2xl border border-white/80 bg-white/75 px-4 py-3 shadow-sm backdrop-blur dark:border-slate-800 dark:bg-slate-900/70">
                                <p className="text-xl font-bold text-slate-950 dark:text-white">
                                    {entries.length}
                                </p>
                                <p className="mt-0.5 text-[11px] font-medium text-slate-500 dark:text-slate-400">
                                    Pegawai
                                </p>
                            </div>

                            <div className="min-w-28 rounded-2xl border border-white/80 bg-white/75 px-4 py-3 shadow-sm backdrop-blur dark:border-slate-800 dark:bg-slate-900/70">
                                <p className="text-xl font-bold text-slate-950 dark:text-white">
                                    {totalReviewers}
                                </p>
                                <p className="mt-0.5 text-[11px] font-medium text-slate-500 dark:text-slate-400">
                                    Penilai
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section className="rounded-3xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-5 dark:border-slate-800 dark:bg-slate-950">
                <div className="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                    <div className="w-full md:max-w-sm">
                        <label className="mb-2 block text-xs font-semibold tracking-wide text-slate-500 uppercase dark:text-slate-400">
                            Pilih Jabatan
                        </label>

                        <div className="flex items-center gap-2">
                            <Select
                                value={selectedJabatanId}
                                onValueChange={handleJabatanChange}
                                disabled={positions.length === 0 || isLoading}
                            >
                                <SelectTrigger className="h-11 w-full rounded-xl border-slate-200 bg-slate-50/80 px-3.5 shadow-none focus:ring-2 focus:ring-sky-500/20 dark:border-slate-800 dark:bg-slate-900">
                                    <SelectValue placeholder="Pilih Jabatan Outsourcing" />
                                </SelectTrigger>

                                <SelectContent>
                                    {positions.map((position) => (
                                        <SelectItem
                                            key={position.value}
                                            value={position.value}
                                        >
                                            {position.label}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                            {isLoading && (
                                <Loader2 className="h-4 w-4 animate-spin text-muted-foreground" />
                            )}
                        </div>
                    </div>

                    <div className="flex min-w-0 items-center gap-3 rounded-2xl bg-slate-50 px-4 py-3 dark:bg-slate-900/70">
                        <div className="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white shadow-sm dark:bg-slate-800">
                            <BriefcaseBusiness className="h-4 w-4 text-sky-600 dark:text-sky-400" />
                        </div>

                        <div className="min-w-0">
                            <p className="text-[11px] font-medium text-slate-400 uppercase">
                                Ditampilkan
                            </p>
                            <p className="truncate text-sm font-semibold text-slate-700 dark:text-slate-200">
                                {selectedJabatanLabel}
                            </p>
                        </div>
                    </div>
                </div>
            </section>
            {entries.length > 0 ? (
                <section className="grid grid-cols-1 gap-5 lg:grid-cols-2">
                    {entries.map((person) => (
                        <PersonCard key={person.id} person={person} />
                    ))}
                </section>
            ) : (
                <section className="rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center dark:border-slate-800 dark:bg-slate-950">
                    <div className="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-900">
                        <UsersRound className="h-5 w-5 text-slate-400" />
                    </div>

                    <h2 className="mt-4 text-sm font-semibold text-slate-800 dark:text-slate-200">
                        Data belum tersedia
                    </h2>

                    <p className="mx-auto mt-1 max-w-sm text-sm leading-6 text-slate-500 dark:text-slate-400">
                        Belum ada data outsourcing untuk jabatan yang dipilih.
                    </p>
                </section>
            )}
        </div>
    );
}
