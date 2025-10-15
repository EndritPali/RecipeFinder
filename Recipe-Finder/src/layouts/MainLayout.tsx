import './index.scss'
import CommentsSection from '@/features/comments/components/CommentsSection.jsx'
import Header from '../Components/common/header/Header.js'
import MobileFooter from '@/Components/common/MobileFooter.js'
import RecipeGrid from '../features/recipes/components/RecipeGrid/RecipeGrid'
import RecipeGridMobile from '../features/recipes/components/RecipeGrid/RecipeGridMobile.jsx'
import RecipesRow from '../features/recipes/components/RecipesRow.jsx'
import { useFetchSavedRecipes } from '@/features/recipes/hooks/useSavedRecipes'
import { useFetchRecipes } from "@/features/recipes/hooks/useFetchRecipes";
import { useAuth } from '../context/AuthContext';

export default function MainLayout() {
    const { data } = useFetchRecipes();
    const recipes = data?.recipes || [];
    const auth = useAuth();
    const isAuthenticated = auth?.isAuthenticated ?? false;
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