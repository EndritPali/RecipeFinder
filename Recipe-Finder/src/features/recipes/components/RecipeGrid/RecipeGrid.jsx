import { useMemo, useState, useCallback } from 'react';
import useBatchSize from '@/lib/hooks/useBatchSize';
import { useFetchRecipes } from "@/features/recipes/hooks/useFetchRecipes";
import { useUserAccount } from '@/features/auth/hooks/useUserAccount';
import RecipeDetailsModal from '@/features/recipes/components/RecipeDetailsModal';
import { Skeleton } from 'antd';
import AccountModal from '@/features/auth/components/AccountModal';
import '@/Scss/RecipeGrid.scss';
import RecipeGridHeader from '@/features/recipes/components/RecipeGrid/RecipeGridHeader';
import RecipeGridColumnLeft from './RecipeGridColumnLeft';
import RecipeGridColumnRight from '@/features/recipes/components/RecipeGrid/RecipeGridColumnRight';

export default function RecipeGrid() {
    const { isLoading, data } = useFetchRecipes({ paginate: false });
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedRecipe, setSelectedRecipe] = useState(null);
    const batchSize = useBatchSize();
    const [currentPage, setCurrentPage] = useState(0);

    const filteredRecipes = useMemo(() => {
        const recipes = data?.recipes || [];
        return recipes.filter(recipe => recipe.category === 'With Benefits');
    }, [data]);

    const startIndex = batchSize === Infinity ? 0 : currentPage * batchSize;
    const endIndex = batchSize === Infinity ? filteredRecipes.length : startIndex + batchSize;
    const displayedRecipes = filteredRecipes.slice(startIndex, endIndex);

    const hasNext = endIndex < filteredRecipes.length;
    const hasPrev = currentPage > 0;

    const handleNext = (e) => {
        e.preventDefault();
        if (hasNext) setCurrentPage(prev => prev + 1);
    };

    const handlePrev = (e) => {
        e.preventDefault();
        if (hasPrev) setCurrentPage(prev => prev - 1);
    };

    const handleOpenModal = useCallback(recipe => {
        setSelectedRecipe(recipe);
        setIsModalOpen(true);
    }, []);

    const handleCloseModal = () => {
        setIsModalOpen(false);
        setSelectedRecipe(null);
    };

    const skeletonItems = Array.from({ length: batchSize }).map((_, index) => (
        <div className='skeleton-container' key={index} style={{ width: 550, margin: '0 1rem' }}>
            <Skeleton active paragraph={{ rows: 3 }} />
        </div>
    ));

    const [isAccountModalOpen, setIsAccountModalOpen] = useState(false);
    const [modalMode, setModalMode] = useState('login');
    const { user, openAccountModal } = useUserAccount(setModalMode, setIsAccountModalOpen);

    const randomRecipe = useMemo(() => {
        if (!filteredRecipes.length) return null;
        return filteredRecipes[Math.floor(Math.random() * filteredRecipes.length)];
    }, [filteredRecipes]);

    return (
        <>
            <div className="recipe-grid">
                <RecipeGridHeader
                    hasPrev={hasPrev}
                    hasNext={hasNext}
                    handlePrev={handlePrev}
                    handleNext={handleNext}
                />

                <div className="recipe-grid__wrapper">
                    <RecipeGridColumnLeft
                        loading={isLoading}
                        skeletonItems={skeletonItems}
                        displayedRecipes={displayedRecipes}
                        handleOpenModal={handleOpenModal}
                    />
                    <RecipeGridColumnRight
                        user={user}
                        openAccountModal={openAccountModal}
                        randomRecipe={randomRecipe}
                        handleOpenModal={handleOpenModal}
                    />
                </div>
            </div>

            <RecipeDetailsModal
                open={isModalOpen}
                onOk={handleCloseModal}
                onCancel={handleCloseModal}
                recipe={selectedRecipe}
            />

            <AccountModal
                open={isAccountModalOpen}
                onOk={() => setIsAccountModalOpen(false)}
                onCancel={() => setIsAccountModalOpen(false)}
                mode={modalMode}
            />
        </>
    );
}
