import '../Scss/MobileFooter.scss';
import { Link } from 'react-router-dom';
import { Dropdown, Modal } from 'antd';
import { useState } from 'react';
import AccountModal from '../Templates/AccountModal';
import RecipeDetailsModal from '../Templates/RecipeDetailsModal';

export default function MobileFooter({
    user,
    savedRecipes,
    savedLoading,
    menuItems,
    setIsAccountModalOpen,
    setModalMode,
    recipes = []
}) {
    const [isRecipeModalOpen, setIsRecipeModalOpen] = useState(false);
    const [selectedRecipe, setSelectedRecipe] = useState(null);
    const [isAccountModalOpen, setIsAccountModalLocalOpen] = useState(false);
    const [modalMode, setModalModeLocal] = useState('login');
    const [isAuthDropdownOpen, setIsAuthDropdownOpen] = useState(false);

    const handleOpenAccountModal = (mode) => {
        setModalModeLocal(mode);
        setIsAccountModalLocalOpen(true);
        if (setModalMode && setIsAccountModalOpen) {
            setModalMode(mode);
            setIsAccountModalOpen(true);
        }
        setIsAuthDropdownOpen(false);
    };

    const handleSavedRecipeClick = (recipe) => {
        setSelectedRecipe(recipe);
        setIsRecipeModalOpen(true);
    };

    const handleCloseRecipeModal = () => {
        setIsRecipeModalOpen(false);
        setSelectedRecipe(null);
    };

    const handleRollDice = () => {
        if (!recipes.length) return;
        const random = recipes[Math.floor(Math.random() * recipes.length)];
        setSelectedRecipe(random);
        setIsRecipeModalOpen(true);
    };


    const authOptions = [
        {
            key: 'login',
            label: 'Login',
            onClick: () => handleOpenAccountModal('login')
        },
        {
            key: 'register',
            label: 'Register',
            onClick: () => handleOpenAccountModal('register')
        }
    ];

    return (
        <>
            <div className="mobile-footer">
                <div className="mobile-footer__top">
                    <Link to={'/admin'}>
                        <i className="fas fa-globe"></i>
                    </Link>
                </div>
                <div className="mobile-footer__bottom">
                    <div className="mobile-footer__bottom-left">
                        <Link to={'/'}>
                            <i className="fas fa-house"></i>
                        </Link>
                        <button
                            onClick={handleRollDice}
                            className="mobile-footer__dice-btn">
                            <i className="fas fa-dice"></i>
                        </button>
                    </div>
                    <div className="mobile-footer__bottom-right">
                        <Dropdown
                            menu={{
                                items: savedLoading
                                    ? [{ key: 'loading', label: 'Loading saved recipes...', disabled: true }]
                                    : !savedRecipes || savedRecipes.length === 0
                                        ? [{ key: 'empty', label: 'No saved recipes', disabled: true }]
                                        : savedRecipes.map(recipe => ({
                                            key: recipe.key,
                                            label: (
                                                <div className="saved-recipe-item">
                                                    {recipe.image && (
                                                        <img src={recipe.image} alt={recipe.recipetitle} width={30} height={30} />
                                                    )}
                                                    <span>{recipe.recipetitle}</span>
                                                </div>
                                            ),
                                            onClick: () => handleSavedRecipeClick(recipe)
                                        }))
                            }}
                            placement='topRight'
                            trigger={['click']}
                        >
                            <i className="far fa-bookmark"></i>
                        </Dropdown>

                        {user ? (
                            <Dropdown menu={{ items: menuItems }} placement='topRight'>
                                <i className="far fa-user"></i>
                            </Dropdown>
                        ) : (
                            <Dropdown
                                menu={{ items: authOptions }}
                                placement='topRight'
                                open={isAuthDropdownOpen}
                                onOpenChange={setIsAuthDropdownOpen}
                                trigger={['click']}
                            >
                                <i className="far fa-user"></i>
                            </Dropdown>
                        )}
                    </div>
                </div>
            </div>

            {selectedRecipe && (
                <RecipeDetailsModal
                    open={isRecipeModalOpen}
                    onOk={handleCloseRecipeModal}
                    onCancel={handleCloseRecipeModal}
                    recipe={selectedRecipe}
                />
            )}

            {(!setModalMode || !setIsAccountModalOpen) && (
                <AccountModal
                    open={isAccountModalOpen}
                    onOk={() => setIsAccountModalLocalOpen(false)}
                    onCancel={() => setIsAccountModalLocalOpen(false)}
                    mode={modalMode}
                />
            )}
        </>
    );
}