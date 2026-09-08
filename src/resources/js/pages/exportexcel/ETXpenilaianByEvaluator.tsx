'use client';

import { statuspenilaian } from '@/routes/penugasan';
import { router } from '@inertiajs/react';
import { useEffect, useRef } from 'react';
import * as XLSX from 'xlsx';

export default function ETXpenilaianByEvaluator({ evaluators }: any) {
    const downloaded = useRef(false);

    useEffect(() => {
        if (downloaded.current) return;
        downloaded.current = true;

        const formattedData = evaluators.map((item: any, index: number) => ({
            ID: index + 1,
            Evaluator: item.evaluator_name,
            'Tipe Penilai': item.tipe_penilai,
            Status: item.status,
            Outsourcing: item.outsourcing_name,
            Jabatan: item.outsourcing_jabatan,
        }));

        const worksheet = XLSX.utils.json_to_sheet(formattedData);
        const workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet1');

        XLSX.writeFile(workbook, 'status_penilaian_by_evaluator.xlsx');

        setTimeout(() => {
            router.get(statuspenilaian.url());
        }, 300);
    }, [evaluators]);

    // Page ini memang tidak render apa-apa
    return null;
}
