export default function RecipeGridColumnRight({ user, openAccountModal, randomRecipe, handleOpenModal }) {
    return (
        <div className="recipe-grid__col recipe-grid__col--second">
            <div className="recipe-grid__card recipe-grid__card--first">
                <h2>
                    {user
                        ? 'Upload your unique recipes now! Like a real master chef'
                        : 'Learn how to become a master chef right now!'}
                </h2>
                {user ? (
                    <a href="/admin"><button>Dashboard</button></a>
                ) : (
                    <button onClick={() => openAccountModal('login')}>Login</button>
                )}
            </div>

            {randomRecipe && (
                <div
                    className="recipe-grid__card recipe-grid__card--second"
                    style={{
                        backgroundImage: `url(${randomRecipe.image})`,
                        backgroundSize: 'cover',
                        backgroundPosition: 'center',
                    }}
                >
                    <div className="recipe-grid__wishlist-bar">
                        <i className="far fa-heart recipe-grid__wishlist-bar-icon"></i>
                        <button onClick={() => handleOpenModal(randomRecipe)}>Start cook</button>
                    </div>
                    <div className="recipe-grid__col-info">
                        <h2>Weekly pick</h2>
                        <p>{randomRecipe.shortdescription}</p>
                    </div>
                </div>
            )}
        </div>
    );
}
