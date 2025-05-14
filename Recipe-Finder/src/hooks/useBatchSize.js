import { useEffect, useState } from 'react';

export default function useBatchPagination() {
    const [batchSize, setBatchSize] = useState(5);

    useEffect(() => {
        const updateBatchSize = () => {
            const width = window.innerWidth;
        if (width < 1024) setBatchSize(4); 
            else setBatchSize(5);                  
        };

        updateBatchSize();
        window.addEventListener('resize', updateBatchSize);
        return () => window.removeEventListener('resize', updateBatchSize);
    }, []);

    return batchSize;
}
