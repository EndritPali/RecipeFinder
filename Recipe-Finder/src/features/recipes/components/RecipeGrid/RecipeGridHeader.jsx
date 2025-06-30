export default function RecipeGridHeader({ hasPrev, hasNext, handlePrev, handleNext }) {
    return (
        <div className="recipe-grid__header">
            <h3>with benefits</h3>
            <div className="recipe-grid__pagination">
                {hasPrev && (
                    <a href="#" onClick={handlePrev} className="recipe-grid__header-link">See less</a>
                )}
                {hasNext && (
                    <a href="#" onClick={handleNext} className="recipe-grid__header-link">See all</a>
                )}
            </div>
        </div>
    );
}
