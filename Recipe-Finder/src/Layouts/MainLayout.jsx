import './index.scss'
import CommentsSection from '../Components/CommentsSection.jsx'
import Header from '../Components/Header/Header.jsx'
import MobileFooter from '../Components/MobileFooter.jsx'
import RecipeGrid from '../Components/RecipeGrid/RecipeGrid.jsx'
import RecipeGridMobile from '../Components/RecipeGrid/RecipeGridMobile.jsx'
import RecipesRow from '../Components/RecipesRow.jsx'
import { useFetchSavedRecipes } from '../hooks/useSavedRecipes'
import { useFetchRecipes } from '../hooks/useFetchRecipes.js'
import { useAuth } from '../context/AuthContext';

export default function MainLayout() {
    const { recipes } = useFetchRecipes();
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