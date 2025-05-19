import { createContext, useContext, useState } from 'react';

const ModalContext = createContext();

export const ModalProvider = ({ children }) => {
    const [isAccountModalOpen, setIsAccountModalOpen] = useState(false);
    const [modalMode, setModalMode] = useState('login');

    const [isRecipeModalOpen, setIsRecipeModalOpen] = useState(false);
    const [selectedRecipe, setSelectedRecipe] = useState(null);

    const openAccountModal = (mode = 'login') => {
        setModalMode(mode);
        setIsAccountModalOpen(true);
    };

    const openRecipeModal = (recipe) => {
        setSelectedRecipe(recipe);
        setIsRecipeModalOpen(true);
    };

    const closeRecipeModal = () => {
        setSelectedRecipe(null);
        setIsRecipeModalOpen(false);
    };

    return (
        <ModalContext.Provider value={{
            isAccountModalOpen,
            setIsAccountModalOpen,
            modalMode,
            setModalMode,
            openAccountModal,
            isRecipeModalOpen,
            selectedRecipe,
            openRecipeModal,
            closeRecipeModal
        }}>
            {children}
        </ModalContext.Provider>
    );
};

export const useModals = () => useContext(ModalContext);
