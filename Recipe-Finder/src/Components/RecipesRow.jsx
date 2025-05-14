import { useFetchRecipes } from '../hooks/useFetchRecipes';
import useResponsiveCount from '../hooks/useResponsiveCount';
import '../Scss/RecipesRow.scss';
import { Skeleton } from 'antd';
import RecipeBox from '../Templates/RecipeBox';
import { useState } from 'react';

export default function RecipesRow() {
  const { loading, recipes } = useFetchRecipes();
  const [showAll, setShowAll] = useState(false);
  const maxVisible = useResponsiveCount();

  const filteredRecipes = recipes.filter(recipe => recipe.category === 'With Features');

  const displayedRecipes = showAll ? filteredRecipes : filteredRecipes.slice(0, maxVisible);

  const handleToggleShowAll = (e) => {
    e.preventDefault();
    setShowAll(prev => !prev);
  };

  return (
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
        {loading ? (
          Array.from({ length: maxVisible }).map((_, index) => (
            <div key={index} style={{ width: 300, margin: '0 1rem' }}>
              <Skeleton active paragraph={{ rows: 3 }} />
            </div>
          ))
        ) : (
          displayedRecipes.map(recipe => (
            <RecipeBox
              key={recipe.key}
              recipePlate={recipe.image}
              saladName={recipe.recipetitle}
              saladIngredients={recipe.ingredients}
              saladRating={recipe.rating}
            />
          ))
        )}
      </div>
    </div>
  );
}
