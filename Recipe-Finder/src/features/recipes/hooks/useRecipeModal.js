import { useState } from 'react';

export default function useRecipeModal() {
    const [isRecipeModalOpen, setIsRecipeModalOpen] = useState(false);
    const [selectedRecipe, setSelectedRecipe] = useState(null);

    const handleOpenRecipeModal = (recipe) => {
        setSelectedRecipe(recipe);
        setIsRecipeModalOpen(true);
    };

    const handleCloseRecipeModal = () => {
        setIsRecipeModalOpen(false);
        setSelectedRecipe(null);
    };

    const handleRollDice = (recipes) => {
        if (!recipes?.length) return;
        const random = recipes[Math.floor(Math.random() * recipes.length)];
        handleOpenRecipeModal(random);
    };

    return {
        isRecipeModalOpen,
        selectedRecipe,
        handleOpenRecipeModal,
        handleCloseRecipeModal,
        handleRollDice
    };
}