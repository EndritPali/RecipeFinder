import { useMemo, useState } from 'react';
import useBatchSize from '../hooks/useBatchSize';
import { useFetchRecipes } from '../hooks/useFetchRecipes';
import { Skeleton } from 'antd';
import RecipeBanner from "../Templates/RecipeBanner";
import '../Scss/RecipeGrid.scss';
import RecipeDetailsModal from '../Templates/RecipeDetailsModal';
import { useUserAccount } from '../hooks/useUserAccount';
import AccountModal from '../Templates/AccountModal';

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
                <div className="recipe-grid__header">
                    <h3>with benefits</h3>
                    <div className="recipe-grid__pagination">
                        {hasPrev && (
                            <a href="#" onClick={handlePrev} className="recipe-grid__header-link">See less</a>
                        )}
                        {hasNext && (
                            <a href="#" onClick={handleNext} className="recipe-grid__header-link">See all</a>
                        )}
                    </div>
                </div>


                <div className="recipe-grid__wrapper">
                    <div className="recipe-grid__col recipe-grid__col--first">
                        {loading ? skeletonItems : displayedRecipes.map(recipe => (
                            <RecipeBanner
                                background={recipe.image}
                                key={recipe.key}
                                header={recipe.recipetitle}
                                subheader={recipe.shortdescription}
                                rating={recipe.rating}
                            />
                        ))}
                    </div>

                    <div className="recipe-grid__col recipe-grid__col--second">
                        <div className="recipe-grid__card recipe-grid__card--first">
                            <h2>
                                {user
                                    ? 'Upload your unique recipes now! Like a real master chef'
                                    : 'Learn how to become a master chef right now!'}
                            </h2>
                            {user ? (
                                <a href="/admin">
                                    <button>Dashboard</button>
                                </a>
                            ) : (
                                <button onClick={() => openAccountModal('login')}>Login</button>
                            )}
                        </div>

                        {randomRecipe && (
                            <div
                                className="recipe-grid__card recipe-grid__card--second"
                                style={{
                                    backgroundImage: `url(${randomRecipe.image})`,
                                    backgroundSize: 'cover',
                                    backgroundPosition: 'center',
                                }}
                            >
                                <div className="recipe-grid__wishlist-bar">
                                    <i className="far fa-heart recipe-grid__wishlist-bar-icon"></i>
                                    <button onClick={() => handleOpenModal(randomRecipe)}>Start cook</button>
                                </div>
                                <div className="recipe-grid__col-info">
                                    <h2>Weekly pick</h2>
                                    <p>{randomRecipe.shortdescription}</p>
                                </div>
                            </div>
                        )}
                    </div>
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
