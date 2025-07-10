import Magnify from '@/assets/MagnifyingGlass.svg';
import User from '@/assets/User.svg';
import Heart from '@/assets/Heart.svg';
import { Skeleton } from 'antd';
import RecipeSearch from '@/Components/common/header/RecipeSearch';
import SavedRecipesDropdown from '@/features/recipes/components/SavedRecipesDropdown';
import UserDropdown from '@/features/auth/components/UserDropdown';
import { useFetchResetRequests } from '@/features/admin/hooks/useFetchRequests'
import { usePasswordResetEvents } from '@/features/admin/hooks/usePasswordResetEvents'
import { Badge } from 'antd'
import { HeaderWidgetsProps } from '@/types/user';

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
}: HeaderWidgetsProps) {

    const isAdmin = user?.role === 'Admin';
    const { data: resetRequests, refetch } = useFetchResetRequests(isAdmin);
    const pendingRequests = resetRequests?.length || 0;
    usePasswordResetEvents(async () => { await refetch(); }, isAdmin);

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

            <Badge count={pendingRequests}>
                <UserDropdown
                    menuItems={menuItems}
                    placement="bottomRight"
                    trigger={<button><img src={User} alt="user" /></button>}
                />
            </Badge>
        </div>
    );
}