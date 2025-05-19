import '../Scss/MobileFooter.scss';
import { Link } from 'react-router-dom';
import { Dropdown } from 'antd';
import AccountModal from '../Templates/AccountModal';
import RecipeDetailsModal from '../Templates/RecipeDetailsModal';
import useRecipeModal from '../hooks/useRecipeModal';
import useAccountModal from '../hooks/useAccountModal';
import AuthDropdown from '../Templates/AuthDropdown';
import SavedRecipesDropdown from '../Templates/SavedRecipesDropdown';

export default function MobileFooter({
    user,
    savedRecipes,
    savedLoading,
    menuItems,
    setIsAccountModalOpen,
    setModalMode,
    recipes = []
}) {
    const {
        isRecipeModalOpen,
        selectedRecipe,
        handleOpenRecipeModal,
        handleCloseRecipeModal,
        handleRollDice
    } = useRecipeModal();

    const {
        isAccountModalOpen,
        modalMode,
        openAccountModal
    } = useAccountModal(setIsAccountModalOpen, setModalMode);

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

                        {user ? (
                            <Dropdown menu={{ items: menuItems }} placement='topRight'>
                                <i className="far fa-user"></i>
                            </Dropdown>
                        ) : (
                            <AuthDropdown
                                onLogin={openAccountModal}
                                onRegister={openAccountModal}
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

            {(!setModalMode || !setIsAccountModalOpen) && (
                <AccountModal
                    open={isAccountModalOpen}
                    onOk={() => setIsAccountModalOpen(false)}
                    onCancel={() => setIsAccountModalOpen(false)}
                    mode={modalMode}
                />
            )}
        </>
    );
}