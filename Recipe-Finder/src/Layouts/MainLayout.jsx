import './index.scss'
import { useState } from 'react'
import CommentsSection from '../Components/CommentsSection.jsx'
import Header from '../Components/Header/Header.jsx'
import MobileFooter from '../Components/MobileFooter.jsx'
import RecipeGrid from '../Components/RecipeGrid/RecipeGrid.jsx'
import RecipeGridMobile from '../Components/RecipeGrid/RecipeGridMobile.jsx'
import RecipesRow from '../Components/RecipesRow.jsx'
import { useSavedRecipes } from '../hooks/useSavedRecipes'
import { useUserAccount } from '../hooks/useUserAccount'
import { useFetchRecipes } from '../hooks/useFetchRecipes.js'

export default function MainLayout() {

    const [setIsAccountModalOpen] = useState(false);
    const [setModalMode] = useState('login');
    const { recipes } = useFetchRecipes();
    const { savedRecipes, loading: savedLoading } = useSavedRecipes();
    const { user, menuItems } = useUserAccount(setModalMode, setIsAccountModalOpen);


    return (
        <>
            <Header />
            <RecipesRow />
            <RecipeGrid />
            <RecipeGridMobile />
            <CommentsSection />

            <MobileFooter
                user={user}
                savedRecipes={savedRecipes}
                recipes={recipes}
                savedLoading={savedLoading}
                menuItems={menuItems}
                setIsAccountModalOpen={setIsAccountModalOpen}
                setModalMode={setModalMode}
            />

        </>
    )
}