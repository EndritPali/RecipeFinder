import StarShape from '../assets/star-filled.svg';
import '../Scss/Recipe-Box.scss';

export default function RecipeBox({ recipePlate, saladName, saladIngredients, saladRating }) {
    const truncatedName = saladName.split(' ').slice(0, 2).join(' ') + '...';
    const truncatedIngredients = saladIngredients.slice(0, 2).join(', ') + '...';

    return (
        <div className="recipe-box">
            <div className="recipe-box__image">
                <img src={recipePlate} alt="recipe-plate" />
            </div>
            <div className="recipe-box__display">
                <div className="recipe-box__info">
                    <h4>{truncatedName}</h4>
                    <div className="recipe-box__info-rating">
                        <p>{truncatedIngredients}</p>
                        <span className="rating">
                            <img src={StarShape} alt="star" />
                            <p>{saladRating}</p>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    );
}
