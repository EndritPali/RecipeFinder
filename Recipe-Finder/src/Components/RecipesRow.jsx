import { useFetchRecipes } from '../hooks/useFetchRecipes';
import '../Scss/Recipes-Row.scss';
import { Skeleton } from 'antd';
import RecipeBox from '../Templates/RecipeBox';

export default function RecipesRow() {
  const { loading, recipes } = useFetchRecipes();

  const filteredRecipes = recipes.filter(recipe => recipe.category === 'With Features');

  return (
    <div className="recipes-row">
      <div className="recipes-row__header">
        <div className="recipes-row__header-primary">
          <h2>Healthy Recipes</h2>
          <h3>with features</h3>
        </div>
        <div className="recipes-row__header-link">
          <a href="#">See all</a>
        </div>
      </div>
      <div className="recipes-row__content">
        {loading ? (
          Array.from({ length: 3 }).map((_, index) => (
            <div key={index} style={{ width: 300, margin: '0 1rem' }}>
              <Skeleton active paragraph={{ rows: 3 }} />
            </div>
          ))
        ) : (
          filteredRecipes.map(recipe => (
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
