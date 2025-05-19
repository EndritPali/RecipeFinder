import { Dropdown, Skeleton } from 'antd';

export default function SavedRecipesDropdown({
    user,
    savedRecipes = [],
    savedLoading,
    onRecipeClick,
    trigger,
    placement = 'bottomRight'
}) {
    if (!user) return null;

    const items = savedLoading
        ? [{ key: 'loading', label: 'Loading saved recipes...', disabled: true }]
        : savedRecipes.length === 0
            ? [{ key: 'empty', label: 'No saved recipes', disabled: true }]
            : savedRecipes.map(recipe => ({
                key: recipe.key,
                label: (
                    <div className="saved-recipe-item">
                        {recipe.image && (
                            <img src={recipe.image} alt={recipe.recipetitle} width={30} height={30} />
                        )}
                        <span>{recipe.recipetitle}</span>
                    </div>
                ),
                onClick: () => onRecipeClick(recipe)
            }));

    return (
        <Dropdown menu={{ items }} placement={placement} trigger={['click']}>
            {trigger}
        </Dropdown>
    );
}