import { useState, useCallback, useMemo } from 'react';

export default function useAccountModal(externalSetIsOpen: any, externalSetMode: any) {
    const [isAccountModalOpenLocal, setIsAccountModalOpenLocal] = useState(false);
    const [modalModeLocal, setModalModeLocal] = useState('login');

    const openAccountModal = useCallback((mode: any) => {
        setModalModeLocal(mode);
        setIsAccountModalOpenLocal(true);
        if (externalSetMode && externalSetIsOpen) {
            externalSetMode(mode);
            externalSetIsOpen(true);
        }
    }, [externalSetIsOpen, externalSetMode]);

    const closeAccountModal = useCallback(() => {
        setIsAccountModalOpenLocal(false);
        if (externalSetIsOpen) {
            externalSetIsOpen(false);
        }
    }, [externalSetIsOpen]);

    return useMemo(() => ({
        isAccountModalOpen: externalSetIsOpen ? isAccountModalOpenLocal : isAccountModalOpenLocal,
        modalMode: externalSetMode ? modalModeLocal : modalModeLocal,
        openAccountModal,
        closeAccountModal
    }), [isAccountModalOpenLocal, modalModeLocal, openAccountModal, closeAccountModal, externalSetIsOpen, externalSetMode])}