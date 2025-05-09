import { useMemo, useState } from 'react';
import { useFetchRecipes } from '../hooks/useFetchRecipes';
import { Skeleton } from 'antd';
import RecipeBanner from "../Templates/RecipeBanner"
import '../Scss/Recipe-Grid.scss'
import RecipeDetailsModal from '../Templates/RecipeDetailsModal';

export default function RecipeGrid() {
    const { loading, recipes } = useFetchRecipes();

    const filteredRecipes = useMemo(() =>
        recipes.filter(recipe => recipe.category === 'With benefits'),
        [recipes]
    );

    const randomRecipe = useMemo(() => {
        if (filteredRecipes.length === 0) return null;
        return filteredRecipes[Math.floor(Math.random() * filteredRecipes.length)];
    }, [filteredRecipes]);

    const [isModalOpen, setIsModalOpen] = useState(false)
    const [selectedRecipe, setSelectedRecipe] = useState(null);

    const handleOpenModal = (recipe) => {
        setSelectedRecipe(recipe);
        setIsModalOpen(true);
    };

    const handleCloseModal = () => {
        setIsModalOpen(false);
        setSelectedRecipe(null);
    };

    return (
        <>
            <div className="recipe-grid">
                <div className="recipe-grid__header">
                    <div className="recipe-grid__header-primary">
                        <h3>with benefits</h3>
                    </div>
                    <div className="recipe-grid__header-link">
                        <a href="#">See all</a>
                    </div>
                </div>

                <div className="recipe-grid__wrapper">
                    <div className="recipe-grid__col recipe-grid__col--first">
                        {loading
                            ? Array.from({ length: 5 }).map((_, index) => (
                                <div key={index} style={{ width: 550, margin: '0 1rem' }}>
                                    <Skeleton active paragraph={{ rows: 3 }} />
                                </div>
                            ))
                            : filteredRecipes.map((recipe) => (
                                <RecipeBanner
                                    background={recipe.image}
                                    key={recipe.key}
                                    header={recipe.recipetitle}
                                    subheader={recipe.shortdescription}
                                    rating={recipe.rating} />

                            ))}
                    </div>

                    <div className="recipe-grid__col recipe-grid__col--second">
                        <div className="recipe-grid__card recipe-grid__card--first">
                            <h2>Learn how to become a master chef right now!</h2>
                            <button>Login</button>
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
                                    <div className="recipe-grid__wishlist-bar-icon">
                                        <i className="far fa-heart"></i>
                                    </div>
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
        </>
    );
}
