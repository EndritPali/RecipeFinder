import { useMemo, useState } from 'react';
import useBatchSize from '../../hooks/useBatchSize';
import { useFetchRecipes } from '../../hooks/useFetchRecipes';
import { useUserAccount } from '../../hooks/useUserAccount';
import RecipeDetailsModal from '../../Templates/RecipeDetailsModal';
import { Skeleton } from 'antd';
import AccountModal from '../../Templates/AccountModal';
import '../../Scss/RecipeGrid.scss';
import RecipeGridHeader from './RecipeGridHeader';
import RecipeGridColumnLeft from './RecipeGridColumnLeft';
import RecipeGridColumnRight from './RecipeGridColumnRight';

export default function RecipeGrid() {
    const { loading, recipes } = useFetchRecipes();
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedRecipe, setSelectedRecipe] = useState(null);
    const batchSize = useBatchSize();
    const [currentPage, setCurrentPage] = useState(0);

    const filteredRecipes = useMemo(() =>
        recipes.filter(recipe => recipe.category === 'With benefits'),
        [recipes]
    );

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

    const handleOpenModal = recipe => {
        setSelectedRecipe(recipe);
        setIsModalOpen(true);
    };

    const handleCloseModal = () => {
        setIsModalOpen(false);
        setSelectedRecipe(null);
    };

    const skeletonItems = Array.from({ length: batchSize }).map((_, index) => (
        <div key={index} style={{ width: 550, margin: '0 1rem' }}>
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
                        loading={loading}
                        skeletonItems={skeletonItems}
                        displayedRecipes={displayedRecipes}
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
