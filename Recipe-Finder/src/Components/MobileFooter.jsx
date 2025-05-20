import '../Scss/MobileFooter.scss';
import { Link } from 'react-router-dom';
import { Dropdown } from 'antd';
import AccountModal from '../Templates/AccountModal';
import RecipeDetailsModal from '../Templates/RecipeDetailsModal';
import useRecipeModal from '../hooks/useRecipeModal';
import AuthDropdown from '../Templates/AuthDropdown';
import SavedRecipesDropdown from '../Templates/SavedRecipesDropdown';
import { useState } from 'react';
import { useUserAccount } from '../hooks/useUserAccount';

export default function MobileFooter({
    user,
    savedRecipes,
    savedLoading,
    menuItems,
    recipes = []
}) {

    const [isAccountModalOpen, setIsAccountModalOpen] = useState(false);
    const [modalMode, setModalMode] = useState('login');

    const updatedUserContext = useUserAccount(setModalMode, setIsAccountModalOpen);
    const effectiveUser = user || updatedUserContext.user;
    const effectiveMenuItems = menuItems || updatedUserContext.menuItems;

    const {
        isRecipeModalOpen,
        selectedRecipe,
        handleOpenRecipeModal,
        handleCloseRecipeModal,
        handleRollDice
    } = useRecipeModal();

    const openAccountModal = (mode) => {
        setModalMode(mode);
        setIsAccountModalOpen(true);
    };

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
                            onClick={() => handleRollDice(recipes)}
                            className="mobile-footer__dice-btn">
                            <i className="fas fa-dice"></i>
                        </button>
                    </div>
                    <div className="mobile-footer__bottom-right">
                        <SavedRecipesDropdown
                            user={effectiveUser}
                            savedRecipes={savedRecipes}
                            savedLoading={savedLoading}
                            onRecipeClick={handleOpenRecipeModal}
                            trigger={<i className="far fa-bookmark" />}
                            placement="topRight"
                        />

                        {effectiveUser ? (
                            <Dropdown menu={{ items: effectiveMenuItems }} placement='topRight'>
                                <i className="far fa-user"></i>
                            </Dropdown>
                        ) : (
                            <AuthDropdown
                                onLogin={() => openAccountModal('login')}
                                onRegister={() => openAccountModal('register')}
                                trigger={<i className="far fa-user" />}
                            />
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

            <AccountModal
                open={isAccountModalOpen}
                onOk={() => setIsAccountModalOpen(false)}
                onCancel={() => setIsAccountModalOpen(false)}
                mode={modalMode}
            />
        </>
    );
}