import { useEffect, useCallback } from 'react';

export const useHorizontalScroll = (
    containerId: string,
    isEnabled: boolean,
    scrollSpeed: number = 1
) => {
    const handleWheel = useCallback((event: WheelEvent) => {
        const container = document.getElementById(containerId);
        if (!container || !isEnabled) return;

        event.preventDefault();

        const delta = event.deltaY || event.deltaX;
        const scrollAmount = delta * scrollSpeed;

        container.scrollBy({
            left: scrollAmount,
            behavior: 'smooth'
        });
    }, [containerId, isEnabled, scrollSpeed]);

    useEffect(() => {
        const container = document.getElementById(containerId);
        if (!container) return;

        container.addEventListener('wheel', handleWheel, { passive: false });

        return () => {
            container.removeEventListener('wheel', handleWheel);
        };
    }, [handleWheel]);
};