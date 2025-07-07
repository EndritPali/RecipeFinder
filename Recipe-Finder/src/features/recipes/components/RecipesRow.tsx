import { useFetchRecipes } from "@/features/recipes/hooks/useFetchRecipes";
import useResponsiveCount from '@/lib/hooks/useResponsiveCount';
import useRecipeModal from '@/features/recipes/hooks/useRecipeModal';
import '@/Scss/RecipesRow.scss';
import { Skeleton, Empty } from 'antd';
import RecipeBox from './RecipeBox';
import RecipeDetailsModal from './RecipeDetailsModal';
import React, { useState } from 'react';
import { MappedRecipe } from '@/types/recipe';

export default function RecipesRow() {
  const { isLoading, data } = useFetchRecipes({ paginate: false });
  const [showAll, setShowAll] = useState(false);
  const maxVisible = useResponsiveCount();
  const {
    isRecipeModalOpen,
    selectedRecipe,
    handleOpenRecipeModal,
    handleCloseRecipeModal
  } = useRecipeModal();

  const recipes = data?.recipes || [];
  const filteredRecipes = recipes.filter((recipe: MappedRecipe) => recipe.category === 'With Features');

  const displayedRecipes = showAll ? filteredRecipes : filteredRecipes.slice(0, maxVisible);

  const handleToggleShowAll = (e: React.MouseEvent<HTMLAnchorElement>) => {
    e.preventDefault();
    setShowAll(prev => !prev);
  };

  return (
    <>
      <div className="recipes-row">
        <div className="recipes-row__header">
          <div className="recipes-row__header-primary">
            <h2>Healthy Recipes</h2>
            <h3>with features</h3>
          </div>
          <div className="recipes-row__header-link">
            <a href="#" onClick={handleToggleShowAll}>
              {showAll ? 'See less' : 'See all'}
            </a>
          </div>
        </div>

        <div className="recipes-row__content">
          {isLoading ? (
            Array.from({ length: maxVisible }).map((_, index) => (
              <div key={index} style={{ width: 300, margin: '0 1rem' }}>
                <Skeleton active paragraph={{ rows: 3 }} />
              </div>
            ))
          ) : recipes.length === 0 ? (
            <Empty className="recipes-row__content-empty" description="No recipes found" />
          ) : (
            displayedRecipes.map((recipe: MappedRecipe) => (
              <RecipeBox
                key={recipe.key}
                recipePlate={recipe.image}
                saladName={recipe.recipetitle}
                saladIngredients={recipe.ingredients}
                saladRating={recipe.rating}
                onClick={() => handleOpenRecipeModal(recipe)}
              />
            ))
          )}
        </div>
      </div>

      <RecipeDetailsModal
        open={isRecipeModalOpen}
        onOk={handleCloseRecipeModal}
        onCancel={handleCloseRecipeModal}
        recipe={selectedRecipe}
      />
    </>
  );
}