import './index.scss'
import CommentsSection from '@/features/comments/components/CommentsSection.jsx'
import Header from '../Components/common/header/Header.jsx'
import MobileFooter from '@/Components/common/MobileFooter.jsx'
import RecipeGrid from '../features/recipes/components/RecipeGrid/RecipeGrid.jsx'
import RecipeGridMobile from '../features/recipes/components/RecipeGrid/RecipeGridMobile.jsx'
import RecipesRow from '../features/recipes/components/RecipesRow.jsx'
import { useFetchSavedRecipes } from '@/features/recipes/hooks/useSavedRecipes'
import { useFetchRecipes } from "@/features/recipes/hooks/useFetchRecipes";
import { useAuth } from '../context/AuthContext';

export default function MainLayout() {
    const { data } = useFetchRecipes();
    const recipes = data?.recipes || [];
    const { isAuthenticated } = useAuth();
    const { data: savedRecipes, isLoading: savedLoading } = useFetchSavedRecipes({ enabled: isAuthenticated });

    return (
        <>
            <Header />
            <RecipesRow />
            <RecipeGrid />
            <RecipeGridMobile />
            <CommentsSection />

            <MobileFooter
                savedRecipes={savedRecipes || []}
                recipes={recipes}
                savedLoading={savedLoading}
            />
        </>
    )
}