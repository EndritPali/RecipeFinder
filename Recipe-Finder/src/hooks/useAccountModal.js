import { useState } from 'react';

export default function useAccountModal(externalSetIsOpen, externalSetMode) {
    const [isAccountModalOpenLocal, setIsAccountModalOpenLocal] = useState(false);
    const [modalModeLocal, setModalModeLocal] = useState('login');

    const openAccountModal = (mode) => {
        setModalModeLocal(mode);
        setIsAccountModalOpenLocal(true);
        if (externalSetMode && externalSetIsOpen) {
            externalSetMode(mode);
            externalSetIsOpen(true);
        }
    };

    const closeAccountModal = () => {
        setIsAccountModalOpenLocal(false);
        if (externalSetIsOpen) {
            externalSetIsOpen(false);
        }
    };

    return {
        isAccountModalOpen: externalSetIsOpen ? isAccountModalOpenLocal : isAccountModalOpenLocal,
        modalMode: externalSetMode ? modalModeLocal : modalModeLocal,
        openAccountModal,
        closeAccountModal
    };
}