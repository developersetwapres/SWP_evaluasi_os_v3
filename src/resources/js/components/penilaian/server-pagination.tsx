import {
    ChevronLeft,
    ChevronRight,
    ChevronsLeft,
    ChevronsRight,
} from 'lucide-react';
import { Button } from '@/components/ui/button';

type PaginationMeta = {
    current_page: number;
    from: number | null;
    last_page: number;
    to: number | null;
    total: number;
};

export function ServerPagination({
    meta,
    onPageChange,
    disabled = false,
}: {
    meta: PaginationMeta;
    onPageChange: (page: number) => void;
    disabled?: boolean;
}) {
    const currentPage = meta?.current_page ?? 1;
    const totalPages = meta?.last_page ?? 1;
    const totalItems = meta?.total ?? 0;
    const from = meta?.from ?? 0;
    const to = meta?.to ?? 0;

    const getPageNumbers = () => {
        const pages: (number | string)[] = [];
        const maxVisiblePages = 5;

        if (totalPages <= maxVisiblePages) {
            for (let page = 1; page <= totalPages; page++) {
                pages.push(page);
            }
        } else if (currentPage <= 3) {
            for (let page = 1; page <= 4; page++) {
                pages.push(page);
            }

            pages.push('...', totalPages);
        } else if (currentPage >= totalPages - 2) {
            pages.push(1, '...');

            for (let page = totalPages - 3; page <= totalPages; page++) {
                pages.push(page);
            }
        } else {
            pages.push(1, '...');

            for (let page = currentPage - 1; page <= currentPage + 1; page++) {
                pages.push(page);
            }

            pages.push('...', totalPages);
        }

        return pages;
    };

    if (totalItems === 0) {
        return null;
    }

    return (
        <div className="mt-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div className="text-sm text-muted-foreground">
                Menampilkan{' '}
                <span className="font-medium text-foreground">{from}</span> -{' '}
                <span className="font-medium text-foreground">{to}</span> dari{' '}
                <span className="font-medium text-foreground">
                    {totalItems}
                </span>{' '}
                data
            </div>

            <div className="flex items-center gap-1">
                <Button
                    variant="outline"
                    size="icon"
                    className="h-8 w-8 bg-transparent"
                    onClick={() => onPageChange(1)}
                    disabled={disabled || currentPage === 1}
                >
                    <ChevronsLeft className="h-4 w-4" />
                    <span className="sr-only">Halaman pertama</span>
                </Button>
                <Button
                    variant="outline"
                    size="icon"
                    className="h-8 w-8 bg-transparent"
                    onClick={() => onPageChange(currentPage - 1)}
                    disabled={disabled || currentPage === 1}
                >
                    <ChevronLeft className="h-4 w-4" />
                    <span className="sr-only">Halaman sebelumnya</span>
                </Button>

                <div className="flex items-center gap-1">
                    {getPageNumbers().map((page, index) =>
                        page === '...' ? (
                            <span
                                key={`ellipsis-${index}`}
                                className="px-2 text-muted-foreground"
                            >
                                ...
                            </span>
                        ) : (
                            <Button
                                key={page}
                                variant={
                                    currentPage === page ? 'default' : 'outline'
                                }
                                size="icon"
                                className="h-8 w-8"
                                onClick={() => onPageChange(page as number)}
                                disabled={disabled}
                            >
                                {page}
                            </Button>
                        ),
                    )}
                </div>

                <Button
                    variant="outline"
                    size="icon"
                    className="h-8 w-8 bg-transparent"
                    onClick={() => onPageChange(currentPage + 1)}
                    disabled={disabled || currentPage === totalPages}
                >
                    <ChevronRight className="h-4 w-4" />
                    <span className="sr-only">Halaman selanjutnya</span>
                </Button>
                <Button
                    variant="outline"
                    size="icon"
                    className="h-8 w-8 bg-transparent"
                    onClick={() => onPageChange(totalPages)}
                    disabled={disabled || currentPage === totalPages}
                >
                    <ChevronsRight className="h-4 w-4" />
                    <span className="sr-only">Halaman terakhir</span>
                </Button>
            </div>
        </div>
    );
}
