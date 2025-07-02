import Magnify from '@/assets/MagnifyingGlass.svg';
import User from '@/assets/User.svg';
import Heart from '@/assets/Heart.svg';
import { Skeleton } from 'antd';
import RecipeSearch from '@/Components/common/header/RecipeSearch';
import SavedRecipesDropdown from '@/features/recipes/components/SavedRecipesDropdown';
import UserDropdown from '@/features/auth/components/UserDropdown';

interface HeaderWidgetsProps {
    showSearch: boolean;
    setShowSearch: React.Dispatch<React.SetStateAction<boolean>>;
    filteredOptions: any;
    handleSearch: (value: string) => void;
    handleSelect: (value: string, option: any) => void;
    loading?: boolean;
    user?: any;
    savedRecipes?: any[];
    savedLoading?: boolean;
    onSavedRecipeClick: (recipe: any) => void;
    menuItems: any;
    onLogin?: () => void;
    onRegister?: () => void;
}

export default function HeaderWidgets({
    showSearch,
    setShowSearch,
    filteredOptions,
    handleSearch,
    handleSelect,
    loading,
    user,
    savedRecipes = [],
    savedLoading,
    onSavedRecipeClick,
    menuItems,
    onLogin,
    onRegister
}: HeaderWidgetsProps) {
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

            <SavedRecipesDropdown
                user={user}
                savedRecipes={savedRecipes}
                savedLoading={savedLoading}
                onRecipeClick={onSavedRecipeClick}
                trigger={<button><img src={Heart} alt="heart" /></button>}
                placement="bottomRight"
            />

            <UserDropdown
                menuItems={menuItems}
                placement="bottomRight"
                trigger={<button><img src={User} alt="user" /></button>}
            />
        </div>
    );
}