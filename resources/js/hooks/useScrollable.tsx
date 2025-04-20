import { useState, useEffect } from 'react';

export const useScrollable = (
    containerId: string,
    minItems: number,
    items: any[]
) => {
    const [canScroll, setCanScroll] = useState(false);

    useEffect(() => {
        const checkScrollable = () => {
            const container = document.getElementById(containerId);
            if (!container) return;

            const hasMinItems = items.length >= minItems;
            const hasOverflow = container.scrollWidth > container.clientWidth;

            setCanScroll(hasMinItems && hasOverflow);
        };

        checkScrollable();
        window.addEventListener('resize', checkScrollable);

        return () => {
            window.removeEventListener('resize', checkScrollable);
        };
    }, [containerId, minItems, items]);

    return canScroll;
};