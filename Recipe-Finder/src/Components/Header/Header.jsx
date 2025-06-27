import '../../Scss/Header.scss';
import { useState } from 'react';
import Logo from './Logo';
import MobileSearch from './MobileSearch';
import HeaderWidgets from './HeaderWidgets';
import AccountModal from '../../Templates/AccountModal';
import RecipeDetailsModal from '../../Templates/RecipeDetailsModal';
import { useFetchRecipes } from '../../hooks/useFetchRecipes';
import { useFetchSavedRecipes } from '../../hooks/useSavedRecipes';
import { useUserAccount } from '../../hooks/useUserAccount';
import { useRecipeSearch } from '../../hooks/useRecipeSearch';
import { useAuth } from '../../context/AuthContext';
import { Link } from 'react-router-dom';

export default function Header() {
  const [isAccountModalOpen, setIsAccountModalOpen] = useState(false);
  const [isRecipeModalOpen, setIsRecipeModalOpen] = useState(false);
  const [modalMode, setModalMode] = useState('login');
  const [showSearch, setShowSearch] = useState(false);
  const [showMobileSearch, setShowMobileSearch] = useState(false);
  const [selectedRecipe, setSelectedRecipe] = useState(null);

  const { isAuthenticated } = useAuth();

  const { recipes, loading } = useFetchRecipes(false, false);
  const { data: savedRecipes, isLoading: savedLoading } = useFetchSavedRecipes({ enabled: isAuthenticated });
  const { user, menuItems } = useUserAccount(setModalMode, setIsAccountModalOpen);
  const { filteredOptions, handleSearch, handleSelect } = useRecipeSearch(
    recipes,
    setSelectedRecipe,
    setIsRecipeModalOpen,
    setShowSearch
  );

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
          loading={loading}
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