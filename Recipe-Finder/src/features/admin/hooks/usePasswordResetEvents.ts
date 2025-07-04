import { useEffect } from 'react';

export function usePasswordResetEvents(onPasswordResetRequested: () => void | Promise<void>, enabled: boolean = true) {
    useEffect(() => {
        if (!enabled || !window.Echo) return;

        const channel = window.Echo.channel('password-resets')
            .listen('.PasswordResetRequested', async () => {
                await onPasswordResetRequested();
            });

        return () => {
            window.Echo.leave('password-resets');
        };
    }, [onPasswordResetRequested, enabled]);
}