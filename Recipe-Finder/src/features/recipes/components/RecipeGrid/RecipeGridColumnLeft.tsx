import { Skeleton, Empty } from 'antd';
import RecipeBanner from "../RecipeBanner";
import { MappedRecipe } from '@/types/recipe';

export default function RecipeGridColumnLeft({ loading, skeletonItems, displayedRecipes, handleOpenModal }: any) {
    return (
        <div className="recipe-grid__col recipe-grid__col--first">
            {loading
                ? skeletonItems : displayedRecipes.length === 0 ? (
                    <Empty className="recipes-grid-empty" description="No recipes found" />
                )
                    : displayedRecipes.map((recipe: MappedRecipe) => (
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
