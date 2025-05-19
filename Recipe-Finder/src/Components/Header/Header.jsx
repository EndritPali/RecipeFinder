import '../../Scss/Header.scss';
import { useState } from 'react';
import Logo from './Logo';
import MobileSearch from './MobileSearch';
import HeaderWidgets from './HeaderWidgets';
import AccountModal from '../../Templates/AccountModal';
import RecipeDetailsModal from '../../Templates/RecipeDetailsModal';
import { useFetchRecipes } from '../../hooks/useFetchRecipes';
import { useSavedRecipes } from '../../hooks/useSavedRecipes';
import { useUserAccount } from '../../hooks/useUserAccount';
import { useRecipeSearch } from '../../hooks/useRecipeSearch';

export default function Header() {
  const [isAccountModalOpen, setIsAccountModalOpen] = useState(false);
  const [isRecipeModalOpen, setIsRecipeModalOpen] = useState(false);
  const [modalMode, setModalMode] = useState('login');
  const [showSearch, setShowSearch] = useState(false);
  const [showMobileSearch, setShowMobileSearch] = useState(false);
  const [selectedRecipe, setSelectedRecipe] = useState(null);

  const { recipes, loading } = useFetchRecipes();
  const { savedRecipes, loading: savedLoading } = useSavedRecipes();
  const { user, menuItems } = useUserAccount(setModalMode, setIsAccountModalOpen);
  const { filteredOptions, handleSearch, handleSelect } = useRecipeSearch(
    recipes,
    setSelectedRecipe,
    setIsRecipeModalOpen,
    setShowSearch
  );

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
          savedRecipes={savedRecipes}
          savedLoading={savedLoading}
          onSavedRecipeClick={handleSavedRecipeClick}
          menuItems={menuItems}
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