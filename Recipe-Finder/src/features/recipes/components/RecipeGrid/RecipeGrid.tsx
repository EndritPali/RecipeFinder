import { useMemo, useState, useCallback, useEffect } from 'react';
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
import { Recipe as RecipeType, MappedRecipe } from '@/types/recipe';
import { useCheckIfSaved } from '../../hooks/useSavedRecipes';
import { useAuth } from '@/context/AuthContext';
import { AccountModalMode } from '@/types/auth';

export default function RecipeGrid() {
    const { isLoading, data } = useFetchRecipes({ paginate: false });
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedRecipe, setSelectedRecipe] = useState<RecipeType | null>(null);
    const batchSize = useBatchSize();
    const [currentPage, setCurrentPage] = useState(0);

    const filteredRecipes: MappedRecipe[] = useMemo(() => {
        const recipes = data?.recipes || [];
        return recipes.filter((recipe: MappedRecipe) => recipe.category === 'With Benefits');
    }, [data]);

    const startIndex = batchSize === Infinity ? 0 : currentPage * batchSize;
    const endIndex = batchSize === Infinity ? filteredRecipes.length : startIndex + batchSize;
    const displayedRecipes = filteredRecipes.slice(startIndex, endIndex);

    const hasNext = endIndex < filteredRecipes.length;
    const hasPrev = currentPage > 0;

    const handleNext = (e: React.MouseEvent<HTMLAnchorElement>) => {
        e.preventDefault();
        if (hasNext) setCurrentPage(prev => prev + 1);
    };

    const handlePrev = (e: React.MouseEvent<HTMLAnchorElement>) => {
        e.preventDefault();
        if (hasPrev) setCurrentPage(prev => prev - 1);
    };

    const handleOpenModal = useCallback((recipe: RecipeType) => {
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
    const [modalMode, setModalMode] = useState<AccountModalMode>('login');
    const { user, openAccountModal } = useUserAccount(setModalMode, setIsAccountModalOpen);
    const [randomRecipe, setRandomRecipe] = useState<MappedRecipe | null>(null);

    useEffect(() => {
        if (!filteredRecipes.length) return;

        const pickRandom = () => {
            const random = filteredRecipes[Math.floor(Math.random() * filteredRecipes.length)];
            setRandomRecipe(random);
        };

        pickRandom();
        const interval = setInterval(pickRandom, 30000);

        return () => clearInterval(interval);
    }, [filteredRecipes])

    const auth = useAuth();
    const isAuthenticated = auth?.isAuthenticated ?? false;
    const recipeId = randomRecipe?.key;

    const { data: isSaved } = useCheckIfSaved(recipeId, {
        enabled: !!recipeId && !!randomRecipe && isAuthenticated,
    });

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
                        isSaved={isSaved}
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
