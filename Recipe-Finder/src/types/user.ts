export interface User {
    id: string;
    username: string;
    email: string;
    role: string;
    created_at: string;
}

export interface HeaderWidgetsProps {
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