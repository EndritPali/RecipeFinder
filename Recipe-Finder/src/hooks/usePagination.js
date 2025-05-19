import { useState, useMemo } from 'react';

export default function usePagination(items = [], itemsPerPage) {
    const [currentPage, setCurrentPage] = useState(0);

    const totalItems = items.length;
    const totalPages = Math.ceil(totalItems / itemsPerPage);

    const paginatedItems = useMemo(() => {
        const startIndex = itemsPerPage === Infinity ? 0 : currentPage * itemsPerPage;
        const endIndex = itemsPerPage === Infinity ? totalItems : startIndex + itemsPerPage;
        return items.slice(startIndex, endIndex);
    }, [items, currentPage, itemsPerPage, totalItems]);

    const hasNext = currentPage < totalPages - 1;
    const hasPrev = currentPage > 0;

    const nextPage = () => hasNext && setCurrentPage(prev => prev + 1);
    const prevPage = () => hasPrev && setCurrentPage(prev => prev - 1);

    return {
        paginatedItems,
        currentPage,
        totalPages,
        hasNext,
        hasPrev,
        nextPage,
        prevPage,
        setCurrentPage
    };
}