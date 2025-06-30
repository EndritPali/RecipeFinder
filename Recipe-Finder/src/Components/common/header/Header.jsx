import '@/Scss/Header.scss';
import { useState } from 'react';
import Logo from './Logo';
import MobileSearch from './MobileSearch';
import HeaderWidgets from './HeaderWidgets';
import AccountModal from '@/features/auth/components/AccountModal';
import RecipeDetailsModal from '@/features/recipes/components/RecipeDetailsModal';
import { useFetchSavedRecipes } from '@/features/recipes/hooks/useSavedRecipes';
import { useUserAccount } from '@/features/auth/hooks/useUserAccount';
import { useRecipeSearch } from '@/features/recipes/hooks/useRecipeSearch';
import { useAuth } from '@/context/AuthContext';

export default function Header() {
  const [isAccountModalOpen, setIsAccountModalOpen] = useState(false);
  const [isRecipeModalOpen, setIsRecipeModalOpen] = useState(false);
  const [modalMode, setModalMode] = useState('login');
  const [showSearch, setShowSearch] = useState(false);
  const [showMobileSearch, setShowMobileSearch] = useState(false);
  const [selectedRecipe, setSelectedRecipe] = useState(null);

  const { isAuthenticated } = useAuth();

  const { data: savedRecipes, isLoading: savedLoading } = useFetchSavedRecipes({ enabled: isAuthenticated });
  const { user, menuItems } = useUserAccount(setModalMode, setIsAccountModalOpen);
  const { options: filteredOptions, onSearch: handleSearch } = useRecipeSearch();

  const handleSelect = (value, option) => {
    const recipe = option.recipe;
    if (recipe) {
      setSelectedRecipe(recipe);
      setIsRecipeModalOpen(true);
    }
    setShowSearch(false);
  };

  const openAccountModal = (mode) => {
    setModalMode(mode);
    setIsAccountModalOpen(true);
  }

  const handleSavedRecipeClick = (recipe) => {
    setSelectedRecipe(recipe);
    setIsRecipeModalOpen(true);
  };

  const handleCloseRecipeModal = () => {
    setIsRecipeModalOpen(false);
    setSelectedRecipe(null);
  };

  return (
    <>
      <div className="header">
        <Logo setShowMobileSearch={setShowMobileSearch} />

        <MobileSearch
          showMobileSearch={showMobileSearch}
          filteredOptions={filteredOptions}
          handleSearch={handleSearch}
          handleSelect={handleSelect}
        />

        <HeaderWidgets
          showSearch={showSearch}
          setShowSearch={setShowSearch}
          filteredOptions={filteredOptions}
          handleSearch={handleSearch}
          handleSelect={handleSelect}
          user={user}
          savedRecipes={savedRecipes || []}
          savedLoading={savedLoading}
          onSavedRecipeClick={handleSavedRecipeClick}
          menuItems={menuItems}
          onLogin={() => openAccountModal('login')}
          onRegister={() => openAccountModal('register')}
        />
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