import './index.scss'
import CommentsSection from '../Components/CommentsSection.jsx'
import Header from '../Components/Header.jsx'
import MobileFooter from '../Components/MobileFooter.jsx'
import RecipeGrid from '../Components/RecipeGrid.jsx'
import RecipeGridMobile from '../Components/RecipeGridMobile.jsx'
import RecipesRow from '../Components/RecipesRow.jsx'

export default function MainLayout() {
    return (
        <>
            <Header />
            <RecipesRow />
            <RecipeGrid />
            <RecipeGridMobile />
            <CommentsSection />
            <MobileFooter />
        </>
    )
}