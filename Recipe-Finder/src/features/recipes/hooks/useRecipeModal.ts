import { useState } from 'react';
import { MappedRecipe } from '@/types/recipe';

export default function useRecipeModal() {
    const [isRecipeModalOpen, setIsRecipeModalOpen] = useState(false);
    const [selectedRecipe, setSelectedRecipe] = useState<MappedRecipe | null>(null);

    const handleOpenRecipeModal = (recipe: MappedRecipe) => {
        setSelectedRecipe(recipe);
        setIsRecipeModalOpen(true);
    };

    const handleCloseRecipeModal = () => {
        setIsRecipeModalOpen(false);
        setSelectedRecipe(null);
    };

    const handleRollDice = (recipes: MappedRecipe[]) => {
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