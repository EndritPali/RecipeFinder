import '@/Scss/MobileFooter.scss';
import { Link } from 'react-router-dom';
import { Dropdown } from 'antd';
import AccountModal from '@/features/auth/components/AccountModal';
import RecipeDetailsModal from '@/features/recipes/components/RecipeDetailsModal';
import useRecipeModal from '@/features/recipes/hooks/useRecipeModal';
import SavedRecipesDropdown from '@/features/recipes/components/SavedRecipesDropdown';
import { useState } from 'react';
import { useUserAccount } from '@/features/auth/hooks/useUserAccount';

export default function MobileFooter({
    savedRecipes,
    savedLoading,
    recipes = []
}) {

    const [isAccountModalOpen, setIsAccountModalOpen] = useState(false);
    const [modalMode, setModalMode] = useState('login');

    const { user, menuItems } = useUserAccount(setModalMode, setIsAccountModalOpen);

    const {
        isRecipeModalOpen,
        selectedRecipe,
        handleOpenRecipeModal,
        handleCloseRecipeModal,
        handleRollDice
    } = useRecipeModal();

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
                            user={user}
                            savedRecipes={savedRecipes}
                            savedLoading={savedLoading}
                            onRecipeClick={handleOpenRecipeModal}
                            trigger={<i className="far fa-bookmark" />}
                            placement="topRight"
                        />
                        <Dropdown menu={{ items: menuItems }} placement='topRight'>
                            <i className="far fa-user"></i>
                        </Dropdown>
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