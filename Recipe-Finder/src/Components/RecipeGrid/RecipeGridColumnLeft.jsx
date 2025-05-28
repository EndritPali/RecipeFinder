import { Skeleton } from 'antd';
import RecipeBanner from "../../Templates/RecipeBanner";

export default function RecipeGridColumnLeft({ loading, skeletonItems, displayedRecipes, handleOpenModal }) {
    return (
        <div className="recipe-grid__col recipe-grid__col--first">
            {loading
                ? skeletonItems
                : displayedRecipes.map(recipe => (
                    <RecipeBanner
                        background={recipe.image}
                        key={recipe.key}
                        header={recipe.recipetitle}
                        subheader={recipe.shortdescription}
                        rating={recipe.rating}
                        onClick={() => handleOpenModal(recipe)}
                    />
                ))}
        </div>
    );
}
