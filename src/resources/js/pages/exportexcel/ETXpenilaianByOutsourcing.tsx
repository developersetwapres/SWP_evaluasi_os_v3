'use client';

import { statuspenilaian } from '@/routes/penugasan';
import { router } from '@inertiajs/react';
import { useEffect, useRef } from 'react';
import * as XLSX from 'xlsx';

export default function ETXpenilaianByOutsourcing({ outsourcings }: any) {
    const downloaded = useRef(false);

    useEffect(() => {
        if (downloaded.current) return;
        downloaded.current = true;

        const formattedData = outsourcings.map((item: any, index: number) => ({
            ID: index + 1,
            Outsourcing: item.outsourcing_name,
            Jabatan: item.outsourcing_jabatan,
            'Evaluator Atasan': item.evaluatorsAtasan?.name || '-',
            'Status Evaluator Atasan': item.evaluatorsAtasan?.status || '-',
            'Evaluator Penerima Layanan':
                item.evaluatorsPenerimaLayanan?.name || '-',
            'Status Evaluator Penerima Layanan':
                item.evaluatorsPenerimaLayanan?.status || '-',
            'Evaluator Teman Setingkat':
                item.evaluatorsTemanSetingkat?.name || '-',
            'Status Evaluator Teman Setingkat':
                item.evaluatorsTemanSetingkat?.status || '-',
        }));

        const ws = XLSX.utils.json_to_sheet(formattedData);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'Sheet1');

        XLSX.writeFile(wb, 'status_penilaian_by_outsourcing.xlsx');

        // ⏱️ tunggu download mulai → balik
        setTimeout(() => {
            router.get(statuspenilaian.url());
        }, 300);
    }, [outsourcings]);

    return null; // ⬅️ page ini memang "silent worker"
}
