import '@/Scss/MobileFooter.scss';
import { Link } from 'react-router-dom';
import { Dropdown } from 'antd';
import AccountModal from '@/features/auth/components/AccountModal';
import RecipeDetailsModal from '@/features/recipes/components/RecipeDetailsModal';
import useRecipeModal from '@/features/recipes/hooks/useRecipeModal';
import SavedRecipesDropdown from '@/features/recipes/components/SavedRecipesDropdown';
import { useState } from 'react';
import { useUserAccount } from '@/features/auth/hooks/useUserAccount';
import { AccountModalMode } from '@/types/auth';
import { useAuth } from '@/context/AuthContext';
import { useFetchResetRequests } from '@/features/admin/hooks/useFetchRequests'
import { usePasswordResetEvents } from '@/features/admin/hooks/usePasswordResetEvents'
import { Badge } from 'antd'

interface MobileFooterProps {
    savedRecipes: any[];
    savedLoading: boolean;
    recipes?: any[];
}

export default function MobileFooter({ savedRecipes, savedLoading, recipes = [] }: MobileFooterProps) {
    const [isAccountModalOpen, setIsAccountModalOpen] = useState(false);
    const [modalMode, setModalMode] = useState<AccountModalMode>('login');

    const { user, menuItems } = useUserAccount(setModalMode, setIsAccountModalOpen);


    const auth = useAuth();
    const isAdmin = user?.role === 'Admin';
    const { data: resetRequests, refetch } = useFetchResetRequests(isAdmin);
    const pendingRequests = resetRequests?.length || 0;
    const isAuthenticated = auth?.isAuthenticated ?? false;
    usePasswordResetEvents(async () => { await refetch(); }, isAdmin);

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
                    <Link to={'/dashboard'}>
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
                        <Badge count={pendingRequests}>
                            <Dropdown menu={{ items: menuItems as any }} placement='topRight'>
                                <i className="far fa-user"></i>
                            </Dropdown>
                        </Badge>
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