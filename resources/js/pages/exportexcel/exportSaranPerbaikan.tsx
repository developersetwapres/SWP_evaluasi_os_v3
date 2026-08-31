'use client';

import { useEffect, useRef } from 'react';
import * as XLSX from 'xlsx';

type FeedbackValue = string | null | undefined;

type Penugasan = {
    nama?: string | null;
    tipe_penilai?: string | null;
    catatan?: FeedbackValue;
    area_pengembangan?: FeedbackValue;
    kekuatan_teramati?: FeedbackValue;
};

type Evaluator = {
    name?: string | null;
    penugasan?: Penugasan[];
};

type OutsourcingGroup = {
    jabatan?: string | null;
    evaluators?: Evaluator[];
};

type PersonEntry = {
    name: string;
    jabatan: string;
    penugasan: Penugasan[];
};

const normalizeFeedback = (value: FeedbackValue) => {
    if (!value) {
        return null;
    }

    const normalized = value.trim();

    return normalized && normalized !== '-' ? normalized : null;
};

const formatEvaluatorType = (value?: string | null) => {
    if (!value) {
        return 'Penilai';
    }

    return value
        .replace(/_/g, ' ')
        .replace(/([a-zA-Z])(\d+)/g, '$1 $2')
        .replace(/\b\w/g, (char) => char.toUpperCase());
};

const sanitizeSheetName = (name: string, usedNames: Set<string>) => {
    const baseSheetName =
        name.replace(/[\\/?*:[\]]/g, '-').trim() || 'Tanpa Jabatan';
    let sheetName = baseSheetName.slice(0, 31);
    let suffix = 2;

    while (usedNames.has(sheetName)) {
        const suffixText = ` (${suffix})`;
        sheetName = `${baseSheetName.slice(0, 31 - suffixText.length)}${suffixText}`;
        suffix += 1;
    }

    usedNames.add(sheetName);

    return sheetName;
};

export default function ExportSaranPerbaikan({
    outsourcings = [],
}: {
    outsourcings: OutsourcingGroup[];
}) {
    const downloaded = useRef(false);

    useEffect(() => {
        if (downloaded.current) {
            return;
        }

        downloaded.current = true;

        const entries = outsourcings.flatMap((group): PersonEntry[] => {
            const jabatan = group.jabatan?.trim() || 'Tanpa Jabatan';

            return (group.evaluators ?? []).map((evaluator) => ({
                name: evaluator.name?.trim() || 'Tanpa Nama',
                jabatan,
                penugasan: evaluator.penugasan ?? [],
            }));
        });
        const entriesByPosition = new Map<string, PersonEntry[]>();

        for (const entry of entries) {
            const positionEntries = entriesByPosition.get(entry.jabatan) ?? [];

            positionEntries.push(entry);
            entriesByPosition.set(entry.jabatan, positionEntries);
        }

        const workbook = XLSX.utils.book_new();
        const usedSheetNames = new Set<string>();

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
                            normalizeFeedback(reviewer.kekuatan_teramati) ||
                            '-',
                        'Area Pengembangan':
                            normalizeFeedback(reviewer.area_pengembangan) ||
                            '-',
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

            XLSX.utils.book_append_sheet(
                workbook,
                worksheet,
                sanitizeSheetName(position, usedSheetNames),
            );
        });

        if (entriesByPosition.size === 0) {
            XLSX.utils.book_append_sheet(
                workbook,
                XLSX.utils.json_to_sheet([]),
                'Data Kosong',
            );
        }

        XLSX.writeFile(workbook, 'umpan-balik-outsourcing.xlsx');

        window.setTimeout(() => {
            window.history.back();
        }, 300);
    }, [outsourcings]);

    return null;
}
