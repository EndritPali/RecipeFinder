import Magnify from '../../assets/MagnifyingGlass.svg';
import User from '../../assets/User.svg';
import Heart from '../../assets/Heart.svg';
import { Dropdown, Skeleton } from 'antd';
import RecipeSearch from './RecipeSearch';

export default function HeaderWidgets({
    showSearch,
    setShowSearch,
    filteredOptions,
    handleSearch,
    handleSelect,
    loading,
    user,
    savedRecipes,
    savedLoading,
    onSavedRecipeClick,
    menuItems
}) {
    return (
        <div className="header__widgets">
            <button onClick={() => setShowSearch(prev => !prev)}>
                <img src={Magnify} alt="magnify" />
            </button>

            <RecipeSearch
                visible={showSearch}
                options={filteredOptions}
                onSearch={handleSearch}
                onSelect={handleSelect}
            />

            {loading && <Skeleton active paragraph={{ rows: 1 }} />}

            {user && (
                <Dropdown
                    menu={{
                        items: savedLoading
                            ? [{ key: 'loading', label: <Skeleton active paragraph={{ rows: 1 }} />, disabled: true }]
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
                                    onClick: () => onSavedRecipeClick(recipe)
                                }))
                    }}
                    placement='bottomRight'
                    trigger={['click']}
                >
                    <button><img src={Heart} alt="heart" /></button>
                </Dropdown>
            )}

            <Dropdown menu={{ items: menuItems }} placement='bottomRight'>
                <button><img src={User} alt="user" /></button>
            </Dropdown>
        </div>
    );
}