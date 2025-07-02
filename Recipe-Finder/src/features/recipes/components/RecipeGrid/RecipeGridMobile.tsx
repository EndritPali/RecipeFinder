import { useMemo, useState } from 'react';
import { useFetchRecipes } from "@/features/recipes/hooks/useFetchRecipes";
import RecipeBanner from '@/features/recipes/components/RecipeBanner';
import RecipeDetailsModal from '@/features/recipes/components/RecipeDetailsModal';
import { useUserAccount } from '@/features/auth/hooks/useUserAccount';
import AccountModal from '@/features/auth/components/AccountModal';
import '@/Scss/RecipeGridMobile.scss';
import { MappedRecipe } from '@/types/recipe';
import { AccountModalMode } from '@/types/auth';

export default function RecipeGridMobile() {
    const { data } = useFetchRecipes({ paginate: false });

    const filteredRecipes: MappedRecipe[] = useMemo(() => {
        const recipes = data?.recipes || [];
        return recipes.filter((recipe: MappedRecipe) => recipe.category === 'With Benefits');
    }, [data]);

    const randomRecipe: MappedRecipe | null = useMemo(() => {
        if (filteredRecipes.length === 0) return null;
        return filteredRecipes[Math.floor(Math.random() * filteredRecipes.length)];
    }, [filteredRecipes]);

    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedRecipe, setSelectedRecipe] = useState<MappedRecipe | null>(null);

    const handleOpenModal = (recipe: MappedRecipe) => {
        setSelectedRecipe(recipe);
        setIsModalOpen(true);
    };

    const handleCloseModal = () => {
        setIsModalOpen(false);
        setSelectedRecipe(null);
    };

    const [isAccountModalOpen, setIsAccountModalOpen] = useState(false);
    const [modalMode, setModalMode] = useState<AccountModalMode>('login');
    const { user, openAccountModal } = useUserAccount(setModalMode, setIsAccountModalOpen);

    return (
        <>
            <div className="recipe-grid-mobile">

                <div className="recipe-grid-mobile__card recipe-grid-mobile__card--first">
                    <h2>
                        {user
                            ? 'Upload your unique recipes now! Like a real master chef'
                            : 'Learn how to become a master chef right now!'}
                    </h2>
                    {user ? (
                        <a href="/admin"><button>Dashboard</button></a>
                    ) : (
                        <button onClick={() => openAccountModal('login')}>Login</button>
                    )}
                </div>

                <div className="recipe-grid-mobile__header">
                    <div className="recipe-grid-mobile__header-primary">
                        <h3>With benefits</h3>
                    </div>
                    {/* <div className="recipe-grid-mobile__header-link">
                        <a href="#">See all</a>
                    </div> */}
                </div>

                <div className="recipe-grid-mobile__col recipe-grid-mobile__col--first">
                    {filteredRecipes.map((recipe: MappedRecipe) => (
                        <RecipeBanner
                            background={recipe.image}
                            key={recipe.key}
                            header={recipe.recipetitle}
                            subheader={recipe.shortdescription}
                            rating={recipe.rating}
                            onClick={() => handleOpenModal(recipe)}
                        />
                    ))}
                </div>

                {randomRecipe && (
                    <div
                        className="recipe-grid-mobile__card recipe-grid-mobile__card--second"
                        style={{
                            backgroundImage: `url(${randomRecipe.image})`,
                            backgroundSize: 'cover',
                            backgroundPosition: 'center',
                        }}
                    >
                        <div className="recipe-grid-mobile__wishlist-bar">
                            <div className="recipe-grid-mobile__bar-ico">
                                <i className="far fa-heart"></i>
                            </div>
                            <button onClick={() => handleOpenModal(randomRecipe)}>
                                Start cook
                            </button>
                        </div>
                        <div className="recipe-grid-mobile__info">
                            <h2>Weekly pick</h2>
                            <p>{randomRecipe.shortdescription}</p>
                        </div>
                    </div>
                )}
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
